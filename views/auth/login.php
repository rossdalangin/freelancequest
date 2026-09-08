<?php require __DIR__ . '/../layout/header.php'; ?>
<div class="max-w-md mx-auto my-12 bg-slate-900 border border-slate-800 p-8 rounded-2xl shadow-2xl space-y-6">
    <div class="text-center space-y-2">
        <span class="text-4xl">⚔️</span>
        <h1 class="text-2xl font-black text-white tracking-wide">WELCOME BACK, PLAYER</h1>
        <p class="text-xs text-slate-400">Log in to continue your freelancing career quest.</p>
    </div>

    <?php if (!empty($error)): ?>
        <div class="bg-red-500/10 border border-red-500/30 text-red-400 text-xs p-3 rounded-xl font-semibold">
            ⚠️ <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <form action="/login" method="POST" class="space-y-4">
        <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">

        <div>
            <label class="block text-xs font-extrabold text-slate-300 uppercase tracking-wider mb-2">Email Address</label>
            <input type="email" name="email" required placeholder="maria@example.com" value="maria@example.com" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500 transition">
        </div>

        <div>
            <label class="block text-xs font-extrabold text-slate-300 uppercase tracking-wider mb-2">Password</label>
            <input type="password" name="password" required placeholder="••••••••" value="password" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500 transition">
        </div>

        <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-extrabold py-3.5 rounded-xl text-sm transition shadow-xl shadow-indigo-600/30">
            ENTER THE GAME &rarr;
        </button>
    </form>

    <div class="pt-4 border-t border-slate-800 text-center space-y-2 text-xs">
        <p class="text-slate-400">Don't have a player account yet?</p>
        <a href="/register" class="text-amber-400 font-bold hover:underline">Create Your Freelancer Character (Free) &rarr;</a>
    </div>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>
