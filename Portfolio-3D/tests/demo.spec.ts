import { test } from '@playwright/test'

test('record demo walkthrough', async ({ page, context }) => {
  await context.tracing.start({ screenshots: true, snapshots: true })
  await page.goto('/')
  await page.waitForSelector('.hero', { timeout: 15000 })
  await page.waitForTimeout(1200)

  // Hover to trigger glow
  const { width, height } = page.viewportSize()!
  await page.mouse.move(width * 0.65, height * 0.45)
  await page.waitForTimeout(800)

  // Smooth scroll to each section by anchor clicks to avoid wheel flakiness
  for (const anchor of ['About', 'Skills', 'Projects', 'Resume', 'Contact']) {
    await page.click(`text=${anchor}`)
    await page.waitForTimeout(1000)
  }
  await page.click('text=Home')
  await page.waitForTimeout(1000)

  await context.tracing.stop({ path: 'playwright-trace.zip' })
})


