/**
 * Shopee Price Extractor using Puppeteer + Stealth Plugin
 * 
 * Opens the Shopee product page in a stealth headless Chrome that
 * bypasses Shopee's bot detection, then extracts the real price.
 * 
 * Usage: node shopee_price.cjs <shopee_url>
 * Output: JSON { price: 45000, name: "...", source: "api"|"dom" }
 */

const puppeteer = require('puppeteer-extra');
const StealthPlugin = require('puppeteer-extra-plugin-stealth');
const fs = require('fs');

puppeteer.use(StealthPlugin());

async function extractShopeePrice(url) {
    let browser;
    try {
        // Find Chrome browser
        const possiblePaths = [
            'C:\\Users\\victus\\AppData\\Local\\Google\\Chrome\\Application\\chrome.exe',
            'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe',
            'C:\\Program Files (x86)\\Google\\Chrome\\Application\\chrome.exe',
        ];
        
        let chromePath = null;
        for (const p of possiblePaths) {
            if (fs.existsSync(p)) { chromePath = p; break; }
        }

        browser = await puppeteer.launch({
            headless: 'new',
            executablePath: chromePath || undefined,
            channel: chromePath ? undefined : 'chrome',
            args: [
                '--no-sandbox',
                '--disable-setuid-sandbox',
                '--disable-dev-shm-usage',
                '--disable-gpu',
                '--window-size=1366,768',
                '--lang=id-ID',
            ],
            defaultViewport: { width: 1366, height: 768 },
            timeout: 30000,
        });

        const page = await browser.newPage();

        // Set realistic browser fingerprint
        await page.setUserAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/128.0.0.0 Safari/537.36');
        await page.setExtraHTTPHeaders({
            'Accept-Language': 'id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7',
        });

        // Intercept Shopee's internal API responses to catch real price data
        let apiPrice = null;
        let apiName = null;
        
        page.on('response', async (response) => {
            try {
                const respUrl = response.url();
                if (respUrl.includes('/api/v4/item/get') || 
                    respUrl.includes('/api/v4/pdp/get') ||
                    respUrl.includes('/api/v2/item/get')) {
                    const json = await response.json();
                    const item = json?.data?.item || json?.item || json?.data;
                    if (item) {
                        if (item.name) apiName = item.name;
                        
                        // Shopee stores prices as price * 100000
                        const candidates = [
                            item.price, item.price_min, item.price_max,
                            item.price_before_discount, item.price_min_before_discount,
                        ].filter(p => p && p > 0);

                        for (const c of candidates) {
                            let p = c;
                            if (p > 10000000) p = Math.round(p / 100000);
                            if (p >= 1000 && p <= 50000000) {
                                apiPrice = p;
                                break;
                            }
                        }
                    }
                }
            } catch (e) {}
        });

        // Block images/fonts/media to speed up loading
        await page.setRequestInterception(true);
        page.on('request', (req) => {
            const type = req.resourceType();
            if (['image', 'font', 'media'].includes(type)) {
                req.abort();
            } else {
                req.continue();
            }
        });

        // Navigate to the Shopee page
        await page.goto(url, { 
            waitUntil: 'networkidle2',
            timeout: 25000 
        });

        // Wait for page JavaScript to fully render + API calls to complete
        await new Promise(resolve => setTimeout(resolve, 5000));

        // If API interception got the price, use it (most reliable)
        if (apiPrice) {
            const pageTitle = await page.title();
            let name = apiName || pageTitle || '';
            name = name.replace(/\s*[|\-]\s*Shopee.*$/i, '').replace(/^(?:Jual|Beli)\s+/i, '').trim();
            console.log(JSON.stringify({ price: apiPrice, name, raw_price_text: 'from API', source: 'api' }));
            return;
        }

        // Fallback: extract price from rendered DOM
        const priceData = await page.evaluate(() => {
            const allElements = document.querySelectorAll('div, span, p');
            const priceCandidates = [];

            for (const el of allElements) {
                const text = el.textContent.trim();
                if (/^[Rr][Pp]\s*\.?\s*\d/.test(text) && text.length < 30) {
                    const numStr = text.replace(/[^0-9]/g, '');
                    const num = parseInt(numStr, 10);
                    if (num >= 1000 && num <= 50000000) {
                        const rect = el.getBoundingClientRect();
                        if (rect.width > 0 && rect.height > 0) {
                            priceCandidates.push({ text, price: num, top: rect.top });
                        }
                    }
                }
            }

            if (priceCandidates.length > 0) {
                priceCandidates.sort((a, b) => {
                    if (Math.abs(a.top - b.top) < 50) return a.price - b.price;
                    return a.top - b.top;
                });
                return { price: priceCandidates[0].price, raw: priceCandidates[0].text };
            }
            return null;
        });

        let price = priceData?.price || null;
        let rawText = priceData?.raw || null;

        const pageTitle = await page.title();
        let name = apiName || pageTitle || '';
        name = name.replace(/\s*[|\-]\s*Shopee.*$/i, '').replace(/^(?:Jual|Beli)\s+/i, '').trim();

        console.log(JSON.stringify({ 
            price, name, raw_price_text: rawText,
            source: price ? 'dom' : 'none'
        }));
        
    } catch (error) {
        console.log(JSON.stringify({ 
            price: null, name: '', error: error.message, source: 'error'
        }));
    } finally {
        if (browser) await browser.close();
    }
}

const url = process.argv[2];
if (!url) {
    console.log(JSON.stringify({ price: null, error: 'No URL provided', source: 'error' }));
    process.exit(1);
}

extractShopeePrice(url);
