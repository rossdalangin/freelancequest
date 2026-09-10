# Architecture & System API Manual

This document details the complete architectural layout, PDO SQLite database design, custom MVC pattern, and routing table for **FREELANCEQUEST**.

---

## 1. Directory Structure

```
/
├── config/
│   └── database.php         # PDO SQLite Singleton connection wrapper
├── database/
│   ├── database.sqlite      # SQLite database file
│   ├── schema.php           # DDL schema definition script (31 normalized tables)
│   └── seed.php             # Native database seeder (51 masterclass lessons & 153 quiz questions)
├── public/
│   ├── index.php            # Front Controller & PSR-4 autoloader
│   ├── .htaccess            # Apache URL rewrite rules
│   └── downloads/           # Public resource vault templates & checklists
├── src/
│   ├── Router.php           # Lightweight regex-based HTTP router
│   ├── Controllers/         # MVC Controllers (Admin, Auth, Dashboard, Learning, Portfolio, etc.)
│   ├── Models/              # Native Data Models & Query Wrappers
│   └── Services/            # Core Services (GameEngineService, SecurityService, DataManagementService)
├── views/                   # Native PHP Blade-style templates (Admin, Dashboard, Portfolio, Resume, etc.)
├── docs/                    # System Documentation, Manuals, & API Specs
├── marketing/               # Sales, Email, Social, & 30-Day Video Campaign Scripts
└── tests/                  # PHPUnit Automated Test Suite
```

---

## 2. Core Routing Table

The application utilizes a custom regex router (`src/Router.php`) dispatched via `public/index.php`.

| Method | Path | Controller & Action | Functionality |
| :--- | :--- | :--- | :--- |
| `GET` | `/` | `HomeController::index` | Landing page with stats, 16 career levels, founder bio, & testimonials |
| `GET` | `/about` | `PageController::about` | About page with Ross Dalangin founder story & dynamic CMS override |
| `GET` | `/features` | `PageController::features` | Interactive feature breakdown & dynamic CMS override |
| `GET` | `/faq` | `PageController::faq` | Frequently Asked Questions & dynamic CMS override |
| `GET` | `/login` / `POST` | `AuthController::showLogin` / `processLogin` | Secure session authentication |
| `GET` | `/register` / `POST` | `AuthController::showRegister` / `processRegister` | User signups with referral code tracking (`ref=username`) |
| `GET` | `/dashboard` | `DashboardController::index` | Main player dashboard, daily quests, RPG map, & skill tree |
| `GET` | `/leaderboard` | `LeaderboardController::index` | Global rankings sorted by Level, XP, or Coins with pagination |
| `GET` | `/learn` | `LearningController::index` | Learning academy level roadmap and course modules |
| `GET` | `/learn/{slug}` | `LearningController::showLesson` | Interactive masterclass lesson viewer |
| `POST` | `/quiz/{id}/submit` | `LearningController::submitQuiz` | Anti-farming quiz submission & XP/coin rewards |
| `GET` / `POST` | `/mission/{id}` | `LearningController::showMission` / `submitMission` | Interactive client scenario missions |
| `GET` / `POST` | `/resume-builder` | `ResumeBuilderController::index` / `update` | ATS Resume Builder with live preview sync |
| `GET` / `POST` | `/portfolio-builder` | `PortfolioController::index` / `update` | Public Portfolio Builder with live preview sync & LinkedIn URL |
| `GET` | `/p/{username}` | `PortfolioController::showPublic` | Live public portfolio showcase URL |
| `GET` | `/cover-letter-builder` | `CoverLetterController::index` | Interactive Cover Letter Generator |
| `GET` | `/application-tracker` | `ApplicationTrackerController::index` | Job application Kanban pipeline tracker |
| `GET` | `/jobs` | `JobController::index` | Freelancer job marketplace & candidate applications |
| `GET` | `/resources` | `ResourceVaultController::index` | Downloadable Vault SOP templates and checklists |
| `GET` / `POST` | `/settings` | `UserController::showSettings` / `updateSettings` | Profile settings, referral link, & password updates |
| `POST` | `/settings/testimonial` | `UserController::submitTestimonial` | Game testimonial review submission (+150 XP) |
| `GET` | `/admin` | `AdminController::index` | Main admin control center & settings |
| `GET` | `/admin/pages` | `AdminController::managePages` | Page Content Management System (CMS) |
| `POST` | `/admin/pages/update` | `AdminController::updatePageContent` | Update static page HTML/text contents |
| `POST` | `/admin/ai-course-builder` | `AdminController::generateAiCourse` | Generate 3-lesson course modules + quizzes + resources |
| `POST` | `/admin/coupons/create` | `AdminController::createCoupon` | Create discount coupon codes |
| `GET` / `POST` | `/admin/database/export` / `import` | `AdminController::exportDatabase` / `importDatabase` | Binary SQLite backup download & restore engine |

---

## 3. Database Schema Overview

The SQLite database (`database/database.sqlite`) contains 31 normalized tables:
- `users`: Stores account profiles, level, XP, coins, streak days, subscription tier, and referral codes.
- `levels`: Defines the 16 career stages (0 to 15) with titles, descriptions, icons, and certificate badges.
- `courses` & `lessons`: Stores course modules and 51 masterclass lessons formatted in clean HTML.
- `quizzes` & `questions`: Stores 51 quizzes and 153 multiple-choice questions with answer keys and explanations.
- `missions` & `mission_attempts`: Defines 50 interactive client scenario tasks, submission data, and scores.
- `settings`: Key-value configuration table storing system parameters, payment receiving accounts, custom prices, and CMS page content overrides.
- `testimonials`: User review submissions with rating scores, review text, and admin approval status.
- `coupons`: Discount codes with percentage or fixed dollar deductions and active status flags.
- `certificates`: Verifiable completion records with unique verification hashes (`FQ-XXXX-1234`).
- `portfolios` & `resumes`: User candidate career assets, public slug URLs, and LinkedIn contact details.
