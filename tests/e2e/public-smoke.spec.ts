import { expect, test } from '@playwright/test';

test.describe('public smoke coverage', () => {
  test('homepage renders the new editorial search-first shell', async ({ page }) => {
    await page.goto('/?lang=en');

    await expect(page.locator('[data-homepage-stitch-reset]')).toBeVisible();
    await expect(page.locator('[data-hero-search]')).toBeVisible();
    await expect(page.getByRole('heading', { name: /Knowledge, Curated for the Scholar/i })).toBeVisible();
  });

  test('catalog keeps the critical discovery controls visible', async ({ page }) => {
    await page.goto('/catalog');

    await expect(page.locator('#language-chips')).toBeVisible();
    await expect(page.locator('#sort-select')).toBeVisible();
    await expect(page.locator('#filter-available-only')).toBeVisible();
  });

  test('guest account access redirects to login while resources stay public', async ({ page }) => {
    await page.goto('/account');
    await expect(page).toHaveURL(/\/login\?redirect=%2Faccount/);
    await expect(page.locator('form')).toBeVisible();

    await page.goto('/resources?lang=en');
    await expect(page.getByText(/Digital collections and research databases/i)).toBeVisible();
  });
});
