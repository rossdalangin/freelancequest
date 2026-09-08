<?php require __DIR__ . '/../layout/header.php'; ?>
<div class="space-y-8">
    <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-white">ADMIN CONTROL CENTER</h1>
            <p class="text-slate-400 text-sm">System management, analytics, audit logs, and data exports.</p>
        </div>
        <a href="/admin/export-data" class="bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black px-4 py-2 rounded-xl text-xs shadow-lg transition flex items-center gap-2">
            📥 EXPORT SYSTEM BACKUP (JSON)
        </a>
    </div>

    <!-- Analytics Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-slate-900 border border-slate-800 p-5 rounded-2xl space-y-1">
            <span class="text-xs font-bold text-slate-400 uppercase">Total Learners</span>
            <div class="text-3xl font-black text-indigo-400"><?= $usersCount ?></div>
        </div>
        <div class="bg-slate-900 border border-slate-800 p-5 rounded-2xl space-y-1">
            <span class="text-xs font-bold text-slate-400 uppercase">Published Lessons</span>
            <div class="text-3xl font-black text-emerald-400"><?= $lessonsCount ?></div>
        </div>
        <div class="bg-slate-900 border border-slate-800 p-5 rounded-2xl space-y-1">
            <span class="text-xs font-bold text-slate-400 uppercase">Active Missions</span>
            <div class="text-3xl font-black text-purple-400"><?= $missionsCount ?></div>
        </div>
        <div class="bg-slate-900 border border-slate-800 p-5 rounded-2xl space-y-1">
            <span class="text-xs font-bold text-slate-400 uppercase">Knowledge Quizzes</span>
            <div class="text-3xl font-black text-amber-400"><?= $quizzesCount ?></div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- AI Course Builder -->
        <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl space-y-4">
            <h2 class="text-xl font-extrabold text-white">🤖 AI COURSE BUILDER</h2>
            <form action="/admin/ai-course-builder" method="POST" class="space-y-4">
                <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">
                <input type="text" name="topic" required placeholder="Topic..." class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-sm text-slate-100">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white font-extrabold py-3 px-6 rounded-xl text-sm">GENERATE MODULE &rarr;</button>
            </form>
        </div>

        <!-- System Settings Form -->
        <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl space-y-4">
            <h2 class="text-xl font-extrabold text-white">⚙️ SYSTEM GAME SETTINGS</h2>
            <form action="/admin/settings" method="POST" class="space-y-4">
                <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1">Global XP Reward Multiplier</label>
                    <input type="text" name="xp_multiplier" value="<?= htmlspecialchars($xpMultiplier ?? '1.0', ENT_QUOTES, 'UTF-8') ?>" class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-sm text-slate-100">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1">7-Day Streak Bonus XP</label>
                    <input type="text" name="streak_bonus_xp" value="<?= htmlspecialchars($streakBonus ?? '200', ENT_QUOTES, 'UTF-8') ?>" class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-sm text-slate-100">
                </div>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white font-extrabold py-3 px-6 rounded-xl text-sm">SAVE SETTINGS &rarr;</button>
            </form>
        </div>
    </div>

    <!-- Audit Logs -->
    <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl space-y-4">
        <h2 class="text-xl font-extrabold text-white">📋 SYSTEM AUDIT LOGS</h2>
        <div class="space-y-2">
            <?php foreach (($auditLogs ?? []) as $log): ?>
                <div class="bg-slate-950 p-3 rounded-xl border border-slate-800 text-xs flex justify-between items-center">
                    <div>
                        <span class="font-bold text-indigo-400"><?= htmlspecialchars($log['action'], ENT_QUOTES, 'UTF-8') ?></span>
                        <p class="text-[11px] text-slate-400"><?= htmlspecialchars($log['details'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
                    </div>
                    <span class="text-[10px] text-slate-500"><?= htmlspecialchars($log['created_at'], ENT_QUOTES, 'UTF-8') ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>
