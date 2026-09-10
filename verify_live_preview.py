import asyncio
from playwright.async_api import async_playwright

async def main():
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)
        context = await browser.new_context(viewport={'width': 1280, 'height': 900})
        page = await context.new_page()

        # Login
        await page.goto("http://localhost:8000/login")
        await page.fill('input[name="email"]', "maria@example.com")
        await page.fill('input[name="password"]', "password")
        await page.click('button[type="submit"]')
        await page.wait_for_url("http://localhost:8000/dashboard")

        # Resume Builder
        await page.goto("http://localhost:8000/resume-builder")
        await page.wait_for_selector("#input_full_name")

        # Fill in resume fields
        await page.fill("#input_full_name", "Maria Executive Santos")
        await page.fill("#input_professional_title", "Senior Operations Virtual Assistant")
        await page.fill("#input_skills", "Executive Calendar, Email Triage, Project Operations")

        # Check preview text
        name_preview = await page.inner_text("#preview_full_name")
        title_preview = await page.inner_text("#preview_professional_title")
        skills_preview = await page.inner_text("#preview_skills")

        print(f"Resume Name Preview: {name_preview}")
        print(f"Resume Title Preview: {title_preview}")
        print(f"Resume Skills Preview: {skills_preview}")

        assert "Maria Executive Santos" in name_preview
        assert "Senior Operations Virtual Assistant" in title_preview
        assert "Executive Calendar" in skills_preview

        await page.screenshot(path="/tmp/resume_builder_live_preview.png", full_page=True)

        # Portfolio Builder
        await page.goto("http://localhost:8000/portfolio-builder")
        await page.wait_for_selector("#input_title")

        # Fill in portfolio fields
        await page.fill("#input_title", "Maria Santos - Executive Operational Services")
        await page.fill("#input_tagline", "Scaling CEO productivity by 20+ hours a week")

        title_port_preview = await page.inner_text("#preview_title")
        tagline_port_preview = await page.inner_text("#preview_tagline")

        print(f"Portfolio Title Preview: {title_port_preview}")
        print(f"Portfolio Tagline Preview: {tagline_port_preview}")

        assert "Maria Santos - Executive Operational Services" in title_port_preview
        assert "Scaling CEO productivity by 20+ hours a week" in tagline_port_preview

        await page.screenshot(path="/tmp/portfolio_builder_live_preview.png", full_page=True)

        await browser.close()

if __name__ == "__main__":
    asyncio.run(main())
