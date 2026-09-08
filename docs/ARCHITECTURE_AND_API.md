# Architecture & System API Manual

This document details the architectural layout, PDO SQLite database design, custom MVC pattern, and routing table for **FREELANCEQUEST**.

---

## 1. Directory Structure

```
/
├── config/
│   └── database.php         # PDO SQLite Singleton connection wrapper
├── database/
│   ├── database.sqlite      # SQLite database file
│   ├── schema.php           # DDL schema definition script
│   └── seed.php             # Native database seeder
├── public/
│   ├── index.php            # Front Controller & PSR-4 autoloader
│   └── .htaccess            # Apache URL rewrite rules
├── src/
│   ├── Router.php           # Lightweight regex-based HTTP router
│   ├── Controllers/         # Application MVC Controllers
│   ├── Models/              # Native Data Models & Query Wrappers
│   └── Services/            # Business Logic Services (Game Engine, Certs)
├── views/                   # Native PHP Blade-style templates
├── docs/                    # System Documentation & Tutorials
├── marketing/               # Sales, Email, & Social Campaign Copy
└── tests/                  # PHPUnit Automated Test Suite
```

---

## 2. Router & Routing Table

The system uses a custom regex router (`src/Router.php`) dispatching requests via `public/index.php`.

| Method | Path | Controller & Action |
| :--- | :--- | :--- |
| `GET` | `/` or `/dashboard` | `DashboardController::index` |
| `GET` / `POST` | `/onboarding` | `OnboardingController::index` / `store` |
| `GET` | `/learn` | `LearningController::index` |
| `GET` | `/learn/{slug}` | `LearningController::showLesson` |
| `POST` | `/learn/{slug}/complete` | `LearningController::completeLesson` |
| `POST` | `/quiz/{id}/submit` | `LearningController::submitQuiz` |
| `GET` / `POST` | `/mission/{id}` | `LearningController::showMission` / `submitMission` |
| `GET` / `POST` | `/resume-builder` | `ResumeBuilderController::index` / `update` |
| `GET` / `POST` | `/portfolio-builder` | `PortfolioController::index` / `update` |
| `GET` | `/p/{username}` | `PortfolioController::showPublic` |
| `GET` | `/verify/{code}` | `CertificateController::verify` |
| `GET` | `/resources` | `ResourceVaultController::index` |
| `GET` / `POST` | `/community` | `CommunityController::index` / `storePost` |
| `POST` | `/community/post/{id}/comment` | `CommunityController::storeComment` |
| `GET` / `POST` | `/pricing` | `SubscriptionController::index` / `subscribe` |
| `GET` / `POST` | `/admin` | `AdminController::index` / `generateAiCourse` |

---

## 3. Database Schema Overview

All tables use primary keys and foreign key constraints:
- `users`: learner profiles, level, XP, coins, streak, and subscription tier.
- `levels`: level numbers (0-15), titles, XP requirements, icons.
- `courses` & `lessons`: curriculum content, video links, rewards, sorting.
- `quizzes` & `questions`: multiple-choice questions with correct option strings and explanations.
- `missions` & `mission_attempts`: interactive client exercises, submission drafts, and scores.
- `badges` & `user_badges`: achievement badges and award timestamps.
- `certificates`: unique codes (`FQ-XXXX-1234`), level numbers, and skill breakdown JSON.
- `resumes` & `portfolios`: user candidate profiles and public portfolio URL records.
