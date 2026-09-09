<?php require __DIR__ . '/../layout/header.php'; ?>
<div class="max-w-4xl mx-auto space-y-8">
    <div class="bg-slate-900 border border-slate-800 p-6 sm:p-8 rounded-2xl space-y-4 shadow-xl">
        <div class="flex items-center justify-between text-xs font-bold">
            <a href="/learn" class="text-indigo-400 hover:underline flex items-center gap-1">&larr; Back to Academy</a>
            <span class="bg-indigo-500/20 text-indigo-400 px-3 py-1 rounded-full border border-indigo-500/30">
                Level <?= $lesson['level_number'] ?> &bull; +<?= $lesson['xp_reward'] ?> XP
            </span>
        </div>

        <h1 class="text-3xl font-extrabold text-white"><?= htmlspecialchars($lesson['title'], ENT_QUOTES, 'UTF-8') ?></h1>
        <p class="text-slate-300 text-sm leading-relaxed"><?= htmlspecialchars($lesson['summary'], ENT_QUOTES, 'UTF-8') ?></p>

        <div class="pt-4 border-t border-slate-800 flex items-center justify-between">
            <span class="text-xs text-amber-400 font-bold"><i class="fa-solid fa-coins mr-1"></i> +<?= $lesson['coin_reward'] ?> Coins</span>
            <?php if ($isCompleted): ?>
                <span class="bg-emerald-500/20 text-emerald-400 px-4 py-2 rounded-xl text-xs font-extrabold border border-emerald-500/30">✓ LESSON COMPLETED</span>
            <?php else: ?>
                <form action="/learn/<?= htmlspecialchars($lesson['slug'], ENT_QUOTES, 'UTF-8') ?>/complete" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white font-extrabold px-5 py-2.5 rounded-xl text-xs shadow-lg shadow-indigo-600/30 transition">
                        MARK AS COMPLETE (+<?= $lesson['xp_reward'] ?> XP)
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <div class="bg-slate-900 border border-slate-800 p-6 sm:p-8 rounded-2xl text-slate-200 text-sm leading-relaxed space-y-4 shadow-xl">
        <h2 class="text-xl font-bold text-white border-b border-slate-800 pb-3">LESSON MATERIAL</h2>
        <div class="prose prose-invert max-w-none space-y-4">
            <?= strip_tags($lesson['content'], '<h2><h3><h4><p><ul><ol><li><strong><em><br><code><pre><blockquote>') ?>
        </div>
    </div>

    <?php if (!empty($quizzes)): ?>
    <div class="bg-slate-900 border border-slate-800 p-6 sm:p-8 rounded-2xl space-y-6 shadow-xl">
        <div class="flex items-center justify-between border-b border-slate-800 pb-4">
            <div class="flex items-center gap-2">
                <span class="text-2xl">🧠</span>
                <div>
                    <h2 class="text-xl font-extrabold text-white">KNOWLEDGE CHECK QUIZ</h2>
                    <p class="text-xs text-slate-400">Pass with 70%+ score to demonstrate mastery.</p>
                </div>
            </div>
        </div>

        <?php foreach ($quizzes as $quiz): ?>
        <form action="/quiz/<?= $quiz['id'] ?>/submit" method="POST" class="space-y-6">
            <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">
            <?php foreach ($quiz['questions'] as $index => $q): ?>
            <div class="bg-slate-950 border border-slate-800 p-5 rounded-xl space-y-3">
                <h4 class="font-bold text-sm text-white">Question <?= $index + 1 ?>: <?= htmlspecialchars($q['question_text'], ENT_QUOTES, 'UTF-8') ?></h4>
                <div class="space-y-2">
                    <?php foreach ($q['options'] as $option): ?>
                    <label class="flex items-center gap-3 bg-slate-900 p-3 rounded-lg border border-slate-800 hover:border-indigo-500/50 cursor-pointer transition">
                        <input type="radio" name="answers[<?= $q['id'] ?>]" value="<?= htmlspecialchars($option, ENT_QUOTES, 'UTF-8') ?>" required class="text-indigo-600">
                        <span class="text-xs text-slate-300 font-medium"><?= htmlspecialchars($option, ENT_QUOTES, 'UTF-8') ?></span>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>

            <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-extrabold py-3 px-6 rounded-xl text-sm transition shadow-xl shadow-indigo-600/30">
                SUBMIT QUIZ ANSWERS &rarr;
            </button>
        </form>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>
