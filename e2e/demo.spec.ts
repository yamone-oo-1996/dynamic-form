import { test, expect } from '@playwright/test';

test('e2e sandbox demo page loads and reveals result on click', async ({ page }) => {
    await page.goto('/e2e-demo');

    await expect(page.locator('#heading')).toHaveText('Mergify E2E Sandbox');
    await expect(page.locator('#result')).toBeHidden();

    await page.locator('#reveal-button').click();

    await expect(page.locator('#result')).toBeVisible();
    await expect(page.locator('#result')).toHaveText('Revealed!');
});
