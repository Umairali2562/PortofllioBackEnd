<?php

// Get the extracted project folder path from command-line argument
$projectFolderPath = $argv[1];


// Save the current directory
$currentDirectory = getcwd(); // api/public

// Change the directory to the screenshots directory (create if it doesn't exist)
$screenshotsDirectory = $currentDirectory . '/screenshots'; // api/public/screenshots
if (!file_exists($screenshotsDirectory)) {
    mkdir($screenshotsDirectory, 0777, true); // Create the directory recursively with full permissions
}

// Change the current working directory to the project folder
chdir($projectFolderPath); // hoobank


// Start the npm start project asynchronously
// Function to check if a process is running on a given port
function isProcessRunning($port) {
    $output = shell_exec("netstat -ano | findstr LISTENING | findstr :$port");
    return !empty($output);
}

// Function to kill a process running on a given port
function killProcess($port) {
    $output = shell_exec("netstat -ano | findstr LISTENING | findstr :$port");
    $lines = explode("\n", $output);
    foreach ($lines as $line) {
        if (preg_match('/\s+\d+\s+TCP\s+\d+\.\d+\.\d+\.\d+:' . $port . '\s+\d+\.\d+\.\d+\.\d+:0\s+LISTENING\s+(\d+)/', $line, $matches)) {
            $pid = trim($matches[1]);
            exec("taskkill /F /PID $pid");
            return true;
        }
    }
    return false;
}
// Execute npm start command in the background
$command = 'start /B cmd /c "npm start"';
exec($command);
// Wait for the React server to start (up to 60 seconds)
$max_attempts = 12; // Total attempts (each attempt waits for 5 seconds)
$attempts = 0;
while (!isProcessRunning(3000) && $attempts < $max_attempts) {
    sleep(5); // Wait for 5 seconds
    $attempts++;
}

// If the React server is running, execute mkdir command for hi directory
if (isProcessRunning(3000)) {
    // Wait for the React project to fully load (adjust the delay as needed)
    //sleep(10); // Wait for 10 seconds, adjust as needed

// Change the directory to the screenshots directory
    chdir($screenshotsDirectory); // api/public/screenshots

// Get the full path to the screenshots directory
    $outputPath = getcwd();

// Change the current working directory back to the original directory
    chdir($currentDirectory); // api/public


// Execute the Puppeteer script to capture a screenshot


    // Set the path where the screenshot will be saved and replace backslashes with forward slashes
    $screenshotPath = str_replace('\\', '/', $screenshotsDirectory . '/screenshot.png');

    $scriptPath = base_path('captureScreenshot.js');
    $command = "node {$scriptPath} http://localhost:3000 {$screenshotPath}";
    exec($command);
    sleep(10);
    // Kill the React server process running on port 3000
    killProcess(3000);
    // Kill the Node.js runtime process
    exec("taskkill /F /IM node.exe");


} else {
    // If the React server didn't start within the specified time, display an error message
    echo "Error: React server did not start within the specified time.";
}


// Create the 'ok' subdirectory within the screenshots directory if it doesn't exist
$screenshotsDirectorys = $screenshotsDirectory . '/ok';
if (!file_exists($screenshotsDirectorys)) {
    mkdir($screenshotsDirectorys, 0777, true); // Create the directory recursively with full permissions
}

