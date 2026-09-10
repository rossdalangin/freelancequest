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

            $lessonsMasterData = [
        // Level 1: What is Freelancing & Remote VA Work?
        [
            'course_id' => $courseIds[1], 'level' => 1, 'title' => 'What is Freelancing & Remote VA Work?', 'slug' => 'what-is-freelancing-and-remote-va-work', 'summary' => 'Understanding core concepts of freelancing, virtual assistance, and remote work.', 'xp' => 100, 'coins' => 25,
            'content' => "<h2>Executive Masterclass: What is Freelancing & Remote VA Work?</h2><p>Understanding core concepts of freelancing, virtual assistance, and remote work.</p><h3>1. Why This Topic is Needed & Important</h3><p>Understanding the distinction between traditional employment and freelancing prepares you for independence and global earning power.</p><h3>2. Standard Operating Procedure (SOP) Blueprint</h3><p>1. Identify core skills.<br>2. Select a freelance category.<br>3. Establish a dedicated remote workspace.</p><h3>3. Real-World Example & Copy-Paste Script</h3><p><b>Value Pitch:</b> 'I help busy CEOs regain 10+ hours a week by taking over calendar management and inbox filtering.'</p><h3>4. Pro Tips & Tricks for VAs</h3><ul><li>Treat freelancing as a business from day one.</li><li>Track your working hours accurately using Toggl.</li></ul><h3>5. Common Pitfalls to Avoid</h3><p>Avoid taking low-paying jobs without a clear scope or contract agreement.</p>",
            'quiz' => [
                [
                    'question' => "What is the primary difference between a traditional employee and an independent freelancer?",
                    'options' => ['Freelancers contract directly with multiple clients as independent business owners', 'Freelancers get paid hourly minimum wage guaranteed by one company', 'Freelancers pay no taxes', 'Freelancers work exclusively in corporate offices'],
                    'correct' => "Freelancers contract directly with multiple clients as independent business owners",
                    'explanation' => "Freelancers operate independent service businesses directly."
                ],
                [
                    'question' => "Which key metric describes the core value proposition of an Executive Virtual Assistant?",
                    'options' => ['Saving the client 10+ hours per week on routine operational tasks', 'Working 80 hours a week for a supervisor', 'Buying office furniture for clients', 'Writing custom Linux C++ drivers'],
                    'correct' => "Saving the client 10+ hours per week on routine operational tasks",
                    'explanation' => "VAs sell time savings and efficiency to busy executives."
                ],
                [
                    'question' => "What tool is essential for tracking time spent on client tasks?",
                    'options' => ['Toggl or Clockify', 'Microsoft Paint', 'Windows Calculator', 'Adobe Photoshop'],
                    'correct' => "Toggl or Clockify",
                    'explanation' => "Time tracking software provides verifiable proof of work."
                ]
            ]
        ],
        // Level 1: Marketplaces vs Direct Client Sourcing
        [
            'course_id' => $courseIds[1], 'level' => 1, 'title' => 'Marketplaces vs Direct Client Sourcing', 'slug' => 'marketplaces-vs-direct-sourcing', 'summary' => 'Comparing Upwork/Fiverr with direct outbound client sourcing on LinkedIn.', 'xp' => 100, 'coins' => 25,
            'content' => "<h2>Executive Masterclass: Marketplaces vs Direct Client Sourcing</h2><p>Comparing Upwork/Fiverr with direct outbound client sourcing on LinkedIn.</p><h3>1. Why This Topic is Needed & Important</h3><p>Diversifying lead channels ensures steady income without relying on a single platform algorithm.</p><h3>2. Standard Operating Procedure (SOP) Blueprint</h3><p>1. Build an optimized Upwork profile.<br>2. Build a proactive LinkedIn direct outreach engine.<br>3. Combine both channels.</p><h3>3. Real-World Example & Copy-Paste Script</h3><p><b>Outreach Pitch:</b> 'Hi [Name], I noticed your team is expanding rapidly. I help fast-growing founders manage operations seamlessly.'</p><h3>4. Pro Tips & Tricks for VAs</h3><ul><li>Marketplaces offer buyer trust; direct outreach offers higher rates and retainer control.</li></ul><h3>5. Common Pitfalls to Avoid</h3><p>Never rely 100% on a single freelance marketplace account.</p>",
            'quiz' => [
                [
                    'question' => "What is a main advantage of direct client sourcing via LinkedIn or Cold Email?",
                    'options' => ['Higher rate potential, no marketplace commission fees, and full contract control', 'Guaranteed hourly minimum wages enforced by platform bots', 'No need to communicate with clients', 'Automatic client assignments without applying'],
                    'correct' => "Higher rate potential, no marketplace commission fees, and full contract control",
                    'explanation' => "Direct sourcing allows custom high-ticket pricing and direct client agreements."
                ],
                [
                    'question' => "Why should freelancers avoid relying solely on a single platform like Upwork?",
                    'options' => ['Algorithm changes or account policy updates can instantly eliminate your income stream', 'Platforms forbid freelancers from earning money', 'Clients on platforms never pay invoice balances', 'Upwork requires 50 years of experience'],
                    'correct' => "Algorithm changes or account policy updates can instantly eliminate your income stream",
                    'explanation' => "Platform independence builds a resilient freelancing career."
                ],
                [
                    'question' => "What advantage do platforms like Upwork provide for beginner freelancers?",
                    'options' => ['Built-in payment protection escrow and an established pool of active buyers', 'Free laptops sent to all new signups', 'Zero competition from other applicants', 'Exemption from delivering work on deadlines'],
                    'correct' => "Built-in payment protection escrow and an established pool of active buyers",
                    'explanation' => "Marketplaces provide escrow payment security and immediate access to active client jobs."
                ]
            ]
        ],
        // Level 1: Digital Professionalism & Remote Work Mindset
        [
            'course_id' => $courseIds[1], 'level' => 1, 'title' => 'Digital Professionalism & Remote Work Mindset', 'slug' => 'digital-professionalism-remote-mindset', 'summary' => 'Essential communication standards, reliability, and remote work discipline.', 'xp' => 100, 'coins' => 25,
            'content' => "<h2>Executive Masterclass: Digital Professionalism & Remote Work Mindset</h2><p>Essential communication standards, reliability, and remote work discipline.</p><h3>1. Why This Topic is Needed & Important</h3><p>Communication is the single most critical factor in remote client retention.</p><h3>2. Standard Operating Procedure (SOP) Blueprint</h3><p>1. Send daily EOD status reports.<br>2. Acknowledge client messages within 1-2 hours.<br>3. Proactively report roadblocks with solutions.</p><h3>3. Real-World Example & Copy-Paste Script</h3><p><b>EOD Report Template:</b><br>- Completed Today: Calendar reorganized.<br>- In Progress: Rescheduling Thursday meeting.<br>- Blockers: Awaiting client API key.</p><h3>4. Pro Tips & Tricks for VAs</h3><ul><li>Over-communicate progress so the client never has to ask what you are working on.</li></ul><h3>5. Common Pitfalls to Avoid</h3><p>Avoid ghosting clients or delaying status updates when encountering a mistake.</p>",
            'quiz' => [
                [
                    'question' => "What is an EOD (End of Day) report and why is it critical for remote VAs?",
                    'options' => ['A concise daily update detailing completed tasks, ongoing work, and potential blockers', 'An invoice demanding daily payment advances', 'A social media post sharing client passwords', 'A weekly complaint letter to management'],
                    'correct' => "A concise daily update detailing completed tasks, ongoing work, and potential blockers",
                    'explanation' => "EOD reports provide clients with total visibility and trust."
                ],
                [
                    'question' => "How should a professional remote freelancer respond when facing a work blocker?",
                    'options' => ['Report the issue promptly alongside a proposed solution or next steps', 'Wait silent for two weeks hoping the client notices', 'Blame the client publicly on social media', 'Delete all project files'],
                    'correct' => "Report the issue promptly alongside a proposed solution or next steps",
                    'explanation' => "Proactive problem-solving builds professional trust."
                ],
                [
                    'question' => "What is the recommended response window for client messages during working hours?",
                    'options' => ['Within 1-2 hours during agreed business hours', 'Within 5-7 business days', 'Only once per month', 'Never respond to text messages'],
                    'correct' => "Within 1-2 hours during agreed business hours",
                    'explanation' => "Prompt communication reassures remote clients."
                ]
            ]
        ],
        // Level 2: Google Workspace & Cloud File Architecture Mastery
        [
            'course_id' => $courseIds[2], 'level' => 2, 'title' => 'Google Workspace & Cloud File Architecture Mastery', 'slug' => 'google-workspace-file-architecture', 'summary' => 'Organizing Google Drive folders, permission access, and document collaboration.', 'xp' => 100, 'coins' => 25,
            'content' => "<h2>Executive Masterclass: Google Workspace & Cloud File Architecture Mastery</h2><p>Organizing Google Drive folders, permission access, and document collaboration.</p><h3>1. Why This Topic is Needed & Important</h3><p>Clean cloud drive folder structures prevent lost documents and security leaks.</p><h3>2. Standard Operating Procedure (SOP) Blueprint</h3><p>1. Establish a clear root folder hierarchy.<br>2. Use standardized naming conventions (`YYYY-MM-DD_Title_v1`).<br>3. Set strict permission levels.</p><h3>3. Real-World Example & Copy-Paste Script</h3><p><b>Drive File Naming Standard:</b> `2025-05-15_Executive_Board_Deck_v2.pdf`</p><h3>4. Pro Tips & Tricks for VAs</h3><ul><li>Never share top-level root folders publicly; always create specific shared subfolders.</li></ul><h3>5. Common Pitfalls to Avoid</h3><p>Avoid leaving permissions open to 'Anyone with the link can edit' for confidential files.</p>",
            'quiz' => [
                [
                    'question' => "What is the industry-standard file naming format for cloud document organization?",
                    'options' => ['YYYY-MM-DD_DocumentTitle_v1', 'doc123_final_final_v2_new.docx', 'Untitled Document', 'my_file_stuff.pdf'],
                    'correct' => "YYYY-MM-DD_DocumentTitle_v1",
                    'explanation' => "ISO date formatting sorts files chronologically automatically."
                ],
                [
                    'question' => "Which Google Drive permission level should be used when sharing reference files externally?",
                    'options' => ['Viewer', 'Editor', 'Owner', 'Public Unlimited Domain Transfer'],
                    'correct' => "Viewer",
                    'explanation' => "Viewer access allows reading without risking accidental modifications or deletions."
                ],
                [
                    'question' => "Why is a structured folder hierarchy crucial for client cloud storage?",
                    'options' => ['It prevents lost files, speeds up team onboarding, and protects confidential data', 'It slows down computer processing speeds intentionally', 'It increases Google Drive monthly bill costs', 'It hides files permanently from search indexing'],
                    'correct' => "It prevents lost files, speeds up team onboarding, and protects confidential data",
                    'explanation' => "Proper folder architecture saves team time and maintains security."
                ]
            ]
        ],
        // Level 2: Inbox Zero & Business Email Communication Protocol
        [
            'course_id' => $courseIds[2], 'level' => 2, 'title' => 'Inbox Zero & Business Email Communication Protocol', 'slug' => 'inbox-zero-email-communication-protocol', 'summary' => 'Managing executive inboxes, email labels, filters, and professional etiquette.', 'xp' => 100, 'coins' => 25,
            'content' => "<h2>Executive Masterclass: Inbox Zero & Business Email Communication Protocol</h2><p>Managing executive inboxes, email labels, filters, and professional etiquette.</p><h3>1. Why This Topic is Needed & Important</h3><p>Busy executives receive hundreds of emails daily. Managing their inbox is a top VA responsibility.</p><h3>2. Standard Operating Procedure (SOP) Blueprint</h3><p>1. Categorize incoming emails into 4 folders.<br>2. Create automated email filters.<br>3. Draft canned responses for common inquiries.</p><h3>3. Real-World Example & Copy-Paste Script</h3><p><b>Canned Reply:</b> 'Hi [Name], thank you for reaching out! [Executive] is in meetings. I will review this and reply by 3:00 PM EST today.'</p><h3>4. Pro Tips & Tricks for VAs</h3><ul><li>Always use clear subject lines indicating required action (e.g. `[ACTION REQUIRED]`).</li></ul><h3>5. Common Pitfalls to Avoid</h3><p>Never delete emails permanently without executive confirmation; always use Archive.</p>",
            'quiz' => [
                [
                    'question' => "What is the core principle of the 'Inbox Zero' email management methodology?",
                    'options' => ['Processing incoming emails immediately into defined action categories so the primary inbox stays clear', 'Deleting every unread email without reading', 'Sending 500 promotional emails every hour', 'Never opening email applications on weekends'],
                    'correct' => "Processing incoming emails immediately into defined action categories so the primary inbox stays clear",
                    'explanation' => "Inbox Zero categorizes emails systematically so nothing falls through the cracks."
                ],
                [
                    'question' => "Why should an Executive VA archive emails instead of permanently deleting them?",
                    'options' => ['Archiving keeps messages searchable for future legal or historical reference while clearing the main inbox view', 'Archiving consumes zero internet data', 'Deleting emails locks the executive user account', 'Deleting requires a paid subscription'],
                    'correct' => "Archiving keeps messages searchable for future legal or historical reference while clearing the main inbox view",
                    'explanation' => "Archive retains historic record access without cluttering active inbox views."
                ],
                [
                    'question' => "What subject line prefix helps busy recipients prioritize urgent messages?",
                    'options' => ['`[ACTION REQUIRED]` or `[URGENT]`', '`Hey check this out`', 'No subject line', '`FWD: FWD: Re: Hello`'],
                    'correct' => "`[ACTION REQUIRED]` or `[URGENT]`",
                    'explanation' => "Prefixes signal exact urgency and expected action at a glance."
                ]
            ]
        ],
        // Level 2: Calendar Management & Time Zone Scheduling Mastery
        [
            'course_id' => $courseIds[2], 'level' => 2, 'title' => 'Calendar Management & Time Zone Scheduling Mastery', 'slug' => 'calendar-management-time-zone-mastery', 'summary' => 'Managing executive Google Calendars, scheduling across time zones, and Calendly setup.', 'xp' => 100, 'coins' => 25,
            'content' => "<h2>Executive Masterclass: Calendar Management & Time Zone Scheduling Mastery</h2><p>Managing executive Google Calendars, scheduling across time zones, and Calendly setup.</p><h3>1. Why This Topic is Needed & Important</h3><p>Scheduling meetings seamlessly across time zones prevents missed appointments.</p><h3>2. Standard Operating Procedure (SOP) Blueprint</h3><p>1. Display multiple time zone columns in Google Calendar.<br>2. Always include video links and agendas.<br>3. Set up Calendly buffer times.</p><h3>3. Real-World Example & Copy-Paste Script</h3><p><b>Scheduling Invite Template:</b><br>Topic: Strategy Call - [Client] x [Prospect]<br>Agenda: 1. Scope (15m), 2. Q&A (10m), 3. Next Steps (5m).</p><h3>4. Pro Tips & Tricks for VAs</h3><ul><li>Always double-check Daylight Saving Time shifts when scheduling international calls.</li></ul><h3>5. Common Pitfalls to Avoid</h3><p>Avoid sending meeting invites without specifying time zone abbreviations (EST, PST, GMT).</p>",
            'quiz' => [
                [
                    'question' => "When proposing meeting times across international regions, what must always be specified?",
                    'options' => ['Exact time zone abbreviations (e.g. 2:00 PM EST / 11:00 AM PST)', "The client's home address zip code", 'The exact weather forecast for the meeting day', 'The model of laptop being used'],
                    'correct' => "Exact time zone abbreviations (e.g. 2:00 PM EST / 11:00 AM PST)",
                    'explanation' => "Explicit time zone labels eliminate scheduling confusion across regions."
                ],
                [
                    'question' => "What feature in booking tools like Calendly prevents back-to-back meeting exhaustion?",
                    'options' => ['Buffer time settings between scheduled appointments', 'Automatic meeting cancellation software', 'Paid credit card deposits per booking', 'Random time zone switching'],
                    'correct' => "Buffer time settings between scheduled appointments",
                    'explanation' => "Buffer times allow executives to take notes and prepare before the next call starts."
                ],
                [
                    'question' => "What crucial details should every calendar invitation contain?",
                    'options' => ['Video conference link (Zoom/Meet), clear meeting agenda, and attendee confirmation', 'A list of personal hobbies', 'Full banking deposit instructions', 'Unformatted plain text attachments'],
                    'correct' => "Video conference link (Zoom/Meet), clear meeting agenda, and attendee confirmation",
                    'explanation' => "Complete invites ensure all attendees arrive prepared and can join instantly."
                ]
            ]
        ],
        // Level 3: Calendar & Email Management Best Practices
        [
            'course_id' => $courseIds[3], 'level' => 3, 'title' => 'Calendar & Email Management Best Practices', 'slug' => 'calendar-email-management-best-practices', 'summary' => 'Handling complex executive schedules, travel itineraries, and email delegation.', 'xp' => 100, 'coins' => 25,
            'content' => "<h2>Executive Masterclass: Calendar & Email Management Best Practices</h2><p>Handling complex executive schedules, travel itineraries, and email delegation.</p><h3>1. Why This Topic is Needed & Important</h3><p>Executive assistants act as the gatekeeper of executive attention and time.</p><h3>2. Standard Operating Procedure (SOP) Blueprint</h3><p>1. Perform a morning inbox sweep at 8:00 AM.<br>2. Confirm all calendar meetings for next 48 hours.<br>3. Prepare a daily morning briefing document.</p><h3>3. Real-World Example & Copy-Paste Script</h3><p><b>Daily Briefing:</b> 'Good morning! Today you have 3 meetings: 10 AM Sales Call, 2 PM Team Sync. 2 priority emails require signature.'</p><h3>4. Pro Tips & Tricks for VAs</h3><ul><li>Block dedicated focus time on the calendar for deep work tasks.</li></ul><h3>5. Common Pitfalls to Avoid</h3><p>Avoid booking back-to-back meetings during lunch hours without explicit consent.</p>",
            'quiz' => [
                [
                    'question' => "What is the main goal of a Daily Morning Briefing sent by an Executive VA?",
                    'options' => ["Providing a concise summary of the day's schedule, key meetings, and urgent action items", 'Demanding immediate salary raises', 'Sending random internet memes to executive leadership', 'Requesting password updates for personal accounts'],
                    'correct' => "Providing a concise summary of the day's schedule, key meetings, and urgent action items",
                    'explanation' => "Morning briefings prepare executives for their day in under 60 seconds."
                ],
                [
                    'question' => "Why should an Executive VA block 'Focus Time' on a client's calendar?",
                    'options' => ['To protect uninterrupted hours for deep strategic work without meeting interruptions', 'To force the client to sleep during work hours', 'To lock external users out of Google Workspace', 'To fill empty calendar space artificially'],
                    'correct' => "To protect uninterrupted hours for deep strategic work without meeting interruptions",
                    'explanation' => "Focus time blocks prevent meeting fatigue and ensure high-priority projects move forward."
                ],
                [
                    'question' => "What should be attached to calendar invites for executive strategy calls?",
                    'options' => ['Relevant meeting slide decks, background documents, and agenda links', 'Random video links', 'Blank PDF documents', 'Unrelated personal photos'],
                    'correct' => "Relevant meeting slide decks, background documents, and agenda links",
                    'explanation' => "Attaching relevant context ensures productive executive discussions."
                ]
            ]
        ],
        // Level 3: Executive Support & Meeting Minutes SOP
        [
            'course_id' => $courseIds[3], 'level' => 3, 'title' => 'Executive Support & Meeting Minutes SOP', 'slug' => 'executive-support-meeting-minutes-sop', 'summary' => 'Taking accurate meeting notes, tracking action items, and post-meeting follow-ups.', 'xp' => 100, 'coins' => 25,
            'content' => "<h2>Executive Masterclass: Executive Support & Meeting Minutes SOP</h2><p>Taking accurate meeting notes, tracking action items, and post-meeting follow-ups.</p><h3>1. Why This Topic is Needed & Important</h3><p>Capturing clear meeting minutes and converting them into actionable tasks drives team accountability.</p><h3>2. Standard Operating Procedure (SOP) Blueprint</h3><p>1. Record meetings using Otter.ai.<br>2. Structure minutes: Attendees, Decisions, Action Items.<br>3. Distribute minutes within 2 hours.</p><h3>3. Real-World Example & Copy-Paste Script</h3><p><b>Action Item Format:</b><br>- [ ] Design Homepage Mockup - Assigned: Sarah - Deadline: May 20.</p><h3>4. Pro Tips & Tricks for VAs</h3><ul><li>Focus on capturing decisions made and specific task assignments rather than transcripts.</li></ul><h3>5. Common Pitfalls to Avoid</h3><p>Avoid waiting more than 24 hours to publish meeting minutes.</p>",
            'quiz' => [
                [
                    'question' => "What are the three essential components of professional meeting minutes?",
                    'options' => ['Attendees list, Key Decisions made, and Action Items with assigned owners and deadlines', 'Verbatim transcript of every word spoken, jokes told, and lunch menus', 'Financial bank account numbers, passwords, and tax codes', 'Personal opinions on meeting attendees'],
                    'correct' => "Attendees list, Key Decisions made, and Action Items with assigned owners and deadlines",
                    'explanation' => "Minutes must highlight decisions and concrete assigned action items."
                ],
                [
                    'question' => "When should meeting minutes be distributed to participants after a meeting?",
                    'options' => ['Within 2-24 hours while discussions are fresh', 'After 3 months', 'Only if team members ask repeatedly', 'Never publish meeting minutes'],
                    'correct' => "Within 2-24 hours while discussions are fresh",
                    'explanation' => "Fast distribution ensures immediate momentum on assigned commitments."
                ],
                [
                    'question' => "What AI transcription tools help VAs record and summarize executive calls accurately?",
                    'options' => ['Otter.ai or Fireflies.ai', 'Windows Notepad', 'Microsoft Paint', 'Google Translate'],
                    'correct' => "Otter.ai or Fireflies.ai",
                    'explanation' => "AI transcription tools generate real-time transcripts and AI summary highlights."
                ]
            ]
        ],
        // Level 3: Data Entry & Web Research Mastery
        [
            'course_id' => $courseIds[3], 'level' => 3, 'title' => 'Data Entry & Web Research Mastery', 'slug' => 'data-entry-web-research-mastery', 'summary' => 'Efficient web scraping, data cleaning, and structured spreadsheet reporting.', 'xp' => 100, 'coins' => 25,
            'content' => "<h2>Executive Masterclass: Data Entry & Web Research Mastery</h2><p>Efficient web scraping, data cleaning, and structured spreadsheet reporting.</p><h3>1. Why This Topic is Needed & Important</h3><p>Gathering accurate data from the web and organizing it into spreadsheets provides market intelligence.</p><h3>2. Standard Operating Procedure (SOP) Blueprint</h3><p>1. Utilize Google Search operators (`site:`, `filetype:`).<br>2. Standardize Google Sheets columns.<br>3. Verify emails using NeverBounce.</p><h3>3. Real-World Example & Copy-Paste Script</h3><p><b>Search Operator:</b> `site:linkedin.com/in/ \"CEO\" \"SaaS\" \"Austin\"`</p><h3>4. Pro Tips & Tricks for VAs</h3><ul><li>Use conditional formatting in Google Sheets to highlight duplicate entries automatically.</li></ul><h3>5. Common Pitfalls to Avoid</h3><p>Avoid delivering unverified email lists or messy spreadsheets.</p>",
            'quiz' => [
                [
                    'question' => "Which Google search operator restricts results to a specific website like LinkedIn?",
                    'options' => ['`site:linkedin.com/in/`', '`find:linkedin`', '`search_url=linkedin`', '`#linkedin`'],
                    'correct' => "`site:linkedin.com/in/`",
                    'explanation' => "The `site:` operator targets domain specific search queries directly."
                ],
                [
                    'question' => "Why is email verification software (e.g. ZeroBounce) necessary before delivering lead lists?",
                    'options' => ['It eliminates invalid emails, preventing high bounce rates that damage domain reputation', 'It automatically pays the client invoice', 'It converts emails into PDF images', 'It sends automated text messages'],
                    'correct' => "It eliminates invalid emails, preventing high bounce rates that damage domain reputation",
                    'explanation' => "Clean email lists maintain sender domain reputation and deliverability."
                ],
                [
                    'question' => "What spreadsheet feature identifies duplicate rows automatically?",
                    'options' => ['Conditional Formatting or Data Cleanup -> Remove Duplicates', 'Font Size Selector', 'Spell Check', 'Print Preview'],
                    'correct' => "Conditional Formatting or Data Cleanup -> Remove Duplicates",
                    'explanation' => "Data cleanup tools quickly purge repeated data entries."
                ]
            ]
        ],
        // Level 4: Social Media Management & Content Scheduling
        [
            'course_id' => $courseIds[4], 'level' => 4, 'title' => 'Social Media Management & Content Scheduling', 'slug' => 'social-media-management-content-scheduling', 'summary' => 'Creating content calendars, graphic design basics in Canva, and scheduling with Buffer.', 'xp' => 125, 'coins' => 35,
            'content' => "<h2>Executive Masterclass: Social Media Management & Content Scheduling</h2><p>Creating content calendars, graphic design basics in Canva, and scheduling with Buffer.</p><h3>1. Why This Topic is Needed & Important</h3><p>Brands require consistent online publishing to build authority and attract clients.</p><h3>2. Standard Operating Procedure (SOP) Blueprint</h3><p>1. Create a 30-day content calendar in Notion.<br>2. Design branded visuals in Canva.<br>3. Schedule posts using Buffer or Metricool.</p><h3>3. Real-World Example & Copy-Paste Script</h3><p><b>Content Framework:</b> 40% Education, 30% Case Studies, 20% Behind-the-Scenes, 10% Promo.</p><h3>4. Pro Tips & Tricks for VAs</h3><ul><li>Batch create and schedule content two weeks in advance to maintain consistency.</li></ul><h3>5. Common Pitfalls to Avoid</h3><p>Avoid posting without proofreading text or checking image aspect ratios.</p>",
            'quiz' => [
                [
                    'question' => "What is a 'content pillar' strategy in social media management?",
                    'options' => ['Categorizing posts into core thematic topics (e.g. Education, Case Studies, Promotion) for balanced posting', 'Posting 50 sales pitches every day', 'Deleting old social media accounts monthly', 'Using only black and white images'],
                    'correct' => "Categorizing posts into core thematic topics (e.g. Education, Case Studies, Promotion) for balanced posting",
                    'explanation' => "Content pillars maintain a balanced and engaging posting mix."
                ],
                [
                    'question' => "What social media scheduling tools allow VAs to publish posts automatically across channels?",
                    'options' => ['Buffer, Publer, or Metricool', 'Microsoft Excel 2003', 'Google Maps', 'VLC Media Player'],
                    'correct' => "Buffer, Publer, or Metricool",
                    'explanation' => "Social media management platforms automate scheduled publishing across channels."
                ],
                [
                    'question' => "Why is batching content creation advantageous for Social Media VAs?",
                    'options' => ['Creating 2-4 weeks of content in single focused sessions saves time and maintains schedule consistency', 'It allows VAs to skip reviewing graphics', 'It forces clients to pay daily bonuses', 'It eliminates the need for internet access'],
                    'correct' => "Creating 2-4 weeks of content in single focused sessions saves time and maintains schedule consistency",
                    'explanation' => "Batching maximizes efficiency and keeps social feeds active predictably."
                ]
            ]
        ],
        // Level 4: B2B Lead Generation & Web Scraping
        [
            'course_id' => $courseIds[4], 'level' => 4, 'title' => 'B2B Lead Generation & Web Scraping', 'slug' => 'b2b-lead-generation-web-scraping', 'summary' => 'Sourcing prospective client leads using Sales Navigator and Apollo.io.', 'xp' => 125, 'coins' => 35,
            'content' => "<h2>Executive Masterclass: B2B Lead Generation & Web Scraping</h2><p>Sourcing prospective client leads using Sales Navigator and Apollo.io.</p><h3>1. Why This Topic is Needed & Important</h3><p>Lead generation directly powers client sales pipelines, making it a lucrative VA specialization.</p><h3>2. Standard Operating Procedure (SOP) Blueprint</h3><p>1. Define Ideal Customer Profile (ICP).<br>2. Filter prospect leads using Sales Navigator or Apollo.<br>3. Verify email deliverability.</p><h3>3. Real-World Example & Copy-Paste Script</h3><p><b>ICP Criteria Example:</b> Founders of B2B SaaS companies in US/Canada, 10-50 employees, Series A funded.</p><h3>4. Pro Tips & Tricks for VAs</h3><ul><li>Cross-reference job titles to ensure leads hold purchasing decision authority.</li></ul><h3>5. Common Pitfalls to Avoid</h3><p>Avoid scraping low-quality unverified databases that produce high bounce rates.</p>",
            'quiz' => [
                [
                    'question' => "What does ICP stand for in B2B Lead Generation?",
                    'options' => ['Ideal Customer Profile', 'Internet Connection Protocol', 'International Client Price', 'Internal Company Performance'],
                    'correct' => "Ideal Customer Profile",
                    'explanation' => "An ICP defines the exact targeting criteria for high-converting prospective accounts."
                ],
                [
                    'question' => "Which professional tools are industry standards for B2B prospect lead sourcing?",
                    'options' => ['LinkedIn Sales Navigator and Apollo.io', 'TikTok Video Editor', 'Google Translate', 'Windows Media Player'],
                    'correct' => "LinkedIn Sales Navigator and Apollo.io",
                    'explanation' => "Sales Navigator and Apollo provide rich database filtering for decision-maker leads."
                ],
                [
                    'question' => "Why must Lead Generation VAs verify prospective lead emails before running outbound campaigns?",
                    'options' => ['To protect client email domains from high bounce rates and spam blacklisting', 'To charge extra postage fees', 'Because email providers charge per letter sent', 'To automatically sign leads up for spam lists'],
                    'correct' => "To protect client email domains from high bounce rates and spam blacklisting",
                    'explanation' => "Email verification ensures list deliverability and protects domain sender score."
                ]
            ]
        ],
        // Level 4: WordPress VA & Website Maintenance Fundamentals
        [
            'course_id' => $courseIds[4], 'level' => 4, 'title' => 'WordPress VA & Website Maintenance Fundamentals', 'slug' => 'wordpress-va-website-maintenance', 'summary' => 'Updating WordPress plugins, formatting blog posts, and managing site security.', 'xp' => 125, 'coins' => 35,
            'content' => "<h2>Executive Masterclass: WordPress VA & Website Maintenance Fundamentals</h2><p>Updating WordPress plugins, formatting blog posts, and managing site security.</p><h3>1. Why This Topic is Needed & Important</h3><p>Millions of business websites run on WordPress; VAs who manage WordPress content are highly sought after.</p><h3>2. Standard Operating Procedure (SOP) Blueprint</h3><p>1. Perform site backups using UpdraftPlus before running updates.<br>2. Format blog posts with proper headers and alt text.<br>3. Test contact forms weekly.</p><h3>3. Real-World Example & Copy-Paste Script</h3><p><b>Blog Checklist:</b> Title Tag, H2 Subheads, Featured Image (1200x630px), Meta Description (155 chars), Alt Text.</p><h3>4. Pro Tips & Tricks for VAs</h3><ul><li>Always test plugin updates on a staging site or take a full backup first.</li></ul><h3>5. Common Pitfalls to Avoid</h3><p>Never run bulk plugin updates without creating a full database backup beforehand.</p>",
            'quiz' => [
                [
                    'question' => "What critical step must a WordPress VA perform before updating plugins or core files?",
                    'options' => ['Create a full site and database backup using tools like UpdraftPlus', 'Delete all user accounts', 'Change the website domain name', 'Uninstall WordPress completely'],
                    'correct' => "Create a full site and database backup using tools like UpdraftPlus",
                    'explanation' => "Backups allow instant site restoration if a plugin update causes a site crash."
                ],
                [
                    'question' => "What SEO element should be added to every image uploaded to a WordPress blog post?",
                    'options' => ['Descriptive Alt Text (Alternative Text)', 'JavaScript code tags', 'Client home phone numbers', 'Password protection strings'],
                    'correct' => "Descriptive Alt Text (Alternative Text)",
                    'explanation' => "Alt text improves search engine indexing and screen reader web accessibility."
                ],
                [
                    'question' => "How should blog post content be structured visually in the WordPress Gutenberg editor?",
                    'options' => ['Using proper H1, H2, and H3 headers, short paragraphs, and bullet points', 'In one giant wall of unformatted text', 'Using ALL CAPS for every paragraph', 'As scanned image screenshots of text'],
                    'correct' => "Using proper H1, H2, and H3 headers, short paragraphs, and bullet points",
                    'explanation' => "Structured headers and short paragraphs enhance reader scannability and SEO ranking."
                ]
            ]
        ],
        // Level 5: Resume Optimization & ATS Formatting
        [
            'course_id' => $courseIds[5], 'level' => 5, 'title' => 'Resume Optimization & ATS Formatting', 'slug' => 'resume-optimization-ats-formatting', 'summary' => 'Building ATS-friendly resumes that pass automated recruiter filters.', 'xp' => 125, 'coins' => 35,
            'content' => "<h2>Executive Masterclass: Resume Optimization & ATS Formatting</h2><p>Building ATS-friendly resumes that pass automated recruiter filters.</p><h3>1. Why This Topic is Needed & Important</h3><p>Applicant Tracking Systems (ATS) scan resumes for keywords before human recruiters see them.</p><h3>2. Standard Operating Procedure (SOP) Blueprint</h3><p>1. Use clean single-column layout.<br>2. Align bullet points with quantifiable results.<br>3. Match skill keywords directly from job descriptions.</p><h3>3. Real-World Example & Copy-Paste Script</h3><p><b>Quantified Bullet:</b> 'Managed Google Calendar and inbox for CEO, reducing meeting scheduling delays by 40% across 5 team members.'</p><h3>4. Pro Tips & Tricks for VAs</h3><ul><li>Test your resume using free ATS scanner tools like Jobscan before submitting.</li></ul><h3>5. Common Pitfalls to Avoid</h3><p>Avoid putting vital contact information in header/footer zones that ATS readers fail to parse.</p>",
            'quiz' => [
                [
                    'question' => "What does ATS stand for in hiring and recruitment?",
                    'options' => ['Applicant Tracking System', 'Automated Task Scheduler', 'Advanced Technical Service', 'Application Transfer Server'],
                    'correct' => "Applicant Tracking System",
                    'explanation' => "ATS software parses and ranks candidate applications for recruiters automatically."
                ],
                [
                    'question' => "Why should resume bullet points include quantifiable numbers and metrics?",
                    'options' => ['Metrics provide tangible proof of impact and business achievement', 'Numbers make resumes look longer', 'ATS systems disqualify resumes without numbers', 'Numbers replace the need for work experience'],
                    'correct' => "Metrics provide tangible proof of impact and business achievement",
                    'explanation' => "Quantified achievements demonstrate real business value to hiring decision-makers."
                ],
                [
                    'question' => "What resume design element often breaks ATS parsing algorithms?",
                    'options' => ['Complex multi-column graphic layouts, text boxes, and images', 'Standard bulleted lists', 'Black text on white backgrounds', 'Standard Calibri or Arial fonts'],
                    'correct' => "Complex multi-column graphic layouts, text boxes, and images",
                    'explanation' => "ATS parsers struggle to read text embedded inside graphics, tables, or complex text boxes."
                ]
            ]
        ],
        // Level 5: Building an Authority LinkedIn Profile
        [
            'course_id' => $courseIds[5], 'level' => 5, 'title' => 'Building an Authority LinkedIn Profile', 'slug' => 'building-authority-linkedin-profile', 'summary' => 'Optimizing your headline, banner, about section, and featured portfolio items.', 'xp' => 125, 'coins' => 35,
            'content' => "<h2>Executive Masterclass: Building an Authority LinkedIn Profile</h2><p>Optimizing your headline, banner, about section, and featured portfolio items.</p><h3>1. Why This Topic is Needed & Important</h3><p>Your LinkedIn profile functions as your landing page and primary sales funnel for remote clients.</p><h3>2. Standard Operating Procedure (SOP) Blueprint</h3><p>1. Write a value-focused headline.<br>2. Upload a high-resolution headshot and custom banner.<br>3. Structure the About section with clear CTA.</p><h3>3. Real-World Example & Copy-Paste Script</h3><p><b>Headline Formula:</b> Executive Virtual Assistant | Helping SaaS Founders Scale Operations & Reclaim 15+ Hours/Week | Inbox Zero Specialist</p><h3>4. Pro Tips & Tricks for VAs</h3><ul><li>Featured section should link directly to your portfolio, resume, and booking calendar.</li></ul><h3>5. Common Pitfalls to Avoid</h3><p>Avoid vague headlines like 'Seeking opportunities' or 'Generic Virtual Assistant'.</p>",
            'quiz' => [
                [
                    'question' => "What is the primary objective of an authority LinkedIn profile headline?",
                    'options' => ['Communicating clearly who you help, what specific problem you solve, and key business outcomes', 'Listing your high school graduation year', 'Stating that you are unemployed and desperate for work', 'Copying famous quote proverbs'],
                    'correct' => "Communicating clearly who you help, what specific problem you solve, and key business outcomes",
                    'explanation' => "Value-focused headlines attract targeted prospects and stand out in search results."
                ],
                [
                    'question' => "What links should be pinned prominently in your LinkedIn 'Featured' section?",
                    'options' => ['Your public portfolio, case studies, resume PDF, and discovery call booking link', 'Random news articles from 5 years ago', 'Personal vacation photos', 'Competitor website links'],
                    'correct' => "Your public portfolio, case studies, resume PDF, and discovery call booking link",
                    'explanation' => "The Featured section acts as immediate social proof and conversion links for profile visitors."
                ],
                [
                    'question' => "Why should job seekers avoid using vague headlines like 'Virtual Assistant Seeking Opportunities'?",
                    'options' => ['Vague headlines fail to convey specialization, value, or relevance to target client searches', 'LinkedIn blocks profiles with that phrase', 'It causes internet connection speed drops', 'Clients prefer hiring unlisted profiles'],
                    'correct' => "Vague headlines fail to convey specialization, value, or relevance to target client searches",
                    'explanation' => "Clients hire specialists who articulate specific operational solutions."
                ]
            ]
        ],
        // Level 5: Crafting Unique Selling Propositions for VAs
        [
            'course_id' => $courseIds[5], 'level' => 5, 'title' => 'Crafting Unique Selling Propositions for VAs', 'slug' => 'crafting-unique-selling-propositions', 'summary' => 'Differentiating yourself from generic freelancers through niche specialization.', 'xp' => 125, 'coins' => 35,
            'content' => "<h2>Executive Masterclass: Crafting Unique Selling Propositions for VAs</h2><p>Differentiating yourself from generic freelancers through niche specialization.</p><h3>1. Why This Topic is Needed & Important</h3><p>A Unique Selling Proposition (USP) defines why a client should choose you over hundreds of applicants.</p><h3>2. Standard Operating Procedure (SOP) Blueprint</h3><p>1. Combine a tool skill + target industry niche.<br>2. Articulate a clear outcome promise.<br>3. Integrate your USP into proposals.</p><h3>3. Real-World Example & Copy-Paste Script</h3><p><b>USP Example:</b> 'I am an E-commerce VA specializing in Shopify store management and Klaviyo email flows that generate 20%+ revenue retention.'</p><h3>4. Pro Tips & Tricks for VAs</h3><ul><li>Specialization allows you to charge $25-$50+/hr compared to $5-$8/hr for general data entry.</li></ul><h3>5. Common Pitfalls to Avoid</h3><p>Avoid claiming to be an expert in 30 unrelated tools without depth in any single domain.</p>",
            'quiz' => [
                [
                    'question' => "How does niche specialization impact a freelancer's hourly earning potential?",
                    'options' => ['Specialized VAs command higher premium rates because they solve specific, high-value client problems', 'Specialization forces freelancers to work for free', 'It reduces client interest to zero', 'It limits income to minimum wage laws'],
                    'correct' => "Specialized VAs command higher premium rates because they solve specific, high-value client problems",
                    'explanation' => "Specialization positions you as an expert authority rather than a replaceable commodity."
                ],
                [
                    'question' => "What constitutes a strong Unique Selling Proposition (USP)?",
                    'options' => ['Combining a specific skill set + target industry niche + measurable value outcome', 'Offering the lowest price per hour in the market', 'Promising to work 24 hours a day without sleeping', 'Claiming to know every software ever invented'],
                    'correct' => "Combining a specific skill set + target industry niche + measurable value outcome",
                    'explanation' => "A clear USP articulates targeted, measurable value for specific client profiles."
                ],
                [
                    'question' => "Why do generic 'do-anything' virtual assistants struggle to command high retainer rates?",
                    'options' => ['Clients view generalists as interchangeable commodities and compete primarily on price', 'General VAs are legally prohibited from receiving retainers', 'Clients prefer hiring software bots', 'General VAs cannot use email'],
                    'correct' => "Clients view generalists as interchangeable commodities and compete primarily on price",
                    'explanation' => "Without specialized value positioning, generalists face severe price competition."
                ]
            ]
        ],
        // Level 6: Creating High-Converting Work Samples & Case Studies
        [
            'course_id' => $courseIds[6], 'level' => 6, 'title' => 'Creating High-Converting Work Samples & Case Studies', 'slug' => 'creating-work-samples-case-studies', 'summary' => 'Structuring before-and-after project case studies that prove real business results.', 'xp' => 125, 'coins' => 35,
            'content' => "<h2>Executive Masterclass: Creating High-Converting Work Samples & Case Studies</h2><p>Structuring before-and-after project case studies that prove real business results.</p><h3>1. Why This Topic is Needed & Important</h3><p>Clients hire based on proof of execution. Case studies provide visual evidence of your skill.</p><h3>2. Standard Operating Procedure (SOP) Blueprint</h3><p>1. Frame project scope around the Problem, Solution, and Result (STAR framework).<br>2. Include screenshots and sample artifacts.<br>3. Highlight measurable client metrics.</p><h3>3. Real-World Example & Copy-Paste Script</h3><p><b>Case Study Format:</b> Problem: CEO overwhelmed by 100+ emails/day -> Solution: Implemented Inbox Zero & Canned Responses -> Result: 85% reduction in response time.</p><h3>4. Pro Tips & Tricks for VAs</h3><ul><li>Always anonymize sensitive client metrics unless given explicit permission.</li></ul><h3>5. Common Pitfalls to Avoid</h3><p>Avoid publishing plain text descriptions without visual proof or screenshots.</p>",
            'quiz' => [
                [
                    'question' => "What framework effectively structures a freelancer case study?",
                    'options' => ['Problem, Solution, and Result (STAR framework)', 'Writing a 100-page fantasy novel', 'Publishing raw unedited code logs', 'Listing personal hobbies'],
                    'correct' => "Problem, Solution, and Result (STAR framework)",
                    'explanation' => "The Problem-Solution-Result layout clearly demonstrates business value."
                ],
                [
                    'question' => "Why are visual screenshots and work samples essential in a portfolio?",
                    'options' => ['Visual proof validates claims and builds instant buyer trust', 'Screenshots consume less memory', 'Clients cannot read plain text', 'Visuals increase browser download speeds'],
                    'correct' => "Visual proof validates claims and builds instant buyer trust",
                    'explanation' => "Visual evidence demonstrates hands-on experience."
                ],
                [
                    'question' => "How should VAs handle client confidentiality when creating portfolio samples?",
                    'options' => ['Anonymize sensitive company names and private data while showcasing the methodology and results', 'Publish client passwords publicly', 'Refuse to build a portfolio', 'Fabricate fake company names completely'],
                    'correct' => "Anonymize sensitive company names and private data while showcasing the methodology and results",
                    'explanation' => "Anonymization protects client privacy while presenting actual work."
                ]
            ]
        ],
        // Level 6: Building Your No-Code Public Portfolio Website
        [
            'course_id' => $courseIds[6], 'level' => 6, 'title' => 'Building Your No-Code Public Portfolio Website', 'slug' => 'building-no-code-portfolio-website', 'summary' => 'Using FreelanceQuest or Notion/Carrd to launch a public portfolio domain.', 'xp' => 125, 'coins' => 35,
            'content' => "<h2>Executive Masterclass: Building Your No-Code Public Portfolio Website</h2><p>Using FreelanceQuest or Notion/Carrd to launch a public portfolio domain.</p><h3>1. Why This Topic is Needed & Important</h3><p>A dedicated portfolio website serves as your professional digital storefront.</p><h3>2. Standard Operating Procedure (SOP) Blueprint</h3><p>1. Build your public profile using the built-in FreelanceQuest Portfolio Builder.<br>2. Include an About section, Service packages, Case Studies, and Contact links.<br>3. Test mobile responsiveness.</p><h3>3. Real-World Example & Copy-Paste Script</h3><p><b>Portfolio URL Structure:</b> `freelancequest.com/p/yourname`</p><h3>4. Pro Tips & Tricks for VAs</h3><ul><li>Keep navigation clean with a single prominent call-to-action button ('Book Discovery Call').</li></ul><h3>5. Common Pitfalls to Avoid</h3><p>Avoid overly complex animations or broken external links.</p>",
            'quiz' => [
                [
                    'question' => "What is the primary call-to-action (CTA) button on a freelancer portfolio homepage?",
                    'options' => ["'Book Discovery Call' or 'Hire Me'", "'Read My Personal Blog'", "'Download 500 Random Photos'", "'Exit Page'"],
                    'correct' => "'Book Discovery Call' or 'Hire Me'",
                    'explanation' => "A clear primary CTA guides prospects to convert into clients."
                ],
                [
                    'question' => "Why should freelancer portfolio websites be fully responsive on mobile devices?",
                    'options' => ['Over 50% of decision-makers review candidate links on smartphones during transit', 'Mobile sites cost less money', 'Desktop browsers no longer display text', 'Mobile devices block images'],
                    'correct' => "Over 50% of decision-makers review candidate links on smartphones during transit",
                    'explanation' => "Mobile responsiveness ensures flawless presentation on any screen size."
                ],
                [
                    'question' => "What essential sections belong on a professional VA portfolio site?",
                    'options' => ['About Me, Services & Packages, Work Samples/Case Studies, Testimonials, and Contact', 'Family trees and personal expense reports', 'Unedited video game replays', 'Local weather updates'],
                    'correct' => "About Me, Services & Packages, Work Samples/Case Studies, Testimonials, and Contact",
                    'explanation' => "These core sections satisfy all prospect evaluations."
                ]
            ]
        ],
        // Level 6: Collecting & Showcasing Client Testimonials
        [
            'course_id' => $courseIds[6], 'level' => 6, 'title' => 'Collecting & Showcasing Client Testimonials', 'slug' => 'collecting-showcasing-testimonials', 'summary' => 'Strategies for gathering 5-star client reviews, recommendations, and video social proof.', 'xp' => 125, 'coins' => 35,
            'content' => "<h2>Executive Masterclass: Collecting & Showcasing Client Testimonials</h2><p>Strategies for gathering 5-star client reviews, recommendations, and video social proof.</p><h3>1. Why This Topic is Needed & Important</h3><p>Testimonials reduce prospect buying hesitation by leveraging third-party social proof.</p><h3>2. Standard Operating Procedure (SOP) Blueprint</h3><p>1. Request feedback immediately upon successful project milestone completion.<br>2. Provide a 3-question template to make writing recommendations easy for busy clients.<br>3. Embed reviews prominently on your portfolio.</p><h3>3. Real-World Example & Copy-Paste Script</h3><p><b>Feedback Request Template:</b> 'Hi [Client], what was the biggest benefit of our work together? Would you mind if I quote that as a review?'</p><h3>4. Pro Tips & Tricks for VAs</h3><ul><li>Short video testimonials convert 2x higher than text reviews.</li></ul><h3>5. Common Pitfalls to Avoid</h3><p>Avoid waiting months after project completion to ask for a recommendation.</p>",
            'quiz' => [
                [
                    'question' => "When is the optimal moment to ask a client for a testimonial or review?",
                    'options' => ['Immediately after delivering a successful project milestone or receiving praise', '6 months after project completion', 'Before starting the work', 'During a rate disagreement'],
                    'correct' => "Immediately after delivering a successful project milestone or receiving praise",
                    'explanation' => "Asking when client satisfaction is highest yields enthusiastic reviews."
                ],
                [
                    'question' => "What 3 questions help clients write specific, high-converting testimonials?",
                    'options' => ['1. What problem did you have? 2. How did my service help? 3. What measurable results did you achieve?', '1. What is your favorite color? 2. Where did you buy your car? 3. What time is it?', '1. How much money do you earn? 2. Why did you hire someone else? 3. Do you like weather?', '1. Can you pay extra bonus? 2. Do you use Windows? 3. What is your home address?'],
                    'correct' => "1. What problem did you have? 2. How did my service help? 3. What measurable results did you achieve?",
                    'explanation' => "Guided questions elicit structured, results-focused testimonials."
                ],
                [
                    'question' => "Why are video testimonials particularly effective on freelance portfolios?",
                    'options' => ['They provide high authenticity and emotional credibility that text cannot easily replicate', 'They increase website font size', 'They replace the need for service contracts', 'They render without internet access'],
                    'correct' => "They provide high authenticity and emotional credibility that text cannot easily replicate",
                    'explanation' => "Video reviews build strong trust and proof of relationship."
                ]
            ]
        ],
        // Level 7: Job Post Auditing & Client Vetting Checklist
        [
            'course_id' => $courseIds[7], 'level' => 7, 'title' => 'Job Post Auditing & Client Vetting Checklist', 'slug' => 'job-post-auditing-client-vetting', 'summary' => 'Analyzing job descriptions to evaluate client budget, scope, and expectations.', 'xp' => 125, 'coins' => 35,
            'content' => "<h2>Executive Masterclass: Job Post Auditing & Client Vetting Checklist</h2><p>Analyzing job descriptions to evaluate client budget, scope, and expectations.</p><h3>1. Why This Topic is Needed & Important</h3><p>Not every job posting is worth applying to; auditing posts saves time and effort.</p><h3>2. Standard Operating Procedure (SOP) Blueprint</h3><p>1. Evaluate client hire rate, payment verification status, and historical reviews.<br>2. Identify red flags: vague deliverables, unrealistic budgets, or scope creep.<br>3. Categorize jobs into High Priority vs Skip.</p><h3>3. Real-World Example & Copy-Paste Script</h3><p><b>Client Audit Checklist:</b> Payment Verified? YES | Hire Rate > 60%? YES | Clear Deliverables? YES -> Apply immediately.</p><h3>4. Pro Tips & Tricks for VAs</h3><ul><li>Focus applications on jobs posted within the last 24 hours for highest response rates.</li></ul><h3>5. Common Pitfalls to Avoid</h3><p>Avoid applying to postings with unverified payment methods and 0% hire rate history.</p>",
            'quiz' => [
                [
                    'question' => "What key metrics on Upwork job postings indicate a trustworthy and serious client?",
                    'options' => ['Verified payment method, historical hire rate over 50%, and positive freelancer reviews', '0% hire rate and no payment verification', 'Requests for free test work', 'Job post title in ALL CAPS with no description'],
                    'correct' => "Verified payment method, historical hire rate over 50%, and positive freelancer reviews",
                    'explanation' => "These indicators signal an established client with hiring intent."
                ],
                [
                    'question' => "Why is applying to job postings within the first 24 hours recommended?",
                    'options' => ['Early applications receive higher visibility before clients get overwhelmed by proposals', 'Platforms delete job posts after 24 hours', 'Clients pay double for early applications', 'Later applications are automatically deleted'],
                    'correct' => "Early applications receive higher visibility before clients get overwhelmed by proposals",
                    'explanation' => "Early submissions catch client attention while they actively review incoming bids."
                ],
                [
                    'question' => "What is a red flag in a freelance job posting?",
                    'options' => ['Unrealistic workload requirements paired with extremely low non-negotiable budgets', 'Detailed scope of work', 'Fair hourly rate ranges', 'Verified payment badge'],
                    'correct' => "Unrealistic workload requirements paired with extremely low non-negotiable budgets",
                    'explanation' => "Unrealistic demands signal high risk of client dissatisfaction."
                ]
            ]
        ],
        // Level 7: Spotting Freelance Scams & Protecting Your Identity
        [
            'course_id' => $courseIds[7], 'level' => 7, 'title' => 'Spotting Freelance Scams & Protecting Your Identity', 'slug' => 'spotting-freelance-scams-identity-protection', 'summary' => 'Recognizing phishing scams, fake checks, Telegram messaging traps, and unpaid work.', 'xp' => 125, 'coins' => 35,
            'content' => "<h2>Executive Masterclass: Spotting Freelance Scams & Protecting Your Identity</h2><p>Recognizing phishing scams, fake checks, Telegram messaging traps, and unpaid work.</p><h3>1. Why This Topic is Needed & Important</h3><p>Protecting your identity and finances from online scams is vital for remote freelancers.</p><h3>2. Standard Operating Procedure (SOP) Blueprint</h3><p>1. Never communicate or accept payment outside official platform escrow before contract start.<br>2. Reject requests to buy equipment with fake client checks or crypto.<br>3. Never submit massive custom unpaid work samples.</p><h3>3. Real-World Example & Copy-Paste Script</h3><p><b>Golden Rule:</b> If a client asks you to move to Telegram immediately and send money for 'software equipment', it is 100% a scam.</p><h3>4. Pro Tips & Tricks for VAs</h3><ul><li>Keep all communications on platform until an official contract is active.</li></ul><h3>5. Common Pitfalls to Avoid</h3><p>Avoid depositing checks sent by unknown clients for equipment purchasing.</p>",
            'quiz' => [
                [
                    'question' => "What is the #1 rule to avoid freelance marketplace scams on platforms like Upwork?",
                    'options' => ['Never communicate off-platform or accept un-escrowed payments prior to an active contract', 'Send wire transfers to clients upon request', 'Share online banking passwords', 'Complete 20 hours of unpaid work'],
                    'correct' => "Never communicate off-platform or accept un-escrowed payments prior to an active contract",
                    'explanation' => "Platform escrow rules protect freelancers from fraudulent non-payment."
                ],
                [
                    'question' => "What is a classic indicator of a fake client scam?",
                    'options' => ['Asking you to deposit a cashier check and wire money back for office equipment', 'Signing an official contract with escrow funding', 'Scheduling a video conference call on Zoom', 'Asking for your resume PDF'],
                    'correct' => "Asking you to deposit a cashier check and wire money back for office equipment",
                    'explanation' => "The fake check equipment scam is a common fraudulent trap."
                ],
                [
                    'question' => "How should a freelancer respond if a prospect demands 10 hours of custom unpaid test work?",
                    'options' => ['Politely decline and offer a small paid test project or point to existing portfolio samples', 'Work 10 hours unpaid immediately', 'Send bank account routing numbers', 'Report the client to local law enforcement'],
                    'correct' => "Politely decline and offer a small paid test project or point to existing portfolio samples",
                    'explanation' => "Professional freelancers get paid for trial tasks beyond basic short exercises."
                ]
            ]
        ],
        // Level 7: Strategic Application Tracking & Pipeline Management
        [
            'course_id' => $courseIds[7], 'level' => 7, 'title' => 'Strategic Application Tracking & Pipeline Management', 'slug' => 'strategic-application-tracking-pipeline', 'summary' => 'Using the Application Tracker tool to organize job applications and follow-ups.', 'xp' => 125, 'coins' => 35,
            'content' => "<h2>Executive Masterclass: Strategic Application Tracking & Pipeline Management</h2><p>Using the Application Tracker tool to organize job applications and follow-ups.</p><h3>1. Why This Topic is Needed & Important</h3><p>Managing your job search like a sales funnel guarantees consistent outreach volume.</p><h3>2. Standard Operating Procedure (SOP) Blueprint</h3><p>1. Log every application in the FreelanceQuest Application Tracker tool.<br>2. Track metric conversion ratios: Applications -> Responses -> Interviews -> Wins.<br>3. Schedule polite follow-up messages 3-5 days after submission.</p><h3>3. Real-World Example & Copy-Paste Script</h3><p><b>Pipeline Metric Targets:</b> 10 Applications -> 2 Responses -> 1 Interview -> 1 Client Win.</p><h3>4. Pro Tips & Tricks for VAs</h3><ul><li>Consistent daily application activity yields far better results than sporadic weekly blasts.</li></ul><h3>5. Common Pitfalls to Avoid</h3><p>Avoid applying to 5 jobs and waiting weeks without submitting new applications.</p>",
            'quiz' => [
                [
                    'question' => "Why is tracking job application pipeline metrics (Applications sent vs Interviews) beneficial?",
                    'options' => ['It reveals conversion bottlenecks so you can systematically optimize proposals or resumes', 'It guarantees instant client hires', 'It replaces the need for a portfolio website', 'It automates proposal writing'],
                    'correct' => "It reveals conversion bottlenecks so you can systematically optimize proposals or resumes",
                    'explanation' => "Pipeline data highlights whether your proposal or resume needs improvement."
                ],
                [
                    'question' => "What is the recommended timeframe to send a polite follow-up message after applying?",
                    'options' => ['3 to 5 business days if no reply has been received', '2 minutes after applying', '3 months later', 'Never follow up'],
                    'correct' => "3 to 5 business days if no reply has been received",
                    'explanation' => "A polite follow-up demonstrates enthusiasm without appearing impatient."
                ],
                [
                    'question' => "What tool in FreelanceQuest helps learners monitor application statuses?",
                    'options' => ['Application Tracker', 'Resume Builder Print Mode', 'Coin Transfer Utility', 'Password Security Generator'],
                    'correct' => "Application Tracker",
                    'explanation' => "The Application Tracker organizes applications, statuses, and follow-up reminders."
                ]
            ]
        ],
        // Level 8: The Hook-Problem-Solution Proposal Blueprint
        [
            'course_id' => $courseIds[8], 'level' => 8, 'title' => 'The Hook-Problem-Solution Proposal Blueprint', 'slug' => 'hook-problem-solution-proposal-blueprint', 'summary' => 'Writing personalized client proposals that win high response rates.', 'xp' => 150, 'coins' => 50,
            'content' => "<h2>Executive Masterclass: The Hook-Problem-Solution Proposal Blueprint</h2><p>Writing personalized client proposals that win high response rates.</p><h3>1. Why This Topic is Needed & Important</h3><p>Generic proposal copy-pasting gets ignored; personalized proposals win client contracts.</p><h3>2. Standard Operating Procedure (SOP) Blueprint</h3><p>1. Hook the reader in line 1 by referencing their specific business pain point.<br>2. Demonstrate clear understanding of the Problem.<br>3. Present your tailored Solution + relevant portfolio link + low-friction call-to-action.</p><h3>3. Real-World Example & Copy-Paste Script</h3><p><b>Winning Hook:</b> 'Hi [Name], I noticed your Shopify store checkout page is missing abandoned cart recovery flows. Here is how I can fix that...'</p><h3>4. Pro Tips & Tricks for VAs</h3><ul><li>Ask a insightful question about the project at the end of your proposal to prompt a reply.</li></ul><h3>5. Common Pitfalls to Avoid</h3><p>Avoid starting proposals with generic lines like 'Dear Hiring Manager, I am applying for this job'.</p>",
            'quiz' => [
                [
                    'question' => "What is the biggest flaw in generic copy-paste proposals?",
                    'options' => ["They fail to address the client's specific business needs and get ignored immediately", 'They take too long to read', 'They contain too many portfolio links', 'They cost extra money to send'],
                    'correct' => "They fail to address the client's specific business needs and get ignored immediately",
                    'explanation' => "Clients ignore canned proposals that show zero research or relevance."
                ],
                [
                    'question' => "What should the first line (the Hook) of a winning proposal accomplish?",
                    'options' => ["Grab attention by demonstrating immediate understanding of the client's specific project or problem", 'State your full legal name and address', 'Demand an hourly rate raise', 'List every software tool you have ever used'],
                    'correct' => "Grab attention by demonstrating immediate understanding of the client's specific project or problem",
                    'explanation' => "An engaging hook proves you actually read their job description."
                ],
                [
                    'question' => "Why is ending a proposal with a low-friction question effective?",
                    'options' => ['It encourages the client to hit reply to answer your question, starting an active conversation', 'It forces the client to sign a contract instantly', 'It hides your portfolio links', 'It prevents other freelancers from applying'],
                    'correct' => "It encourages the client to hit reply to answer your question, starting an active conversation",
                    'explanation' => "Ending with a question converts passive proposal readers into active chat conversations."
                ]
            ]
        ],
        // Level 8: Deconstructing Client Job Requirements & Pain Points
        [
            'course_id' => $courseIds[8], 'level' => 8, 'title' => 'Deconstructing Client Job Requirements & Pain Points', 'slug' => 'deconstructing-client-job-requirements', 'summary' => 'Extracting hidden client pain points and tailoring proposals to solve them.', 'xp' => 150, 'coins' => 50,
            'content' => "<h2>Executive Masterclass: Deconstructing Client Job Requirements & Pain Points</h2><p>Extracting hidden client pain points and tailoring proposals to solve them.</p><h3>1. Why This Topic is Needed & Important</h3><p>Understanding what a client *really* wants beyond the job text sets you apart.</p><h3>2. Standard Operating Procedure (SOP) Blueprint</h3><p>1. Read between the lines of the job post to identify underlying business anxiety.<br>2. Rephrase their core challenge in your proposal to validate their frustration.<br>3. Outline exact steps to solve that anxiety.</p><h3>3. Real-World Example & Copy-Paste Script</h3><p><b>Insight Example:</b> Job says 'Need VA to organize Drive' -> Hidden Pain: CEO feels out of control and terrified of losing board files.</p><h3>4. Pro Tips & Tricks for VAs</h3><ul><li>Mirror the client's language and key industry terminology in your proposal.</li></ul><h3>5. Common Pitfalls to Avoid</h3><p>Avoid focusing exclusively on what you want; focus entirely on what the client gains.</p>",
            'quiz' => [
                [
                    'question' => "When a client posts 'Need Executive VA for scheduling', what is their underlying hidden pain point?",
                    'options' => ['They are overwhelmed by meeting chaos and need someone to protect their time and mental focus', 'They want to spend money on software licenses', 'They do not know how to use computers', 'They are bored'],
                    'correct' => "They are overwhelmed by meeting chaos and need someone to protect their time and mental focus",
                    'explanation' => "Understanding underlying emotional drivers allows you to position your service as relief."
                ],
                [
                    'question' => "Why should freelancers mirror the client's phrasing and terminology in proposals?",
                    'options' => ['It creates instant psychological rapport and shows you understand their domain', 'It tricks spam filters into ignoring you', 'It increases word count artificially', 'It satisfies legal copyright requirements'],
                    'correct' => "It creates instant psychological rapport and shows you understand their domain",
                    'explanation' => "Language mirroring signals deep familiarity with the client's industry."
                ],
                [
                    'question' => "What angle should a winning proposal focus on primarily?",
                    'options' => ["How your service solves the client's problem and saves them time/money", 'Your personal financial needs and bills', 'A complete list of your personal hobbies', 'Negative complaints about previous clients'],
                    'correct' => "How your service solves the client's problem and saves them time/money",
                    'explanation' => "Client-centric positioning converts far higher than self-focused listing."
                ]
            ]
        ],
        // Level 8: Proposal Scoring & A/B Testing Strategy
        [
            'course_id' => $courseIds[8], 'level' => 8, 'title' => 'Proposal Scoring & A/B Testing Strategy', 'slug' => 'proposal-scoring-ab-testing-strategy', 'summary' => 'Evaluating proposal effectiveness using criteria scoring and testing formats.', 'xp' => 150, 'coins' => 50,
            'content' => "<h2>Executive Masterclass: Proposal Scoring & A/B Testing Strategy</h2><p>Evaluating proposal effectiveness using criteria scoring and testing formats.</p><h3>1. Why This Topic is Needed & Important</h3><p>Systematically testing different proposal angles improves response rates continuously.</p><h3>2. Standard Operating Procedure (SOP) Blueprint</h3><p>1. Score proposals on 5 criteria: Personalization, Relevance, Clarity, Proof, and Call-to-Action.<br>2. Test 2 hook variations: Question Hook vs Direct Insight Hook.<br>3. Track response rate performance.</p><h3>3. Real-World Example & Copy-Paste Script</h3><p><b>A/B Test Metric:</b> Format A (Question Hook): 20% reply rate vs Format B (Direct Insight): 35% reply rate.</p><h3>4. Pro Tips & Tricks for VAs</h3><ul><li>Keep proposals short (150-250 words max); concise messages get read completely.</li></ul><h3>5. Common Pitfalls to Avoid</h3><p>Avoid sending long essay proposals exceeding 500 words.</p>",
            'quiz' => [
                [
                    'question' => "What maximum word count range is optimal for client proposals on marketplaces?",
                    'options' => ['150 to 250 words', '1,000 to 2,000 words', '5 to 10 words', '5,000 words'],
                    'correct' => "150 to 250 words",
                    'explanation' => "Concise, punchy proposals get read completely by busy decision-makers."
                ],
                [
                    'question' => "How does A/B testing benefit a freelancer's application strategy?",
                    'options' => ['By comparing different proposal hooks to identify which message generates the highest client reply rate', 'By doubling application submission costs', 'By automatically deleting rejected applications', 'By hiding portfolio samples'],
                    'correct' => "By comparing different proposal hooks to identify which message generates the highest client reply rate",
                    'explanation' => "A/B testing uses data to refine your proposal conversion rates."
                ],
                [
                    'question' => "Which criteria are used to score proposal quality in FreelanceQuest?",
                    'options' => ['Personalization, Relevance, Clarity, Social Proof, and Call to Action', 'Font size, background color, and page count', 'Number of spelling mistakes and emoji count', 'File upload size'],
                    'correct' => "Personalization, Relevance, Clarity, Social Proof, and Call to Action",
                    'explanation' => "These 5 elements determine whether a proposal converts or gets ignored."
                ]
            ]
        ],
        // Level 9: Discovery Call Structure & Pitching Mastery
        [
            'course_id' => $courseIds[9], 'level' => 9, 'title' => 'Discovery Call Structure & Pitching Mastery', 'slug' => 'discovery-call-structure-pitching', 'summary' => 'Structuring 20-minute client discovery calls to close high-value deals.', 'xp' => 150, 'coins' => 50,
            'content' => "<h2>Executive Masterclass: Discovery Call Structure & Pitching Mastery</h2><p>Structuring 20-minute client discovery calls to close high-value deals.</p><h3>1. Why This Topic is Needed & Important</h3><p>Discovery calls are sales conversations where you diagnose client needs and prescribe your solution.</p><h3>2. Standard Operating Procedure (SOP) Blueprint</h3><p>1. Agenda Setting (2m) -> 2. Client Needs Diagnosis (10m) -> 3. Solution Pitch (5m) -> 4. Next Steps (3m).<br>2. Ask powerful open-ended diagnostic questions.<br>3. Confidently state your rate and offer.</p><h3>3. Real-World Example & Copy-Paste Script</h3><p><b>Call Opener:</b> 'Thanks for joining, [Name]! To make the best use of our 20 minutes, I would love to learn more about your operational goals before showing how I can help.'</p><h3>4. Pro Tips & Tricks for VAs</h3><ul><li>Let the client speak 70% of the time during the diagnosis phase.</li></ul><h3>5. Common Pitfalls to Avoid</h3><p>Avoid rambling about your resume background for 15 minutes straight.</p>",
            'quiz' => [
                [
                    'question' => "What is the optimal speaking time ratio during a client discovery call?",
                    'options' => ['Freelancer listens 70% of the time and speaks 30% of the time', 'Freelancer speaks 100% of the time without stopping', 'Client speaks 0% of the time', 'Freelancer plays background music'],
                    'correct' => "Freelancer listens 70% of the time and speaks 30% of the time",
                    'explanation' => "Listening allows you to diagnose exact client needs before prescribing solutions."
                ],
                [
                    'question' => "What is the 4-part structure of an effective 20-minute discovery call?",
                    'options' => ['1. Agenda Setting, 2. Diagnosis Questions, 3. Solution Pitch, 4. Next Steps', '1. Price Negotiation, 2. Argument, 3. Apology, 4. Hangup', '1. Resume Reading, 2. Hobby Chat, 3. Weather Review, 4. Goodbye', '1. Silence, 2. Screen Share, 3. Payment Request, 4. Feedback'],
                    'correct' => "1. Agenda Setting, 2. Diagnosis Questions, 3. Solution Pitch, 4. Next Steps",
                    'explanation' => "This structured framework maintains control and leads naturally to closing."
                ],
                [
                    'question' => "How should a freelancer transition to closing at the end of a discovery call?",
                    'options' => ["Propose clear next steps: 'I will send a summary proposal and contract link by 5 PM today for your review.'", 'Ask the client to transfer money immediately via personal wire', 'Beg for the job repeatedly', 'Hang up without speaking'],
                    'correct' => "Propose clear next steps: 'I will send a summary proposal and contract link by 5 PM today for your review.'",
                    'explanation' => "Clear next steps maintain momentum and professional authority."
                ]
            ]
        ],
        // Level 9: Handling Difficult Client Objections & Tough Questions
        [
            'course_id' => $courseIds[9], 'level' => 9, 'title' => 'Handling Difficult Client Objections & Tough Questions', 'slug' => 'handling-client-objections-tough-questions', 'summary' => 'Overcoming price objections, lack of experience concerns, and timeline doubts.', 'xp' => 150, 'coins' => 50,
            'content' => "<h2>Executive Masterclass: Handling Difficult Client Objections & Tough Questions</h2><p>Overcoming price objections, lack of experience concerns, and timeline doubts.</p><h3>1. Why This Topic is Needed & Important</h3><p>Objections are requests for further information; handling them calmly closes deals.</p><h3>2. Standard Operating Procedure (SOP) Blueprint</h3><p>1. Acknowledge and validate the client concern without getting defensive.<br>2. Reframe price as an investment return.<br>3. Provide social proof or a small risk-free trial.</p><h3>3. Real-World Example & Copy-Paste Script</h3><p><b>Price Objection Handling:</b> 'I understand budget is important. My rate reflects my guarantee to handle this completely without needing micromanagement, saving you 15 hours a week.'</p><h3>4. Pro Tips & Tricks for VAs</h3><ul><li>Never argue with a client over an objection; validate first, then reframe.</li></ul><h3>5. Common Pitfalls to Avoid</h3><p>Avoid discounting your rates immediately at the first sign of price hesitation.</p>",
            'quiz' => [
                [
                    'question' => "How should a freelancer respond when a client says 'Your hourly rate is too expensive'?",
                    'options' => ['Validate the budget concern and reframe your rate around value, saved time, and execution quality', 'Slash your price by 50% immediately', 'Get angry and insult the client', 'Cancel the meeting and block the client'],
                    'correct' => "Validate the budget concern and reframe your rate around value, saved time, and execution quality",
                    'explanation' => "Reframing rate around return on investment addresses client value perception."
                ],
                [
                    'question' => "What technique helps overcome a client's concern about a lack of experience in a specific niche?",
                    'options' => ['Highlighting transferable core skills, fast learning capability, and proposing a small initial milestone', 'Lying about 20 years of non-existent experience', 'Ignoring the question completely', 'Blaming previous clients'],
                    'correct' => "Highlighting transferable core skills, fast learning capability, and proposing a small initial milestone",
                    'explanation' => "Transparency combined with confidence and trial milestones overcomes experience doubts."
                ],
                [
                    'question' => "When a client expresses concern about meeting deadlines, what reassures them?",
                    'options' => ['Presenting your standard workflow process, time tracking tools, and daily status update policy', 'Promising to never sleep until the project is finished', 'Offering to work for free forever', 'Refusing to commit to deadlines'],
                    'correct' => "Presenting your standard workflow process, time tracking tools, and daily status update policy",
                    'explanation' => "Showing structured processes proves reliability and execution discipline."
                ]
            ]
        ],
        // Level 9: Interactive AI Interview Simulator Practice
        [
            'course_id' => $courseIds[9], 'level' => 9, 'title' => 'Interactive AI Interview Simulator Practice', 'slug' => 'interactive-ai-interview-simulator-practice', 'summary' => 'Simulating video and text client interviews with instant feedback metrics.', 'xp' => 150, 'coins' => 50,
            'content' => "<h2>Executive Masterclass: Interactive AI Interview Simulator Practice</h2><p>Simulating video and text client interviews with instant feedback metrics.</p><h3>1. Why This Topic is Needed & Important</h3><p>Practicing interview responses in low-stakes simulations builds unshakable call confidence.</p><h3>2. Standard Operating Procedure (SOP) Blueprint</h3><p>1. Launch the Interview Arena simulator.<br>2. Answer randomized client interview questions within timed limits.<br>3. Review AI score feedback on Confidence, Clarity, and Relevance.</p><h3>3. Real-World Example & Copy-Paste Script</h3><p><b>STAR Response Method:</b> Situation -> Task -> Action -> Result.</p><h3>4. Pro Tips & Tricks for VAs</h3><ul><li>Use the STAR method for every behavioral interview question ('Tell me about a time when...').</li></ul><h3>5. Common Pitfalls to Avoid</h3><p>Avoid giving long, unstructured rambling answers without a clear outcome result.</p>",
            'quiz' => [
                [
                    'question' => "What does the STAR interview response framework stand for?",
                    'options' => ['Situation, Task, Action, Result', 'Speed, Toughness, Accuracy, Reliability', 'Software, Testing, Analysis, Research', 'System, Team, Approval, Report'],
                    'correct' => "Situation, Task, Action, Result",
                    'explanation' => "The STAR framework structures behavioral answers logically and concisely."
                ],
                [
                    'question' => "What key metrics does the Interview Arena evaluate after an interview practice session?",
                    'options' => ['Communication, Confidence, Relevance, and Problem Solving scores', 'Typing speed and monitor resolution', 'Hair style and room background lighting', 'Microphone brand and cable length'],
                    'correct' => "Communication, Confidence, Relevance, and Problem Solving scores",
                    'explanation' => "These core metrics reflect candidate professionalism and delivery quality."
                ],
                [
                    'question' => "Why is practice in the Interview Simulator valuable before real client calls?",
                    'options' => ['It builds vocal confidence, refines structured responses, and eliminates nervous hesitation', 'It guarantees 100% job placement automatically', 'It replaces the need for a service agreement', 'It bypasses client interview calls completely'],
                    'correct' => "It builds vocal confidence, refines structured responses, and eliminates nervous hesitation",
                    'explanation' => "Low-stakes simulation practice prepares you to execute flawlessly on real sales calls."
                ]
            ]
        ],
        // Level 10: High Ticket Cold Outreach & Pitching
        [
            'course_id' => $courseIds[10], 'level' => 10, 'title' => 'High Ticket Cold Outreach & Pitching', 'slug' => 'cold-outreach-pitching', 'summary' => 'Finding decision makers, personalized Loom videos, cold email templates.', 'xp' => 150, 'coins' => 50,
            'content' => "<h2>Executive Masterclass: High Ticket Cold Outreach & Pitching</h2><p>Finding decision makers, personalized Loom videos, cold email templates.</p><h3>1. Why This Topic is Needed & Important</h3><p>Direct cold outreach allows you to pitch CEOs and founders directly, bypassing public marketplace price competition.</p><h3>2. Standard Operating Procedure (SOP) Blueprint</h3><p>1. Audit target account websites or social channels.<br>2. Record a 90-second Loom audit video pointing out operational gaps.<br>3. Follow up politely.</p><h3>3. Real-World Example & Copy-Paste Script</h3><p><b>Loom Pitch Script:</b> 'Hi [Name], I noticed your website contact form has broken validation. I made a quick 60-second video showing how to fix it...'</p><h3>4. Pro Tips & Tricks for VAs</h3><ul><li>Keep Loom audit videos under 90 seconds so busy founders actually watch them.</li></ul><h3>5. Common Pitfalls to Avoid</h3><p>Avoid blast-sending automated generic pitch templates.</p>",
            'quiz' => [
                [
                    'question' => "Why is a 90-second personalized Loom audit video highly effective in cold outreach?",
                    'options' => ['It provides instant value and proves you did real research on the client business', 'It takes 3 hours for the client to watch', 'It forces the client to pay video viewing fees', 'It replaces the need for a service agreement'],
                    'correct' => "It provides instant value and proves you did real research on the client business",
                    'explanation' => "Loom audits showcase your expertise and effort, breaking through noisy email inboxes."
                ],
                [
                    'question' => "What is the recommended approach when finding target contact leads for cold email outreach?",
                    'options' => ['Targeting specific decision-makers (Founders, CEOs, Marketing Directors) directly', 'Sending mass blast emails to info@ or support@ generic inboxes', 'Buying unverified random email lists of 100,000 consumers', 'Posting comments on unrelated forum threads'],
                    'correct' => "Targeting specific decision-makers (Founders, CEOs, Marketing Directors) directly",
                    'explanation' => "Direct decision-maker targeting ensures your pitch reaches the person with budget authority."
                ],
                [
                    'question' => "How many follow-up messages should be included in a professional cold email sequence?",
                    'options' => ['2 to 4 polite, value-adding follow-up emails spaced over 2 weeks', 'Sending 10 messages per day until they reply', 'Never follow up after sending one initial email', 'Sending angry complaints if they do not reply'],
                    'correct' => "2 to 4 polite, value-adding follow-up emails spaced over 2 weeks",
                    'explanation' => "Polite, value-adding follow-ups recover up to 50% of outreach responses from busy decision-makers."
                ]
            ]
        ],
        // Level 10: LinkedIn Social Selling & Direct Messaging Strategy
        [
            'course_id' => $courseIds[10], 'level' => 10, 'title' => 'LinkedIn Social Selling & Direct Messaging Strategy', 'slug' => 'linkedin-social-selling-strategy', 'summary' => 'Building prospect relationships via LinkedIn content and warm DMs.', 'xp' => 150, 'coins' => 50,
            'content' => "<h2>Executive Masterclass: LinkedIn Social Selling & Direct Messaging Strategy</h2><p>Building prospect relationships via LinkedIn content and warm DMs.</p><h3>1. Why This Topic is Needed & Important</h3><p>Social selling on LinkedIn turns cold prospects into warm inbound leads through value commenting and strategic direct messaging.</p><h3>2. Standard Operating Procedure (SOP) Blueprint</h3><p>1. Identify 20 target founder accounts on LinkedIn.<br>2. Leave insightful comments on their posts for 3 days.<br>3. Send a personalized connection request.</p><h3>3. Real-World Example & Copy-Paste Script</h3><p><b>Connection Script:</b> 'Hi [Name], loved your recent post on team communication! As an Executive VA, I agree on EOD reports. Would love to connect!'</p><h3>4. Pro Tips & Tricks for VAs</h3><ul><li>Never pitch your services in the very first connection request message.</li></ul><h3>5. Common Pitfalls to Avoid</h3><p>Avoid automated generic DM sales blasts that sound like spam bots.</p>",
            'quiz' => [
                [
                    'question' => "What is the recommended first step before sending a direct message to a target founder on LinkedIn?",
                    'options' => ['Leaving insightful, value-adding comments on their public posts for several days', 'Sending 10 automated sales pitches immediately', 'Calling their personal cell phone at midnight', 'Reporting their profile'],
                    'correct' => "Leaving insightful, value-adding comments on their public posts for several days",
                    'explanation' => "Insightful comments build warm familiarity and profile visibility before initiating a private chat."
                ],
                [
                    'question' => "Why should freelancers avoid pitching paid services in the initial LinkedIn connection request?",
                    'options' => ['Pitching immediately feels spammy and leads to rejected connection requests', 'LinkedIn blocks all text in connection requests', 'Founders do not have budget for freelancers', 'It violates local labor laws'],
                    'correct' => "Pitching immediately feels spammy and leads to rejected connection requests",
                    'explanation' => "Building initial rapport before pitching creates a higher quality conversation."
                ],
                [
                    'question' => "How does posting weekly authority content on LinkedIn benefit a freelancer's outreach efforts?",
                    'options' => ['When prospects view your profile, your content validates your expertise and authority', 'It guarantees 1,000 likes per post', 'It replaces the need for a resume', 'It allows you to bypass tax filings'],
                    'correct' => "When prospects view your profile, your content validates your expertise and authority",
                    'explanation' => "Relevant content acts as visual proof of your knowledge when prospects inspect your profile."
                ]
            ]
        ],
        // Level 10: Cold Email Infrastructure & Deliverability Setup
        [
            'course_id' => $courseIds[10], 'level' => 10, 'title' => 'Cold Email Infrastructure & Deliverability Setup', 'slug' => 'cold-email-infrastructure-setup', 'summary' => 'Setting up SPF, DKIM, DMARC, secondary domains, and email warm-up.', 'xp' => 150, 'coins' => 50,
            'content' => "<h2>Executive Masterclass: Cold Email Infrastructure & Deliverability Setup</h2><p>Setting up SPF, DKIM, DMARC, secondary domains, and email warm-up.</p><h3>1. Why This Topic is Needed & Important</h3><p>Technical email setup ensures your cold outreach arrives in the inbox rather than the spam folder.</p><h3>2. Standard Operating Procedure (SOP) Blueprint</h3><p>1. Purchase a secondary domain dedicated to outreach.<br>2. Configure DNS authentication records: SPF, DKIM, DMARC.<br>3. Warm up email accounts for 14 days.</p><h3>3. Real-World Example & Copy-Paste Script</h3><p><b>DNS Record Checklist:</b><br>- SPF: `v=spf1 include:_spf.google.com ~all`<br>- DMARC: `v=DMARC1; p=none; rua=mailto:dmarc@yourdomain.com`</p><h3>4. Pro Tips & Tricks for VAs</h3><ul><li>Keep daily cold email volume under 30 emails per inbox account.</li></ul><h3>5. Common Pitfalls to Avoid</h3><p>Avoid inserting tracking pixels or multiple attachments in initial cold outreach emails.</p>",
            'quiz' => [
                [
                    'question' => "Why should freelancers conduct cold email campaigns using a secondary domain rather than their main domain?",
                    'options' => ['To protect the main website domain reputation in case emails get flagged as spam', 'Because main domains cannot send emails', 'Secondary domains are free of charge', 'It increases typing speed'],
                    'correct' => "To protect the main website domain reputation in case emails get flagged as spam",
                    'explanation' => "Using secondary domains isolates outreach deliverability risks from your primary business website."
                ],
                [
                    'question' => "What do DNS records like SPF, DKIM, and DMARC accomplish for email sending accounts?",
                    'options' => ['Verifying sender authenticity so receiving mail servers deliver messages to the inbox', 'Increasing computer RAM memory', 'Creating graphic website logos', 'Encrypting PDF document passwords'],
                    'correct' => "Verifying sender authenticity so receiving mail servers deliver messages to the inbox",
                    'explanation' => "DNS records prove to Gmail and Outlook that the email legitimately belongs to your domain."
                ],
                [
                    'question' => "How long should a new outreach email inbox be 'warmed up' before launching cold campaigns?",
                    'options' => ['14 days of automated warm-up sending', '0 minutes', '6 months', '3 years'],
                    'correct' => "14 days of automated warm-up sending",
                    'explanation' => "A 14-day warm-up period establishes baseline sender reputation with major email service providers."
                ]
            ]
        ],
        // Level 11: Value-Based Pricing & Rate Negotiation
        [
            'course_id' => $courseIds[11], 'level' => 11, 'title' => 'Value-Based Pricing & Rate Negotiation', 'slug' => 'value-based-pricing-rate-negotiation', 'summary' => 'Transitioning from low hourly rates to value-based project and retainer pricing.', 'xp' => 150, 'coins' => 50,
            'content' => "<h2>Executive Masterclass: Value-Based Pricing & Rate Negotiation</h2><p>Transitioning from low hourly rates to value-based project and retainer pricing.</p><h3>1. Why This Topic is Needed & Important</h3><p>Charging based on the business value delivered allows you to command $1,000-$3,000+ monthly retainers.</p><h3>2. Standard Operating Procedure (SOP) Blueprint</h3><p>1. Calculate the financial impact or time value saved for the client.<br>2. Present 3 tiered pricing options (Basic, Growth, Premium).<br>3. Anchor pricing with high value outcomes.</p><h3>3. Real-World Example & Copy-Paste Script</h3><p><b>Tiered Retainer Offer:</b> Tier 1 ($500/mo): 10 hrs inbox management; Tier 2 ($1,200/mo): Full Executive Support + Lead Gen; Tier 3 ($2,500/mo): Full Operations Ownership.</p><h3>4. Pro Tips & Tricks for VAs</h3><ul><li>Always present 3 options; clients naturally gravitiate toward the middle tier.</li></ul><h3>5. Common Pitfalls to Avoid</h3><p>Avoid competing solely on being the cheapest option in the market.</p>",
            'quiz' => [
                [
                    'question' => "What is the primary concept behind value-based pricing?",
                    'options' => ['Pricing services based on the financial outcome and time savings created for the client business', 'Pricing work strictly by counting total seconds typed', 'Charging whatever the competitor charges minus 50%', 'Offering free work to every client'],
                    'correct' => "Pricing services based on the financial outcome and time savings created for the client business",
                    'explanation' => "Value pricing aligns freelancer earnings with the tangible return on investment generated for the client."
                ],
                [
                    'question' => "Why is presenting 3 tiered pricing packages effective during rate negotiations?",
                    'options' => ["It shifts the client mindset from 'Should I hire this VA?' to 'Which package fits my budget best?'", 'It confuses the client into overpaying', 'It forces the client to pick option 1', 'It reduces proposal word count'],
                    'correct' => "It shifts the client mindset from 'Should I hire this VA?' to 'Which package fits my budget best?'",
                    'explanation' => "Tiered options provide choice architecture that increases average contract value."
                ],
                [
                    'question' => "How does anchor pricing influence client rate perception?",
                    'options' => ['By establishing a higher premium tier first, making lower tiers appear more affordable and attractive', 'By lowering all prices to $1/hr', 'By hiding pricing details until work is done', 'By charging surprise hidden fees'],
                    'correct' => "By establishing a higher premium tier first, making lower tiers appear more affordable and attractive",
                    'explanation' => "Anchor pricing establishes premium positioning and frames secondary options as great value."
                ]
            ]
        ],
        // Level 11: Structuring Monthly Recurring Retainers
        [
            'course_id' => $courseIds[11], 'level' => 11, 'title' => 'Structuring Monthly Recurring Retainers', 'slug' => 'structuring-monthly-recurring-retainers', 'summary' => 'Designing predictable recurring monthly retainer packages for long-term client retention.', 'xp' => 150, 'coins' => 50,
            'content' => "<h2>Executive Masterclass: Structuring Monthly Recurring Retainers</h2><p>Designing predictable recurring monthly retainer packages for long-term client retention.</p><h3>1. Why This Topic is Needed & Important</h3><p>Monthly retainers create predictable income stability without constant job hunting.</p><h3>2. Standard Operating Procedure (SOP) Blueprint</h3><p>1. Scope a dedicated monthly deliverables list or reserved monthly hours.<br>2. Require upfront invoice payment on the 1st of every month.<br>3. Define rollover policy rules (unused hours expire monthly).</p><h3>3. Real-World Example & Copy-Paste Script</h3><p><b>Retainer Agreement Clause:</b> 'Monthly retainer of $1,500 is due on the 1st of each month prior to work commencement. Unused hours do not roll over.'</p><h3>4. Pro Tips & Tricks for VAs</h3><ul><li>Bill retainers strictly upfront before providing service hours each month.</li></ul><h3>5. Common Pitfalls to Avoid</h3><p>Avoid allowing client hours to roll over indefinitely into future months.</p>",
            'quiz' => [
                [
                    'question' => "What payment schedule should be enforced for monthly freelancer retainers?",
                    'options' => ['Upfront invoice payment at the start of each month before work commences', 'Payment 90 days after the month ends', 'Payment only when the client feels like paying', 'Annual payments in arrears'],
                    'correct' => "Upfront invoice payment at the start of each month before work commences",
                    'explanation' => "Upfront retainer billing ensures cash flow predictability and eliminates payment default risks."
                ],
                [
                    'question' => "Why should unused retainer hours expire at the end of each billing month?",
                    'options' => ['To maintain predictable workload capacity and protect freelancer time boundaries', 'To punish clients intentionally', 'Because software deletes unused hours', 'Because bank accounts reset monthly'],
                    'correct' => "To maintain predictable workload capacity and protect freelancer time boundaries",
                    'explanation' => "Expiration prevents client hour hoarding that creates unmanageable future workload spikes."
                ],
                [
                    'question' => "What is the primary business benefit of converting one-off clients into monthly retainers?",
                    'options' => ['Predictable monthly recurring revenue (MRR) that eliminates sales stress', 'Fewer total working hours forever', 'Exemption from paying taxes', 'Automatic client referrals without effort'],
                    'correct' => "Predictable monthly recurring revenue (MRR) that eliminates sales stress",
                    'explanation' => "Retainers establish sustainable financial stability for freelance businesses."
                ]
            ]
        ],
        // Level 11: Freelance Service Contracts & Scope Creep Defense
        [
            'course_id' => $courseIds[11], 'level' => 11, 'title' => 'Freelance Service Contracts & Scope Creep Defense', 'slug' => 'freelance-service-contracts-scope-creep-defense', 'summary' => 'Drafting legal agreements, IP transfer clauses, and defending boundaries against scope creep.', 'xp' => 150, 'coins' => 50,
            'content' => "<h2>Executive Masterclass: Freelance Service Contracts & Scope Creep Defense</h2><p>Drafting legal agreements, IP transfer clauses, and defending boundaries against scope creep.</p><h3>1. Why This Topic is Needed & Important</h3><p>Solid service agreements protect your business from scope creep, unpaid work, and legal disputes.</p><h3>2. Standard Operating Procedure (SOP) Blueprint</h3><p>1. Define explicit Scope of Work boundaries and deliverable lists.<br>2. Include Change Order pricing terms for out-of-scope requests.<br>3. Specify payment terms, late fees, and termination notice periods.</p><h3>3. Real-World Example & Copy-Paste Script</h3><p><b>Scope Creep Response Script:</b> 'I would be happy to help with that additional graphic! Since it falls outside our current agreement, I can add it as a Change Order for $75. Should I proceed?'</p><h3>4. Pro Tips & Tricks for VAs</h3><ul><li>Never start client work without a signed written contract agreement.</li></ul><h3>5. Common Pitfalls to Avoid</h3><p>Avoid doing extra unbilled tasks repeatedly without enforcing Change Orders.</p>",
            'quiz' => [
                [
                    'question' => "What is 'scope creep' in freelance client project management?",
                    'options' => ['When a client continuously requests additional tasks beyond the original agreed contract scope without extra pay', 'When project deadlines are met early', 'When a client pays invoices early', 'When a project gets canceled'],
                    'correct' => "When a client continuously requests additional tasks beyond the original agreed contract scope without extra pay",
                    'explanation' => "Scope creep erodes profitability by adding uncompensated work expectations."
                ],
                [
                    'question' => "How should a professional freelancer handle an out-of-scope client request?",
                    'options' => ['Politely acknowledge the request and present a Change Order invoice with additional fees before starting', 'Do the extra work silently while complaining to friends', 'Refuse aggressively and insult the client', 'Quit the project immediately'],
                    'correct' => "Politely acknowledge the request and present a Change Order invoice with additional fees before starting",
                    'explanation' => "Change Orders establish professional boundaries while monetizing additional client requests."
                ],
                [
                    'question' => "What essential legal clause protects freelancer intellectual property until full payment is received?",
                    'options' => ['IP Ownership Transfer upon Final Payment Clause', 'Non-compete clause', 'Unconditional free transfer clause', 'Public domain release clause'],
                    'correct' => "IP Ownership Transfer upon Final Payment Clause",
                    'explanation' => "Retaining IP rights until full invoice settlement ensures payment leverage."
                ]
            ]
        ],
        // Level 12: Asynchronous Communication & Project Management Frameworks
        [
            'course_id' => $courseIds[12], 'level' => 12, 'title' => 'Asynchronous Communication & Project Management Frameworks', 'slug' => 'async-communication-project-management', 'summary' => 'Mastering Asana, Trello, ClickUp, and Slack for smooth remote project execution.', 'xp' => 175, 'coins' => 75,
            'content' => "<h2>Executive Masterclass: Asynchronous Communication & Project Management Frameworks</h2><p>Mastering Asana, Trello, ClickUp, and Slack for smooth remote project execution.</p><h3>1. Why This Topic is Needed & Important</h3><p>Asynchronous management enables seamless execution without needing constant real-time meetings.</p><h3>2. Standard Operating Procedure (SOP) Blueprint</h3><p>1. Setup ClickUp/Asana boards with standardized status columns.<br>2. Communicate via structured Loom videos and written Slack threads.<br>3. Maintain centralized SOP documentation.</p><h3>3. Real-World Example & Copy-Paste Script</h3><p><b>Slack Update Blueprint:</b> `[PROJECT UPDATE] Status: Green | Completed: User Auth | Next: API Integration | Blockers: None`</p><h3>4. Pro Tips & Tricks for VAs</h3><ul><li>Record 2-minute Loom videos instead of scheduling 30-minute status meetings.</li></ul><h3>5. Common Pitfalls to Avoid</h3><p>Avoid sending fragmented, unformatted Slack messages across 10 separate chat bubbles.</p>",
            'quiz' => [
                [
                    'question' => "What is the key advantage of asynchronous communication in remote team operations?",
                    'options' => ['Work progresses across time zones without requiring real-time overlapping meeting hours', 'It eliminates the need for written text', 'It forces team members to work at 3 AM', 'It makes projects take twice as long'],
                    'correct' => "Work progresses across time zones without requiring real-time overlapping meeting hours",
                    'explanation' => "Async workflows allow team members to focus deeply and collaborate across global time zones."
                ],
                [
                    'question' => "How should project task status boards (Asana/ClickUp) be organized?",
                    'options' => ['Clear workflow columns: Backlog, To Do, In Progress, Review, Completed', 'Random scattered notes with no tags', 'One single column with 500 tasks mixed together', 'Deleted daily after work'],
                    'correct' => "Clear workflow columns: Backlog, To Do, In Progress, Review, Completed",
                    'explanation' => "Kanban status columns provide immediate visual clarity on project stage progress."
                ],
                [
                    'question' => "Why are 2-minute video updates (Loom) superior to 30-minute status meetings?",
                    'options' => ["They convey visual context efficiently while respecting everyone's working schedule", 'They consume more internet bandwidth', 'They prevent clients from asking questions', 'They replace all written contracts'],
                    'correct' => "They convey visual context efficiently while respecting everyone's working schedule",
                    'explanation' => "Async video updates save hours of wasted meeting time while preserving visual detail."
                ]
            ]
        ],
        // Level 12: Handling Client Emergencies & Crisis Communication
        [
            'course_id' => $courseIds[12], 'level' => 12, 'title' => 'Handling Client Emergencies & Crisis Communication', 'slug' => 'handling-client-emergencies-crisis-communication', 'summary' => 'Managing urgent site downtime, missed deadlines, and high-stress client panics.', 'xp' => 175, 'coins' => 75,
            'content' => "<h2>Executive Masterclass: Handling Client Emergencies & Crisis Communication</h2><p>Managing urgent site downtime, missed deadlines, and high-stress client panics.</p><h3>1. Why This Topic is Needed & Important</h3><p>Remaining calm and structured during operational crises turns panicky clients into lifelong advocates.</p><h3>2. Standard Operating Procedure (SOP) Blueprint</h3><p>1. Acknowledge the emergency instantly (under 15 mins).<br>2. Issue an Incident Report with current status, cause, and ETA.<br>3. Provide hourly progress updates until resolution.</p><h3>3. Real-World Example & Copy-Paste Script</h3><p><b>Crisis Acknowledgment Script:</b> 'Hi [Name], I have identified the website downtime. I am actively restoring the database backup now. Next update in 20 minutes.'</p><h3>4. Pro Tips & Tricks for VAs</h3><ul><li>Focus 100% on immediate problem resolution before debating blame or root causes.</li></ul><h3>5. Common Pitfalls to Avoid</h3><p>Avoid panicking, ignoring client calls, or making unverified promises.</p>",
            'quiz' => [
                [
                    'question' => "What is the first step a freelancer must take when a client operational crisis occurs?",
                    'options' => ['Acknowledge receipt of the issue immediately and state the active resolution steps underway', 'Turn off your phone and go offline for 24 hours', 'Blame third-party software publicly', 'Demand an emergency bonus before helping'],
                    'correct' => "Acknowledge receipt of the issue immediately and state the active resolution steps underway",
                    'explanation' => "Immediate acknowledgment de-escalates panic and reassures the client that help is active."
                ],
                [
                    'question' => "How frequently should status updates be provided during an active operational outage?",
                    'options' => ['Regular predictable intervals (e.g. every 30-60 minutes) until resolved', 'Once every 3 weeks', 'Only when the client emails 10 times', 'Never update the client during an outage'],
                    'correct' => "Regular predictable intervals (e.g. every 30-60 minutes) until resolved",
                    'explanation' => "Frequent status updates maintain client trust during high-stress operational incidents."
                ],
                [
                    'question' => "What document should be provided to the client after resolving an emergency incident?",
                    'options' => ['A Post-Mortem Incident Report detailing root cause, resolution steps, and preventive measures', 'A termination letter', 'An invoice with 500% emergency surcharges without notice', 'A blank text file'],
                    'correct' => "A Post-Mortem Incident Report detailing root cause, resolution steps, and preventive measures",
                    'explanation' => "Post-mortems demonstrate high operational maturity and prevent repeat errors."
                ]
            ]
        ],
        // Level 12: Standard Operating Procedure (SOP) Creation Mastery
        [
            'course_id' => $courseIds[12], 'level' => 12, 'title' => 'Standard Operating Procedure (SOP) Creation Mastery', 'slug' => 'sop-creation-mastery', 'summary' => 'Documenting repeatable workflows, process documentation, and loom walkthroughs.', 'xp' => 175, 'coins' => 75,
            'content' => "<h2>Executive Masterclass: Standard Operating Procedure (SOP) Creation Mastery</h2><p>Documenting repeatable workflows, process documentation, and loom walkthroughs.</p><h3>1. Why This Topic is Needed & Important</h3><p>Writing clear SOPs allows clients to delegate operational workflows reliably to team members.</p><h3>2. Standard Operating Procedure (SOP) Blueprint</h3><p>1. Record step-by-step Loom screen recording performing the task.<br>2. Transcribe steps into written checklist format.<br>3. Store in centralized company wiki (Notion/Scribe).</p><h3>3. Real-World Example & Copy-Paste Script</h3><p><b>SOP Document Header:</b> Title, Purpose, Prerequisites, Step-by-Step Instructions, Troubleshooting, Owner.</p><h3>4. Pro Tips & Tricks for VAs</h3><ul><li>Test your SOP by having a junior colleague execute the task without your help.</li></ul><h3>5. Common Pitfalls to Avoid</h3><p>Avoid writing vague instructions that assume prior institutional knowledge.</p>",
            'quiz' => [
                [
                    'question' => "What is the purpose of a Standard Operating Procedure (SOP) in business operations?",
                    'options' => ['Providing step-by-step instructions so any qualified team member can execute a task consistently', 'Creating legal tax exemption forms', 'Encrypting client database passwords', 'Writing marketing sales emails'],
                    'correct' => "Providing step-by-step instructions so any qualified team member can execute a task consistently",
                    'explanation' => "SOPs standardize execution quality and streamline team onboarding."
                ],
                [
                    'question' => "What dual format makes SOPs easiest for remote team members to digest?",
                    'options' => ['A screen recording video walkthrough paired with a written step-by-step checklist', 'A 50-page unformatted text document', 'An audio recording spoken in whispers', 'A hand-drawn paper diagram mailed via post'],
                    'correct' => "A screen recording video walkthrough paired with a written step-by-step checklist",
                    'explanation' => "Visual video plus step-by-step written text covers all learning preferences."
                ],
                [
                    'question' => "How can a VA verify that an SOP they authored is complete and accurate?",
                    'options' => ['Have another team member complete the task using only the SOP without verbal assistance', 'Assume it is perfect without testing', 'Delete the SOP after writing', 'Ask the client to grade the grammar'],
                    'correct' => "Have another team member complete the task using only the SOP without verbal assistance",
                    'explanation' => "Independent execution testing confirms zero missing steps or ambiguous instructions."
                ]
            ]
        ],
        // Level 13: Client Retention & Account Expansion Systems
        [
            'course_id' => $courseIds[13], 'level' => 13, 'title' => 'Client Retention & Account Expansion Systems', 'slug' => 'client-retention-account-expansion', 'summary' => 'Increasing Lifetime Value (LTV) through monthly performance reviews and proactive upsells.', 'xp' => 175, 'coins' => 75,
            'content' => "<h2>Executive Masterclass: Client Retention & Account Expansion Systems</h2><p>Increasing Lifetime Value (LTV) through monthly performance reviews and proactive upsells.</p><h3>1. Why This Topic is Needed & Important</h3><p>Retaining existing clients is 5x cheaper than acquiring new ones; upselling increases monthly revenue.</p><h3>2. Standard Operating Procedure (SOP) Blueprint</h3><p>1. Host Quarterly Business Reviews (QBRs) showcasing completed ROI.<br>2. Identify emerging client operational gaps.<br>3. Pitch complementary service add-ons.</p><h3>3. Real-World Example & Copy-Paste Script</h3><p><b>QBR Upsell Pitch:</b> 'Over Q2, we saved 45 executive hours on inbox management. For Q3, I recommend expanding our scope to handle lead follow-ups for $800/mo.'</p><h3>4. Pro Tips & Tricks for VAs</h3><ul><li>Proactively propose solutions before the client realizes they need them.</li></ul><h3>5. Common Pitfalls to Avoid</h3><p>Avoid waiting for clients to complain before reviewing account health.</p>",
            'quiz' => [
                [
                    'question' => "What is a Quarterly Business Review (QBR) in freelance client retention?",
                    'options' => ['A structured strategic meeting reviewing past ROI accomplishments and mapping future goals', 'An invoice demanding immediate double payment', 'A tax audit conducted by government agents', 'A job interview for a new company'],
                    'correct' => "A structured strategic meeting reviewing past ROI accomplishments and mapping future goals",
                    'explanation' => "QBRs reinforce your value contribution and open strategic upsell opportunities."
                ],
                [
                    'question' => "Why is retaining existing clients more profitable than constantly acquiring new ones?",
                    'options' => ['Lower customer acquisition cost, higher trust, and expansion opportunity through upselling', 'Existing clients do not require payment invoices', 'New clients always pay late', 'Existing clients require zero work'],
                    'correct' => "Lower customer acquisition cost, higher trust, and expansion opportunity through upselling",
                    'explanation' => "Existing relationships yield higher profit margins and steady recurring revenue."
                ],
                [
                    'question' => "When pitching a service upsell to an existing client, what should be emphasized?",
                    'options' => ['The additional business value, efficiency, and revenue outcomes created for their company', 'Your need for more personal spending money', 'How easy the task is for you', 'A list of personal monthly expenses'],
                    'correct' => "The additional business value, efficiency, and revenue outcomes created for their company",
                    'explanation' => "Frame upsells around client growth and operational relief."
                ]
            ]
        ],
        // Level 13: Automation Workflows with Zapier & Make.com
        [
            'course_id' => $courseIds[13], 'level' => 13, 'title' => 'Automation Workflows with Zapier & Make.com', 'slug' => 'automation-workflows-zapier-make', 'summary' => 'Building automated multi-step workflows between web apps without code.', 'xp' => 175, 'coins' => 75,
            'content' => "<h2>Executive Masterclass: Automation Workflows with Zapier & Make.com</h2><p>Building automated multi-step workflows between web apps without code.</p><h3>1. Why This Topic is Needed & Important</h3><p>Automation allows VAs to handle 10x task volume in a fraction of the time, boosting profit margins.</p><h3>2. Standard Operating Procedure (SOP) Blueprint</h3><p>1. Identify repetitive manual data transfer tasks.<br>2. Build multi-step Zaps or Make scenarios.<br>3. Test trigger filters and error handling.</p><h3>3. Real-World Example & Copy-Paste Script</h3><p><b>Zapier Blueprint:</b> Trigger: New Typeform Submission -> Action 1: Create Contact in HubSpot -> Action 2: Send Slack Alert to Sales Team.</p><h3>4. Pro Tips & Tricks for VAs</h3><ul><li>Charge clients for initial automation setup plus monthly maintenance retainers.</li></ul><h3>5. Common Pitfalls to Avoid</h3><p>Avoid building fragile automations without error handling or notification alerts.</p>",
            'quiz' => [
                [
                    'question' => "What do no-code automation platforms like Zapier or Make.com accomplish?",
                    'options' => ['Connecting web applications to automatically trigger multi-step workflows without custom code', 'Designing 3D video graphics', 'Managing physical server hardware', 'Hosting domain registration names'],
                    'correct' => "Connecting web applications to automatically trigger multi-step workflows without custom code",
                    'explanation' => "Automation links web tools to eliminate manual data entry and task repetition."
                ],
                [
                    'question' => "How does leveraging automation benefit a freelancer's profit margin?",
                    'options' => ['It allows you to deliver higher output in significantly fewer manual hours while charging for value', 'It forces clients to pay per computer click', 'It reduces client contract prices to zero', 'It deletes client project files'],
                    'correct' => "It allows you to deliver higher output in significantly fewer manual hours while charging for value",
                    'explanation' => "Delivering automated results efficiently increases hourly earnings dramatically."
                ],
                [
                    'question' => "In Zapier automation architecture, what is a 'Trigger'?",
                    'options' => ['The initial event that starts an automated workflow sequence (e.g., a new form submission)', 'The button used to delete an account', 'The invoice sent to a client', 'The password encryption key'],
                    'correct' => "The initial event that starts an automated workflow sequence (e.g., a new form submission)",
                    'explanation' => "Triggers detect new data events that launch downstream automated actions."
                ]
            ]
        ],
        // Level 13: Managing Subcontractors & White-Label Delegation
        [
            'course_id' => $courseIds[13], 'level' => 13, 'title' => 'Managing Subcontractors & White-Label Delegation', 'slug' => 'managing-subcontractors-white-label-delegation', 'summary' => 'Hiring junior VAs, setting up quality control, and sub-contracting work.', 'xp' => 175, 'coins' => 75,
            'content' => "<h2>Executive Masterclass: Managing Subcontractors & White-Label Delegation</h2><p>Hiring junior VAs, setting up quality control, and sub-contracting work.</p><h3>1. Why This Topic is Needed & Important</h3><p>Delegating execution tasks to junior team members transforms you from a freelancer into a agency operator.</p><h3>2. Standard Operating Procedure (SOP) Blueprint</h3><p>1. Document strict SOPs and quality benchmarks.<br>2. Hire junior specialists on a per-project or hourly basis.<br>3. Review all work outputs before final client delivery.</p><h3>3. Real-World Example & Copy-Paste Script</h3><p><b>White-Label Margin Formula:</b> Client pays you $50/hr -> You pay Subcontractor $20/hr -> Net Margin = $30/hr.</p><h3>4. Pro Tips & Tricks for VAs</h3><ul><li>Maintain total ownership of client communication and final quality assurance.</li></ul><h3>5. Common Pitfalls to Avoid</h3><p>Never allow subcontractors to contact your direct clients directly without authorization.</p>",
            'quiz' => [
                [
                    'question' => "What is 'white-label' subcontracting in freelance business growth?",
                    'options' => ['Hiring external specialists to execute project tasks while delivering final work under your brand name', 'Selling blank paper invoices to clients', 'Working for zero profit margin', 'Redesigning client brand logos in white'],
                    'correct' => "Hiring external specialists to execute project tasks while delivering final work under your brand name",
                    'explanation' => "White-labeling allows you to expand capacity and service offerings under your unified brand."
                ],
                [
                    'question' => "What remains the primary responsibility of the lead freelancer when subcontracting?",
                    'options' => ['Client relationship management, strategy, and final Quality Assurance (QA) review', 'Doing 100% of the manual labor personally', 'Hiding subcontractor existence from taxes', 'Forcing subcontractors to speak to clients'],
                    'correct' => "Client relationship management, strategy, and final Quality Assurance (QA) review",
                    'explanation' => "You remain fully accountable to the client for delivery standards and relationship management."
                ],
                [
                    'question' => "How is net profit margin calculated in a subcontracting model?",
                    'options' => ['Client Contract Rate minus Subcontractor Pay Rate equals Net Profit Margin', 'Subcontractor Pay Rate multiplied by 100', 'Total Revenue divided by zero', 'Client Rate plus Tax'],
                    'correct' => "Client Contract Rate minus Subcontractor Pay Rate equals Net Profit Margin",
                    'explanation' => "The difference between your client billing rate and labor costs forms your gross profit margin."
                ]
            ]
        ],
        // Level 14: Transitioning from Freelancer to Digital Agency Founder
        [
            'course_id' => $courseIds[14], 'level' => 14, 'title' => 'Transitioning from Freelancer to Digital Agency Founder', 'slug' => 'transitioning-freelancer-to-agency-founder', 'summary' => 'Structuring agency offerings, branding, and team leadership models.', 'xp' => 200, 'coins' => 100,
            'content' => "<h2>Executive Masterclass: Transitioning from Freelancer to Digital Agency Founder</h2><p>Structuring agency offerings, branding, and team leadership models.</p><h3>1. Why This Topic is Needed & Important</h3><p>Transitioning to an agency model unlocks enterprise client contracts and scalable revenue.</p><h3>2. Standard Operating Procedure (SOP) Blueprint</h3><p>1. Define specialized agency niche service packages.<br>2. Establish brand entity, legal structure, and domain.<br>3. Hire dedicated Account Managers and Specialists.</p><h3>3. Real-World Example & Copy-Paste Script</h3><p><b>Agency Positioning:</b> 'We are a specialized Operations & Lead Gen Agency for B2B SaaS Companies.'</p><h3>4. Pro Tips & Tricks for VAs</h3><ul><li>Focus your founder role exclusively on Sales, Strategy, and Team Leadership.</li></ul><h3>5. Common Pitfalls to Avoid</h3><p>Avoid getting stuck doing low-level task execution once you launch your agency.</p>",
            'quiz' => [
                [
                    'question' => "What is the primary shift in founder focus when transitioning from freelancer to agency owner?",
                    'options' => ['Shifting from manual task execution to sales growth, strategic leadership, and team management', 'Working 100 hours a week on basic data entry', 'Deleting all client contracts', 'Stopping all sales outreach'],
                    'correct' => "Shifting from manual task execution to sales growth, strategic leadership, and team management",
                    'explanation' => "Agency founders build systems and teams rather than performing every task personally."
                ],
                [
                    'question' => "Why do agency entities command larger enterprise contracts than solo freelancers?",
                    'options' => ['Clients perceive agencies as lower risk with broader team capacity, redundancy, and specialized depth', 'Agencies pay no taxes anywhere', 'Agencies guarantee zero mistakes ever', 'Solo freelancers are legally barred from enterprise work'],
                    'correct' => "Clients perceive agencies as lower risk with broader team capacity, redundancy, and specialized depth",
                    'explanation' => "Enterprise buyers favor agency infrastructure for risk mitigation and scale."
                ],
                [
                    'question' => "What key leadership role manages day-to-day client relationships in an agency structure?",
                    'options' => ['Account Manager', 'Lead Software Engineer', 'Junior Intern', 'Bookkeeping Assistant'],
                    'correct' => "Account Manager",
                    'explanation' => "Account Managers oversee client communication and ensure project delivery align with expectations."
                ]
            ]
        ],
        // Level 14: Agency Business Finances, Taxes & Profit Margins
        [
            'course_id' => $courseIds[14], 'level' => 14, 'title' => 'Agency Business Finances, Taxes & Profit Margins', 'slug' => 'agency-business-finances-taxes-margins', 'summary' => 'Managing business cash flow, tax compliance, invoicing software, and profit targets.', 'xp' => 200, 'coins' => 100,
            'content' => "<h2>Executive Masterclass: Agency Business Finances, Taxes & Profit Margins</h2><p>Managing business cash flow, tax compliance, invoicing software, and profit targets.</p><h3>1. Why This Topic is Needed & Important</h3><p>Rigorous financial management ensures agency profitability, tax compliance, and long-term stability.</p><h3>2. Standard Operating Procedure (SOP) Blueprint</h3><p>1. Separate personal and business bank accounts strictly.<br>2. Target healthy gross profit margins (50%+) and net margins (20%+).<br>3. Utilize QuickBooks or Xero for real-time tracking.</p><h3>3. Real-World Example & Copy-Paste Script</h3><p><b>Target Financial Breakdown:</b> 50% Delivery Costs | 30% Overhead & Sales | 20% Net Owner Profit.</p><h3>4. Pro Tips & Tricks for VAs</h3><ul><li>Set aside 25-30% of gross revenue monthly into a dedicated tax holding account.</li></ul><h3>5. Common Pitfalls to Avoid</h3><p>Never commingle personal expenses with business bank accounts.</p>",
            'quiz' => [
                [
                    'question' => "What target net profit margin range should a lean digital agency aim for?",
                    'options' => ['20% to 35% Net Profit Margin', '0% Net Margin', '95% Net Margin with zero overhead', 'Negative 10% Margin'],
                    'correct' => "20% to 35% Net Profit Margin",
                    'explanation' => "Healthy net profit margins ensure cash reserves for business growth and owner distribution."
                ],
                [
                    'question' => "Why must personal and business bank accounts be strictly separated from day one?",
                    'options' => ['To ensure clean financial auditing, legal liability protection, and easy tax compliance', 'Because banks require separate passwords', 'To prevent credit cards from working', 'To hide income from business partners'],
                    'correct' => "To ensure clean financial auditing, legal liability protection, and easy tax compliance",
                    'explanation' => "Separation protects corporate veil liability and simplifies accounting bookkeeping."
                ],
                [
                    'question' => "What percentage of gross freelance/agency revenue should be reserved for tax obligations?",
                    'options' => ['25% to 30% reserved monthly in a dedicated tax account', '0% because freelancers pay no tax', '100% of all earnings', '5% once every 10 years'],
                    'correct' => "25% to 30% reserved monthly in a dedicated tax account",
                    'explanation' => "Reserving funds monthly prevents surprise end-of-year tax liabilities."
                ]
            ]
        ],
        // Level 14: Inbound Organic Lead Engines for Agencies
        [
            'course_id' => $courseIds[14], 'level' => 14, 'title' => 'Inbound Organic Lead Engines for Agencies', 'slug' => 'inbound-organic-lead-engines-agencies', 'summary' => 'Building inbound content funnels, SEO guides, and referral networks.', 'xp' => 200, 'coins' => 100,
            'content' => "<h2>Executive Masterclass: Inbound Organic Lead Engines for Agencies</h2><p>Building inbound content funnels, SEO guides, and referral networks.</p><h3>1. Why This Topic is Needed & Important</h3><p>Inbound lead engines attract qualified clients who seek out your expertise, eliminating cold outreach fatigue.</p><h3>2. Standard Operating Procedure (SOP) Blueprint</h3><p>1. Publish weekly high-value case study articles on LinkedIn and YouTube.<br>2. Create downloadable lead magnet guides.<br>3. Build an active client referral incentive network.</p><h3>3. Real-World Example & Copy-Paste Script</h3><p><b>Referral Incentive Script:</b> 'We offer a $250 credit or cash reward for every client referral you introduce to our agency!'</p><h3>4. Pro Tips & Tricks for VAs</h3><ul><li>Case study breakdowns turn casual readers into high-intent inbound discovery calls.</li></ul><h3>5. Common Pitfalls to Avoid</h3><p>Avoid publishing generic motivational posts that lack actionable business value.</p>",
            'quiz' => [
                [
                    'question' => "What is an inbound lead engine in digital marketing strategy?",
                    'options' => ['A content and authority system that attracts prospective clients directly to your booking calendar', 'A cold phone calling bot platform', 'Buying unverified leads from spam brokers', 'Sending physical mail letters'],
                    'correct' => "A content and authority system that attracts prospective clients directly to your booking calendar",
                    'explanation' => "Inbound funnels convert authority content into qualified incoming prospect leads."
                ],
                [
                    'question' => "Why are client referral incentive programs highly effective for agency growth?",
                    'options' => ['Referred leads close faster and trust your agency based on existing peer recommendations', 'Referrals cost $0 to fulfill', 'Referred clients never require contracts', 'Referral programs replace the need for work quality'],
                    'correct' => "Referred leads close faster and trust your agency based on existing peer recommendations",
                    'explanation' => "Peer trust significantly accelerates deal closure rates and pipeline conversion."
                ],
                [
                    'question' => "What lead magnet asset converts content readers into sales prospects effectively?",
                    'options' => ['Actionable downloadable templates, SOP checklists, or industry benchmark guides', 'A 500-page generic textbook', 'Random unformatted software code', 'Personal vacation photo albums'],
                    'correct' => "Actionable downloadable templates, SOP checklists, or industry benchmark guides",
                    'explanation' => "High-utility lead magnets attract target prospects looking to solve specific challenges."
                ]
            ]
        ],
        // Level 15: The Freelance Master Mindset & Industry Leadership
        [
            'course_id' => $courseIds[15], 'level' => 15, 'title' => 'The Freelance Master Mindset & Industry Leadership', 'slug' => 'freelance-master-mindset-industry-leadership', 'summary' => 'Building a recognized personal brand, keynote speaking, and industry authority.', 'xp' => 250, 'coins' => 150,
            'content' => "<h2>Executive Masterclass: The Freelance Master Mindset & Industry Leadership</h2><p>Building a recognized personal brand, keynote speaking, and industry authority.</p><h3>1. Why This Topic is Needed & Important</h3><p>True freelance mastery transforms you into a recognized category leader and industry mentor.</p><h3>2. Standard Operating Procedure (SOP) Blueprint</h3><p>1. Position yourself as a thought leader through podcasting, writing, and speaking.<br>2. Mentor emerging freelancers in the community.<br>3. Maintain ruthless personal discipline and work-life harmony.</p><h3>3. Real-World Example & Copy-Paste Script</h3><p><b>Industry Authority Mantra:</b> 'Mastery is not about knowing everything; it is about consistent execution, mentorship, and continuous learning.'</p><h3>4. Pro Tips & Tricks for VAs</h3><ul><li>Give back to the community by answering questions and guiding junior VAs.</li></ul><h3>5. Common Pitfalls to Avoid</h3><p>Avoid complacency; continuously refine your skills as technology and AI evolve.</p>",
            'quiz' => [
                [
                    'question' => "What defines a true Freelance Master in the digital economy?",
                    'options' => ['Sustained business excellence, industry authority, high client retention, and community mentorship', 'Working 100 hours a week for minimum wage', 'Refusing to learn new AI software tools', 'Competing exclusively on being the lowest price'],
                    'correct' => "Sustained business excellence, industry authority, high client retention, and community mentorship",
                    'explanation' => "Mastery encompasses professional competence, business scale, and community leadership."
                ],
                [
                    'question' => "How does building personal brand authority benefit high-level consultants?",
                    'options' => ['Commanding premium consulting fees, attracting inbound opportunities, and building long-term equity', 'It guarantees zero business expenses', 'It bypasses all local business licensing', 'It replaces the need for internet access'],
                    'correct' => "Commanding premium consulting fees, attracting inbound opportunities, and building long-term equity",
                    'explanation' => "Authority positioning creates inelastic demand for your high-level expertise."
                ],
                [
                    'question' => "Why is community mentorship an essential component of career mastery?",
                    'options' => ['Sharing knowledge reinforces your own mastery while building a powerful professional network', 'It is legally mandatory for freelancers', 'Mentorship pays immediate cash bonuses from platform bots', 'It eliminates the need for contracts'],
                    'correct' => "Sharing knowledge reinforces your own mastery while building a powerful professional network",
                    'explanation' => "Mentorship strengthens industry standards and builds lasting professional goodwill."
                ]
            ]
        ],
        // Level 15: AI-Augmented Freelancing & Future-Proofing Strategy
        [
            'course_id' => $courseIds[15], 'level' => 15, 'title' => 'AI-Augmented Freelancing & Future-Proofing Strategy', 'slug' => 'ai-augmented-freelancing-future-proofing', 'summary' => 'Leveraging LLMs, AI agents, and prompt engineering to scale productivity 10x.', 'xp' => 250, 'coins' => 150,
            'content' => "<h2>Executive Masterclass: AI-Augmented Freelancing & Future-Proofing Strategy</h2><p>Leveraging LLMs, AI agents, and prompt engineering to scale productivity 10x.</p><h3>1. Why This Topic is Needed & Important</h3><p>Embracing AI tools supercharges execution speed, positioning you as an indispensable modern freelancer.</p><h3>2. Standard Operating Procedure (SOP) Blueprint</h3><p>1. Master prompt engineering frameworks (Context, Role, Task, Constraints, Format).<br>2. Integrate ChatGPT, Claude, and Midjourney into daily workflows.<br>3. Build custom AI GPT assistants for client operations.</p><h3>3. Real-World Example & Copy-Paste Script</h3><p><b>Prompt Framework:</b> Role: Senior Copywriter | Task: Write 3 email subject lines | Context: Launching SaaS tool | Constraints: Under 50 chars.</p><h3>4. Pro Tips & Tricks for VAs</h3><ul><li>Use AI as an expert assistant to draft 80% of work, then apply human quality editing for the final 20%.</li></ul><h3>5. Common Pitfalls to Avoid</h3><p>Never deliver unedited raw AI outputs directly to clients without human review.</p>",
            'quiz' => [
                [
                    'question' => "What is the 80/20 rule of AI-augmented freelancing execution?",
                    'options' => ['AI generates the 80% baseline draft in seconds; human expertise polishes the final 20% for quality and context', 'AI does 20% of work and human works 80 hours', '100% raw AI output delivered without reading', 'AI is forbidden in remote work'],
                    'correct' => "AI generates the 80% baseline draft in seconds; human expertise polishes the final 20% for quality and context",
                    'explanation' => "Combining AI generation speed with human expert curation yields optimal performance."
                ],
                [
                    'question' => "What are the 5 core components of a structured AI prompt framework?",
                    'options' => ['Role, Context, Task, Constraints, and Output Format', 'Name, Age, Address, Zip Code, Phone', 'Title, Color, Size, Font, Margin', 'Start, Pause, Stop, Record, Play'],
                    'correct' => "Role, Context, Task, Constraints, and Output Format",
                    'explanation' => "Structured prompts guide language models to produce accurate, high-utility results."
                ],
                [
                    'question' => "Why is human curation necessary when utilizing AI language models for client work?",
                    'options' => ['AI models can produce subtle hallucinations, factual errors, or tone mismatches that require expert human review', 'AI outputs are encrypted and unreadable', 'Clients require hand-written paper submissions', 'AI software deletes files automatically'],
                    'correct' => "AI models can produce subtle hallucinations, factual errors, or tone mismatches that require expert human review",
                    'explanation' => "Human oversight guarantees accuracy, brand alignment, and contextual nuance."
                ]
            ]
        ],
        // Level 15: Building Long-Term Wealth & Exit Strategy for Freelancers
        [
            'course_id' => $courseIds[15], 'level' => 15, 'title' => 'Building Long-Term Wealth & Exit Strategy for Freelancers', 'slug' => 'building-long-term-wealth-exit-strategy', 'summary' => 'Investing freelance cash flows, recurring equity, asset acquisition, and business exits.', 'xp' => 250, 'coins' => 150,
            'content' => "<h2>Executive Masterclass: Building Long-Term Wealth & Exit Strategy for Freelancers</h2><p>Investing freelance cash flows, recurring equity, asset acquisition, and business exits.</p><h3>1. Why This Topic is Needed & Important</h3><p>Converting freelance income into long-term financial wealth creates true independence and freedom.</p><h3>2. Standard Operating Procedure (SOP) Blueprint</h3><p>1. Allocate profits into diversified investment index funds and real estate.<br>2. Build sellable agency assets with recurring contract equity.<br>3. Execute an eventual business sale exit or passive management transition.</p><h3>3. Real-World Example & Copy-Paste Script</h3><p><b>Wealth Plan:</b> Earn high active freelance revenue -> Invest 40% into passive assets -> Build equity assets.</p><h3>4. Pro Tips & Tricks for VAs</h3><ul><li>Treat active freelance revenue as the engine to fund long-term passive investment assets.</li></ul><h3>5. Common Pitfalls to Avoid</h3><p>Avoid lifestyle creep where expenses rise to match every increase in freelance income.</p>",
            'quiz' => [
                [
                    'question' => "What is the ultimate financial goal of transforming active freelance income into investments?",
                    'options' => ['Building long-term passive wealth and financial independence that does not rely on daily labor', 'Spending 100% of earnings on luxury consumer goods immediately', 'Keeping all cash in a physical paper wallet', 'Stopping all investments'],
                    'correct' => "Building long-term passive wealth and financial independence that does not rely on daily labor",
                    'explanation' => "Investing active earnings converts time-bound labor into compounding long-term assets."
                ],
                [
                    'question' => "What makes a digital agency or freelance business sellable to outside acquirers?",
                    'options' => ['Standardized SOPs, recurring retainer revenue, and a team that operates without founder involvement', 'A founder who does 100% of all work personally', 'Zero written contracts or client records', 'High debt and no revenue'],
                    'correct' => "Standardized SOPs, recurring retainer revenue, and a team that operates without founder involvement",
                    'explanation' => "Acquirers purchase predictable systems, recurring revenue, and independent operational teams."
                ],
                [
                    'question' => "What is 'lifestyle creep' and why should growing freelancers avoid it?",
                    'options' => ['Increasing personal spending at the same rate as income growth, preventing long-term wealth accumulation', 'Working in multiple coffee shops', 'Moving to remote time zones', 'Upgrading computer hardware'],
                    'correct' => "Increasing personal spending at the same rate as income growth, preventing long-term wealth accumulation",
                    'explanation' => "Controlling personal expenses allows you to channel rising profits into wealth-building assets."
                ]
            ]
        ]
    ];

    $stmtLes = $pdo->prepare("INSERT INTO lessons (course_id, level_number, title, slug, summary, content, xp_reward, coin_reward, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmtQz = $pdo->prepare("INSERT INTO quizzes (lesson_id, title, passing_score, xp_reward) VALUES (?, ?, 70, 100)");
    $stmtQn = $pdo->prepare("INSERT INTO questions (quiz_id, question_text, options, correct_option, explanation) VALUES (?, ?, ?, ?, ?)");

    $sort = 1;
    foreach ($lessonsMasterData as $ld) {
        $stmtLes->execute([$ld['course_id'], $ld['level'], $ld['title'], $ld['slug'], $ld['summary'], $ld['content'], $ld['xp'], $ld['coins'], $sort++]);
        $lesId = $pdo->lastInsertId();

        $stmtQz->execute([$lesId, 'Knowledge Check: ' . $ld['title']]);
        $quizId = $pdo->lastInsertId();

        foreach ($ld['quiz'] as $qData) {
            $optionsJson = json_encode($qData['options']);
            $stmtQn->execute([
                $quizId,
                $qData['question'],
                $optionsJson,
                $qData['correct'],
                $qData['explanation']
            ]);
        }
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

    // 6. Resources Vault Items across Course Levels
    $resources = [
        ['level_number' => 1, 'title' => 'Virtual Assistant Starter Playbook', 'description' => 'Complete beginner guide to setting up your VA career and service menu.', 'type' => 'pdf', 'file_content_or_url' => '/downloads/level-01-va-starter-playbook.md', 'is_premium' => false],
        ['level_number' => 2, 'title' => 'Google Workspace Keyboard Shortcuts Cheat Sheet', 'description' => 'Boost your typing speed and cloud efficiency instantly.', 'type' => 'cheat_sheet', 'file_content_or_url' => '/downloads/level-02-google-workspace-shortcuts.md', 'is_premium' => false],
        ['level_number' => 3, 'title' => 'Client Onboarding SOP Checklist', 'description' => 'Professional checklist for onboarding new client projects without friction.', 'type' => 'template', 'file_content_or_url' => '/downloads/level-03-client-onboarding-sop-checklist.md', 'is_premium' => false],
        ['level_number' => 4, 'title' => 'Canva & Social Media Content Calendar Template', 'description' => 'Monthly social content planning grid for Social Media VAs.', 'type' => 'template', 'file_content_or_url' => '/downloads/level-04-social-media-content-calendar.md', 'is_premium' => false],
        ['level_number' => 5, 'title' => 'High-Converting ATS VA Resume Template', 'description' => 'ATS-friendly resume layout designed specifically for remote VAs.', 'type' => 'template', 'file_content_or_url' => '/downloads/level-05-ats-resume-template.md', 'is_premium' => true],
        ['level_number' => 6, 'title' => 'Portfolio Case Study Builder Framework', 'description' => 'Structured template for writing Problem-Solution-Result case studies.', 'type' => 'template', 'file_content_or_url' => '/downloads/level-06-portfolio-case-study-template.md', 'is_premium' => true],
        ['level_number' => 7, 'title' => 'Scam Detection & Client Vetting Checklist', 'description' => 'Red flag screening checklist for evaluating job offers and client verification.', 'type' => 'checklist', 'file_content_or_url' => '/downloads/level-07-scam-detection-checklist.md', 'is_premium' => false],
        ['level_number' => 8, 'title' => '10 Winning Proposal & Pitch Scripts', 'description' => 'Proven proposal templates that earned over $100k in freelancing.', 'type' => 'script', 'file_content_or_url' => '/downloads/level-08-pitch-scripts.md', 'is_premium' => true],
        ['level_number' => 9, 'title' => 'STAR Interview Response Preparation Sheet', 'description' => 'Behavioral interview question prep worksheet using the STAR framework.', 'type' => 'worksheet', 'file_content_or_url' => '/downloads/level-09-star-interview-worksheet.md', 'is_premium' => true],
        ['level_number' => 10, 'title' => 'High Ticket Loom Cold Audit Script', 'description' => '90-second video audit framework for pitching founders directly.', 'type' => 'script', 'file_content_or_url' => '/downloads/level-10-loom-cold-audit-script.md', 'is_premium' => true],
        ['level_number' => 11, 'title' => 'Client Service Agreement & Contract Template', 'description' => 'Standard freelance agreement covering payment terms and scope limits.', 'type' => 'template', 'file_content_or_url' => '/downloads/level-11-freelance-contract-template.md', 'is_premium' => true],
        ['level_number' => 12, 'title' => 'Hourly Rate & Retainer Calculator Worksheet', 'description' => 'Calculate your exact hourly rates and monthly retainer packages.', 'type' => 'calculator', 'file_content_or_url' => '/downloads/level-12-retainer-calculator-worksheet.md', 'is_premium' => true],
        ['level_number' => 13, 'title' => 'Monthly Client ROI Review Presentation Deck', 'description' => 'Slide deck framework for presenting monthly ROI to retainer accounts.', 'type' => 'template', 'file_content_or_url' => '/downloads/level-13-monthly-roi-review-deck.md', 'is_premium' => true],
        ['level_number' => 14, 'title' => 'Virtual Agency SOP Operations Manual', 'description' => 'Standard Operating Procedures for hiring subcontractors and managing agency workflows.', 'type' => 'manual', 'file_content_or_url' => '/downloads/level-14-agency-operations-manual.md', 'is_premium' => true],
        ['level_number' => 15, 'title' => '6-Figure Business Automation Playbook', 'description' => 'Advanced Zapier, Make, and AI workflow integration manual.', 'type' => 'manual', 'file_content_or_url' => '/downloads/level-15-business-automation-playbook.md', 'is_premium' => true],
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

    // Seed Default Coupons
    $stmtCpn = $pdo->prepare("INSERT INTO coupons (code, discount_percent, discount_amount, is_active) VALUES (?, ?, ?, 1)");
    $stmtCpn->execute(['FREELANCE50', 50, 0]);
    $stmtCpn->execute(['WELCOME10', 0, 10.00]);

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
