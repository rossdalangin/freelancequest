<?php require __DIR__ . '/../layout/header.php'; ?>
<div class="space-y-8">
    <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-white">ADMIN CONTROL CENTER</h1>
            <p class="text-slate-400 text-sm">System management, analytics, audit logs, and data exports.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <form action="/admin/reseed-database" method="POST" onsubmit="return confirm('Are you sure you want to update and re-seed all course levels, lessons, quizzes, and resources?');">
                <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">
                <button type="submit" class="bg-amber-500 hover:bg-amber-400 text-slate-950 font-black px-4 py-2 rounded-xl text-xs shadow-lg transition flex items-center gap-2">
                    🔄 RE-SEED & UPDATE DATABASE
                </button>
            </form>
            <a href="/admin/export-data" class="bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black px-4 py-2 rounded-xl text-xs shadow-lg transition flex items-center gap-2">
                📥 EXPORT SYSTEM BACKUP (JSON)
            </a>
        </div>
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
        <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl space-y-2">
            <span class="text-xs font-bold text-slate-400 uppercase">Payments</span>
            <div class="text-3xl font-black text-emerald-400"><?= $paymentsCount ?></div>
        </div>
    </div>

    <!-- ADMIN QUICK NAVIGATION -->
    <div class="flex flex-wrap items-center gap-3">
        <a href="/admin/courses" class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold px-4 py-2.5 rounded-xl text-xs shadow-md">🎓 Manage Courses</a>
        <a href="/admin/lessons" class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold px-4 py-2.5 rounded-xl text-xs shadow-md">📚 Manage Lessons</a>
        <a href="/admin/quizzes" class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold px-4 py-2.5 rounded-xl text-xs shadow-md">🧠 Manage Quizzes</a>
        <a href="/admin/missions" class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold px-4 py-2.5 rounded-xl text-xs shadow-md">🎯 Manage Missions</a>
        <a href="/admin/resources" class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold px-4 py-2.5 rounded-xl text-xs shadow-md">📁 Manage Resources</a>
        <a href="/admin/users" class="bg-slate-800 hover:bg-slate-700 text-white font-bold px-4 py-2.5 rounded-xl text-xs border border-slate-700">👥 Users & Plans</a>
        <a href="/admin/payments" class="bg-slate-800 hover:bg-slate-700 text-white font-bold px-4 py-2.5 rounded-xl text-xs border border-slate-700">💳 Payments & Refunds</a>
        <a href="/admin/certificates" class="bg-slate-800 hover:bg-slate-700 text-white font-bold px-4 py-2.5 rounded-xl text-xs border border-slate-700">📜 Manage Certificates</a>
        <a href="/admin/logs" class="bg-slate-800 hover:bg-slate-700 text-white font-bold px-4 py-2.5 rounded-xl text-xs border border-slate-700">📋 Audit Logs</a>
    </div>

    <!-- DEDICATED DATABASE SEED CONTROL CARD -->
    <div class="bg-gradient-to-r from-amber-950/40 via-slate-900 to-slate-900 border border-amber-500/40 p-6 rounded-2xl flex flex-col md:flex-row items-start md:items-center justify-between gap-6 shadow-xl">
        <div class="space-y-1 max-w-2xl">
            <div class="flex items-center gap-2">
                <span class="text-xl">⚡</span>
                <h2 class="text-xl font-extrabold text-amber-400">DATABASE SEED & CONTENT REFRESH</h2>
            </div>
            <p class="text-slate-300 text-xs leading-relaxed">
                Clicking this button completely refreshes the database schema and updates all seeds for all 15 course levels, masterclass lessons, knowledge check quizzes, interactive scenario missions, achievement badges, and downloadable vault resources.
            </p>
        </div>
        <form action="/admin/reseed-database" method="POST" onsubmit="return confirm('Are you sure you want to re-seed and update all database contents for all 15 levels?');" class="w-full md:w-auto">
            <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">
            <button type="submit" class="w-full md:w-auto bg-gradient-to-r from-amber-500 to-yellow-500 hover:from-amber-400 hover:to-yellow-400 text-slate-950 font-black py-3.5 px-6 rounded-xl text-sm shadow-xl shadow-amber-500/20 transition flex items-center justify-center gap-2 border border-amber-400/50">
                🔄 RE-SEED & UPDATE DATABASE NOW
            </button>
        </form>
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

        <!-- System & Payment Gateway Settings Form -->
        <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl space-y-4">
            <h2 class="text-xl font-extrabold text-white">⚙️ GAME & PAYMENT GATEWAY ACCOUNTS</h2>
            <form action="/admin/settings" method="POST" class="space-y-4">
                <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1">Global XP Multiplier</label>
                        <input type="text" name="xp_multiplier" value="<?= htmlspecialchars($xpMultiplier ?? '1.0', ENT_QUOTES, 'UTF-8') ?>" class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-xs text-slate-100">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1">7-Day Streak Bonus XP</label>
                        <input type="text" name="streak_bonus_xp" value="<?= htmlspecialchars($streakBonus ?? '200', ENT_QUOTES, 'UTF-8') ?>" class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-xs text-slate-100">
                    </div>
                </div>

                <div class="border-t border-slate-800 pt-4 space-y-3">
                    <h3 class="text-xs font-extrabold text-amber-400 uppercase tracking-wider">Admin Payment Receiving Accounts</h3>
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1">PayPal Admin Merchant Email</label>
                        <input type="email" name="paypal_email" value="<?= htmlspecialchars($paypalEmail ?? 'admin@freelancequest.com', ENT_QUOTES, 'UTF-8') ?>" class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-xs text-slate-100">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1">Stripe Publishable Key / Account ID</label>
                        <input type="text" name="stripe_key" value="<?= htmlspecialchars($stripeKey ?? 'pk_live_freelancequest_admin_key', ENT_QUOTES, 'UTF-8') ?>" class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-xs text-slate-100">
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-300 mb-1">GCash Account Mobile Number</label>
                            <input type="text" name="gcash_number" value="<?= htmlspecialchars($gcashNumber ?? '09171234567', ENT_QUOTES, 'UTF-8') ?>" class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-xs text-slate-100">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-300 mb-1">GCash Account Name</label>
                            <input type="text" name="gcash_name" value="<?= htmlspecialchars($gcashName ?? 'FreelanceQuest Admin', ENT_QUOTES, 'UTF-8') ?>" class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-xs text-slate-100">
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-extrabold py-3 px-6 rounded-xl text-xs shadow-lg">SAVE ALL SETTINGS & PAYMENT ACCOUNTS &rarr;</button>
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
