<?php require __DIR__ . '/layout/header.php'; ?>
<div class="space-y-24 py-6">

    <!-- HERO SECTION -->
    <section class="relative overflow-hidden bg-slate-900/60 border border-slate-800/80 rounded-3xl p-8 sm:p-14 text-center space-y-8 shadow-2xl backdrop-blur-sm">
        <div class="inline-flex items-center gap-2 bg-indigo-500/10 border border-indigo-500/30 text-indigo-400 font-extrabold text-xs px-4 py-2 rounded-full uppercase tracking-widest shadow-inner">
            <span>🎮 THE #1 VIRTUAL ASSISTANT & FREELANCING CAREER SIMULATOR</span>
        </div>

        <h1 class="text-4xl sm:text-6xl font-black text-white tracking-tight leading-none max-w-4xl mx-auto">
            PLAY. LEARN. LEVEL UP.<br>
            <span class="bg-gradient-to-r from-amber-400 via-orange-400 to-indigo-400 bg-clip-text text-transparent">GET CLIENTS. GET PAID.</span>
        </h1>

        <p class="text-slate-300 text-base sm:text-xl max-w-2xl mx-auto font-medium leading-relaxed">
            Transform from absolute beginner to high-earning Virtual Assistant & Freelancer in an entertaining, RPG-style career simulation game.
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4">
            <a href="/register" class="w-full sm:w-auto bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-400 hover:to-orange-400 text-slate-950 font-black text-base px-8 py-4 rounded-2xl shadow-xl shadow-amber-500/25 transition transform hover:-translate-y-0.5">
                START YOUR QUEST FOR FREE &rarr;
            </a>
            <a href="/login" class="w-full sm:w-auto bg-slate-800 hover:bg-slate-700 text-slate-100 font-bold text-base px-8 py-4 rounded-2xl border border-slate-700 transition">
                CONTINUE GAME &rarr;
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
