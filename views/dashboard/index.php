<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="space-y-8">

    <div class="bg-gradient-to-r from-indigo-900/80 via-slate-900 to-slate-900 border border-indigo-500/30 rounded-2xl p-6 sm:p-8 relative overflow-hidden shadow-2xl">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 relative z-10">
            <div class="space-y-2 max-w-2xl">
                <div class="flex items-center gap-3">
                    <span class="bg-indigo-500/20 text-indigo-400 font-extrabold text-xs px-3 py-1 rounded-full border border-indigo-500/30 uppercase tracking-widest">
                        LEVEL <?= $user['level'] ?> &bull; <?= htmlspecialchars($currentLevelModel['title'] ?? 'Explorer') ?>
                    </span>
                    <span class="text-xs text-amber-400 font-semibold"><i class="fa-solid fa-fire mr-1"></i> <?= $user['streak_count'] ?> Day Streak</span>
                </div>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">
                    WELCOME BACK, <span class="text-indigo-400 uppercase"><?= htmlspecialchars($user['name']) ?></span>! ⚔️
                </h1>
                <p class="text-slate-300 text-sm leading-relaxed">
                    You are currently at <strong class="text-white"><?= htmlspecialchars($currentLevelModel['title'] ?? 'Level ' . $user['level']) ?></strong>. Complete daily quests, practice client simulations, and build your portfolio to unlock high-paying client opportunities.
                </p>
            </div>

            <?php if (!empty($currentMission)): ?>
            <div class="bg-slate-900/90 border border-indigo-500/40 p-5 rounded-xl shadow-xl w-full md:w-80 space-y-3">
                <div class="flex justify-between items-center text-xs text-indigo-400 font-bold uppercase tracking-wider">
                    <span>⚡ CURRENT MISSION</span>
                    <span>+<?= $currentMission['xp_reward'] ?> XP</span>
                </div>
                <h3 class="font-extrabold text-white text-base line-clamp-1"><?= htmlspecialchars($currentMission['title']) ?></h3>
                <p class="text-xs text-slate-400 line-clamp-2"><?= htmlspecialchars($currentMission['instructions']) ?></p>
                <a href="/mission/<?= $currentMission['id'] ?>" class="block text-center w-full bg-indigo-600 hover:bg-indigo-500 text-white font-extrabold py-2.5 px-4 rounded-lg text-sm transition shadow-lg shadow-indigo-600/30">
                    START MISSION &rarr;
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if (($user['role'] ?? '') === 'admin'): ?>
    <!-- ADMIN DATABASE RE-SEED QUICK CONTROL CARD -->
    <div class="bg-gradient-to-r from-amber-950/50 via-slate-900 to-slate-900 border border-amber-500/50 p-6 rounded-2xl flex flex-col md:flex-row items-start md:items-center justify-between gap-6 shadow-xl">
        <div class="space-y-1">
            <div class="flex items-center gap-2">
                <span class="text-xl">⚡</span>
                <h2 class="text-xl font-extrabold text-amber-400">ADMIN CONTROL: RE-SEED & UPDATE DATABASE</h2>
            </div>
            <p class="text-slate-300 text-xs">
                As an Administrator, you can instantly refresh the database schema and seed all 15 course levels, masterclass lessons, quizzes, missions, and downloadable resources.
            </p>
        </div>
        <form action="/admin/reseed-database" method="POST" onsubmit="return confirm('Are you sure you want to update and re-seed all database content across all 15 levels?');">
            <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">
            <button type="submit" class="bg-gradient-to-r from-amber-500 to-yellow-500 hover:from-amber-400 hover:to-yellow-400 text-slate-950 font-black py-3 px-6 rounded-xl text-xs shadow-lg transition flex items-center gap-2 whitespace-nowrap">
                🔄 RE-SEED & UPDATE DATABASE NOW
            </button>
        </form>
    </div>
    <?php endif; ?>


    <!-- ADMIN DATABASE BACKUP & RESTORE BANNER (Visible to Admins) -->
    <?php if (($user['role'] ?? '') === 'admin'): ?>
        <div class="bg-slate-800 rounded-2xl p-6 border border-amber-500/30 shadow-xl mb-8">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-4 pb-3 border-b border-slate-700">
                <div>
                    <h3 class="text-lg font-extrabold text-amber-400 flex items-center gap-2">
                        <span>🛡️</span> ADMIN DATABASE BACKUP & RESTORE ENGINE
                    </h3>
                    <p class="text-xs text-slate-400">Export your complete database snapshot or import a backup file directly from your dashboard.</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="/admin/database/export" class="bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black px-4 py-2 rounded-xl text-xs flex items-center gap-1.5 shadow transition">
                        <span>📥</span> Export Backup (.sqlite)
                    </a>
                    <a href="/settings" class="bg-slate-700 hover:bg-slate-600 text-white font-bold px-3 py-2 rounded-xl text-xs transition">
                        ⚙️ Admin Settings
                    </a>
                </div>
            </div>

            <form action="/admin/database/import" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row items-center gap-3" onsubmit="return confirm('WARNING: Importing a database backup file will replace all current data. Proceed?');">
                <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">
                <input type="file" name="backup_file" accept=".sqlite,.db" required class="block w-full text-xs text-slate-400 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-amber-500 file:text-slate-950 hover:file:bg-amber-400 cursor-pointer bg-slate-900/60 rounded-xl border border-slate-700 p-1">
                <button type="submit" class="w-full sm:w-auto bg-amber-500 hover:bg-amber-400 text-slate-950 font-black px-5 py-2.5 rounded-xl text-xs whitespace-nowrap shadow transition flex items-center justify-center gap-1.5">
                    <span>🚀</span> Import Backup
                </button>
            </form>
        </div>
    <?php endif; ?>


