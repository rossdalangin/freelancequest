import asyncio
from playwright.async_api import async_playwright

async def run():
    async with async_playwright() as p:
        browser = await p.chromium.launch()
        page = await browser.new_page()

        # Login
        await page.goto("http://localhost:8000/login")
        await page.fill('input[name="email"]', "maria@example.com")
        await page.fill('input[name="password"]', "password")
        await page.click('button[type="submit"]')
        await page.wait_for_url("**/dashboard")

        await page.screenshot(path="/tmp/dashboard_skill_tree_verified.png", full_page=True)
        await browser.close()

asyncio.run(run())
