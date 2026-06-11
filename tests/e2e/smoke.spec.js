const { expect, test } = require('@playwright/test');

const baseUrl = process.env.BASE_URL || 'http://127.0.0.1:8080';

test('homepage opens', async ({ page }) => {
  const response = await page.goto(baseUrl + '/', { waitUntil: 'domcontentloaded' });

  expect(response.ok()).toBeTruthy();
  await expect(page.locator('body')).toContainText('Test drupal website');
});

test('admin user can log in', async ({ page }) => {
  await page.goto(baseUrl + '/user/login', { waitUntil: 'domcontentloaded' });

  await page.locator('#edit-name').fill('admin');
  await page.locator('#edit-pass').fill('admin');
  await page.getByRole('button', { name: 'Log in' }).click();

  await page.waitForLoadState('domcontentloaded');
  await page.goto(baseUrl + '/user', { waitUntil: 'domcontentloaded' });

  await expect(page).not.toHaveURL(/\/user\/login/);
  await expect(page.locator('body')).toContainText('admin');
});
