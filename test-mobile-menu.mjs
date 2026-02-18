/**
 * Playwright ile mobil menü testi - tarayıcıda açar, hamburgera tıklar, ekran görüntüsü alır
 * Çalıştır: node test-mobile-menu.mjs
 * Headed (görünür tarayıcı): HEADED=1 node test-mobile-menu.mjs
 */
import { chromium } from 'playwright';
import fs from 'fs';

const BASE_URL = 'https://nakliyepark-v1.test/';
const HEADED = process.env.HEADED === '1';

async function main() {
  const browser = await chromium.launch({ headless: !HEADED });
  const context = await browser.newContext({
    viewport: { width: 375, height: 667 },
    ignoreHTTPSErrors: true,
  });
  const page = await context.newPage();

  console.log('Sayfaya gidiliyor:', BASE_URL);
  await page.goto(BASE_URL, { waitUntil: 'domcontentloaded', timeout: 15000 }).catch(() => {});

  const openBtn = await page.locator('#mobile-menu-open').first();
  const count = await openBtn.count();
  console.log('Menü butonu sayısı:', count);

  if (count === 0) {
    console.log('HATA: #mobile-menu-open bulunamadı.');
    await page.screenshot({ path: 'test-no-button.png' });
    await browser.close();
    process.exit(1);
  }

  // Açılmadan önce ekran görüntüsü
  await page.screenshot({ path: 'test-before-click.png' });
  console.log('Ekran görüntüsü: test-before-click.png');

  await openBtn.click();
  await page.waitForTimeout(400);

  const overlayVisible = await page.evaluate(() => {
    const el = document.getElementById('mobile-menu-overlay');
    if (!el) return false;
    return el.getAttribute('data-open') === 'true';
  });
  const closeBtnExists = await page.locator('#mobile-menu-close').count() > 0;
  console.log('Overlay görünür (display=flex):', overlayVisible);
  console.log('Kapat butonu var:', closeBtnExists);

  await page.screenshot({ path: 'test-after-click.png' });
  console.log('Ekran görüntüsü: test-after-click.png');

  await browser.close();
  process.exit(overlayVisible && closeBtnExists ? 0 : 1);
}

main().catch(err => {
  console.error('Hata:', err);
  process.exit(1);
});
