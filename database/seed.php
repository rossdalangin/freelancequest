<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/schema.php';

function seedDatabase()
{
    initializeSchema();
    $pdo = Database::getConnection();

    echo "Seeding native database with expanded levels & resources...\n";

    // 1. Seed Levels 0 to 15
    $levels = [
        ['level_number' => 0, 'title' => 'Career Zero', 'subtitle' => 'The Uninitiated', 'description' => 'Beginning your journey from scratch with zero experience.', 'required_xp' => 0, 'certificate_title' => null, 'icon' => '🌱'],
        ['level_number' => 1, 'title' => 'Explorer', 'subtitle' => 'The Discovery Phase', 'description' => 'Understanding Virtual Assistance, freelancing, and digital work fundamentals.', 'required_xp' => 100, 'certificate_title' => 'Freelancing Foundations Certificate', 'icon' => '🧭'],
        ['level_number' => 2, 'title' => 'Digital Survivor', 'subtitle' => 'Core Digital Tools', 'description' => 'Mastering Google Workspace, email, spreadsheets, calendar management, and cloud drive.', 'required_xp' => 500, 'certificate_title' => 'Digital Productivity Certificate', 'icon' => '💻'],
        ['level_number' => 3, 'title' => 'VA Apprentice', 'subtitle' => 'Core Administrative Support', 'description' => 'Data entry, customer support, internet research, lead generation, and CRM basics.', 'required_xp' => 1200, 'certificate_title' => 'Virtual Assistant Fundamentals', 'icon' => '⚡'],
        ['level_number' => 4, 'title' => 'Specialist', 'subtitle' => 'Choose Your Niche Track', 'description' => 'Selecting a specialization: Social Media, Executive Admin, Lead Gen, E-commerce, WordPress.', 'required_xp' => 2200, 'certificate_title' => 'VA Specialist Certification', 'icon' => '🎯'],
        ['level_number' => 5, 'title' => 'Job Ready', 'subtitle' => 'Professional Candidate Branding', 'description' => 'Crafting an irresistible resume, CV, headline, and bio.', 'required_xp' => 3500, 'certificate_title' => 'Job Ready Candidate Certificate', 'icon' => '📝'],
        ['level_number' => 6, 'title' => 'Portfolio Builder', 'subtitle' => 'Visual Proof of Competence', 'description' => 'Building work samples, case studies, testimonials, and a public portfolio website.', 'required_xp' => 5000, 'certificate_title' => 'Certified Portfolio Developer', 'icon' => '🎨'],
        ['level_number' => 7, 'title' => 'Application Academy', 'subtitle' => 'Opportunity Finding', 'description' => 'Navigating job boards, analyzing descriptions, avoiding scams, and proposal strategy.', 'required_xp' => 7000, 'certificate_title' => 'Freelance Job Acquisition Certificate', 'icon' => '🔍'],
        ['level_number' => 8, 'title' => 'Proposal Master', 'subtitle' => 'Winning Client Interest', 'description' => 'Writing irresistible, high-converting client proposals and pitches.', 'required_xp' => 9500, 'certificate_title' => 'Master Proposal Writer', 'icon' => '💌'],
        ['level_number' => 9, 'title' => 'Interview Arena', 'subtitle' => 'Closing the Deal', 'description' => 'Navigating client interview questions, confidence, and objection handling.', 'required_xp' => 12500, 'certificate_title' => 'Interview Specialist Certification', 'icon' => '🎙️'],
        ['level_number' => 10, 'title' => 'Client Acquisition', 'subtitle' => 'Active Prospecting', 'description' => 'Cold email, LinkedIn outreach, networking, and direct client hunt.', 'required_xp' => 16000, 'certificate_title' => 'Client Acquisition Specialist', 'icon' => '🧲'],
        ['level_number' => 11, 'title' => 'Client Negotiator', 'subtitle' => 'Rates & Agreements', 'description' => 'Pricing strategies, contracts, boundary setting, and handling scope creep.', 'required_xp' => 20000, 'certificate_title' => 'Freelance Negotiation Specialist', 'icon' => '🤝'],
        ['level_number' => 12, 'title' => 'Project Manager', 'subtitle' => 'Service Delivery', 'description' => 'Managing workflows, client communication, deadlines, and issue resolution.', 'required_xp' => 25000, 'certificate_title' => 'Virtual Project Management Specialist', 'icon' => '📊'],
        ['level_number' => 13, 'title' => 'Freelancer Pro', 'subtitle' => 'Retention & Retainers', 'description' => 'Turning one-time projects into long-term retainer clients and upselling.', 'required_xp' => 31000, 'certificate_title' => 'Professional Freelancer Certificate', 'icon' => '🚀'],
        ['level_number' => 14, 'title' => 'Freelance Business Owner', 'subtitle' => 'Agency & Systems', 'description' => 'Building SOPs, subcontracting, agency building, and scalable systems.', 'required_xp' => 38000, 'certificate_title' => 'Virtual Agency Specialist', 'icon' => '🏛️'],
        ['level_number' => 15, 'title' => 'Freelance Master', 'subtitle' => 'Apex Industry Leader', 'description' => 'Mastery across client acquisition, agency scaling, business automation, and revenue.', 'required_xp' => 50000, 'certificate_title' => 'FREELANCE MASTER CERTIFIED', 'icon' => '👑'],
    ];

    $stmtLvl = $pdo->prepare("INSERT INTO levels (level_number, title, subtitle, description, required_xp, certificate_title, icon) VALUES (?, ?, ?, ?, ?, ?, ?)");
    foreach ($levels as $l) {
        $stmtLvl->execute([$l['level_number'], $l['title'], $l['subtitle'], $l['description'], $l['required_xp'], $l['certificate_title'], $l['icon']]);
    }

    // 2. Skill Categories & Skills
    $pdo->exec("INSERT INTO skill_categories (name, slug, description, color) VALUES ('Technical Skills', 'technical', 'Software tools and digital applications.', '#3B82F6');");
    $catTech = $pdo->lastInsertId();

    $pdo->exec("INSERT INTO skill_categories (name, slug, description, color) VALUES ('Business & Sales', 'business', 'Client acquisition, pricing, negotiation, and strategy.', '#10B981');");
    $catBiz = $pdo->lastInsertId();

    $pdo->exec("INSERT INTO skill_categories (name, slug, description, color) VALUES ('Professional Skills', 'professional', 'Communication, organization, time management, and reliability.', '#8B5CF6');");
    $catProf = $pdo->lastInsertId();

    $skills = [
        ['category_id' => $catTech, 'name' => 'Google Workspace', 'slug' => 'google-workspace', 'description' => 'Docs, Sheets, Drive, Gmail, Calendar mastery.'],
        ['category_id' => $catTech, 'name' => 'Email Management', 'slug' => 'email-management', 'description' => 'Inbox zero, filtering, labeling, draft writing.'],
        ['category_id' => $catTech, 'name' => 'Data Entry & Research', 'slug' => 'data-entry', 'description' => 'Web research, data cleaning, verification, formatting.'],
        ['category_id' => $catTech, 'name' => 'WordPress & Web VA', 'slug' => 'wordpress-va', 'description' => 'Content publishing, plugin updates, website management.'],
        ['category_id' => $catTech, 'name' => 'Social Media Management', 'slug' => 'social-media', 'description' => 'Content scheduling, Canva graphics, engagement.'],
        ['category_id' => $catBiz, 'name' => 'Proposal Writing', 'slug' => 'proposal-writing', 'description' => 'Crafting high-converting client pitches.'],
        ['category_id' => $catBiz, 'name' => 'Client Negotiation', 'slug' => 'client-negotiation', 'description' => 'Hourly rates, project scoping, contract agreements.'],
        ['category_id' => $catBiz, 'name' => 'Lead Generation', 'slug' => 'lead-generation', 'description' => 'Finding decision makers, contact mining, lead verification.'],
        ['category_id' => $catProf, 'name' => 'Client Communication', 'slug' => 'client-communication', 'description' => 'Professional emails, slack chat etiquette, updates.'],
        ['category_id' => $catProf, 'name' => 'Time & Task Management', 'slug' => 'time-management', 'description' => 'Prioritization, Asana/Trello, meeting deadlines.'],
    ];

    $stmtSk = $pdo->prepare("INSERT INTO skills (skill_category_id, name, slug, description) VALUES (?, ?, ?, ?)");
    foreach ($skills as $s) {
        $stmtSk->execute([$s['category_id'], $s['name'], $s['slug'], $s['description']]);
    }

    // 3. Courses & Expanded Lessons for Levels 1 - 15
    $stmtC = $pdo->prepare("INSERT INTO courses (title, slug, description, level_number, category) VALUES (?, ?, ?, ?, ?)");
    $stmtC->execute(['Freelancing & VA Foundations', 'foundations', 'Master the basics of remote virtual assistance.', 1, 'Foundations']);
    $c1 = $pdo->lastInsertId();

    $stmtC->execute(['Digital Productivity & Tools', 'digital-productivity', 'Essential tools every Virtual Assistant relies on daily.', 2, 'Tools']);
    $c2 = $pdo->lastInsertId();

    $stmtC->execute(['Core Administrative Support', 'admin-support', 'High demand core VA tasks and client workflows.', 3, 'Admin']);
    $c3 = $pdo->lastInsertId();

    $stmtC->execute(['Specialist Tracks & Niche Mastery', 'specialist-tracks', 'Deep dive into specialized high-value VA niches.', 4, 'Specialist']);
    $c4 = $pdo->lastInsertId();

    $stmtC->execute(['Client Acquisition & Business Mastery', 'client-acquisition-mastery', 'Advanced sales, negotiation, pricing, and agency building.', 10, 'Business']);
    $c5 = $pdo->lastInsertId();

    $lessonsData = [
        // Level 1
        ['course_id' => $c1, 'level' => 1, 'title' => 'What is a Virtual Assistant?', 'slug' => 'what-is-a-va', 'summary' => 'Understand the role, services, and opportunities of modern VAs.', 'xp' => 50, 'coins' => 10],
        ['course_id' => $c1, 'level' => 1, 'title' => 'Freelancing vs Traditional Employment', 'slug' => 'freelancing-vs-employment', 'summary' => 'Key differences in mindset, taxes, freedom, and responsibility.', 'xp' => 50, 'coins' => 10],
        ['course_id' => $c1, 'level' => 1, 'title' => 'Types of Clients & Platforms', 'slug' => 'types-of-clients-and-platforms', 'summary' => 'Upwork, Fiverr, OnlineJobs.ph, and direct client outreach explained.', 'xp' => 50, 'coins' => 10],
        ['course_id' => $c1, 'level' => 1, 'title' => 'Common VA Career Paths & Niche Specializations', 'slug' => 'va-career-paths', 'summary' => 'Explore Admin, Social Media, Real Estate, E-commerce, and Executive VA roles.', 'xp' => 50, 'coins' => 10],
        ['course_id' => $c1, 'level' => 1, 'title' => 'Essential Digital Professionalism', 'slug' => 'digital-professionalism', 'summary' => 'Response speed, tone of voice, netiquette, and reliability.', 'xp' => 50, 'coins' => 10],
        ['course_id' => $c1, 'level' => 1, 'title' => 'Setting Up Your Home Office & Workstation', 'slug' => 'home-office-setup', 'summary' => 'Hardware, backup internet, power backup, and ergonomics.', 'xp' => 50, 'coins' => 10],
        ['course_id' => $c1, 'level' => 1, 'title' => 'Understanding Freelancing Terminology', 'slug' => 'freelancing-terminology', 'summary' => 'SOW, Retainer, Scope Creep, Hourly vs Fixed, Deliverables.', 'xp' => 50, 'coins' => 10],
        ['course_id' => $c1, 'level' => 1, 'title' => 'Basic Cybersecurity & Password Hygiene', 'slug' => 'cybersecurity-basics', 'summary' => 'LastPass, 2FA, phishing awareness, protecting client credentials.', 'xp' => 50, 'coins' => 10],
        ['course_id' => $c1, 'level' => 1, 'title' => 'Overcoming Imposter Syndrome as a Beginner', 'slug' => 'overcoming-imposter-syndrome', 'summary' => 'Building confidence through continuous action and competence.', 'xp' => 50, 'coins' => 10],
        ['course_id' => $c1, 'level' => 1, 'title' => 'Level 1 Summary: Your Career Roadmap', 'slug' => 'level-1-summary', 'summary' => 'Putting foundational knowledge into practice.', 'xp' => 75, 'coins' => 20],

        // Level 2
        ['course_id' => $c2, 'level' => 2, 'title' => 'Mastering Google Workspace (Gmail & Drive)', 'slug' => 'google-workspace-mastery', 'summary' => 'Inbox Zero, cloud file organization, sharing permissions.', 'xp' => 60, 'coins' => 12],
        ['course_id' => $c2, 'level' => 2, 'title' => 'Google Docs Professional Document Formatting', 'slug' => 'google-docs-formatting', 'summary' => 'Creating clean corporate reports, proposals, and SOPs.', 'xp' => 60, 'coins' => 12],
        ['course_id' => $c2, 'level' => 2, 'title' => 'Google Sheets for Beginners: Formulas & Sorting', 'slug' => 'google-sheets-beginners', 'summary' => 'SUM, AVERAGE, VLOOKUP basics, data cleaning and filters.', 'xp' => 60, 'coins' => 12],
        ['course_id' => $c2, 'level' => 2, 'title' => 'Calendar Management & Scheduling Etiquette', 'slug' => 'calendar-management', 'summary' => 'Google Calendar, Calendly, time zones, avoiding double bookings.', 'xp' => 60, 'coins' => 12],
        ['course_id' => $c2, 'level' => 2, 'title' => 'Professional Email Drafting & Inbox Zero Method', 'slug' => 'professional-email-drafting', 'summary' => 'Structuring clear, concise emails that clients love to read.', 'xp' => 60, 'coins' => 12],
        ['course_id' => $c2, 'level' => 2, 'title' => 'Effective Internet Research Strategies', 'slug' => 'internet-research-strategies', 'summary' => 'Advanced Google search operators, source verification, fact checking.', 'xp' => 60, 'coins' => 12],
        ['course_id' => $c2, 'level' => 2, 'title' => 'Task Management Tools (Asana, Trello, ClickUp)', 'slug' => 'task-management-tools', 'summary' => 'Creating task boards, assignees, due dates, and status updates.', 'xp' => 60, 'coins' => 12],
        ['course_id' => $c2, 'level' => 2, 'title' => 'Time Tracking & Productivity Apps', 'slug' => 'time-tracking-apps', 'summary' => 'Clockify, Hubstaff, Toggl Track best practices.', 'xp' => 60, 'coins' => 12],
        ['course_id' => $c2, 'level' => 2, 'title' => 'Slack & Microsoft Teams Etiquette', 'slug' => 'slack-teams-etiquette', 'summary' => 'Channel communication, async updates, thread management.', 'xp' => 60, 'coins' => 12],
        ['course_id' => $c2, 'level' => 2, 'title' => 'Level 2 Review: Digital Productivity System', 'slug' => 'level-2-review', 'summary' => 'Synthesizing tool proficiency into daily workflow.', 'xp' => 80, 'coins' => 25],

        // Level 3
        ['course_id' => $c3, 'level' => 3, 'title' => 'Data Entry Techniques & Accuracy Control', 'slug' => 'data-entry-techniques', 'summary' => 'Speed vs accuracy, data validation rules, double checking.', 'xp' => 70, 'coins' => 15],
        ['course_id' => $c3, 'level' => 3, 'title' => 'Customer Support Fundamentals (Email & Chat)', 'slug' => 'customer-support-fundamentals', 'summary' => 'Empathy, macros, ticket resolution, handling angry buyers.', 'xp' => 70, 'coins' => 15],
        ['course_id' => $c3, 'level' => 3, 'title' => 'Lead Generation 101: Finding Prospect Contact Info', 'slug' => 'lead-generation-101', 'summary' => 'Apollo.io, Hunter.io, LinkedIn Sales Navigator techniques.', 'xp' => 70, 'coins' => 15],
        ['course_id' => $c3, 'level' => 3, 'title' => 'CRM Basics (HubSpot, Salesforce, Zoho)', 'slug' => 'crm-basics', 'summary' => 'Updating contact records, deal stages, and call notes.', 'xp' => 70, 'coins' => 15],
        ['course_id' => $c3, 'level' => 3, 'title' => 'Executive Travel & Accommodation Booking', 'slug' => 'travel-booking-va', 'summary' => 'Itinerary creation, flights, hotels, time zone considerations.', 'xp' => 70, 'coins' => 15],
        ['course_id' => $c3, 'level' => 3, 'title' => 'Meeting Preparation & Minute Taking (SOP)', 'slug' => 'meeting-prep-minutes', 'summary' => 'Agenda creation, recording action items, transcript summary.', 'xp' => 70, 'coins' => 15],
        ['course_id' => $c3, 'level' => 3, 'title' => 'File Organization & Naming Conventions', 'slug' => 'file-organization-sop', 'summary' => 'Folder structures, version control (v1, v2_final), cloud backups.', 'xp' => 70, 'coins' => 15],
        ['course_id' => $c3, 'level' => 3, 'title' => 'Social Media VA Basics (Canva & Scheduling)', 'slug' => 'social-media-va-basics', 'summary' => 'Creating graphics on Canva, scheduling posts on Buffer/Metricool.', 'xp' => 70, 'coins' => 15],
        ['course_id' => $c3, 'level' => 3, 'title' => 'WordPress Publishing & Basic Content Updates', 'slug' => 'wordpress-publishing-basics', 'summary' => 'Gutenberg editor, adding featured images, publishing blogs.', 'xp' => 70, 'coins' => 15],
        ['course_id' => $c3, 'level' => 3, 'title' => 'Level 3 Review: VA Apprenticeship Mastery', 'slug' => 'level-3-review', 'summary' => 'Becoming a fully competent generalist Virtual Assistant.', 'xp' => 100, 'coins' => 30],

        // Level 4 Specialist
        ['course_id' => $c4, 'level' => 4, 'title' => 'Executive VA Masterclass', 'slug' => 'executive-va-masterclass', 'summary' => 'High level C-suite support, inbox management, gatekeeping, confidential operations.', 'xp' => 120, 'coins' => 35],
        ['course_id' => $c4, 'level' => 4, 'title' => 'Social Media & Graphic Creation Specialist', 'slug' => 'social-media-specialist-track', 'summary' => 'Canva Pro design secrets, content calendars, analytics reporting, and community moderation.', 'xp' => 120, 'coins' => 35],

        // Level 10-15 Advanced Business
        ['course_id' => $c5, 'level' => 10, 'title' => 'High Ticket Cold Outreach & Pitching', 'slug' => 'cold-outreach-pitching', 'summary' => 'Finding decision makers, personalized Loom videos, cold email templates.', 'xp' => 150, 'coins' => 50],
        ['course_id' => $c5, 'level' => 14, 'title' => 'Building & Scaling Your Virtual VA Agency', 'slug' => 'building-virtual-va-agency', 'summary' => 'Subcontracting, SOP systems, pricing packages, profit margins, team leadership.', 'xp' => 250, 'coins' => 100],
    ];

    $stmtLes = $pdo->prepare("INSERT INTO lessons (course_id, level_number, title, slug, summary, content, xp_reward, coin_reward, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmtQz = $pdo->prepare("INSERT INTO quizzes (lesson_id, title, passing_score, xp_reward) VALUES (?, ?, 70, 100)");
    $stmtQn = $pdo->prepare("INSERT INTO questions (quiz_id, question_text, options, correct_option, explanation) VALUES (?, ?, ?, ?, ?)");

    $sort = 1;
    foreach ($lessonsData as $ld) {
        $content = "## What is " . $ld['title'] . "?\n\nThis comprehensive lesson covers actionable strategies, practical step-by-step examples, and practical workflows for Virtual Assistants.\n\n### Core Principles\n- **Understand the Client Need**: Always focus on saving the client time.\n- **Attention to Detail**: Precision builds trust.\n- **Proactive Communication**: Update early and often.\n\n### Practical Example\nWhen tasked with managing inbox messages, categorize emails into: Action Required, Waiting on Response, Reference/Archive.\n\n### Real World Mission\nApply these learnings in the accompanying interactive mission below!";

        $stmtLes->execute([$ld['course_id'], $ld['level'], $ld['title'], $ld['slug'], $ld['summary'], $content, $ld['xp'], $ld['coins'], $sort++]);
        $lesId = $pdo->lastInsertId();

        $stmtQz->execute([$lesId, 'Knowledge Check: ' . $ld['title']]);
        $quizId = $pdo->lastInsertId();

        $options = json_encode(['Save time for the client and communicate proactively', 'Wait for instructions without taking initiative', 'Charge high rates before delivering work', 'Ignore deadlines if busy']);
        $stmtQn->execute([$quizId, 'What is the primary responsibility of a Virtual Assistant when handling client requests?', $options, 'Save time for the client and communicate proactively', 'Virtual Assistants succeed by saving clients time and communicating clearly.']);

        $options2 = json_encode(['Responding within agreed timelines and proofreading messages', 'Using unprofessional slang in business emails', 'Sharing client credentials publicly', 'Missing meetings without notice']);
        $stmtQn->execute([$quizId, 'Which of the following is considered a best practice for digital professionalism?', $options2, 'Responding within agreed timelines and proofreading messages', 'Prompt communication and accuracy build high professional trust.']);

        $options3 = json_encode(['Inform the client immediately and propose a practical solution', 'Hide the mistake', 'Blame external factors', 'Abandon the project']);
        $stmtQn->execute([$quizId, 'What should you do if you encounter an unexpected problem during a client assignment?', $options3, 'Inform the client immediately and propose a practical solution', 'Clients appreciate transparency paired with immediate solutions.']);
    }

    // 4. 50 Interactive Missions
    $stmtM = $pdo->prepare("INSERT INTO missions (level_number, title, type, scenario, instructions, data, xp_reward, coin_reward) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    for ($i = 1; $i <= 50; $i++) {
        $levelNum = (($i - 1) % 15) + 1;
        $type = ($i % 3 == 0) ? 'interactive' : (($i % 3 == 1) ? 'proposal' : 'client_sim');
        $scenario = "Client Alex asks you to handle an urgent task: '{$i} - We need to re-organize our customer leads spreadsheet and draft a quick follow-up proposal.'";
        $instructions = "Review the client's request, identify key deliverables, format the output cleanly, and submit your response for automated evaluation.";
        $data = json_encode([
            'client_name' => 'Alex Rivera (CEO, TechFlow)',
            'task_type' => 'Data Formatting & Email Draft',
            'sample_data' => "Lead Name: John Doe | Email: john@tech.com | Status: Pending\nLead Name: Sarah Smith | Email: sarah@biz.org | Status: Hot",
            'correct_answer' => 'Properly formatted CSV / clean email draft',
        ]);

        $stmtM->execute([$levelNum, "Mission #{$i}: Real World Scenario Exercise {$i}", $type, $scenario, $instructions, $data, 150 + ($i * 10), 20 + ($i * 2)]);
    }

    // 5. 20 Badges
    $badges = [
        ['name' => 'First Step', 'slug' => 'first-step', 'description' => 'Completed your very first lesson on FreelanceQuest!', 'icon' => '🚀', 'category' => 'learning', 'xp_bonus' => 100],
        ['name' => 'Resume Ready', 'slug' => 'resume-ready', 'description' => 'Built your professional candidate resume.', 'icon' => '📄', 'category' => 'career', 'xp_bonus' => 150],
        ['name' => 'Portfolio Builder', 'slug' => 'portfolio-builder', 'description' => 'Created and published your public portfolio.', 'icon' => '🌐', 'category' => 'career', 'xp_bonus' => 200],
        ['name' => 'Proposal Pro', 'slug' => 'proposal-pro', 'description' => 'Completed 10 proposal challenges with top scores.', 'icon' => '✍️', 'category' => 'sales', 'xp_bonus' => 250],
        ['name' => 'Interview Survivor', 'slug' => 'interview-survivor', 'description' => 'Passed your first client interview simulation.', 'icon' => '🎙️', 'category' => 'sales', 'xp_bonus' => 300],
        ['name' => 'Client Hunter', 'slug' => 'client-hunter', 'description' => 'Completed 50 outreach missions.', 'icon' => '🎯', 'category' => 'sales', 'xp_bonus' => 350],
        ['name' => 'First Client Win', 'slug' => 'first-client', 'description' => 'Successfully won your first simulated client contract!', 'icon' => '🏆', 'category' => 'career', 'xp_bonus' => 500],
        ['name' => 'Five-Star Freelancer', 'slug' => 'five-star-freelancer', 'description' => 'Achieved flawless ratings on 5 consecutive projects.', 'icon' => '⭐', 'category' => 'reputation', 'xp_bonus' => 400],
        ['name' => 'Streak Master', 'slug' => 'streak-master', 'description' => 'Maintained a active 7-day learning streak!', 'icon' => '🔥', 'category' => 'dedication', 'xp_bonus' => 200],
        ['name' => 'Legendary Streak', 'slug' => 'legendary-streak', 'description' => 'Maintained an incredible 30-day streak!', 'icon' => '⚡', 'category' => 'dedication', 'xp_bonus' => 1000],
        ['name' => 'Community Hero', 'slug' => 'community-hero', 'description' => 'Helped 10 fellow learners in community discussions.', 'icon' => '🤝', 'category' => 'social', 'xp_bonus' => 200],
        ['name' => 'Certificated Scholar', 'slug' => 'certificated-scholar', 'description' => 'Earned 3 official level completion certificates.', 'icon' => '📜', 'category' => 'achievement', 'xp_bonus' => 300],
        ['name' => 'Speedy Typist', 'slug' => 'speedy-typist', 'description' => 'Passed data entry speed and accuracy mission with 100% precision.', 'icon' => '⌨️', 'category' => 'skill', 'xp_bonus' => 150],
        ['name' => 'Google Master', 'slug' => 'google-master', 'description' => 'Mastered Google Workspace tools.', 'icon' => '📊', 'category' => 'skill', 'xp_bonus' => 200],
        ['name' => 'Inbox Zero Ninja', 'slug' => 'inbox-ninja', 'description' => 'Cleaned up complex email client inbox in mission mode.', 'icon' => '📬', 'category' => 'skill', 'xp_bonus' => 150],
        ['name' => 'WordPress Expert', 'slug' => 'wordpress-expert', 'description' => 'Completed WordPress publishing and formatting exercises.', 'icon' => '🖥️', 'category' => 'skill', 'xp_bonus' => 250],
        ['name' => 'Lead Gen Wizard', 'slug' => 'lead-gen-wizard', 'description' => 'Extracted 50 verified leads without error.', 'icon' => '🔍', 'category' => 'skill', 'xp_bonus' => 300],
        ['name' => 'Master Negotiator', 'slug' => 'master-negotiator', 'description' => 'Negotiated client rate from $10/hr to $25/hr in simulator.', 'icon' => '💎', 'category' => 'sales', 'xp_bonus' => 400],
        ['name' => 'Agency Starter', 'slug' => 'agency-starter', 'description' => 'Created virtual freelancing agency SOPs.', 'icon' => '🏛️', 'category' => 'business', 'xp_bonus' => 500],
        ['name' => 'Freelance Master', 'slug' => 'freelance-master', 'description' => 'Completed the entire FreelanceQuest career path!', 'icon' => '👑', 'category' => 'apex', 'xp_bonus' => 2500],
    ];

    $stmtB = $pdo->prepare("INSERT INTO badges (name, slug, description, icon, category, xp_bonus) VALUES (?, ?, ?, ?, ?, ?)");
    foreach ($badges as $b) {
        $stmtB->execute([$b['name'], $b['slug'], $b['description'], $b['icon'], $b['category'], $b['xp_bonus']]);
    }

    // 6. Resources & Groups
    $pdo->exec("INSERT INTO resources (level_number, title, description, type, file_content_or_url, is_premium) VALUES (1, 'Virtual Assistant Starter Playbook (PDF)', 'Complete beginner guide to setting up your VA career.', 'pdf', 'https://freelancequest.test/resources/va-starter-playbook.pdf', 0);");
    $pdo->exec("INSERT INTO resources (level_number, title, description, type, file_content_or_url, is_premium) VALUES (2, 'Google Workspace Keyboard Shortcuts Cheat Sheet', 'Boost your typing speed and efficiency instantly.', 'cheat_sheet', 'https://freelancequest.test/resources/google-shortcuts.pdf', 0);");
    $pdo->exec("INSERT INTO resources (level_number, title, description, type, file_content_or_url, is_premium) VALUES (3, 'Client Onboarding SOP Checklist', 'Professional checklist for onboarding new client projects.', 'template', 'https://freelancequest.test/resources/onboarding-sop.docx', 0);");
    $pdo->exec("INSERT INTO resources (level_number, title, description, type, file_content_or_url, is_premium) VALUES (5, 'High-Converting VA Resume Template (Word/PDF)', 'ATS-friendly resume layout designed specifically for remote VAs.', 'template', 'https://freelancequest.test/resources/va-resume-template.docx', 1);");
    $pdo->exec("INSERT INTO resources (level_number, title, description, type, file_content_or_url, is_premium) VALUES (8, '10 Winning Upwork Proposal Scripts', 'Proven proposal templates that earned over $100k in freelancing.', 'script', 'https://freelancequest.test/resources/winning-proposals.pdf', 1);");
    $pdo->exec("INSERT INTO resources (level_number, title, description, type, file_content_or_url, is_premium) VALUES (11, 'Client Service Agreement & Contract Template', 'Standard freelance agreement covering payment terms and scope limits.', 'template', 'https://freelancequest.test/resources/contract-template.docx', 1);");
    $pdo->exec("INSERT INTO resources (level_number, title, description, type, file_content_or_url, is_premium) VALUES (14, 'Virtual Agency SOP Operations Manual', 'Standard Operating Procedures for hiring subcontractors and managing agency workflows.', 'manual', 'https://freelancequest.test/resources/agency-sop-manual.pdf', 1);");

    $pdo->exec("INSERT INTO groups (name, slug, description, icon) VALUES ('Beginner VA Lounge', 'beginner-va', 'A welcoming community for beginners starting their remote journey.', '🌱');");
    $pdo->exec("INSERT INTO groups (name, slug, description, icon) VALUES ('WordPress & Tech VAs', 'tech-vas', 'Technical support, website management, and troubleshooting.', '💻');");
    $pdo->exec("INSERT INTO groups (name, slug, description, icon) VALUES ('Social Media & Content VAs', 'social-content-vas', 'Canva graphics, content calendars, and social growth.', '🎨');");

    // 7. Users
    $pass = password_hash('password', PASSWORD_BCRYPT);
    $pdo->exec("INSERT INTO users (name, email, password, role, username, headline, bio, level, xp, coins, streak_count, subscription_tier) VALUES ('Admin Boss', 'admin@freelancequest.com', '$pass', 'admin', 'adminboss', 'Lead Product Architect & Instructor', 'Building top tier VAs.', 15, 50000, 9999, 30, 'master');");
    $pdo->exec("INSERT INTO users (name, email, password, role, username, headline, bio, level, xp, coins, streak_count, subscription_tier) VALUES ('Maria Santos', 'maria@example.com', '$pass', 'student', 'mariasantos', 'Aspiring Administrative & Research VA', 'Eager to help founders.', 3, 1350, 280, 12, 'pro');");

    echo "Expanded database seeding completed successfully.\n";
}

if (basename(__FILE__) == basename($_SERVER['PHP_SELF'])) {
    seedDatabase();
}
