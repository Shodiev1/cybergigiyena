import { defineConfig } from '@playwright/test';

export default defineConfig({
  testDir: './tests',
  timeout: 60_000,
  expect: { timeout: 15_000 },
  workers: 1,
  retries: 0,
  reporter: [['list'], ['json', { outputFile: 'test-results/results.json' }]],
  use: {
    baseURL: process.env.CG_WEB_URL || 'http://127.0.0.1:3000',
    channel: process.env.CG_BROWSER || 'chrome',
    launchOptions: { args: ['--no-proxy-server'] },
    headless: true,
    viewport: { width: 393, height: 852 },
    trace: 'retain-on-failure',
    screenshot: 'only-on-failure',
  },
});
