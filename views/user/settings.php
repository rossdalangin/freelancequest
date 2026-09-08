<?php require __DIR__ . '/../layout/header.php'; ?>
<div class="max-w-3xl mx-auto space-y-8 py-6">
    <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl flex items-center justify-between">
        <div>
            <span class="text-xs font-bold text-amber-400 uppercase tracking-wider">PLAYER PROFILE</span>
            <h1 class="text-2xl font-black text-white">ACCOUNT & GAME SETTINGS</h1>
        </div>
        <span class="bg-indigo-500/20 text-indigo-400 font-extrabold text-xs px-3 py-1.5 rounded-full border border-indigo-500/30">
            LEVEL <?= $user['level'] ?> &bull; <?= strtoupper($user['subscription_tier'] ?? 'free') ?> PLAN
        </span>
    </div>

    <?php if (!empty($success)): ?>
        <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs p-4 rounded-xl font-bold">
            ✓ <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="bg-red-500/10 border border-red-500/30 text-red-400 text-xs p-4 rounded-xl font-bold">
            ⚠️ <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <div class="bg-slate-900 border border-slate-800 p-8 rounded-2xl space-y-6 shadow-xl">
        <form action="/settings" method="POST" class="space-y-6">
            <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-2">Full Name</label>
                    <input type="text" name="name" required value="<?= htmlspecialchars($user['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-xs text-white focus:outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-2">Username (Portfolio URL)</label>
                    <input type="text" disabled value="<?= htmlspecialchars($user['username'] ?? '', ENT_QUOTES, 'UTF-8') ?>" class="w-full bg-slate-950/50 border border-slate-800 text-slate-500 rounded-xl px-4 py-3 text-xs cursor-not-allowed">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase mb-2">Professional Headline</label>
                <input type="text" name="headline" placeholder="Virtual Assistant & Administrative Specialist" value="<?= htmlspecialchars($user['headline'] ?? '', ENT_QUOTES, 'UTF-8') ?>" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-xs text-white focus:outline-none focus:border-indigo-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase mb-2">Bio / Professional Summary</label>
                <textarea name="bio" rows="3" placeholder="Brief intro..." class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-xs text-white focus:outline-none focus:border-indigo-500"><?= htmlspecialchars($user['bio'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
            </div>

            <div class="border-t border-slate-800 pt-6 space-y-4">
                <h3 class="text-xs font-extrabold text-amber-400 uppercase tracking-wider">CHANGE PASSWORD (OPTIONAL)</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase mb-2">Current Password</label>
                        <input type="password" name="old_password" placeholder="••••••••" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-xs text-white focus:outline-none focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase mb-2">New Password (Min 6 chars)</label>
                        <input type="password" name="new_password" placeholder="••••••••" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-xs text-white focus:outline-none focus:border-indigo-500">
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-black py-3.5 rounded-xl text-xs transition shadow-xl shadow-indigo-600/30">
                SAVE ACCOUNT SETTINGS &rarr;
            </button>
        </form>
    </div>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>
