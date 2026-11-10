import { test, expect, type Page } from '@playwright/test';

async function ready(page: Page, route: string) {
  await page.goto(route);
  await expect(page.locator('main h1').first()).toBeVisible();
}

test('guest: mission → decisions → explanations → promise survives reload', async ({ page }) => {
  const errors: string[] = [];
  page.on('pageerror', error => errors.push(error.message));
  await ready(page, '/welcome');
  await page.getByRole('link', { name: 'O‘tkazib yuborish' }).click();
  await page.getByRole('link', { name: 'Mehmon bo‘lib davom etish' }).click();
  await page.getByRole('link', { name: 'Missiyani boshlash', exact: true }).click();
  await page.getByRole('button', { name: 'Mashqni boshlash', exact: true }).click();
  for (let step = 0; step < 3; step++) {
    await expect(page.locator('.status-pill')).toContainText(`${step + 1}/3`);
    await page.locator('.answer').nth(step === 0 ? 0 : 1).click();
    await page.getByRole('button', { name: step === 2 ? 'Tahlilni ko‘rish' : 'Qarorni tasdiqlash' }).click();
  }
  await expect(page.locator('.decision-review')).toHaveCount(3);
  await expect(page.locator('.decision-review').first()).toContainText('E’TIBOR BERISH KERAK');
  await page.reload();
  await expect(page.locator('.decision-review')).toHaveCount(3);
  await page.getByRole('link', { name: 'Hayotda qo‘llayman' }).click();
  await page.getByRole('link', { name: 'Bugungi va’dani tanlash' }).click();
  await page.getByRole('button', { name: 'Bajardim', exact: true }).click();
  await expect(page.getByRole('button', { name: 'Bajardim', exact: true })).toHaveAttribute('aria-pressed', 'true');
  await page.reload();
  await expect(page.getByRole('button', { name: 'Bajardim', exact: true })).toHaveAttribute('aria-pressed', 'true');
  expect(errors).toEqual([]);
});

test('daily answer waits for slow progress before showing result', async ({ page }) => {
  await ready(page, '/question/today');
  await page.route('**/api/v1/progress', async route => {
    await new Promise(resolve => setTimeout(resolve, 700));
    await route.continue();
  });
  await page.locator('.answer').first().click();
  await page.getByRole('button', { name: 'Javobni tekshirish' }).click();
  await expect(page).toHaveURL(/\/question\/today\/result$/);
  await expect(page.locator('.completion-card p')).not.toBeEmpty();
  await expect(page.getByRole('link', { name: 'Savolga javob berish', exact: true })).toHaveCount(0);
});

test('fallback daily package is visibly labelled on home', async ({ page }) => {
  await page.route('**/api/v1/daily', async route => {
    const response = await route.fetch();
    const payload = await response.json();
    payload.data.fallback = true;
    payload.data.bundle_id += '-test-fallback';
    await route.fulfill({ response, json: payload });
  });
  await ready(page, '/home');
  await expect(page.getByText('Bugungi tasdiqlangan paket mavjud emas. Hozir zaxira mashq ko‘rsatilmoqda.')).toBeVisible();
  await expect(page.locator('.hero-label')).toContainText('ZAXIRA MASHQ');
  await expect(page.getByRole('link', { name: 'Missiyani boshlash' })).toBeVisible();
});

test('lesson reading, mini-test, completion and saved progress', async ({ page }) => {
  await ready(page, '/learn/lesson/phishing');
  for (let step = 0; step < 4; step++) {
    await page.getByRole('button', { name: 'Davom etish', exact: true }).click();
  }
  await page.getByRole('link', { name: 'O‘zimni tekshiraman' }).click();
  await page.locator('.answer').nth(1).click();
  await page.getByRole('button', { name: 'Javobni tekshirish' }).click();
  await page.getByRole('button', { name: 'Darsni yakunlash' }).click();
  await expect(page.locator('h1')).toHaveText('Yangi bilim siz bilan.');
  await page.reload();
  await expect(page.locator('h1')).toHaveText('Yangi bilim siz bilan.');
});

