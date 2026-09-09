# FREELANCEQUEST — Platform & Operations Manual

Welcome to **FREELANCEQUEST**, the gamified Virtual Assistant & freelancing career simulator that transforms complete beginners into high-earning digital professionals.

---

## 1. System Overview & Core Philosophy

FREELANCEQUEST combines the engagement mechanics of modern RPGs (XP, levels, skill trees, streak flames, badges, daily quests) with real-world freelancing skills training.

### Core Product Loop
`LEARN` &rarr; `PRACTICE` &rarr; `COMPLETE MISSION` &rarr; `EARN XP & COINS` &rarr; `LEVEL UP` &rarr; `BUILD CAREER ASSETS` &rarr; `PASS INTERVIEW` &rarr; `GET CLIENTS`

---

## 2. Student Career Journey (Levels 0 – 15)

- **Level 0: Career Zero** — Starting with zero remote experience.
- **Level 1: Explorer** — Freelancing foundations, remote work basics, and career path options.
- **Level 2: Digital Survivor** — Google Workspace, Gmail, Sheets, Calendar, Asana, and Slack.
- **Level 3: VA Apprentice** — Data entry, customer support, web research, lead gen, and CRM basics.
- **Level 4: Specialist** — Selecting a career niche (Executive VA, Social Media, Tech, Real Estate).
- **Level 5: Job Ready** — Interactive resume builder, headline, bio, and candidate positioning.
- **Level 6: Portfolio Builder** — Public portfolio showcase, case studies, and work samples.
- **Level 7: Application Academy** — Job board navigation, scam detection, and opportunity evaluation.
- **Level 8: Proposal Master** — Writing high-converting, personalized pitch proposals.
- **Level 9: Interview Arena** — Client interview simulator, confidence, and objection handling.
- **Level 10: Client Acquisition** — Cold email outreach, LinkedIn prospecting, and direct lead hunt.
- **Level 11: Client Negotiator** — Hourly rates, project scope, contracts, and boundary setting.
- **Level 12: Project Manager** — Workflow execution, client updates, and issue resolution.
- **Level 13: Freelancer Pro** — Retainer contracts, client retention, upselling, and referral systems.
- **Level 14: Freelance Business Owner** — SOP creation, subcontracting, agency building, and hiring.
- **Level 15: Freelance Master** — Apex status, automation, agency scaling, and sustainable revenue.

---

## 3. Learner Career Tools & Features

1. **Academy & Lesson Player (`/learn`)**:
   - Access comprehensive text, video, and markdown lesson SOPs.
   - Complete lesson-specific knowledge check quizzes with exact score-proportional XP and coin calculations.

2. **Interactive Resume Builder (`/resume-builder`)**:
   - Build ATS-optimized resumes with instant PDF print support (`/resume-builder/print`).

3. **Public Portfolio Showcase (`/p/username`)**:
   - Publish live portfolio pages featuring services, about bios, contact info, and work samples.

4. **Cover Letter Generator (`/cover-letter-builder`)**:
   - Generate tailored, high-converting pitch proposals for specific job opportunities.

5. **Application Tracker Pipeline (`/application-tracker`)**:
   - Track application pipeline conversion rates, interview conversion, and client offers.

6. **Job Board Marketplace (`/jobs`)**:
   - Browse open client opportunities, post jobs, and apply directly via the prominent "Apply for this role" submission form.

7. **Verifiable Certificates (`/verify/code`)**:
   - Issue verifiable credentials (`FQ-XXXX-1234`) with instant print views (`/verify/code/print`).

8. **Resource Vault (`/resources`)**:
   - Download valuable SOP templates, contract agreements, pitch scripts, and pricing calculators.

---

## 4. Platform Administration Guide

### Accessing Admin Control Center
Navigate to `/admin` in your browser. (Requires user role `admin`).

### Key Admin Capabilities & CRUD Management
1. **Analytics Dashboard (`/admin`)**: Monitor total learners, published lessons, active missions, quizzes, and total payment transactions.
2. **Curriculum Lessons (`/admin/lessons`)**: Full CRUD support to create, view, edit, and delete lessons.
3. **Quizzes & Questions (`/admin/quizzes`)**: Full CRUD support for lesson quizzes, plus individual question editing and removal.
4. **Interactive Missions (`/admin/missions`)**: Full CRUD support for scenario exercises and rewards.
5. **Resource Vault (`/resources`)**: Full CRUD support for downloadable PDFs, templates, and scripts.
6. **User & Membership Management (`/admin/users`)**: Update user roles (Student vs Admin) and subscription tiers (Free, Pro, Master).
7. **Payments & Refunds (`/admin/payments`)**: Review PayPal, Stripe, and GCash transactions and execute refunds.
8. **Payment Gateway Settings (`/admin`)**: Configure merchant payment receiving account details (PayPal Email, Stripe Key, GCash Number & Name).
9. **System Audit Logs & Backup Export (`/admin/logs`, `/admin/export-data`)**: Review real-time system logs and download full JSON database backups.
