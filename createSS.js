const puppeteer = require('puppeteer');

async function captureScreenshot(url, outputPath) {
    const browser = await puppeteer.launch();
    const page = await browser.newPage();
    await page.goto(url);
    await page.screenshot({ path: outputPath });
    await browser.close();
}

module.exports = captureScreenshot;