test('invalid stored reading position and negative onboarding step do not crash', async ({ page }) => {
  await page.addInitScript(() => localStorage.setItem('cg:lesson:phishing', '999'));
  await ready(page, '/learn/lesson/phishing');
  await expect(page.getByRole('heading', { name: 'Bitta amaliy qadam', exact: true })).toBeVisible();
  await ready(page, '/welcome?step=-4');
  await expect(page.locator('main h1')).toHaveText('Raqamli hayotda xotirjam bo‘ling.');
});

test('native share is invoked only after the user clicks', async ({ page }) => {
  await page.addInitScript(() => {
    const target = window as Window & { shared?: string[]; CyberGigiyena?: { postMessage: (message: string) => void } };
    target.shared = [];
    target.CyberGigiyena = { postMessage: message => target.shared!.push(message) };
  });
  await ready(page, '/share/today');
  const messages = () => page.evaluate(() => (window as Window & { shared?: string[] }).shared || []);
  expect(await messages()).toEqual([]);
  await page.getByRole('button', { name: 'Ulashish', exact: true }).click();
  const sent = (await messages()).map(value => JSON.parse(value));
  expect(sent).toHaveLength(1);
  expect(sent[0]).toMatchObject({ version: 1, action: 'shareTip' });
  expect(sent[0].payload.text).toContain('CyberGigiyena');
});

test('static SOS remains readable after network disconnect', async ({ page, context }) => {
  await ready(page, '/sos');
  await page.evaluate(() => navigator.serviceWorker.ready.then(() => true));
  await page.goto('/offline.html');
  await context.setOffline(true);
  await page.reload();
  await expect(page.locator('body')).toContainText('SOS');
  await expect(page.locator('body')).toContainText('parol');
});

for (const width of [320, 360, 393, 430, 480, 768, 1440]) {
  test(`main pages fit ${width}px width`, async ({ page }) => {
    await page.setViewportSize({ width, height: 900 });
    for (const route of ['/home', '/learn', '/practice', '/profile', '/sos']) {
      await ready(page, route);
      expect(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth + 1), `${route} horizontal overflow`).toBe(true);
    }
    if ([320, 393, 768, 1440].includes(width)) {
      await ready(page, '/home');
      await page.screenshot({ path: `test-results/home-${width}.png`, fullPage: true });
    }
  });
}

test('keyboard skip-link focus and active verified alert banner on home', async ({ page }) => {
  await page.route('**/api/v1/daily', async route => {
    const response = await route.fetch();
    const payload = await response.json();
    payload.data.alert = {
      slug: 'uzcert-telegram-code',
      title: 'Telegram orqali kod so‘rash ogohlantirishi',
      summary: 'Davlat organi nomidan kod so‘ralganda uni bermang.',
      sourceName: 'UZCERT',
      sourceUrl: 'https://gov.uz/oz/gurlan/news/view/189014',
      publishedAt: '2026-07-07',
      verifiedAt: '2026-11-05',
      activeFrom: '2026-11-01',
      activeUntil: '2026-12-01',
      steps: ['Kodni hech kimga bermang.']
    };
    await route.fulfill({ response, json: payload });
  });
  await ready(page, '/home');
  await expect(page.locator('.alert-strip')).toContainText('Telegram orqali kod so‘rash ogohlantirishi');
  await expect(page.locator('.alert-strip')).toContainText('UZCERT (2026-07-07)');
  await expect(page.locator('.alert-strip')).toHaveAttribute('href', '/alerts/uzcert-telegram-code');
  await page.keyboard.press('Tab');
  const skipLink = page.getByRole('link', { name: 'Asosiy mazmunga o‘tish' });
  await expect(skipLink).toBeFocused();
  await skipLink.press('Enter');
  expect(await page.evaluate(() => document.activeElement?.id)).toBe('main');
});

test('200% text and dark mode remain usable on 320px', async ({ page }) => {
  await page.setViewportSize({ width: 320, height: 900 });
  await ready(page, '/profile/preferences');
  await page.getByRole('button', { name: '200%', exact: true }).click();
  await page.getByRole('button', { name: 'Tungi', exact: true }).click();
  for (const route of ['/home', '/learn', '/practice', '/profile', '/sos']) {
    await ready(page, route);
    expect(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth + 1), `${route} text-scale overflow`).toBe(true);
  }
  await ready(page, '/home');
  await page.screenshot({ path: 'test-results/home-320-dark-200.png', fullPage: true });
});
