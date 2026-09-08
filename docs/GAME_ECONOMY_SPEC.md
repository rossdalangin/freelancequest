# Game Economy & Progression Engine Specification

The game economy in **FREELANCEQUEST** is designed using behavioral psychology principles to maintain motivation, reward mastery, and encourage daily habit formation.

---

## 1. Primary Currencies

### Experience Points (XP)
- **Purpose:** Measures long-term skill progression, unlocks higher career levels (0-15), and determines leaderboard rank.
- **Earning Mechanics:**
  - Complete 1 Lesson: **+50 XP**
  - Pass 1 Quiz: **+100 XP**
  - Complete 1 Mission: **+150 to +600 XP** (based on level difficulty)
  - Build/Update Resume: **+200 XP**
  - Publish Portfolio: **+250 XP**
  - Post Community Discussion: **+50 XP**
  - Comment on Community Post: **+25 XP**

### Virtual Coins
- **Purpose:** In-game currency used to unlock premium template downloads, avatar upgrades, and optional simulation boosts.
- **Earning Mechanics:**
  - Lesson Completion: **+10 Coins**
  - Quiz Pass: **+20 Coins**
  - Mission Completion: **+20 to +100 Coins**
  - Daily Quest Completion: **+15 to +40 Coins**

---

## 2. Level Threshold Scaling Table

| Level | Title | Required Total XP | Unlocked Features |
| :--- | :--- | :--- | :--- |
| **0** | Career Zero | 0 XP | Onboarding & Starter Roadmap |
| **1** | Explorer | 100 XP | Foundations Academy & Level 1 Certificate |
| **2** | Digital Survivor | 500 XP | Google Workspace Tools & Productivity Certificate |
| **3** | VA Apprentice | 1,200 XP | Admin Support Missions & VA Fundamentals Certificate |
| **4** | Specialist | 2,200 XP | Specialization Track Selection |
| **5** | Job Ready | 3,500 XP | Interactive Resume Builder |
| **6** | Portfolio Builder | 5,000 XP | Public Portfolio Showcase Generator |
| **7** | Application Academy | 7,000 XP | Job Board Simulator & Scam Evaluator |
| **8** | Proposal Master | 9,500 XP | Proposal Script Generator & Scoring |
| **9** | Interview Arena | 12,500 XP | Client Interview Simulator |
| **10** | Client Acquisition | 16,000 XP | Cold Outreach & Prospecting Engine |
| **11** | Client Negotiator | 20,000 XP | Pricing Calculators & Contract Templates |
| **12** | Project Manager | 25,000 XP | Service Delivery & Client Management |
| **13** | Freelancer Pro | 31,000 XP | Retainer Upsell Systems |
| **14** | Business Owner | 38,000 XP | Agency Building & Subcontracting SOPs |
| **15** | Freelance Master | 50,000 XP | Apex Mastery Certificate & Master Community Status |

---

## 3. Daily Streak & Habit System
- **Streak Calculation:** If a user logs activity on consecutive calendar days, `streak_count` increments by +1.
- **Streak Bonus:**
  - 7-Day Streak &rarr; **Streak Master Badge (+200 XP)**
  - 30-Day Streak &rarr; **Legendary Streak Badge (+1,000 XP)**
- **Recovery Mechanic:** Missing a single day resets streak to 1 without stripping earned XP or coins, maintaining positive reinforcement.
