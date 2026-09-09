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
        // Level 1: Foundations
        [
            'course_id' => $courseIds[1], 'level' => 1, 'title' => 'What is a Virtual Assistant?', 'slug' => 'what-is-a-va', 'summary' => 'Understand the role, services, and opportunities of modern VAs.', 'xp' => 50, 'coins' => 10,
            'content' => "<h2>Executive Masterclass: What is a Virtual Assistant?</h2><p>A Virtual Assistant (VA) is an independent remote professional who provides administrative, technical, creative, or strategic business support to founders, executives, and agencies. Rather than acting as a passive worker, top VAs operate as proactive operations partners.</p><h3>1. Primary Responsibilities & Core Value</h3><p>Virtual Assistants eliminate operational bottlenecks by handling inbox triage, calendar scheduling, web research, client onboarding, lead verification, and software maintenance. By taking over these recurring tasks, VAs save busy clients 10 to 20 hours every week.</p><h3>2. Proactive Partner Mindset</h3><p>Elite VAs do not wait for step-by-step instructions. They review existing workflows, anticipate upcoming needs, create clear Standard Operating Procedures (SOPs), and propose solutions when problems arise.</p>",
            'quiz' => [
                [
                    'question' => "What is the primary business value a Virtual Assistant provides to a client?",
                    'options' => ["Saving 10 to 20 hours weekly by managing operational tasks", "Replacing the founder as company owner", "Cleaning physical office desks", "Reducing overall company sales"],
                    'correct' => "Saving 10 to 20 hours weekly by managing operational tasks",
                    'explanation' => "Virtual Assistants eliminate routine administrative bottlenecks, freeing up founders to focus on growth."
                ],
                [
                    'question' => "Which mindset distinguishes an elite Virtual Assistant from a basic data processor?",
                    'options' => ["Proactively identifying workflow bottlenecks and offering structured options", "Waiting silently until receiving explicit step-by-step orders", "Ignoring client emails for days", "Working only when supervised in real-time"],
                    'correct' => "Proactively identifying workflow bottlenecks and offering structured options",
                    'explanation' => "Proactive problem-solving creates strategic partnership value that leads to long-term client retention."
                ],
                [
                    'question' => "How do Virtual Assistants typically structure their remote employment?",
                    'options' => ["As independent remote contractors serving one or multiple clients", "As full-time in-office salaried employees", "As unpaid volunteers", "As civil service workers"],
                    'correct' => "As independent remote contractors serving one or multiple clients",
                    'explanation' => "VAs operate remotely as independent contractors, offering flexible professional services globally."
                ]
            ]
        ],
        [
            'course_id' => $courseIds[1], 'level' => 1, 'title' => 'Freelancing vs Traditional Employment', 'slug' => 'freelancing-vs-employment', 'summary' => 'Key differences in mindset, taxes, freedom, and responsibility.', 'xp' => 50, 'coins' => 10,
            'content' => "<h2>Executive Masterclass: Freelancing vs Traditional Employment</h2><p>Transitioning from employee to freelancer requires shifting from trading presence for a paycheck to delivering measurable business value across client relationships.</p><h3>1. Income Structure & Diversification</h3><p>Traditional employees rely on a single employer for income and tax withholding. Freelancers contract with multiple clients, set their own rates, manage expenses, and protect their earnings through client diversification.</p><h3>2. Freedom and Professional Autonomy</h3><p>Freelancers control their schedules, chosen tech stack, and service packages. However, autonomy brings full responsibility for client acquisition, tax compliance, and performance quality control.</p>",
            'quiz' => [
                [
                    'question' => "What is a major financial advantage of freelancing over single-employer jobs?",
                    'options' => ["Income security through client diversification", "Automatic corporate pension plans", "Fixed lifetime salary contracts", "Employer-paid tax withholding"],
                    'correct' => "Income security through client diversification",
                    'explanation' => "Having multiple active client retainer contracts ensures that losing one client does not eliminate your full income."
                ],
                [
                    'question' => "What core metric do clients use to evaluate freelancer performance?",
                    'options' => ["Measurable results and operational efficiency delivered", "Total consecutive hours sitting in a chair", "Physical commute distance", "Number of breaks taken during work"],
                    'correct' => "Measurable results and operational efficiency delivered",
                    'explanation' => "Clients hire freelancers for specific outcomes, reliability, and problem-solving, not just clocked seat time."
                ],
                [
                    'question' => "What key business responsibility must freelancers manage independently?",
                    'options' => ["Tax savings, healthcare, software tools, and invoices", "Providing physical office space for clients", "Setting uniform dress codes", "Paying corporate income tax for clients"],
                    'correct' => "Tax savings, healthcare, software tools, and invoices",
                    'explanation' => "Freelancers act as business owners, managing their own accounting, tax payments, software tools, and invoicing."
                ]
            ]
        ],
        [
            'course_id' => $courseIds[1], 'level' => 1, 'title' => 'Types of Clients & Platforms', 'slug' => 'types-of-clients-and-platforms', 'summary' => 'Upwork, Fiverr, OnlineJobs.ph, and direct client outreach explained.', 'xp' => 50, 'coins' => 10,
            'content' => "<h2>Executive Masterclass: Types of Clients & Platforms</h2><p>Finding high-paying clients requires understanding where remote opportunities exist and how different client types hire talent.</p><h3>1. Job Marketplaces vs Direct Outreach</h3><p>Marketplaces like Upwork, Fiverr, and OnlineJobs.ph provide active job postings with built-in payment escrow. Direct outreach via LinkedIn or email allows you to target decision-makers directly without competing against hundreds of applicants on job boards.</p><h3>2. Solopreneurs vs Agencies vs Corporate Clients</h3><p>Solopreneurs prioritize speed and general support. Agencies require specialized niche skills and adherence to strict agency SOPs. Corporate clients value data security compliance, structured reporting, and formal agreements.</p>",
            'quiz' => [
                [
                    'question' => "Which client type typically requires strict adherence to existing agency Standard Operating Procedures (SOPs)?",
                    'options' => ["Digital Marketing Agencies", "Early-stage solopreneurs with no systems", "Local retail shoppers", "Personal hobby bloggers"],
                    'correct' => "Digital Marketing Agencies",
                    'explanation' => "Agencies rely on established SOPs to maintain consistent service quality across multiple client accounts."
                ],
                [
                    'question' => "Why can direct cold outreach generate higher hourly rates than competitive job marketplaces?",
                    'options' => ["It connects directly with decision-makers without price-bidding wars", "It requires paying marketplace commission fees", "Marketplace clients never pay fair rates", "It bypasses the need for service contracts"],
                    'correct' => "It connects directly with decision-makers without price-bidding wars",
                    'explanation' => "Direct outreach builds one-on-one relationships with CEOs without competing alongside hundreds of public applicants."
                ],
                [
                    'question' => "What is a key benefit of using established platforms like Upwork or Fiverr for beginners?",
                    'options' => ["Built-in escrow and payment protection mechanisms", "Guaranteed hourly rate increases every week", "Automatic resume creation without effort", "Exemption from paying taxes"],
                    'correct' => "Built-in escrow and payment protection mechanisms",
                    'explanation' => "Freelance marketplaces protect talent by holding funds in escrow before work begins."
                ]
            ]
        ],

        // Level 2: Digital Tools
        [
            'course_id' => $courseIds[2], 'level' => 2, 'title' => 'Mastering Google Workspace (Gmail & Drive)', 'slug' => 'google-workspace-mastery', 'summary' => 'Inbox Zero, cloud file organization, sharing permissions.', 'xp' => 60, 'coins' => 12,
            'content' => "<h2>Executive Masterclass: Mastering Google Workspace (Gmail & Drive)</h2><p>Google Workspace is the standard cloud infrastructure for remote teams. Mastering Gmail filters and Drive permissions makes you an indispensable asset.</p><h3>1. Inbox Zero & Filtering Protocols</h3><p>Achieve Inbox Zero by categorizing incoming emails using Gmail labels, stars, and automated filters. Structure folders into Urgent, Waiting on Client, Archive, and Reference.</p><h3>2. Google Drive Systematization & Sharing Security</h3><p>Organize Google Drive using standardized naming conventions (/Client_Name/Project/YYYY-MM-DD_Deliverable_v1). Always verify permissions (Viewer vs Editor) before sending links to ensure client data security.</p>",
            'quiz' => [
                [
                    'question' => "What is the core principle of the Inbox Zero email management method?",
                    'options' => ["Systematically triaging, labeling, or archiving emails so the main inbox remains clear", "Deleting all incoming client emails without reading them", "Leaving thousands of unread emails in the primary inbox folder", "Forwarding all incoming messages to social media"],
                    'correct' => "Systematically triaging, labeling, or archiving emails so the main inbox remains clear",
                    'explanation' => "Inbox Zero prevents missed messages and ensures high priority client inquiries are handled quickly."
                ],
                [
                    'question' => "Which Google Drive permission level should be used when sharing confidential view-only documents with external parties?",
                    'options' => ["Viewer permission", "Editor permission", "Full Admin Owner permission", "Public Access with edit rights"],
                    'correct' => "Viewer permission",
                    'explanation' => "Viewer permissions prevent external parties from modifying or accidentally deleting important document contents."
                ],
                [
                    'question' => "What is the recommended file naming convention for cloud document organization?",
                    'options' => ["/Client_Name/Project_Title/YYYY-MM-DD_Deliverable_v1.0", "document12345.docx", "untitled draft final final.gdoc", "random text copy.pdf"],
                    'correct' => "/Client_Name/Project_Title/YYYY-MM-DD_Deliverable_v1.0",
                    'explanation' => "Clear date and version-stamped file naming prevents file duplication and lost client assets."
                ]
            ]
        ],
        [
            'course_id' => $courseIds[2], 'level' => 2, 'title' => 'Google Sheets for Beginners: Formulas & Sorting', 'slug' => 'google-sheets-beginners', 'summary' => 'SUM, AVERAGE, VLOOKUP basics, data cleaning and filters.', 'xp' => 60, 'coins' => 12,
            'content' => "<h2>Executive Masterclass: Google Sheets for Beginners</h2><p>Data organization is one of the most frequent tasks assigned to VAs. Knowing basic formulas and clean layout rules establishes instant technical credibility.</p><h3>1. Core Formulas Every VA Must Know</h3><p>Master essential functions: =SUM() for totals, =AVERAGE() for statistics, =COUNTIF() for filtered counts, and =VLOOKUP() or =XLOOKUP() for cross-referencing datasets.</p><h3>2. Data Cleaning & Filter Views</h3><p>Clean raw client data by freezing header rows, removing duplicate records, applying Conditional Formatting for alerts, and creating custom Filter Views without affecting team members.</p>",
            'quiz' => [
                [
                    'question' => "Which Google Sheets formula is used to look up matching values from another table column?",
                    'options' => ["=VLOOKUP()", "=SUM()", "=COUNT()", "=CONCATENATE()"],
                    'correct' => "=VLOOKUP()",
                    'explanation' => "VLOOKUP (or XLOOKUP) searches for a key value in one column and returns data from a corresponding row in another column."
                ],
                [
                    'question' => "Why should a Virtual Assistant use Filter Views instead of regular Filters when working in shared team spreadsheets?",
                    'options' => ["Filter Views allow you to filter data without changing what other team members see", "Regular filters permanently delete hidden rows", "Filter Views increase spreadsheet calculation speed by 500%", "Filter Views automatically convert data into video files"],
                    'correct' => "Filter Views allow you to filter data without changing what other team members see",
                    'explanation' => "Filter Views create a temporary private layout, keeping the shared sheet untouched for collaborators."
                ],
                [
                    'question' => "What spreadsheet formatting action keeps top row titles visible when scrolling down thousands of lead records?",
                    'options' => ["Freezing the top header row", "Deleting row 1", "Applying bold text to column A", "Exporting the file to PDF"],
                    'correct' => "Freezing the top header row",
                    'explanation' => "Freezing row 1 locks column headers in place, making large datasets readable during navigation."
                ]
            ]
        ],

        // Level 3: Admin Support
        [
            'course_id' => $courseIds[3], 'level' => 3, 'title' => 'Lead Generation 101: Finding Prospect Contact Info', 'slug' => 'lead-generation-101', 'summary' => 'Apollo.io, Hunter.io, LinkedIn Sales Navigator techniques.', 'xp' => 70, 'coins' => 15,
            'content' => "<h2>Executive Masterclass: Lead Generation 101</h2><p>B2B lead generation is a high-demand skill where VAs extract, verify, and format potential customer lists for sales teams.</p><h3>1. Prospect Mining Tools</h3><p>Use LinkedIn Sales Navigator to target target buyer personas (Industry, Company Size, Title). Utilize Apollo.io, Hunter.io, and NeverBounce to extract verified business email addresses.</p><h3>2. Lead Sheet Verification & Formatting</h3><p>Ensure lead sheets contain clean columns: First Name, Last Name, Title, Company Name, Verified Work Email, LinkedIn URL, and Location. Never deliver unverified or bounce-prone lead lists.</p>",
            'quiz' => [
                [
                    'question' => "Which tool combination is widely used for mining and verifying B2B decision-maker email addresses?",
                    'options' => ["Apollo.io, Hunter.io, and NeverBounce", "Canva, Photoshop, and Illustrator", "WordPress, Elementor, and WooCommerce", "Google Calendar and Calendly"],
                    'correct' => "Apollo.io, Hunter.io, and NeverBounce",
                    'explanation' => "Apollo and Hunter extract email addresses, while NeverBounce validates email deliverability to prevent bounce rate issues."
                ],
                [
                    'question' => "What is the consequence of submitting unverified lead lists with high email bounce rates to a client sales campaign?",
                    'options' => ["The client domain reputation drops and emails get flagged as spam", "Sales conversions instantly increase by 100%", "Email sending limits double automatically", "The client website gets higher Google rankings"],
                    'correct' => "The client domain reputation drops and emails get flagged as spam",
                    'explanation' => "High bounce rates ruin email sender reputation, causing outreach emails to go directly to spam folders."
                ],
                [
                    'question' => "Which LinkedIn tool provides advanced search filters for finding target decision-makers by company revenue and job title?",
                    'options' => ["LinkedIn Sales Navigator", "LinkedIn Learning", "LinkedIn Personal Feed", "LinkedIn Recruiter Lite Lite"],
                    'correct' => "LinkedIn Sales Navigator",
                    'explanation' => "Sales Navigator provides deep search parameters tailored specifically for prospecting B2B buyers."
                ]
            ]
        ],

        // Level 4: Specialist Tracks
        [
            'course_id' => $courseIds[4], 'level' => 4, 'title' => 'Executive VA Masterclass & Gatekeeping', 'slug' => 'executive-va-masterclass', 'summary' => 'High level C-suite support, inbox management, gatekeeping, confidential operations.', 'xp' => 120, 'coins' => 35,
            'content' => "<h2>Executive Masterclass: Executive VA & Gatekeeping</h2><p>Executive Virtual Assistants support CEOs, Founders, and C-suite executives. They act as protective gatekeepers, optimizing executive focus and time allocation.</p><h3>1. Calendar Control & Buffer Protection</h3><p>Protect C-suite schedules by enforcing meeting rules. Add 15-minute buffer periods between calls, block dedicated deep work hours, and confirm attendee agendas prior to scheduling calls.</p><h3>2. Professional Gatekeeping Protocol</h3><p>Screen incoming phone calls, meeting requests, and sales pitches politely but firmly. Triage incoming requests into: Approve, Delegate to Team, Schedule Later, or Politely Decline.</p>",
            'quiz' => [
                [
                    'question' => "What is the primary operational objective of an Executive VA acting as a gatekeeper for a CEO?",
                    'options' => ["Filtering unnecessary interruptions to protect executive focus time", "Blocking all communication with company employees permanently", "Accepting every meeting invitation that arrives", "Canceling all executive appointments without asking"],
                    'correct' => "Filtering unnecessary interruptions to protect executive focus time",
                    'explanation' => "Gatekeeping protects the CEO's calendar from low-value distractions so they can focus on strategic company growth."
                ],
                [
                    'question' => "Why should an Executive VA enforce 15-minute buffer time blocks between C-suite calendar meetings?",
                    'options' => ["To allow time for note taking, bio breaks, and preparation without running late", "To increase total hours spent in meetings", "Because Google Calendar requires mandatory breaks", "To force meeting attendees to pay late fees"],
                    'correct' => "To allow time for note taking, bio breaks, and preparation without running late",
                    'explanation' => "Buffer times prevent back-to-back meeting fatigue and ensure smooth transition time for executive notes."
                ],
                [
                    'question' => "How should an Executive VA handle an uninvited vendor trying to pitch services to the CEO?",
                    'options' => ["Politely ask for an executive deck or summary email to review before granting calendar access", "Give out the CEO personal phone number immediately", "Disconnect the phone call abruptly without responding", "Schedule a 2-hour priority meeting on the CEO calendar"],
                    'correct' => "Politely ask for an executive deck or summary email to review before granting calendar access",
                    'explanation' => "Professional gatekeeping requires screening vendor pitches via email review before committing CEO meeting time."
                ]
            ]
        ],

        // Level 5: Branding
        [
            'course_id' => $courseIds[5], 'level' => 5, 'title' => 'ATS Resume & Headline Optimization', 'slug' => 'ats-resume-mastery', 'summary' => 'Formatting resumes that pass ATS screeners and impress hiring managers.', 'xp' => 130, 'coins' => 40,
            'content' => "<h2>Executive Masterclass: ATS Resume & Headline Optimization</h2><p>Applicant Tracking Systems (ATS) scan resumes before human recruiters read them. Passing ATS screeners requires clean formatting and keyword positioning.</p><h3>1. ATS Formatting Requirements</h3><p>Avoid graphic text boxes, multi-column tables, or embedded images that confuse ATS software. Use standard section headers (Professional Summary, Technical Skills, Professional Experience) and submit clean PDF or DOCX formats.</p><h3>2. Quantifiable Impact Bullet Points</h3><p>Structure experience bullets using the Action Verb + Task + Measurable Result formula. Example: 'Managed executive inbox for CEO, triaging 150+ emails daily and reducing response lag by 40%.'</p>",
            'quiz' => [
                [
                    'question' => "Which design element can cause Applicant Tracking Systems (ATS) to fail when reading a resume file?",
                    'options' => ["Complex graphic text boxes and multi-column table layouts", "Clean single-column text layout", "Standard bullet points with percentage metrics", "Plain black text fonts like Arial or Calibri"],
                    'correct' => "Complex graphic text boxes and multi-column table layouts",
                    'explanation' => "ATS parsers often misread or skip text contained within graphic boxes or non-standard visual tables."
                ],
                [
                    'question' => "What is the recommended formula for writing high-impact resume experience bullet points?",
                    'options' => ["Action Verb + Task + Quantifiable Result", "Job Title + Hours Worked + Daily Salary", "Generic list of job responsibilities without metrics", "Inspirational quotes about hard work"],
                    'correct' => "Action Verb + Task + Quantifiable Result",
                    'explanation' => "Combining action verbs with tangible metric outcomes proves your professional capability to potential employers."
                ],
                [
                    'question' => "Where should primary target keywords like 'Google Workspace', 'Lead Generation', and 'HubSpot CRM' appear on a VA resume?",
                    'options' => ["In both the Professional Summary and a dedicated Technical Skills section", "Hidden in tiny white font at the bottom of the page", "Only in the file name of the document", "In the personal hobbies section"],
                    'correct' => "In both the Professional Summary and a dedicated Technical Skills section",
                    'explanation' => "Placing keywords prominently in summary and skills sections helps ATS screeners match your profile with job criteria."
                ]
            ]
        ],

        // Level 6: Portfolio Development
        [
            'course_id' => $courseIds[6], 'level' => 6, 'title' => 'Building Work Samples & Case Studies', 'slug' => 'work-samples-case-studies', 'summary' => 'Converting simulation mission deliverables into high-impact portfolio proof.', 'xp' => 140, 'coins' => 45,
            'content' => "<h2>Executive Masterclass: Building Work Samples & Case Studies</h2><p>Clients buy proof, not promises. Creating tangible work samples and structured case studies transforms a novice profile into a trusted authority.</p><h3>1. Structure of a Winning VA Case Study</h3><p>Frame your past exercises using the Problem + Solution + Result format. Document the client's initial pain point, explain the exact tools and SOPs you deployed, and highlight the final time or cost savings achieved.</p><h3>2. Live Portfolio Hosting</h3><p>Publish your work samples on a clean public portfolio URL. Include an About section, list of core VA services, embedded document previews, and a direct contact form for inquiries.</p>",
            'quiz' => [
                [
                    'question' => "What is the primary benefit of hosting a public work sample portfolio?",
                    'options' => ["Providing visual proof of competence that builds instant client trust", "Replacing the need to sign contracts", "Guaranteeing search engine traffic without marketing", "Eliminating all client communication"],
                    'correct' => "Providing visual proof of competence that builds instant client trust",
                    'explanation' => "Tangible work samples prove your real-world capability before a client ever hops on an interview call."
                ],
                [
                    'question' => "What structure should be used when writing a VA project case study?",
                    'options' => ["Problem + Solution + Measurable Result", "Headline + Pricing List + Terms", "Personal Bio + Education + Hobbies", "Invoice + Receipts + Bank Details"],
                    'correct' => "Problem + Solution + Measurable Result",
                    'explanation' => "The Problem-Solution-Result framework demonstrates how your technical skills create business value."
                ],
                [
                    'question' => "What element should always be included at the bottom of a portfolio showcase website?",
                    'options' => ["A clear Call to Action and contact form to schedule an inquiry call", "A list of personal social media passwords", "Unformatted text downloads", "Competitor advertising banners"],
                    'correct' => "A clear Call to Action and contact form to schedule an inquiry call",
                    'explanation' => "A prominent call to action makes it frictionless for impressed prospects to contact you immediately."
                ]
            ]
        ],

        // Level 7: Opportunity Hunting
        [
            'course_id' => $courseIds[7], 'level' => 7, 'title' => 'Navigating Job Boards & Avoiding Scams', 'slug' => 'job-boards-scam-detection', 'summary' => 'Identifying verified client opportunities and avoiding common freelancing scams.', 'xp' => 140, 'coins' => 45,
            'content' => "<h2>Executive Masterclass: Navigating Job Boards & Avoiding Scams</h2><p>Job board literacy allows you to spot high-paying, legitimate client opportunities while steering clear of fraudulent scams.</p><h3>1. Key Flags of Verified Legitimate Job Posts</h3><p>Look for clients with verified payment methods, positive past freelancer reviews, detailed job descriptions, realistic hourly budgets, and clear milestone deliverables.</p><h3>2. Scam Detection & Red Flags</h3><p>Never pay upfront application or check-clearing fees. Avoid clients who request off-platform chat transfers (Telegram/WhatsApp) before an official contract is established or ask to send check deposits for equipment purchases.</p>",
            'quiz' => [
                [
                    'question' => "Which scenario represents a common freelancing scam red flag?",
                    'options' => ["A client asking you to deposit a check and transfer funds off-platform before starting work", "A client with a verified payment method scheduling an Upwork interview", "A client requesting a signed service contract and NDA", "A client reviewing your public portfolio link"],
                    'correct' => "A client asking you to deposit a check and transfer funds off-platform before starting work",
                    'explanation' => "Fake check deposits and off-platform money transfers before official hiring are classic fraudulent scams."
                ],
                [
                    'question' => "What job posting indicator signals a trustworthy client opportunity?",
                    'options' => ["Verified payment method and positive ratings from past freelancers", "Vague single-sentence descriptions with zero detail", "Anonymously posted offers asking for financial details", "Guarantees of $10,000/week for 1 hour of work"],
                    'correct' => "Verified payment method and positive ratings from past freelancers",
                    'explanation' => "Verified payment status and historical review ratings confirm the client pays talent reliably."
                ],
                [
                    'question' => "Why should freelancers insist on communicating and contracting through established platform mechanisms during initial hiring?",
                    'options' => ["To maintain payment escrow protection and built-in dispute resolution", "Because off-platform work is illegal in all countries", "To prevent clients from seeing your email address", "Because job platforms charge no fees"],
                    'correct' => "To maintain payment escrow protection and built-in dispute resolution",
                    'explanation' => "Keeping initial contracts on-platform protects your earnings through escrow rules and support mediation."
                ]
            ]
        ],

        // Level 8: Proposal Writing
        [
            'course_id' => $courseIds[8], 'level' => 8, 'title' => 'High-Converting Pitch & Cover Letter Writing', 'slug' => 'cover-letter-pitch-mastery', 'summary' => 'Structuring proposals that address client pain points and win interviews.', 'xp' => 150, 'coins' => 50,
            'content' => "<h2>Executive Masterclass: High-Converting Pitch & Cover Letter Writing</h2><p>Winning client proposals do not focus on your bio; they focus directly on solving the client's explicit pain points described in their job post.</p><h3>1. The 4-Part High-Converting Proposal Structure</h3><p>1. Personalized Greeting & Immediate Pain-Point Hook.<br>2. Clear Solution Blueprint & Strategy.<br>3. Relevant Work Sample / Proof Link.<br>4. Low-Friction Call to Action (CTA) inviting a brief conversation.</p><h3>2. Avoiding Generic Boilerplate Pitches</h3><p>Never copy-paste generic pitch templates. Customize the first two lines to reference the client's industry or exact problem to instantly differentiate yourself from automated AI applicants.</p>",
            'quiz' => [
                [
                    'question' => "What should the opening sentence of a high-converting client proposal focus on?",
                    'options' => ["Acknowledging the client's specific problem or goal mentioned in their job post", "Listing your entire personal life story and background", "Demanding an hourly rate increase immediately", "Apologizing for applying late"],
                    'correct' => "Acknowledging the client's specific problem or goal mentioned in their job post",
                    'explanation' => "Starting with the client's specific problem proves you read their posting and understand their business needs."
                ],
                [
                    'question' => "Why do generic copy-paste proposal templates fail on competitive job boards?",
                    'options' => ["Clients easily spot generic text and reject proposals that show zero effort or personalization", "Marketplaces block template submissions automatically", "Clients prefer reading 10-page essays instead", "Generic proposals take longer to submit"],
                    'correct' => "Clients easily spot generic text and reject proposals that show zero effort or personalization",
                    'explanation' => "Personalization shows genuine interest and professional attention to detail that clients value."
                ],
                [
                    'question' => "What is a low-friction Call to Action (CTA) for closing a client proposal?",
                    'options' => ["'Are you available for a quick 10-minute chat this week to discuss your timeline?'", "'Hire me right now or I will work for your direct competitor!'", "'Send $500 upfront before I answer any questions.'", "'Please read my 20 attached PDF documents.'"],
                    'correct' => "'Are you available for a quick 10-minute chat this week to discuss your timeline?'",
                    'explanation' => "A brief, polite call-to-action makes it easy for the client to reply and initiate a conversation."
                ]
            ]
        ],

        // Level 9: Client Interviews
        [
            'course_id' => $courseIds[9], 'level' => 9, 'title' => 'Client Interview Prep & STAR Framework', 'slug' => 'client-interview-star-framework', 'summary' => 'Answering situational interview questions with confidence and clarity.', 'xp' => 150, 'coins' => 50,
            'content' => "<h2>Executive Masterclass: Client Interview Prep & STAR Framework</h2><p>Client interviews test your confidence, communication clarity, and situational problem-solving skills under real-time conditions.</p><h3>1. The STAR Interview Framework</h3><p>Structure answers to behavioral interview questions using STAR:<br>- Situation: Describe the background context.<br>- Task: Define the explicit challenge or goal.<br>- Action: Detail the specific steps and tools you executed.<br>- Result: Share the quantifiable outcome or metric achieved.</p><h3>2. Handling Tricky Situational Questions</h3><p>When asked 'What happens if you make a mistake?', respond with accountability: acknowledge the error immediately, present a solution, and explain how you updated the SOP to ensure it never happens again.</p>",
            'quiz' => [
                [
                    'question' => "What do the letters in the STAR interview response method stand for?",
                    'options' => ["Situation, Task, Action, Result", "Speed, Time, Accuracy, Reliability", "Sales, Strategy, Technical, Revenue", "Start, Test, Analyze, Repeat"],
                    'correct' => "Situation, Task, Action, Result",
                    'explanation' => "STAR ensures structured, persuasive, and story-driven answers during behavioral interviews."
                ],
                [
                    'question' => "How should a Virtual Assistant answer an interview question about a past mistake?",
                    'options' => ["Own the mistake immediately, explain the fix, and share the updated SOP created to prevent recurrence", "Blame software bugs or former coworkers for the issue", "Deny ever making mistakes in any professional job", "Refuse to answer negative questions"],
                    'correct' => "Own the mistake immediately, explain the fix, and share the updated SOP created to prevent recurrence",
                    'explanation' => "Taking ownership combined with preventative SOP improvements demonstrates extreme professional maturity."
                ],
                [
                    'question' => "What step should a freelancer take prior to joining a live client interview call?",
                    'options' => ["Research the client company, review their job post requirements, and prepare 2 relevant questions", "Arrive 20 minutes late without notification", "Ask for contract payment before starting the call", "Mute the microphone and turn off video permanently"],
                    'correct' => "Research the client company, review their job post requirements, and prepare 2 relevant questions",
                    'explanation' => "Thorough client research and thoughtful questions demonstrate professionalism and initiative."
                ]
            ]
        ],

        // Level 10: Direct Cold Outreach
        [
            'course_id' => $courseIds[10], 'level' => 10, 'title' => 'High Ticket Cold Outreach & Pitching', 'slug' => 'cold-outreach-pitching', 'summary' => 'Finding decision makers, personalized Loom videos, cold email templates.', 'xp' => 150, 'coins' => 50,
            'content' => "<h2>Executive Masterclass: High Ticket Cold Outreach & Pitching</h2><p>Direct cold outreach allows you to pitch CEOs and founders directly, bypassing public marketplace price competition.</p><h3>1. Auditing Target Accounts for Pain Points</h3><p>Before reaching out, audit the target company's website or social media channels. Identify specific operational gaps (e.g., slow response time, missing blog content, outdated graphics) that you can solve.</p><h3>2. The Personalized Loom Audit Method</h3><p>Record a 90-second Loom video greeting the decision-maker, showing their website, pointing out one specific improvement opportunity, and providing a free actionable tip before offering a quick call.</p>",
            'quiz' => [
                [
                    'question' => "Why is a 90-second personalized Loom audit video highly effective in cold outreach?",
                    'options' => ["It provides instant value and proves you did real research on the client business", "It takes 3 hours for the client to watch", "It forces the client to pay video viewing fees", "It replaces the need for a service agreement"],
                    'correct' => "It provides instant value and proves you did real research on the client business",
                    'explanation' => "Loom audits showcase your expertise and effort, breaking through noisy email inboxes."
                ],
                [
                    'question' => "What is the recommended approach when finding target contact leads for cold email outreach?",
                    'options' => ["Targeting specific decision-makers (Founders, CEOs, Marketing Directors) directly", "Sending mass blast emails to info@ or support@ generic inboxes", "Buying unverified random email lists of 100,000 consumers", "Posting comments on unrelated forum threads"],
                    'correct' => "Targeting specific decision-makers (Founders, CEOs, Marketing Directors) directly",
                    'explanation' => "Direct decision-maker targeting ensures your pitch reaches the person with budget authority."
                ],
                [
                    'question' => "How many follow-up messages should be included in a professional cold email sequence?",
                    'options' => ["2 to 4 polite, value-adding follow-up emails spaced over 2 weeks", "Sending 10 messages per day until they reply", "Never follow up after sending one initial email", "Sending angry complaints if they do not reply"],
                    'correct' => "2 to 4 polite, value-adding follow-up emails spaced over 2 weeks",
                    'explanation' => "Polite, value-adding follow-ups recover up to 50% of outreach responses from busy decision-makers."
                ]
            ]
        ],

        // Level 11: Rate Negotiation
        [
            'course_id' => $courseIds[11], 'level' => 11, 'title' => 'Rate Negotiation & Service Agreements', 'slug' => 'rate-negotiation-agreements', 'summary' => 'Hourly vs retainer pricing, scope protection, and contract execution.', 'xp' => 160, 'coins' => 55,
            'content' => "<h2>Executive Masterclass: Rate Negotiation & Service Agreements</h2><p>Securing sustainable freelance revenue requires shifting from hourly billing to monthly value retainers while protecting project scope.</p><h3>1. Hourly vs Monthly Retainer Pricing</h3><p>Hourly pricing caps your earnings based on time spent. Monthly retainers guarantee fixed monthly income (e.g., $1,000/month for 40 hours) while delivering predictable operational stability to clients.</p><h3>2. Scope Creep Protection & Legal Agreements</h3><p>Always execute a written Service Agreement before starting work. Explicitly define what deliverables are included, revision limits, payment due dates, and hourly billing rates for out-of-scope requests.</p>",
            'quiz' => [
                [
                    'question' => "What is 'Scope Creep' in freelance project management?",
                    'options' => ["When a client gradually adds extra tasks beyond the original agreed contract without increasing pay", "When a freelancer finishes work ahead of schedule", "When software tools update automatically", "When project invoices are paid early"],
                    'correct' => "When a client gradually adds extra tasks beyond the original agreed contract without increasing pay",
                    'explanation' => "Scope creep occurs when unpriced tasks are continuously added without updating the contract rate."
                ],
                [
                    'question' => "What is the primary income benefit of converting clients to monthly retainer contracts?",
                    'options' => ["Predictable recurring revenue and stability every month", "Exemption from doing client work", "Working without setting project deadlines", "Double charging clients for expense receipts"],
                    'correct' => "Predictable recurring revenue and stability every month",
                    'explanation' => "Monthly retainers provide predictable baseline revenue, removing income swings between project contracts."
                ],
                [
                    'question' => "How should a Virtual Assistant respond when a retainer client requests a major new project outside the original contract scope?",
                    'options' => ["Politely welcome the project and provide an addendum quote or hourly rate for extra deliverables", "Refuse the request rudely and terminate the contract", "Do the extra work for free silently", "Delete the client shared Google Drive files"],
                    'correct' => "Politely welcome the project and provide an addendum quote or hourly rate for extra deliverables",
                    'explanation' => "Professional freelancers welcome expanding work while establishing clear addendum pricing for extra deliverables."
                ]
            ]
        ],

        // Level 12: Virtual Project Management
        [
            'course_id' => $courseIds[12], 'level' => 12, 'title' => 'Virtual Project Management & Client Operations', 'slug' => 'virtual-project-management-sop', 'summary' => 'EOD updates, Slack etiquette, deadline tracking, and task prioritization.', 'xp' => 170, 'coins' => 60,
            'content' => "<h2>Executive Masterclass: Virtual Project Management & Client Operations</h2><p>Exceptional project execution relies on structured daily communication, task board management, and proactive status updates.</p><h3>1. Asynchronous Daily End-of-Day (EOD) Updates</h3><p>Send a structured EOD report via Slack or email every workday:<br>- Done Today: Bullet list of completed deliverables with live proof links.<br>- Next Up: Planned high-priority tasks scheduled for tomorrow.<br>- Blockers: Clarifying questions or credentials required from the client.</p><h3>2. PM Software Organization (Asana / ClickUp / Trello)</h3><p>Maintain clean task cards containing clear title names, priority tags, due dates, assigned owners, and linked file attachments. Never leave tasks unassigned or missing deadline dates.</p>",
            'quiz' => [
                [
                    'question' => "What are the 3 core sections required in a professional End-of-Day (EOD) status update?",
                    'options' => ["Done Today, Next Up, and Blockers/Questions", "Personal Hours, Total Invoices, and Complaints", "Password Lists, Banking Info, and Personal Notes", "Meeting Agendas, Vacation Dates, and Weather Reports"],
                    'correct' => "Done Today, Next Up, and Blockers/Questions",
                    'explanation' => "EOD reports keep clients informed asynchronously without requiring constant micromanagement calls."
                ],
                [
                    'question' => "Why should every task card in Asana or ClickUp have an assigned owner and due date?",
                    'options' => ["To ensure clear operational accountability and deadline tracking", "To automatically send promotional emails", "To increase task board software fees", "Because project management apps delete unassigned tasks"],
                    'correct' => "To ensure clear operational accountability and deadline tracking",
                    'explanation' => "Explicit assignment and due dates prevent missed tasks and ensure operational accountability."
                ],
                [
                    'question' => "How should a VA handle an urgent priority shift communicated by a client on Slack?",
                    'options' => ["Acknowledge receipt immediately, confirm the new priority order, and update task due dates", "Ignore the Slack message until the end of the week", "Argue with the client about priority changes", "Delete the existing task board"],
                    'correct' => "Acknowledge receipt immediately, confirm the new priority order, and update task due dates",
                    'explanation' => "Fast receipt confirmation paired with immediate board adjustments keeps client operations aligned."
                ]
            ]
        ],

        // Level 13: Client Retention & Retainers
        [
            'course_id' => $courseIds[13], 'level' => 13, 'title' => 'Client Retention & Monthly Retainers', 'slug' => 'client-retention-retainers', 'summary' => 'Turning one-time projects into predictable $1,000+/mo recurring revenue.', 'xp' => 180, 'coins' => 70,
            'content' => "<h2>Executive Masterclass: Client Retention & Monthly Retainers</h2><p>Client retention is the single highest leverage lever for freelance growth. Keeping existing clients produces higher margins than constantly hunting new ones.</p><h3>1. The Monthly ROI Review Call</h3><p>Schedule a 20-minute monthly ROI review with retainer clients. Present a summary report highlighting total hours saved, tasks completed, metrics improved, and proposed goals for the upcoming month.</p><h3>2. Upselling Additional High-Value Services</h3><p>As you master administrative workflows, identify complementary needs (e.g., social media repurposing, email list management, CRM cleanup) and propose monthly add-on packages to expand account value.</p>",
            'quiz' => [
                [
                    'question' => "What is the primary objective of holding a monthly ROI review call with a retainer client?",
                    'options' => ["Demonstrating tangible value delivered and aligning upcoming strategic goals", "Demanding an immediate rate increase without justification", "Complaining about daily work tasks", "Reviewing personal travel plans"],
                    'correct' => "Demonstrating tangible value delivered and aligning upcoming strategic goals",
                    'explanation' => "Monthly ROI reviews reinforce the measurable value you deliver, securing long-term contract retention."
                ],
                [
                    'question' => "Why is retaining existing clients more profitable than constantly acquiring new ones?",
                    'options' => ["Retention eliminates sales acquisition costs, onboarding time, and unpaid proposal writing", "Existing clients do not require invoices", "Retainer contracts are exempt from taxes", "New clients always pay lower rates"],
                    'correct' => "Retention eliminates sales acquisition costs, onboarding time, and unpaid proposal writing",
                    'explanation' => "Serving happy existing clients generates stable recurring revenue without continuous sales overhead."
                ],
                [
                    'question' => "How can a Virtual Assistant ethically upsell an existing administrative client?",
                    'options' => ["Identify a recurring operational bottleneck in their business and propose a structured monthly add-on package", "Increase monthly invoices without informing the client", "Refuse to do administrative work unless they buy new tools", "Send unrequested invoices for unperformed services"],
                    'correct' => "Identify a recurring operational bottleneck in their business and propose a structured monthly add-on package",
                    'explanation' => "Proposing valuable solutions to visible business bottlenecks expands contract scope organically."
                ]
            ]
        ],

        // Level 14: Agency
        [
            'course_id' => $courseIds[14], 'level' => 14, 'title' => 'Building & Scaling Your Virtual VA Agency', 'slug' => 'building-virtual-va-agency', 'summary' => 'Subcontracting, SOP systems, pricing packages, profit margins, team leadership.', 'xp' => 250, 'coins' => 100,
            'content' => "<h2>Executive Masterclass: Building & Scaling Your Virtual VA Agency</h2><p>Scaling beyond individual capacity requires evolving from a solo freelancer into an agency founder who leads a team of virtual assistants.</p><h3>1. Systematization & Standard Operating Procedures</h3><p>Document step-by-step SOPs and Loom video training for every recurring service you offer. Standardized SOPs allow team members to execute client deliverables with identical quality control.</p><h3>2. Subcontracting & Margin Architecture</h3><p>Sell monthly agency service packages to clients at premium rates (e.g., $3,000/mo) and delegate operational execution to qualified subcontractors at target margin rates (30% to 50% gross margin).</p>",
            'quiz' => [
                [
                    'question' => "What is the primary role of Standard Operating Procedures (SOPs) when scaling a Virtual Assistant agency?",
                    'options' => ["Enabling sub-contractors to execute client deliverables with consistent quality without constant supervision", "Increasing monthly software subscription costs", "Preventing subcontractors from communicating with each other", "Filing tax exemption forms"],
                    'correct' => "Enabling sub-contractors to execute client deliverables with consistent quality without constant supervision",
                    'explanation' => "Detailed SOPs allow team members to deliver identical high-quality client results consistently."
                ],
                [
                    'question' => "If an agency sells a client package for $2,000/month and pays a subcontractor $1,200/month to execute, what is the gross profit margin?",
                    'options' => ["$800/month (40% gross margin)", "$2,000/month (100% margin)", "$200/month (10% margin)", "$0 profit"],
                    'correct' => "$800/month (40% gross margin)",
                    'explanation' => "$2,000 revenue minus $1,200 subcontractor cost leaves $800 gross profit (40% margin) to cover operations and agency growth."
                ],
                [
                    'question' => "What transition must a solo freelancer make to successfully become an agency owner?",
                    'options' => ["Shifting from client task execution to business systems design, client acquisition, and team leadership", "Doing all client work personally without telling anyone", "Lowering service prices to compete with low-cost labor", "Stopping all sales outreach permanently"],
                    'correct' => "Shifting from client task execution to business systems design, client acquisition, and team leadership",
                    'explanation' => "Agency owners focus on strategy, sales systems, and managing teams rather than trading personal hours for tasks."
                ]
            ]
        ],

        // Level 15: Apex Master
        [
            'course_id' => $courseIds[15], 'level' => 15, 'title' => 'Apex Freelance Mastery & Business Automation', 'slug' => 'apex-freelance-mastery', 'summary' => 'Systemizing client acquisition, delegation, financial management, and sustainable growth.', 'xp' => 300, 'coins' => 150,
            'content' => "<h2>Executive Masterclass: Apex Freelance Mastery & Business Automation</h2><p>Reaching Apex Mastery represents the pinnacle of remote career progression: automated client acquisition, high-retainer accounts, and operational self-sustainability.</p><h3>1. AI-Driven Operational Automation</h3><p>Integrate AI tools (ChatGPT, Claude, Zapier, Make.com) to automate lead enrichments, content drafts, client reporting, and task syncing across software platforms.</p><h3>2. Building a Sustainable 6-Figure Remote Career</h3><p>Maintain financial stability through recurring high-ticket retainers, ongoing client referral engines, and disciplined financial allocation across business investments and emergency reserves.</p>",
            'quiz' => [
                [
                    'question' => "How do modern automation tools like Zapier or Make.com enhance high-level VA agency workflows?",
                    'options' => ["Connecting separate web apps to automate repetitive data syncing and notification triggers without code", "Replacing human client relationships entirely", "Generating fake client reviews automatically", "Preventing emails from being delivered"],
                    'correct' => "Connecting separate web apps to automate repetitive data syncing and notification triggers without code",
                    'explanation' => "Automation platforms trigger seamless background workflows across web applications, saving hours of manual data entry."
                ],
                [
                    'question' => "What defines an Apex Master remote freelancer?",
                    'options' => ["Achieving sustainable revenue through automated client pipelines, strategic retainers, and business delegation", "Working 100 hours weekly without taking breaks", "Relying on a single unstable hourly job posting", "Refusing to adopt modern software tools"],
                    'correct' => "Achieving sustainable revenue through automated client pipelines, strategic retainers, and business delegation",
                    'explanation' => "Apex Masters combine strong business systems, client retention strategies, and delegation to scale income sustainably."
                ],
                [
                    'question' => "What is the most effective organic channel for securing high-value long-term client referrals?",
                    'options' => ["Delivering consistently flawless operational results that make existing clients enthusiastically recommend you", "Sending spam messages on social media daily", "Offering 90% discounts on all service packages", "Posting negative reviews about competing agencies"],
                    'correct' => "Delivering consistently flawless operational results that make existing clients enthusiastically recommend you",
                    'explanation' => "Exceptional service quality turns satisfied clients into active promoters who refer high-value peer accounts."
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
        ['level_number' => 1, 'title' => 'Virtual Assistant Starter Playbook', 'description' => 'Complete beginner guide to setting up your VA career and service menu.', 'type' => 'pdf', 'file_content_or_url' => '/docs/PLATFORM_MANUAL.md', 'is_premium' => false],
        ['level_number' => 2, 'title' => 'Google Workspace Keyboard Shortcuts Cheat Sheet', 'description' => 'Boost your typing speed and cloud efficiency instantly.', 'type' => 'cheat_sheet', 'file_content_or_url' => '/docs/TUTORIALS_AND_GUIDES.md', 'is_premium' => false],
        ['level_number' => 3, 'title' => 'Client Onboarding SOP Checklist', 'description' => 'Professional checklist for onboarding new client projects without friction.', 'type' => 'template', 'file_content_or_url' => '/docs/SOPS_AND_CHECKLISTS.md', 'is_premium' => false],
        ['level_number' => 4, 'title' => 'Canva & Social Media Content Calendar Template', 'description' => 'Monthly social content planning grid for Social Media VAs.', 'type' => 'template', 'file_content_or_url' => '/docs/TUTORIALS_AND_GUIDES.md', 'is_premium' => false],
        ['level_number' => 5, 'title' => 'High-Converting ATS VA Resume Template', 'description' => 'ATS-friendly resume layout designed specifically for remote VAs.', 'type' => 'template', 'file_content_or_url' => '/resume-builder/print', 'is_premium' => true],
        ['level_number' => 6, 'title' => 'Portfolio Case Study Builder Framework', 'description' => 'Structured template for writing Problem-Solution-Result case studies.', 'type' => 'template', 'file_content_or_url' => '/docs/TUTORIALS_AND_GUIDES.md', 'is_premium' => true],
        ['level_number' => 7, 'title' => 'Scam Detection & Client Vetting Checklist', 'description' => 'Red flag screening checklist for evaluating job offers and client verification.', 'type' => 'checklist', 'file_content_or_url' => '/docs/SOPS_AND_CHECKLISTS.md', 'is_premium' => false],
        ['level_number' => 8, 'title' => '10 Winning Proposal & Pitch Scripts', 'description' => 'Proven proposal templates that earned over $100k in freelancing.', 'type' => 'script', 'file_content_or_url' => '/docs/TUTORIALS_AND_GUIDES.md', 'is_premium' => true],
        ['level_number' => 9, 'title' => 'STAR Interview Response Preparation Sheet', 'description' => 'Behavioral interview question prep worksheet using the STAR framework.', 'type' => 'worksheet', 'file_content_or_url' => '/docs/TUTORIALS_AND_GUIDES.md', 'is_premium' => true],
        ['level_number' => 10, 'title' => 'High Ticket Loom Cold Audit Script', 'description' => '90-second video audit framework for pitching founders directly.', 'type' => 'script', 'file_content_or_url' => '/docs/TUTORIALS_AND_GUIDES.md', 'is_premium' => true],
        ['level_number' => 11, 'title' => 'Client Service Agreement & Contract Template', 'description' => 'Standard freelance agreement covering payment terms and scope limits.', 'type' => 'template', 'file_content_or_url' => '/docs/SOPS_AND_CHECKLISTS.md', 'is_premium' => true],
        ['level_number' => 12, 'title' => 'Hourly Rate & Retainer Calculator Worksheet', 'description' => 'Calculate your exact hourly rates and monthly retainer packages.', 'type' => 'calculator', 'file_content_or_url' => '/docs/TUTORIALS_AND_GUIDES.md', 'is_premium' => true],
        ['level_number' => 13, 'title' => 'Monthly Client ROI Review Presentation Deck', 'description' => 'Slide deck framework for presenting monthly ROI to retainer accounts.', 'type' => 'template', 'file_content_or_url' => '/docs/TUTORIALS_AND_GUIDES.md', 'is_premium' => true],
        ['level_number' => 14, 'title' => 'Virtual Agency SOP Operations Manual', 'description' => 'Standard Operating Procedures for hiring subcontractors and managing agency workflows.', 'type' => 'manual', 'file_content_or_url' => '/docs/SOPS_AND_CHECKLISTS.md', 'is_premium' => true],
        ['level_number' => 15, 'title' => '6-Figure Business Automation Playbook', 'description' => 'Advanced Zapier, Make, and AI workflow integration manual.', 'type' => 'manual', 'file_content_or_url' => '/docs/SOPS_AND_CHECKLISTS.md', 'is_premium' => true],
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
