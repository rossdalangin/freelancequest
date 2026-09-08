<?php

require_once __DIR__ . '/../config/database.php';

function initializeSchema()
{
    $pdo = Database::getConnection();

    $queries = [
        "DROP TABLE IF EXISTS audit_logs;",
        "DROP TABLE IF EXISTS settings;",
        "DROP TABLE IF EXISTS subscriptions;",
        "DROP TABLE IF EXISTS comments;",
        "DROP TABLE IF EXISTS community_posts;",
        "DROP TABLE IF EXISTS groups;",
        "DROP TABLE IF EXISTS portfolio_items;",
        "DROP TABLE IF EXISTS portfolios;",
        "DROP TABLE IF EXISTS resumes;",
        "DROP TABLE IF EXISTS resources;",
        "DROP TABLE IF EXISTS certificates;",
        "DROP TABLE IF EXISTS user_badges;",
        "DROP TABLE IF EXISTS badges;",
        "DROP TABLE IF EXISTS daily_quests;",
        "DROP TABLE IF EXISTS mission_attempts;",
        "DROP TABLE IF EXISTS missions;",
        "DROP TABLE IF EXISTS quiz_attempts;",
        "DROP TABLE IF EXISTS questions;",
        "DROP TABLE IF EXISTS quizzes;",
        "DROP TABLE IF EXISTS lesson_progress;",
        "DROP TABLE IF EXISTS lessons;",
        "DROP TABLE IF EXISTS courses;",
        "DROP TABLE IF EXISTS user_skills;",
        "DROP TABLE IF EXISTS skills;",
        "DROP TABLE IF EXISTS skill_categories;",
        "DROP TABLE IF EXISTS levels;",
        "DROP TABLE IF EXISTS users;",

        "CREATE TABLE users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            email TEXT UNIQUE NOT NULL,
            password TEXT NOT NULL,
            role TEXT DEFAULT 'student',
            username TEXT UNIQUE,
            avatar_url TEXT,
            headline TEXT,
            bio TEXT,
            level INTEGER DEFAULT 0,
            xp INTEGER DEFAULT 0,
            coins INTEGER DEFAULT 100,
            streak_count INTEGER DEFAULT 0,
            last_activity_date TEXT,
            subscription_tier TEXT DEFAULT 'free',
            subscription_ends_at TEXT,
            onboarding_answers TEXT,
            created_at TEXT DEFAULT CURRENT_TIMESTAMP
        );",

        "CREATE TABLE levels (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            level_number INTEGER UNIQUE NOT NULL,
            title TEXT NOT NULL,
            subtitle TEXT,
            description TEXT,
            required_xp INTEGER NOT NULL,
            certificate_title TEXT,
            icon TEXT
        );",

        "CREATE TABLE skill_categories (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            slug TEXT UNIQUE NOT NULL,
            description TEXT,
            color TEXT DEFAULT '#3B82F6'
        );",

        "CREATE TABLE skills (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            skill_category_id INTEGER REFERENCES skill_categories(id) ON DELETE CASCADE,
            name TEXT NOT NULL,
            slug TEXT UNIQUE NOT NULL,
            description TEXT,
            prerequisite_skill_id INTEGER REFERENCES skills(id) ON DELETE SET NULL,
            icon TEXT
        );",

        "CREATE TABLE user_skills (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
            skill_id INTEGER REFERENCES skills(id) ON DELETE CASCADE,
            proficiency INTEGER DEFAULT 0,
            UNIQUE(user_id, skill_id)
        );",

        "CREATE TABLE courses (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL,
            slug TEXT UNIQUE NOT NULL,
            description TEXT,
            level_number INTEGER DEFAULT 1,
            category TEXT
        );",

        "CREATE TABLE lessons (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            course_id INTEGER REFERENCES courses(id) ON DELETE CASCADE,
            level_number INTEGER DEFAULT 1,
            title TEXT NOT NULL,
            slug TEXT UNIQUE NOT NULL,
            summary TEXT,
            content TEXT NOT NULL,
            video_url TEXT,
            xp_reward INTEGER DEFAULT 50,
            coin_reward INTEGER DEFAULT 10,
            sort_order INTEGER DEFAULT 0,
            skill_id INTEGER REFERENCES skills(id) ON DELETE SET NULL
        );",

        "CREATE TABLE lesson_progress (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
            lesson_id INTEGER REFERENCES lessons(id) ON DELETE CASCADE,
            completed INTEGER DEFAULT 0,
            completed_at TEXT,
            UNIQUE(user_id, lesson_id)
        );",

        "CREATE TABLE quizzes (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            lesson_id INTEGER REFERENCES lessons(id) ON DELETE CASCADE,
            title TEXT NOT NULL,
            passing_score INTEGER DEFAULT 70,
            xp_reward INTEGER DEFAULT 100
        );",

        "CREATE TABLE questions (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            quiz_id INTEGER REFERENCES quizzes(id) ON DELETE CASCADE,
            question_text TEXT NOT NULL,
            options TEXT NOT NULL,
            correct_option TEXT NOT NULL,
            explanation TEXT
        );",

        "CREATE TABLE quiz_attempts (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
            quiz_id INTEGER REFERENCES quizzes(id) ON DELETE CASCADE,
            score INTEGER NOT NULL,
            passed INTEGER NOT NULL,
            created_at TEXT DEFAULT CURRENT_TIMESTAMP
        );",

        "CREATE TABLE missions (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            level_number INTEGER DEFAULT 1,
            title TEXT NOT NULL,
            type TEXT DEFAULT 'interactive',
            scenario TEXT NOT NULL,
            instructions TEXT NOT NULL,
            data TEXT,
            xp_reward INTEGER DEFAULT 250,
            coin_reward INTEGER DEFAULT 50
        );",

        "CREATE TABLE mission_attempts (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
            mission_id INTEGER REFERENCES missions(id) ON DELETE CASCADE,
            completed INTEGER DEFAULT 0,
            score INTEGER DEFAULT 0,
            submission_data TEXT,
            feedback TEXT,
            created_at TEXT DEFAULT CURRENT_TIMESTAMP
        );",

        "CREATE TABLE daily_quests (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
            title TEXT NOT NULL,
            quest_type TEXT NOT NULL,
            xp_reward INTEGER DEFAULT 100,
            coin_reward INTEGER DEFAULT 20,
            completed INTEGER DEFAULT 0,
            quest_date TEXT NOT NULL
        );",

        "CREATE TABLE badges (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            slug TEXT UNIQUE NOT NULL,
            description TEXT NOT NULL,
            icon TEXT DEFAULT '🏆',
            category TEXT DEFAULT 'achievement',
            xp_bonus INTEGER DEFAULT 100
        );",

        "CREATE TABLE user_badges (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
            badge_id INTEGER REFERENCES badges(id) ON DELETE CASCADE,
            awarded_at TEXT DEFAULT CURRENT_TIMESTAMP,
            UNIQUE(user_id, badge_id)
        );",

        "CREATE TABLE certificates (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
            certificate_code TEXT UNIQUE NOT NULL,
            title TEXT NOT NULL,
            level_number INTEGER NOT NULL,
            skills_breakdown TEXT,
            issued_at TEXT DEFAULT CURRENT_TIMESTAMP
        );",

        "CREATE TABLE resources (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            level_number INTEGER DEFAULT 1,
            title TEXT NOT NULL,
            description TEXT,
            type TEXT DEFAULT 'pdf',
            file_content_or_url TEXT NOT NULL,
            is_premium INTEGER DEFAULT 0
        );",

        "CREATE TABLE resumes (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
            title TEXT DEFAULT 'My Professional Resume',
            full_name TEXT,
            professional_title TEXT,
            email TEXT,
            phone TEXT,
            location TEXT,
            summary TEXT,
            skills TEXT,
            experience TEXT,
            education TEXT,
            certifications TEXT,
            tools TEXT
        );",

        "CREATE TABLE portfolios (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
            slug TEXT UNIQUE NOT NULL,
            title TEXT NOT NULL,
            tagline TEXT,
            about TEXT,
            services TEXT,
            contact_info TEXT,
            is_published INTEGER DEFAULT 1
        );",

        "CREATE TABLE portfolio_items (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            portfolio_id INTEGER REFERENCES portfolios(id) ON DELETE CASCADE,
            title TEXT NOT NULL,
            description TEXT,
            category TEXT,
            image_url TEXT,
            sample_url TEXT
        );",

        "CREATE TABLE groups (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            slug TEXT UNIQUE NOT NULL,
            description TEXT,
            icon TEXT
        );",

        "CREATE TABLE community_posts (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
            group_id INTEGER REFERENCES groups(id) ON DELETE SET NULL,
            title TEXT NOT NULL,
            content TEXT NOT NULL,
            upvotes INTEGER DEFAULT 0,
            created_at TEXT DEFAULT CURRENT_TIMESTAMP
        );",

        "CREATE TABLE comments (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            community_post_id INTEGER REFERENCES community_posts(id) ON DELETE CASCADE,
            user_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
            content TEXT NOT NULL,
            created_at TEXT DEFAULT CURRENT_TIMESTAMP
        );",

        "CREATE TABLE subscriptions (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
            plan_name TEXT NOT NULL,
            status TEXT DEFAULT 'active',
            price REAL DEFAULT 0.00,
            starts_at TEXT NOT NULL,
            ends_at TEXT
        );",

        "CREATE TABLE settings (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            key_name TEXT UNIQUE NOT NULL,
            value_text TEXT,
            updated_at TEXT DEFAULT CURRENT_TIMESTAMP
        );",

        "CREATE TABLE audit_logs (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER REFERENCES users(id) ON DELETE SET NULL,
            action TEXT NOT NULL,
            details TEXT,
            ip_address TEXT,
            created_at TEXT DEFAULT CURRENT_TIMESTAMP
        );"
    ];

    foreach ($queries as $sql) {
        $pdo->exec($sql);
    }

    echo "Schema initialized successfully.\n";
}

if (basename(__FILE__) == basename($_SERVER['PHP_SELF'])) {
    initializeSchema();
}
