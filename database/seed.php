<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/schema.php';

function seedDatabase()
{
    initializeSchema();
    $pdo = Database::getConnection();

    echo "Seeding native database with expanded levels, resources & SOPs...\n";

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

    // 3. 15 Course Modules (One for each Level 1-15)
    $courseDefs = [
        1 => ['title' => 'Freelancing & VA Foundations', 'slug' => 'foundations', 'desc' => 'Master the fundamentals of remote virtual assistance and freelance mindsets.', 'cat' => 'Foundations'],
        2 => ['title' => 'Digital Productivity & Workspace Tools', 'slug' => 'digital-productivity', 'desc' => 'Google Workspace, cloud organization, spreadsheets, and calendar management.', 'cat' => 'Tools'],
        3 => ['title' => 'Core Administrative Support', 'slug' => 'admin-support', 'desc' => 'High-demand administrative support, data entry, research, and CRM workflows.', 'cat' => 'Admin'],
        4 => ['title' => 'Specialist Tracks & Niche Specialization', 'slug' => 'specialist-tracks', 'desc' => 'Deep dive into high-value niches: Executive Admin, Social Media, Tech, Real Estate.', 'cat' => 'Specialist'],
        5 => ['title' => 'Candidate Branding & Resume Mastery', 'slug' => 'candidate-branding', 'desc' => 'Crafting ATS-optimized resumes, LinkedIn profiles, and candidate positioning.', 'cat' => 'Branding'],
        6 => ['title' => 'Portfolio Development & Case Studies', 'slug' => 'portfolio-development', 'desc' => 'Building tangible work samples, case studies, and a live public portfolio website.', 'cat' => 'Portfolio'],
        7 => ['title' => 'Opportunity Hunting & Job Boards', 'slug' => 'opportunity-hunting', 'desc' => 'Navigating job marketplaces, analyzing job descriptions, and avoiding scams.', 'cat' => 'Career'],
        8 => ['title' => 'Proposal Writing & High-Converting Pitches', 'slug' => 'proposal-writing', 'desc' => 'Drafting client proposals, cover letters, and value-driven pitches.', 'cat' => 'Sales'],
        9 => ['title' => 'Client Interview Arena & Objection Handling', 'slug' => 'interview-arena', 'desc' => 'Passing client interviews with the STAR method and handling rate objections.', 'cat' => 'Sales'],
        10 => ['title' => 'Direct Cold Outreach & Lead Prospecting', 'slug' => 'cold-outreach', 'desc' => 'Targeting decision makers, personalized Loom videos, and cold email sequences.', 'cat' => 'Sales'],
        11 => ['title' => 'Rate Negotiation & Service Agreements', 'slug' => 'negotiation-contracts', 'desc' => 'Setting hourly vs retainer prices, protecting scope limits, and contracts.', 'cat' => 'Business'],
        12 => ['title' => 'Virtual Project Management & Client Operations', 'slug' => 'project-management', 'desc' => 'Managing client workflows, EOD updates, Slack etiquette, and deadlines.', 'cat' => 'Management'],
        13 => ['title' => 'Client Retention & Recurring Retainers', 'slug' => 'retention-retainers', 'desc' => 'Converting one-time projects into predictable $1,000+/mo recurring retainer contracts.', 'cat' => 'Business'],
        14 => ['title' => 'Virtual Agency Building & Systems', 'slug' => 'agency-building', 'desc' => 'Creating agency SOPs, hiring subcontractors, profit margins, and team leadership.', 'cat' => 'Agency'],
        15 => ['title' => 'Apex Freelance Mastery & Business Automation', 'slug' => 'apex-mastery', 'desc' => 'Systemizing business operations, AI automation, and sustainable 6-figure revenue.', 'cat' => 'Apex'],
    ];

    $stmtC = $pdo->prepare("INSERT INTO courses (title, slug, description, level_number, category) VALUES (?, ?, ?, ?, ?)");
    $courseIds = [];
    foreach ($courseDefs as $lvl => $cd) {
        $stmtC->execute([$cd['title'], $cd['slug'], $cd['desc'], $lvl, $cd['cat']]);
        $courseIds[$lvl] = $pdo->lastInsertId();
    }

    $lessonsData = [
        // Level 1: Foundations
        ['course_id' => $courseIds[1], 'level' => 1, 'title' => 'What is a Virtual Assistant?', 'slug' => 'what-is-a-va', 'summary' => 'Understand the role, services, and opportunities of modern VAs.', 'xp' => 50, 'coins' => 10],
        ['course_id' => $courseIds[1], 'level' => 1, 'title' => 'Freelancing vs Traditional Employment', 'slug' => 'freelancing-vs-employment', 'summary' => 'Key differences in mindset, taxes, freedom, and responsibility.', 'xp' => 50, 'coins' => 10],
        ['course_id' => $courseIds[1], 'level' => 1, 'title' => 'Types of Clients & Platforms', 'slug' => 'types-of-clients-and-platforms', 'summary' => 'Upwork, Fiverr, OnlineJobs.ph, and direct client outreach explained.', 'xp' => 50, 'coins' => 10],
        ['course_id' => $courseIds[1], 'level' => 1, 'title' => 'Common VA Career Paths & Niche Specializations', 'slug' => 'va-career-paths', 'summary' => 'Explore Admin, Social Media, Real Estate, E-commerce, and Executive VA roles.', 'xp' => 50, 'coins' => 10],
        ['course_id' => $courseIds[1], 'level' => 1, 'title' => 'Essential Digital Professionalism', 'slug' => 'digital-professionalism', 'summary' => 'Response speed, tone of voice, netiquette, and reliability.', 'xp' => 50, 'coins' => 10],

        // Level 2: Digital Tools
        ['course_id' => $courseIds[2], 'level' => 2, 'title' => 'Mastering Google Workspace (Gmail & Drive)', 'slug' => 'google-workspace-mastery', 'summary' => 'Inbox Zero, cloud file organization, sharing permissions.', 'xp' => 60, 'coins' => 12],
        ['course_id' => $courseIds[2], 'level' => 2, 'title' => 'Google Docs Professional Document Formatting', 'slug' => 'google-docs-formatting', 'summary' => 'Creating clean corporate reports, proposals, and SOPs.', 'xp' => 60, 'coins' => 12],
        ['course_id' => $courseIds[2], 'level' => 2, 'title' => 'Google Sheets for Beginners: Formulas & Sorting', 'slug' => 'google-sheets-beginners', 'summary' => 'SUM, AVERAGE, VLOOKUP basics, data cleaning and filters.', 'xp' => 60, 'coins' => 12],
        ['course_id' => $courseIds[2], 'level' => 2, 'title' => 'Calendar Management & Scheduling Etiquette', 'slug' => 'calendar-management', 'summary' => 'Google Calendar, Calendly, time zones, avoiding double bookings.', 'xp' => 60, 'coins' => 12],

        // Level 3: Admin Support
        ['course_id' => $courseIds[3], 'level' => 3, 'title' => 'Data Entry Techniques & Accuracy Control', 'slug' => 'data-entry-techniques', 'summary' => 'Speed vs accuracy, data validation rules, double checking.', 'xp' => 70, 'coins' => 15],
        ['course_id' => $courseIds[3], 'level' => 3, 'title' => 'Customer Support Fundamentals (Email & Chat)', 'slug' => 'customer-support-fundamentals', 'summary' => 'Empathy, macros, ticket resolution, handling angry buyers.', 'xp' => 70, 'coins' => 15],
        ['course_id' => $courseIds[3], 'level' => 3, 'title' => 'Lead Generation 101: Finding Prospect Contact Info', 'slug' => 'lead-generation-101', 'summary' => 'Apollo.io, Hunter.io, LinkedIn Sales Navigator techniques.', 'xp' => 70, 'coins' => 15],
        ['course_id' => $courseIds[3], 'level' => 3, 'title' => 'CRM Basics (HubSpot, Salesforce, Zoho)', 'slug' => 'crm-basics', 'summary' => 'Updating contact records, deal stages, and call notes.', 'xp' => 70, 'coins' => 15],

        // Level 4: Specialist Tracks
        ['course_id' => $courseIds[4], 'level' => 4, 'title' => 'Executive VA Masterclass & Gatekeeping', 'slug' => 'executive-va-masterclass', 'summary' => 'High level C-suite support, inbox management, gatekeeping, confidential operations.', 'xp' => 120, 'coins' => 35],
        ['course_id' => $courseIds[4], 'level' => 4, 'title' => 'Social Media & Graphic Creation Specialist', 'slug' => 'social-media-specialist-track', 'summary' => 'Canva Pro design secrets, content calendars, analytics reporting, and community moderation.', 'xp' => 120, 'coins' => 35],

        // Level 5: Branding
        ['course_id' => $courseIds[5], 'level' => 5, 'title' => 'ATS Resume & Headline Optimization', 'slug' => 'ats-resume-mastery', 'summary' => 'Formatting resumes that pass ATS screeners and impress hiring managers.', 'xp' => 130, 'coins' => 40],
        ['course_id' => $courseIds[5], 'level' => 5, 'title' => 'LinkedIn Profile Positioning for Freelancers', 'slug' => 'linkedin-profile-mastery', 'summary' => 'Optimizing headlines, bios, featured media, and skill endorsements.', 'xp' => 130, 'coins' => 40],

        // Level 6: Portfolio
        ['course_id' => $courseIds[6], 'level' => 6, 'title' => 'Building Work Samples & Case Studies', 'slug' => 'work-samples-case-studies', 'summary' => 'Converting simulation mission deliverables into high-impact portfolio proof.', 'xp' => 140, 'coins' => 45],

        // Level 7: Opportunity Hunting
        ['course_id' => $courseIds[7], 'level' => 7, 'title' => 'Navigating Job Boards & Avoiding Scams', 'slug' => 'job-boards-scam-detection', 'summary' => 'Identifying verified client opportunities and avoiding common freelancing scams.', 'xp' => 140, 'coins' => 45],

        // Level 8: Proposal Writing
        ['course_id' => $courseIds[8], 'level' => 8, 'title' => 'High-Converting Pitch & Cover Letter Writing', 'slug' => 'cover-letter-pitch-mastery', 'summary' => 'Structuring proposals that address client pain points and win interviews.', 'xp' => 150, 'coins' => 50],

        // Level 9: Interviews
        ['course_id' => $courseIds[9], 'level' => 9, 'title' => 'Client Interview Prep & STAR Framework', 'slug' => 'client-interview-star-framework', 'summary' => 'Answering situational interview questions with confidence and clarity.', 'xp' => 150, 'coins' => 50],

        // Level 10: Direct Outreach
        ['course_id' => $courseIds[10], 'level' => 10, 'title' => 'High Ticket Cold Outreach & Pitching', 'slug' => 'cold-outreach-pitching', 'summary' => 'Finding decision makers, personalized Loom videos, cold email templates.', 'xp' => 150, 'coins' => 50],

        // Level 11: Rate Negotiation
        ['course_id' => $courseIds[11], 'level' => 11, 'title' => 'Rate Negotiation & Service Agreements', 'slug' => 'rate-negotiation-agreements', 'summary' => 'Hourly vs retainer pricing, scope protection, and contract execution.', 'xp' => 160, 'coins' => 55],

        // Level 12: Project Management
        ['course_id' => $courseIds[12], 'level' => 12, 'title' => 'Virtual Project Management & Client Operations', 'slug' => 'virtual-project-management-sop', 'summary' => 'EOD updates, Slack etiquette, deadline tracking, and task prioritization.', 'xp' => 170, 'coins' => 60],

        // Level 13: Retainers
        ['course_id' => $courseIds[13], 'level' => 13, 'title' => 'Client Retention & Monthly Retainers', 'slug' => 'client-retention-retainers', 'summary' => 'Turning one-time projects into predictable $1,000+/mo recurring revenue.', 'xp' => 180, 'coins' => 70],

        // Level 14: Agency
        ['course_id' => $courseIds[14], 'level' => 14, 'title' => 'Building & Scaling Your Virtual VA Agency', 'slug' => 'building-virtual-va-agency', 'summary' => 'Subcontracting, SOP systems, pricing packages, profit margins, team leadership.', 'xp' => 250, 'coins' => 100],

        // Level 15: Apex Master
        ['course_id' => $courseIds[15], 'level' => 15, 'title' => 'Apex Freelance Mastery & Business Automation', 'slug' => 'apex-freelance-mastery', 'summary' => 'Systemizing client acquisition, delegation, financial management, and sustainable growth.', 'xp' => 300, 'coins' => 150],
    ];

    $stmtLes = $pdo->prepare("INSERT INTO lessons (course_id, level_number, title, slug, summary, content, xp_reward, coin_reward, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmtQz = $pdo->prepare("INSERT INTO quizzes (lesson_id, title, passing_score, xp_reward) VALUES (?, ?, 70, 100)");
    $stmtQn = $pdo->prepare("INSERT INTO questions (quiz_id, question_text, options, correct_option, explanation) VALUES (?, ?, ?, ?, ?)");

    $sort = 1;
    foreach ($lessonsData as $ld) {
        $content = "## Executive Masterclass: " . $ld['title'] . "\n\nWelcome to **" . $ld['title'] . "**, a core module in the FREELANCEQUEST Virtual Assistant & Freelance Career Simulator. This masterclass provides actionable strategies, Standard Operating Procedures (SOPs), tool walkthroughs, and real-world client scenarios.\n\n### 1. Module Overview & Executive Objectives\n- **Primary Purpose**: Develop high-demand competence in **" . strtolower($ld['title']) . "** to deliver immediate operational ROI to clients.\n- **Client Value Proposition**: Eliminate 5-15 hours of weekly friction for busy founders, C-suite executives, and agencies.\n- **Core Mindset**: Transition from passive task-taker to proactive operations partner.\n\n### 2. Standard Operating Procedure (SOP) Blueprint\n1. **Scope Analysis**: Carefully review client briefs. Identify core deliverables, deadlines, and tool constraints before taking action.\n2. **File & Folder Systematization**: Maintain structured cloud organization (`/Client_Name/Project_Title/YYYY-MM-DD_Deliverable_v1.0`).\n3. **Asynchronous Communication Protocol**: Send structured daily end-of-day (EOD) updates via Slack/Email:\n   - *Done Today*: Deliverables completed with proof links.\n   - *Next*: High-priority tasks scheduled for tomorrow.\n   - *Blockers*: Clarifying questions or missing credentials required from client.\n4. **Zero-Defect Quality Control**: Verify all links, formula calculations, spelling, and formatting prior to deliverable submission.\n\n### 3. Essential Software & Digital Tools\n- **Google Workspace**: Gmail, Google Docs, Sheets, Calendar, Drive.\n- **Task Management**: Asana, Trello, ClickUp, Notion.\n- **Communication**: Slack, Microsoft Teams, Zoom, Loom.\n- **Specialized VA Software**: Canva Pro, Apollo.io, HubSpot CRM, LastPass.\n\n### 4. Real-World Client Case Study & Best Practices\n* **Scenario**: Client asks for urgent turn-around on a project with ambiguous instructions.\n* **Poor Response**: Waiting silently or guessing without confirmation.\n* **Proactive Response**: *'Hi [Client], I am executing " . $ld['title'] . " right now. To ensure we hit your deadline, I have drafted Option A (Faster) and Option B (Comprehensive). Please confirm which you prefer!'*\n\n### 5. Practical Mission Instructions\nReview the knowledge check quiz below and complete the interactive scenario exercise to earn **+" . $ld['xp'] . " XP** and **+" . $ld['coins'] . " Coins**!";

        $stmtLes->execute([$ld['course_id'], $ld['level'], $ld['title'], $ld['slug'], $ld['summary'], $content, $ld['xp'], $ld['coins'], $sort++]);
        $lesId = $pdo->lastInsertId();

        $stmtQz->execute([$lesId, 'Knowledge Check: ' . $ld['title']]);
        $quizId = $pdo->lastInsertId();

        // Generate lesson-specific quiz questions directly tied to the current lesson title and content
        $options1 = json_encode([
            "Consistently saving the client time in " . strtolower($ld['title']),
            "Delaying tasks until the client follows up twice",
            "Charging upfront fees without outlining deliverables",
            "Disregarding client constraints and deadlines"
        ]);
        $stmtQn->execute([
            $quizId,
            "What is the core objective when executing tasks in '" . $ld['title'] . "'?",
            $options1,
            "Consistently saving the client time in " . strtolower($ld['title']),
            "Explanation: The primary value proposition of a Virtual Assistant is eliminating operational bottlenecks and saving 5-15 hours weekly for the client."
        ]);

        $options2 = json_encode([
            "Following standardized SOPs and self-auditing deliverables prior to submission",
            "Sending unformatted draft documents without proofreading",
            "Sharing confidential client login details over public forums",
            "Ignoring unexpected technical issues"
        ]);
        $stmtQn->execute([
            $quizId,
            "Which Standard Operating Procedure (SOP) best practice applies to '" . $ld['title'] . "'?",
            $options2,
            "Following standardized SOPs and self-auditing deliverables prior to submission",
            "Explanation: Adhering to structured SOPs and conducting self-audits prior to submission guarantees zero-defect quality control."
        ]);

        $options3 = json_encode([
            "Notify the client immediately and propose two actionable solution options",
            "Attempt to hide the issue and hope the client does not notice",
            "Blame external software tools without offering alternatives",
            "Abandon the task completely"
        ]);
        $stmtQn->execute([
            $quizId,
            "If an unexpected obstacle occurs while managing '" . $ld['title'] . "', what is the correct response?",
            $options3,
            "Notify the client immediately and propose two actionable solution options",
            "Explanation: Proactive communication paired with actionable solution options establishes high professional reliability and trust."
        ]);
    }

    // 4. 50 Interactive Missions
    $stmtM = $pdo->prepare("INSERT INTO missions (level_number, title, type, scenario, instructions, data, xp_reward, coin_reward) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    for ($i = 1; $i <= 50; $i++) {
        $levelNum = (($i - 1) % 15) + 1;
        $type = ($i % 3 == 0) ? 'interactive' : (($i % 3 == 1) ? 'proposal' : 'client_sim');

        $scenarioTitle = match($i % 5) {
            0 => "C-Suite Multi-Timezone Calendar Conflict Resolution",
            1 => "High-Priority Inbox Zero Email Categorization",
            2 => "B2B Contact Extraction & Lead Verification",
            3 => "Canva Social Media Graphic & Content Schedule",
            default => "WordPress Blog Formatting & SEO Optimization",
        };

        $scenario = "Client Alex Rivera (CEO, TechFlow Solutions) has submitted an urgent assignment: '{$scenarioTitle} (Task #{$i})'. Review the raw dataset below, resolve any operational conflicts, and produce a polished deliverable.";
        $instructions = "1. Analyze the client requirements and constraints.\n2. Apply the relevant Standard Operating Procedure (SOP) covered in the academy.\n3. Draft your response clearly with exact deliverables and submit for evaluation.";

        $data = json_encode([
            'client_name' => 'Alex Rivera (CEO, TechFlow Solutions)',
            'task_type' => $scenarioTitle,
            'sample_data' => "Meeting Request A: 3:00 PM EST (12:00 PM PST)\nMeeting Request B: 3:15 PM EST (Overlap Conflict!)\nLead Record #1: Sarah Jenkins | Email: sarah@techflow.io | Status: Hot Prospect",
            'expected_output' => 'Cleanly formatted SOP response / proposal pitch',
        ]);

        $stmtM->execute([$levelNum, "Mission #{$i}: {$scenarioTitle}", $type, $scenario, $instructions, $data, 150 + ($i * 10), 20 + ($i * 2)]);
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

    // 6. Resources Vault Items
    $resources = [
        ['level_number' => 1, 'title' => 'Virtual Assistant Starter Playbook (PDF)', 'description' => 'Complete beginner guide to setting up your VA career.', 'type' => 'pdf', 'file_content_or_url' => '/docs/PLATFORM_MANUAL.md', 'is_premium' => false],
        ['level_number' => 2, 'title' => 'Google Workspace Keyboard Shortcuts Cheat Sheet', 'description' => 'Boost your typing speed and efficiency instantly.', 'type' => 'cheat_sheet', 'file_content_or_url' => '/docs/TUTORIALS_AND_GUIDES.md', 'is_premium' => false],
        ['level_number' => 3, 'title' => 'Client Onboarding SOP Checklist', 'description' => 'Professional checklist for onboarding new client projects.', 'type' => 'template', 'file_content_or_url' => '/docs/SOPS_AND_CHECKLISTS.md', 'is_premium' => false],
        ['level_number' => 4, 'title' => 'Canva & Social Media Content Calendar Template', 'description' => 'Monthly social content planning grid for Social Media VAs.', 'type' => 'template', 'file_content_or_url' => '/docs/TUTORIALS_AND_GUIDES.md', 'is_premium' => false],
        ['level_number' => 5, 'title' => 'High-Converting ATS VA Resume Template', 'description' => 'ATS-friendly resume layout designed specifically for remote VAs.', 'type' => 'template', 'file_content_or_url' => '/resume-builder/print', 'is_premium' => true],
        ['level_number' => 8, 'title' => '10 Winning Proposal & Pitch Scripts', 'description' => 'Proven proposal templates that earned over $100k in freelancing.', 'type' => 'script', 'file_content_or_url' => '/docs/TUTORIALS_AND_GUIDES.md', 'is_premium' => true],
        ['level_number' => 11, 'title' => 'Client Service Agreement & Contract Template', 'description' => 'Standard freelance agreement covering payment terms and scope limits.', 'type' => 'template', 'file_content_or_url' => '/docs/SOPS_AND_CHECKLISTS.md', 'is_premium' => true],
        ['level_number' => 12, 'title' => 'Hourly Rate & Retainer Calculator (Worksheet)', 'description' => 'Calculate your exact hourly rates and monthly retainer packages.', 'type' => 'calculator', 'file_content_or_url' => '/docs/TUTORIALS_AND_GUIDES.md', 'is_premium' => true],
        ['level_number' => 14, 'title' => 'Virtual Agency SOP Operations Manual', 'description' => 'Standard Operating Procedures for hiring subcontractors and managing agency workflows.', 'type' => 'manual', 'file_content_or_url' => '/docs/SOPS_AND_CHECKLISTS.md', 'is_premium' => true],
    ];

    $stmtRes = $pdo->prepare("INSERT INTO resources (level_number, title, description, type, file_content_or_url, is_premium) VALUES (?, ?, ?, ?, ?, ?)");
    foreach ($resources as $res) {
        $stmtRes->execute([$res['level_number'], $res['title'], $res['description'], $res['type'], $res['file_content_or_url'], $res['is_premium']]);
    }

    $pdo->exec("INSERT INTO groups (name, slug, description, icon) VALUES ('Beginner VA Lounge', 'beginner-va', 'A welcoming community for beginners starting their remote journey.', '🌱');");
    $pdo->exec("INSERT INTO groups (name, slug, description, icon) VALUES ('WordPress & Tech VAs', 'tech-vas', 'Technical support, website management, and troubleshooting.', '💻');");
    $pdo->exec("INSERT INTO groups (name, slug, description, icon) VALUES ('Social Media & Content VAs', 'social-content-vas', 'Canva graphics, content calendars, and social growth.', '🎨');");

    // 7. Users
    $pass = password_hash('password', PASSWORD_BCRYPT);
    $pdo->exec("INSERT INTO users (name, email, password, role, username, headline, bio, level, xp, coins, streak_count, subscription_tier) VALUES ('Admin Boss', 'admin@freelancequest.com', '$pass', 'admin', 'adminboss', 'Lead Product Architect & Instructor', 'Building top tier VAs.', 15, 50000, 9999, 30, 'master');");
    $pdo->exec("INSERT INTO users (name, email, password, role, username, headline, bio, level, xp, coins, streak_count, subscription_tier) VALUES ('Maria Santos', 'maria@example.com', '$pass', 'student', 'mariasantos', 'Aspiring Administrative & Research VA', 'Eager to help founders.', 3, 1350, 280, 12, 'pro');");

    // 8. Seed Job Postings
    $jobs = [
        [
            'title' => 'Executive Virtual Assistant & Operations Partner',
            'company' => 'TechFlow Solutions (USA)',
            'category' => 'Executive Admin',
            'budget' => '$18 - $25/hour',
            'job_type' => 'Full-Time (30 hrs/week)',
            'description' => 'We are seeking an organized, proactive Executive VA to handle C-suite calendar scheduling, inbox zero management, travel itineraries, and meeting minute summaries.',
            'requirements' => '• 1+ years experience in executive administrative support' . "\n" . '• Mastery of Google Workspace, Calendly, and Slack' . "\n" . '• Impeccable English written and verbal communication',
        ],
        [
            'title' => 'Social Media Content & Canva Graphic Designer',
            'company' => 'BrightScale Media Agency',
            'category' => 'Social Media',
            'budget' => '$15 - $20/hour',
            'job_type' => 'Part-Time (15 hrs/week)',
            'description' => 'Looking for a creative Social Media VA to design carousel graphics in Canva, write engaging captions, and schedule posts on Metricool across Instagram and LinkedIn.',
            'requirements' => '• Strong Canva Pro graphic design portfolio' . "\n" . '• Experience with social scheduling tools (Buffer/Metricool)' . "\n" . '• Basic knowledge of social analytics reporting',
        ],
        [
            'title' => 'B2B Lead Generation & Research Specialist',
            'company' => 'Apex Growth Partners',
            'category' => 'Lead Generation',
            'budget' => '$12 - $18/hour',
            'job_type' => 'Project-Based (20 hrs/week)',
            'description' => 'Extract and verify 500 decision-maker contacts (CEOs, Founders) in the SaaS industry using Apollo.io, LinkedIn Sales Navigator, and Hunter.io.',
            'requirements' => '• High accuracy data entry skills' . "\n" . '• Familiarity with email lookup and verification tools' . "\n" . '• Clean Google Sheets formatting',
        ],
        [
            'title' => 'WordPress Content Manager & Blog Publisher',
            'company' => 'Digital Horizon Publishing',
            'category' => 'Web & WordPress',
            'budget' => '$15 - $22/hour',
            'job_type' => 'Contract (10 hrs/week)',
            'description' => 'Format and publish weekly articles on WordPress Gutenberg editor, optimize SEO tags, insert featured images, and manage comment moderation.',
            'requirements' => '• Basic WordPress Gutenberg editor knowledge' . "\n" . '• Yoast SEO / RankMath keyword optimization' . "\n" . '• Basic image editing',
        ],
    ];

    $stmtJ = $pdo->prepare("INSERT INTO jobs (user_id, title, company, category, budget, job_type, description, requirements) VALUES (1, ?, ?, ?, ?, ?, ?, ?)");
    foreach ($jobs as $j) {
        $stmtJ->execute([$j['title'], $j['company'], $j['category'], $j['budget'], $j['job_type'], $j['description'], $j['requirements']]);
    }

    echo "Expanded database seeding completed successfully.\n";
}

if (basename(__FILE__) == basename($_SERVER['PHP_SELF'])) {
    seedDatabase();
}
