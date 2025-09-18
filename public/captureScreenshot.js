const puppeteer = require('puppeteer');

(async () => {
    // Retrieve URL and output path from command-line arguments
    const url = process.argv[2];
    const outputPath = process.argv[3];

    // Launch a headless browser
    const browser = await puppeteer.launch();

    // Open a new page
    const page = await browser.newPage();

    try {
        // Adjust viewport size
        await page.setViewport({ width: 1920, height: 1080 });

        // Navigate to the specified URL
        await page.goto(url);

        // Add a delay of 5 seconds (adjust as needed)
        await new Promise(resolve => setTimeout(resolve, 200000)); // 5000 milliseconds = 5 seconds

        // Capture a screenshot of the page
        await page.screenshot({ path: outputPath });

        console.log('Screenshot captured successfully.');
    } catch (error) {
        console.error('Error capturing screenshot:', error);
    }

    // Close the browser
    await browser.close();
})();
