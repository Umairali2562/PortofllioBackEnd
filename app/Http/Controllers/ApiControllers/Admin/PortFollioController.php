<?php

namespace App\Http\Controllers\ApiControllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class PortFollioController extends Controller
{
    public function uploadFiles(Request $request)
    {
        $this->authorize('Create_Projects', Project::class);

        $category = $request->input('category');

        if (Auth::check()) {
            $file = $request->file('file');
            $folderPath = $request->input('folder_path'); // Retrieve folder path from request
            $fileName = $file->getClientOriginalName();

            // Store the file inside storage/app/public/{folderPath}
            $filePath = Storage::putFile('public/' . rtrim($folderPath, '/'), $file);

            // Get the URL of the uploaded file
            $fileUrl = Storage::url($filePath);

            // Check if the uploaded file is a zip file
            if ($file->getClientOriginalExtension() === 'zip') {
                // Extract the zip
                $extractedPath = $this->extractZipFile($filePath, $folderPath);

                if ($extractedPath) {
                    // Start the React project and take a screenshot
                    $screenshotPath = $this->takeScreenshot($extractedPath, $category);

                    if ($screenshotPath) {
                        return response()->json([
                            'message'         => 'Zip file extracted successfully.',
                            'filename'        => $fileName,
                            'file_url'        => $fileUrl,
                            'screenshot_path' => $screenshotPath
                        ]);
                    } else {
                        return response()->json([
                            'error' => 'Failed to take screenshot of the React project.'
                        ], 500);
                    }
                } else {
                    return response()->json([
                        'error' => 'Failed to extract the zip file.'
                    ], 500);
                }
            }

            return response()->json([
                'message'  => 'File Uploaded Successfully',
                'filename' => $fileName,
                'file_url' => $fileUrl
            ]);
        } else {
            return response()->json([
                'error' => 'Sorry, you are not authenticated...'
            ], 401);
        }
    }

    private function extractZipFile($filePath, $folderPath)
    {
        $zip = new ZipArchive;

        // Get the full path of the zip file
        $zipFilePath = storage_path('app/' . $filePath);

        // Use Laravel’s public_path for cross-platform support
        $extractPath = public_path('downloads/' . trim($folderPath, '/'));

        // Ensure the target directory exists
        if (!is_dir($extractPath)) {
            mkdir($extractPath, 0777, true);
        }

        if (file_exists($zipFilePath)) {
            if ($zip->open($zipFilePath) === TRUE) {
                // Temporary extract folder
                $tempExtractPath = tempnam(sys_get_temp_dir(), 'extract_');
                unlink($tempExtractPath);
                mkdir($tempExtractPath);

                $zip->extractTo($tempExtractPath);
                $zip->close();

                // Locate React project folder inside extracted content
                $reactProjectDir = $this->findReactProjectDirectory($tempExtractPath);

                if ($reactProjectDir) {
                    $destinationPath = $extractPath . DIRECTORY_SEPARATOR . basename($reactProjectDir);

                    if (!is_dir($destinationPath)) {
                        if (!rename($reactProjectDir, $destinationPath)) {
                            return false;
                        }
                    }

                    $this->removeDirectory($tempExtractPath);
                    return $destinationPath;
                }

                $this->removeDirectory($tempExtractPath);
            } else {
                error_log("Failed to open the zip file: $zipFilePath");
            }
        } else {
            error_log("Zip file does not exist: $zipFilePath");
        }

        return false;
    }

    private function findReactProjectDirectory($dir)
    {
        foreach (scandir($dir) as $file) {
            if ($file !== '.' && $file !== '..') {
                $filePath = $dir . DIRECTORY_SEPARATOR . $file;
                if (is_dir($filePath) && $this->isReactProject($filePath)) {
                    return $filePath;
                }
                if (is_dir($filePath)) {
                    $subDirResult = $this->findReactProjectDirectory($filePath);
                    if ($subDirResult !== false) {
                        return $subDirResult;
                    }
                }
            }
        }
        return false;
    }

    private function removeDirectory($dir)
    {
        $files = array_diff(scandir($dir), array('.', '..'));
        foreach ($files as $file) {
            $path = $dir . DIRECTORY_SEPARATOR . $file;
            (is_dir($path)) ? $this->removeDirectory($path) : unlink($path);
        }
        return rmdir($dir);
    }

    private function takeScreenshot($extractedPath, $category)
    {
        if (Auth::check()) {
            $dir = basename($extractedPath);

            // Define the directory path for screenshots
            $screenshotDir = storage_path('app/public/screenshots');

            // Create the screenshots directory if it doesn't exist
            if (!file_exists($screenshotDir)) {
                mkdir($screenshotDir, 0777, true);
            }

            $ss = 'screenshot' . rand() . '.png';
            $screenshotPath = str_replace('\\', '/', $screenshotDir . '/' . $ss);

            $link = '/downloads/' . $dir;
            $screenshot_Name = basename($screenshotPath);
            $path = '/storage/screenshots/' . $screenshot_Name;

            // Call Puppeteer script to capture the screenshot
            $scriptPath = base_path('captureScreenshot.js');
            $command = "node {$scriptPath} http://localhost:8000/downloads/$dir {$screenshotPath}";
            $user = Auth::user();

            exec($command);

            if (empty($user->name)) {
                $user->name = "Username";
            }

            // Resize the screenshot
            chdir(storage_path('app/public/screenshots'));
            $sourceImage = imagecreatefrompng($screenshot_Name);

            $sourceWidth = imagesx($sourceImage);
            $sourceHeight = imagesy($sourceImage);

            $newWidth = 519;
            $newHeight = 380;

            $scale = $newHeight / $sourceHeight;
            $resizedWidth = min($sourceWidth * $scale, $newWidth);
            $positionX = ($newWidth - $resizedWidth) / 2;

            $destinationImage = imagecreatetruecolor($newWidth, $newHeight);
            imagesavealpha($destinationImage, true);
            $transparentColor = imagecolorallocatealpha($destinationImage, 0, 0, 0, 127);
            imagefill($destinationImage, 0, 0, $transparentColor);

            imagecopyresampled(
                $destinationImage,
                $sourceImage,
                $positionX,
                0,
                0,
                0,
                $resizedWidth,
                $newHeight,
                $sourceWidth,
                $sourceHeight
            );

            imagepng($destinationImage, $screenshotPath, 9);

            imagedestroy($sourceImage);
            imagedestroy($destinationImage);

            // Save project details in DB
            $project = new Project();
            $project->Project_name     = basename($extractedPath);
            $project->Project_category = $category;
            $project->Project_screenshot = $path;
            $project->Project_user     = $user->name;
            $project->Project_link     = $link;
            $project->save();

            if (file_exists($screenshotPath)) {
                return $screenshotPath;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    private function isReactProject($dir)
    {
        return true;
        // A real check could be: return is_file($dir . '/package.json');
    }

    public function index()
    {
        return Project::all();
    }

    public function main()
    {
        $this->authorize('View_Projects', Project::class);
        return Project::all();
    }

    public function deleteit($id)
    {
        $this->authorize('Delete_Projects', Project::class);
        try {
            $project = Project::findOrFail($id);
            $project->delete();
            return response()->json(['Success' => 'Successfully deleted..'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete project'], 500);
        }
    }
}
