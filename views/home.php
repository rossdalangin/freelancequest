<?php require __DIR__ . '/layout/header.php'; ?>
<div class="space-y-24 py-6">

    <!-- HIGH-CONVERTING HERO SECTION -->
    <section class="relative overflow-hidden bg-slate-900/80 border border-slate-800/90 rounded-3xl p-8 sm:p-14 text-center space-y-8 shadow-2xl backdrop-blur-md">
        <div class="inline-flex items-center gap-2 bg-gradient-to-r from-amber-500/10 to-indigo-500/10 border border-amber-500/30 text-amber-400 font-extrabold text-xs px-4 py-2 rounded-full uppercase tracking-widest shadow-inner">
            <span>🚀 YOUR CAREER STARTS AS A GAME</span>
        </div>

        <h1 class="text-4xl sm:text-6xl font-black text-white tracking-tight leading-none max-w-4xl mx-auto">
            PLAY. LEARN. LEVEL UP.<br>
            <span class="bg-gradient-to-r from-amber-400 via-orange-400 to-indigo-400 bg-clip-text text-transparent">GET CLIENTS. GET PAID.</span>
        </h1>

        <p class="text-slate-200 text-base sm:text-xl max-w-2xl mx-auto font-normal leading-relaxed">
            Stop watching boring 50-hour video courses that leave you stuck. <strong class="text-white">FREELANCEQUEST</strong> lets you practice real Virtual Assistant tasks, build your resume & portfolio, pass simulated client calls, and win real online income in an exciting RPG game.
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4">
            <a href="/register" class="w-full sm:w-auto bg-gradient-to-r from-amber-500 via-yellow-500 to-orange-500 hover:from-amber-400 hover:to-orange-400 text-slate-950 font-black text-base px-8 py-4 rounded-2xl shadow-2xl shadow-amber-500/30 transition transform hover:-translate-y-0.5 border border-amber-400/50">
                START YOUR FREE CAREER QUEST &rarr;
            </a>
            <a href="/about" class="w-full sm:w-auto bg-slate-800 hover:bg-slate-700 text-slate-100 font-bold text-base px-8 py-4 rounded-2xl border border-slate-700 transition">
                READ FOUNDER STORY &rarr;
            </a>
        </div>

        <!-- STATS BADGES -->
        <div class="pt-8 border-t border-slate-800/80 grid grid-cols-2 md:grid-cols-6 gap-6 text-center">
            <div>
                <div class="text-3xl font-black text-amber-400"><?= $totalLevels ?></div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Career Levels</div>
            </div>
            <div>
                <div class="text-3xl font-black text-indigo-400"><?= $totalLessons ?></div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Mastery Lessons</div>
            </div>
            <div>
                <div class="text-3xl font-black text-emerald-400"><?= $totalMissions ?></div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Client Missions</div>
            </div>
            <div>
                <div class="text-3xl font-black text-cyan-400"><?= $totalResources ?></div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Resource Vault Files</div>
            </div>
            <div>
                <div class="text-3xl font-black text-purple-400"><?= $totalSkills ?></div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Unlockable Skills</div>
            </div>
            <div>
                <div class="text-3xl font-black text-rose-400">100%</div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Learn By Doing</div>
            </div>
        </div>
    </section>

    <!-- WHY FREELANCEQUEST WORKS (HUMANIZED & CLEAR STORYTELLING) -->
    <section class="bg-gradient-to-r from-slate-900 via-indigo-950/40 to-slate-900 border border-indigo-500/30 rounded-3xl p-8 sm:p-12 space-y-8 shadow-2xl">
        <div class="max-w-3xl mx-auto text-center space-y-3">
            <span class="text-xs text-amber-400 font-extrabold tracking-widest uppercase">WHY TRADITIONAL COURSES FAIL</span>
            <h2 class="text-3xl sm:text-4xl font-black text-white">"WHY CAN'T I GET A FREELANCE CLIENT AFTER WATCHING HOURS OF VIDEOS?"</h2>
            <p class="text-slate-300 text-sm sm:text-base leading-relaxed">
                If you have ever bought an expensive online course, watched 50 hours of lectures, and still felt terrified when trying to apply for real client jobs... <strong class="text-amber-400">you are not alone.</strong>
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center pt-4">
            <div class="space-y-4 bg-slate-950/80 p-6 rounded-2xl border border-rose-500/30">
                <h3 class="text-lg font-bold text-rose-400 flex items-center gap-2">
                    <span>❌</span> The Old Way (Passive Video Fatigue)
                </h3>
                <ul class="space-y-2.5 text-xs text-slate-300 leading-relaxed">
                    <li class="flex items-start gap-2">
                        <span class="text-rose-400">✕</span>
                        <span>Watching endless lectures without ever touching real client software or spreadsheets.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-rose-400">✕</span>
                        <span>No portfolio or work samples to prove to clients that you actually know what you are doing.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-rose-400">✕</span>
                        <span>Freezing up in fear during live client discovery calls because you never practiced objection handling.</span>
                    </li>
                </ul>
            </div>

            <div class="space-y-4 bg-slate-950/80 p-6 rounded-2xl border border-emerald-500/30">
                <h3 class="text-lg font-bold text-emerald-400 flex items-center gap-2">
                    <span>✅</span> The FREELANCEQUEST Way (Interactive Simulation)
                </h3>
                <ul class="space-y-2.5 text-xs text-slate-300 leading-relaxed">
                    <li class="flex items-start gap-2">
                        <span class="text-emerald-400">✓</span>
                        <span>Practice real client assignments: inbox filtering, calendar booking, lead generation, and social scheduling.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-emerald-400">✓</span>
                        <span>Build your ATS Resume and live public portfolio (`freelancequest.com/p/yourname`) directly in the game.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-emerald-400">✓</span>
                        <span>Practice interview calls in our AI Interview Arena until you feel 100% confident before talking to real CEOs.</span>
                    </li>
                </ul>
            </div>
        </div>
    </section>

    <!-- CORE GAME LOOP -->
    <section class="space-y-12">
        <div class="text-center space-y-3">
            <h2 class="text-3xl font-black text-white uppercase tracking-wider">THE CORE GAMEPLAY LOOP</h2>
            <p class="text-slate-400 text-sm max-w-xl mx-auto">No boring lectures. Every level turns theory into hands-on freelancing skills.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-slate-900 border border-slate-800 p-8 rounded-2xl space-y-4 hover:border-indigo-500/50 transition">
                <div class="w-14 h-14 bg-indigo-600/20 text-indigo-400 border border-indigo-500/30 rounded-2xl flex items-center justify-center text-2xl font-black">
                    1
                </div>
                <h3 class="text-xl font-bold text-white">Complete Missions & Quests</h3>
                <p class="text-slate-400 text-sm leading-relaxed">
                    Solve real client problems: organize messy inboxes, format spreadsheets, extract lead contacts, and manage calendars.
                </p>
            </div>

            <div class="bg-slate-900 border border-slate-800 p-8 rounded-2xl space-y-4 hover:border-amber-500/50 transition">
                <div class="w-14 h-14 bg-amber-600/20 text-amber-400 border border-amber-500/30 rounded-2xl flex items-center justify-center text-2xl font-black">
                    2
                </div>
                <h3 class="text-xl font-bold text-white">Earn XP, Coins & Badges</h3>
                <p class="text-slate-400 text-sm leading-relaxed">
                    Level up your skill tree, maintain daily streak bonuses, unlock verifiable certificates, and build real career proof.
                </p>
            </div>

            <div class="bg-slate-900 border border-slate-800 p-8 rounded-2xl space-y-4 hover:border-emerald-500/50 transition">
                <div class="w-14 h-14 bg-emerald-600/20 text-emerald-400 border border-emerald-500/30 rounded-2xl flex items-center justify-center text-2xl font-black">
                    3
                </div>
                <h3 class="text-xl font-bold text-white">Get Real-World Clients</h3>
                <p class="text-slate-400 text-sm leading-relaxed">
                    Use our built-in Resume Builder and Portfolio Builder to transition from in-game missions to real paid client contracts.
                </p>
            </div>
        </div>
    </section>

    <!-- CAREER MAP ROADMAP -->
    <section class="bg-slate-900/40 border border-slate-800 p-8 sm:p-12 rounded-3xl space-y-8">
        <div class="text-center space-y-3">
            <span class="text-xs text-amber-400 font-extrabold tracking-widest uppercase">COMPLETE CAREER PROGRESSION PATHWAY</span>
            <h2 class="text-3xl font-black text-white">THE 16 RPG CAREER LEVELS</h2>
            <p class="text-slate-400 text-sm max-w-2xl mx-auto">From complete beginner to building a high-earning virtual freelancing empire.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($careerLevels as $level): ?>
            <div class="bg-slate-900 border border-slate-800/90 hover:border-indigo-500/50 p-5 rounded-2xl space-y-3 transition group flex flex-col justify-between shadow-lg">
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-3xl group-hover:scale-110 transition transform"><?= $level['icon'] ?? '⚡' ?></span>
                        <span class="text-[10px] font-extrabold bg-indigo-500/20 text-indigo-400 px-2.5 py-1 rounded-full uppercase border border-indigo-500/30">
                            LEVEL <?= $level['level_number'] ?>
                        </span>
                    </div>
                    <h3 class="text-base font-extrabold text-white group-hover:text-indigo-300 transition"><?= htmlspecialchars($level['title']) ?></h3>
                    <p class="text-xs text-amber-400 font-semibold line-clamp-1"><?= htmlspecialchars($level['subtitle'] ?? '') ?></p>
                    <p class="text-xs text-slate-400 leading-relaxed line-clamp-3"><?= htmlspecialchars($level['description'] ?? '') ?></p>
                </div>
                <div class="pt-3 border-t border-slate-800/80 flex items-center justify-between text-[11px] text-slate-500 font-mono">
                    <span>Unlock: <?= $level['xp_required'] ?? ( $level['level_number'] * 1000 ) ?> XP</span>
                    <span class="text-emerald-400 font-bold"><?= htmlspecialchars($level['certificate_name'] ?? 'Certificate') ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- GAMER & FREELANCER TESTIMONIALS SECTION -->
    <?php if (!empty($showTestimonials) && !empty($testimonials)): ?>
    <section class="space-y-8">
        <div class="text-center space-y-3">
            <span class="text-xs text-amber-400 font-extrabold tracking-widest uppercase">REAL GAMER & FREELANCER REVIEWS</span>
            <h2 class="text-3xl font-black text-white">WHAT PLAYERS ARE SAYING</h2>
            <p class="text-slate-400 text-sm max-w-xl mx-auto">Discover how learners are transforming gameplay into real client contracts and income.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($testimonials as $t): ?>
            <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl space-y-4 hover:border-indigo-500/50 transition flex flex-col justify-between shadow-xl">
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="text-amber-400 text-sm font-bold">
                            <?= str_repeat('⭐', $t['rating']) ?>
                        </div>
                        <span class="text-[10px] font-extrabold bg-indigo-500/20 text-indigo-400 px-2 py-0.5 rounded uppercase">
                            LVL <?= $t['user_level'] ?? 1 ?>
                        </span>
                    </div>
                    <p class="text-xs text-slate-300 leading-relaxed italic">"<?= htmlspecialchars($t['review_text'], ENT_QUOTES, 'UTF-8') ?>"</p>
                </div>
                <div class="pt-3 border-t border-slate-800/80 flex items-center justify-between text-xs">
                    <div>
                        <h4 class="font-extrabold text-white text-xs"><?= htmlspecialchars($t['user_name'], ENT_QUOTES, 'UTF-8') ?></h4>
                        <span class="text-[10px] text-slate-400 font-mono">@<?= htmlspecialchars($t['user_username'], ENT_QUOTES, 'UTF-8') ?></span>
                    </div>
                    <span class="text-[10px] text-emerald-400 font-bold">Verified Player</span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- MEET THE FOUNDER SECTION -->
    <section class="bg-slate-900 border border-slate-800/90 rounded-3xl p-8 sm:p-12 shadow-2xl relative overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
            <div class="text-center md:text-left space-y-3 md:col-span-1">
                <div class="w-28 h-28 mx-auto md:mx-0 rounded-2xl bg-gradient-to-tr from-amber-500 to-indigo-600 flex items-center justify-center text-4xl font-black text-slate-950 shadow-xl border-2 border-amber-400">
                    RD
                </div>
                <div>
                    <h3 class="text-2xl font-black text-white">Ross Dalangin</h3>
                    <p class="text-xs font-bold text-amber-400 uppercase tracking-widest">Founder & Chief Architect</p>
                </div>
            </div>
            <div class="space-y-4 md:col-span-2 text-slate-300 text-sm leading-relaxed">
                <p>
                    <strong class="text-white">Built by an educator and freelancing veteran.</strong> Ross Dalangin began his career as an IT and programming teacher at the young age of 19, dedicating nearly 10 years to shaping the next generation of software developers.
                </p>
                <p>
                    With over <strong class="text-indigo-400 font-bold">20+ years of hands-on freelancing experience</strong> spanning lead generation, search engine optimization (SEO), custom software engineering, and web development, Ross designed FREELANCEQUEST to bridge the gap between academic learning and real client income.
                </p>
                <div class="pt-2 flex flex-wrap gap-2 text-[11px] font-mono text-slate-400">
                    <span class="bg-slate-950 border border-slate-800 px-3 py-1 rounded-lg">👨‍🏫 10 Yrs IT Educator</span>
                    <span class="bg-slate-950 border border-slate-800 px-3 py-1 rounded-lg">💻 20+ Yrs Freelancing Veteran</span>
                    <span class="bg-slate-950 border border-slate-800 px-3 py-1 rounded-lg">🚀 Software & Web Dev</span>
                    <span class="bg-slate-950 border border-slate-800 px-3 py-1 rounded-lg">📈 Lead Gen & SEO Specialist</span>
                </div>
            </div>
        </div>
    </section>

    <!-- CALL TO ACTION -->
    <section class="bg-gradient-to-r from-indigo-900 to-purple-900 border border-indigo-700/50 p-12 rounded-3xl text-center space-y-6 shadow-2xl">
        <h2 class="text-3xl sm:text-4xl font-black text-white">READY TO LEVEL UP YOUR CAREER?</h2>
        <p class="text-slate-200 text-base max-w-xl mx-auto">Create your character today and start earning your first virtual income within minutes!</p>
        <div>
            <a href="/register" class="inline-block bg-amber-500 hover:bg-amber-400 text-slate-950 font-black text-base px-10 py-4 rounded-2xl shadow-xl shadow-amber-500/30 transition transform hover:scale-105">
                CREATE PLAYER CHARACTER & PLAY &rarr;
            </a>
        </div>
    </section>

</div>
<?php require __DIR__ . '/layout/footer.php'; ?>
