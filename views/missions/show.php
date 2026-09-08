<?php require __DIR__ . '/../layout/header.php'; ?>
<div class="max-w-4xl mx-auto space-y-8">
    <div class="bg-gradient-to-r from-slate-900 via-indigo-950/80 to-slate-900 border border-indigo-500/40 p-6 sm:p-8 rounded-2xl space-y-4 shadow-2xl">
        <div class="flex justify-between items-center text-xs font-bold">
            <a href="/dashboard" class="text-indigo-400 hover:underline flex items-center gap-1">&larr; Back to Dashboard</a>
            <span class="bg-indigo-500/20 text-indigo-400 px-3 py-1 rounded-full border border-indigo-500/30">LEVEL <?= $mission['level_number'] ?> MISSION</span>
        </div>

        <h1 class="text-3xl font-extrabold text-white"><?= htmlspecialchars($mission['title'], ENT_QUOTES, 'UTF-8') ?></h1>
        <p class="text-slate-300 text-sm leading-relaxed"><?= htmlspecialchars($mission['instructions'], ENT_QUOTES, 'UTF-8') ?></p>

        <div class="flex items-center gap-4 text-xs font-bold pt-2">
            <span class="text-indigo-400">+<?= $mission['xp_reward'] ?> XP Reward</span>
            <span class="text-amber-400"><i class="fa-solid fa-coins mr-1"></i> +<?= $mission['coin_reward'] ?> Coins</span>
        </div>
    </div>

    <div class="bg-slate-900 border border-slate-800 p-6 sm:p-8 rounded-2xl space-y-4 shadow-xl">
        <h2 class="text-lg font-extrabold text-white flex items-center gap-2"><span class="text-xl">💼</span> CLIENT SCENARIO & SPECIFICATIONS</h2>
        <div class="bg-slate-950 p-5 rounded-xl border border-slate-800 text-slate-300 text-sm leading-relaxed">
            <?= nl2br(htmlspecialchars($mission['scenario'], ENT_QUOTES, 'UTF-8')) ?>
        </div>
    </div>

    <div class="bg-slate-900 border border-slate-800 p-6 sm:p-8 rounded-2xl space-y-6 shadow-xl">
        <h2 class="text-lg font-extrabold text-white flex items-center gap-2"><span class="text-xl">✍️</span> YOUR MISSION DELIVERABLE SUBMISSION</h2>
        <form action="/mission/<?= $mission['id'] ?>/submit" method="POST" class="space-y-6">
            <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">
            <div>
                <label for="submission_text" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Enter Proposal / Work Submission Draft</label>
                <textarea id="submission_text" name="submission_text" rows="8" required class="w-full bg-slate-950 border border-slate-800 rounded-xl p-4 text-sm text-slate-100" placeholder="Type your response..."></textarea>
            </div>
            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-extrabold py-3.5 px-6 rounded-xl text-sm transition shadow-xl shadow-indigo-600/30">SUBMIT WORK FOR CLIENT EVALUATION &rarr;</button>
        </form>
    </div>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>