<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl space-y-4">
                <div class="flex justify-between items-center border-b border-slate-800 pb-4">
                    <div class="flex items-center gap-2">
                        <span class="text-amber-400 text-xl">🎯</span>
                        <h2 class="text-xl font-extrabold text-white">DAILY QUESTS</h2>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <?php foreach ($quests as $q): ?>
                    <div class="bg-slate-950/80 border border-slate-800 p-4 rounded-xl flex items-center justify-between hover:border-indigo-500/40 transition">
                        <div class="space-y-1">
                            <span class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest"><?= htmlspecialchars($q['quest_type'] ?? 'quest') ?></span>
                            <h4 class="font-bold text-sm text-slate-200"><?= htmlspecialchars($q['title']) ?></h4>
                            <div class="flex items-center gap-3 text-xs font-semibold">
                                <span class="text-indigo-400">+<?= $q['xp_reward'] ?? $q['xp'] ?? 50 ?> XP</span>
                                <span class="text-amber-400"><i class="fa-solid fa-coins text-amber-400 mr-1"></i> +<?= $q['coin_reward'] ?? $q['coins'] ?? 10 ?></span>
                            </div>
                        </div>
                        <div>
                            <a href="/learn" class="bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold px-3 py-1.5 rounded-lg border border-slate-700">GO</a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- RPG Career World Map -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl space-y-6">
                <div class="flex justify-between items-center border-b border-slate-800 pb-4">
                    <div class="flex items-center gap-2">
                        <span class="text-indigo-400 text-xl">🗺️</span>
                        <h2 class="text-xl font-extrabold text-white">RPG CAREER MAP</h2>
                    </div>
                    <span class="text-xs text-slate-400">Progression Path (Levels 0 - 15)</span>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <?php foreach ($levels as $lvl): ?>
                        <?php
                            $isUnlocked = $user['level'] >= $lvl['level_number'];
                            $isCurrent = $user['level'] == $lvl['level_number'];
                        ?>
                        <div class="relative p-4 rounded-xl border text-center space-y-2 transition shadow-md <?= $isCurrent ? 'bg-indigo-950/90 border-indigo-500 ring-2 ring-indigo-500/50' : ($isUnlocked ? 'bg-slate-950 border-slate-800' : 'bg-slate-950/40 border-slate-900 opacity-60') ?>">
                            <div class="text-3xl mb-1"><?= $lvl['icon'] ?? '🔒' ?></div>
                            <span class="text-[10px] font-extrabold px-2 py-0.5 rounded-full uppercase <?= $isCurrent ? 'bg-indigo-500 text-white' : ($isUnlocked ? 'bg-emerald-500/20 text-emerald-400' : 'bg-slate-800 text-slate-500') ?>">
                                LVL <?= $lvl['level_number'] ?>
                            </span>
                            <h4 class="font-extrabold text-xs text-slate-200 line-clamp-1"><?= htmlspecialchars($lvl['title']) ?></h4>
                            <p class="text-[10px] text-slate-400 line-clamp-1"><?= htmlspecialchars($lvl['subtitle']) ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="space-y-8">
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl space-y-6">
                <div class="flex justify-between items-center border-b border-slate-800 pb-4">
                    <div class="flex items-center gap-2">
                        <span class="text-purple-400 text-xl">⚡</span>
                        <h2 class="text-xl font-extrabold text-white">SKILL TREE</h2>
                    </div>
                </div>

                <div class="space-y-4">
                    <?php foreach ($skillCategories as $cat): ?>
                        <div class="space-y-2">
                            <div class="flex justify-between text-xs font-extrabold" style="color: <?= $cat['color'] ?>">
                                <span><?= strtoupper(htmlspecialchars($cat['name'])) ?></span>
                                <span><?= count($cat['skills']) ?> Skills</span>
                            </div>
                            <div class="bg-slate-950 p-3 rounded-xl border border-slate-800 space-y-2">
                                <?php foreach ($cat['skills'] as $sk): ?>
                                    <div class="flex justify-between items-center text-xs">
                                        <span class="text-slate-300 font-semibold"><?= htmlspecialchars($sk['name']) ?></span>
                                        <span class="text-emerald-400 font-mono font-bold">Lvl 1 (Active)</span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
