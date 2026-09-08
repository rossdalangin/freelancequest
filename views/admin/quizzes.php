<?php require __DIR__ . '/../layout/header.php'; ?>
<div class="space-y-8">
    <div class="flex items-center justify-between bg-slate-900 border border-slate-800 p-6 rounded-2xl">
        <div>
            <span class="text-xs font-bold text-amber-400 uppercase tracking-wider">ADMIN CONTROL CENTER</span>
            <h1 class="text-2xl font-black text-white">QUIZ MANAGEMENT</h1>
        </div>
        <a href="/admin" class="text-xs text-indigo-400 font-bold hover:underline">&larr; Back to Dashboard</a>
    </div>

    <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl space-y-4">
        <h3 class="text-lg font-bold text-white">CREATE NEW QUIZ</h3>
        <form action="/admin/quizzes/create" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">

            <div>
                <label class="block text-xs font-bold text-slate-400 mb-1">Associated Lesson</label>
                <select name="lesson_id" class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2.5">
                    <?php foreach ($lessons as $les): ?>
                    <option value="<?= $les['id'] ?>"><?= htmlspecialchars($les['title']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-400 mb-1">Quiz Title</label>
                <input type="text" name="title" required value="Knowledge Check Quiz" class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2.5">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-400 mb-1">XP Reward</label>
                <input type="number" name="xp_reward" value="100" class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2.5">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-400 mb-1">Coin Reward</label>
                <input type="number" name="coin_reward" value="25" class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2.5">
            </div>

            <div class="md:col-span-2">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white font-black py-2.5 px-6 rounded-xl text-xs transition">
                    + CREATE QUIZ
                </button>
            </div>
        </form>
    </div>

    <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden">
        <table class="w-full text-left text-xs text-slate-300">
            <thead class="bg-slate-950 text-slate-400 uppercase font-extrabold border-b border-slate-800">
                <tr>
                    <th class="p-4">ID</th>
                    <th class="p-4">Quiz Title</th>
                    <th class="p-4">Associated Lesson</th>
                    <th class="p-4">Passing Score</th>
                    <th class="p-4">Rewards</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800">
                <?php foreach ($quizzes as $q): ?>
                <tr>
                    <td class="p-4 font-mono text-slate-400">#<?= $q['id'] ?></td>
                    <td class="p-4 font-bold text-white"><?= htmlspecialchars($q['title']) ?></td>
                    <td class="p-4"><?= htmlspecialchars($q['lesson_title'] ?? 'General Lesson') ?></td>
                    <td class="p-4 font-bold text-amber-400"><?= $q['passing_score'] ?>%</td>
                    <td class="p-4 font-bold text-indigo-400">+<?= $q['xp_reward'] ?> XP &bull; +<?= $q['coin_reward'] ?? 25 ?> Coins</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>
