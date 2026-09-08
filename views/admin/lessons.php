<?php require __DIR__ . '/../layout/header.php'; ?>
<div class="space-y-8">
    <div class="flex items-center justify-between bg-slate-900 border border-slate-800 p-6 rounded-2xl">
        <div>
            <span class="text-xs font-bold text-amber-400 uppercase tracking-wider">ADMIN CONTROL CENTER</span>
            <h1 class="text-2xl font-black text-white">LESSON CURRICULUM MANAGEMENT</h1>
        </div>
        <a href="/admin" class="text-xs text-indigo-400 font-bold hover:underline">&larr; Back to Dashboard</a>
    </div>

    <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl space-y-4">
        <h3 class="text-lg font-bold text-white">CREATE NEW LESSON</h3>
        <form action="/admin/lessons/create" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">

            <div>
                <label class="block text-xs font-bold text-slate-400 mb-1">Course</label>
                <select name="course_id" class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2.5">
                    <?php foreach ($courses as $c): ?>
                    <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['title']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-400 mb-1">Level Number</label>
                <input type="number" name="level_number" value="1" min="1" max="15" class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2.5">
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-slate-400 mb-1">Lesson Title</label>
                <input type="text" name="title" required placeholder="Mastering Advanced Calendar Management" class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2.5">
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-slate-400 mb-1">Summary</label>
                <input type="text" name="summary" required placeholder="Step-by-step SOP for managing multi-time-zone executive calendars." class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2.5">
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-slate-400 mb-1">Lesson Content (Markdown)</label>
                <textarea name="content" rows="4" required class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2.5" placeholder="## Blueprint SOP..."></textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-400 mb-1">XP Reward</label>
                <input type="number" name="xp_reward" value="100" class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2.5">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-400 mb-1">Coin Reward</label>
                <input type="number" name="coin_reward" value="20" class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2.5">
            </div>

            <div class="md:col-span-2">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white font-black py-2.5 px-6 rounded-xl text-xs transition">
                    + PUBLISH LESSON
                </button>
            </div>
        </form>
    </div>

    <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden">
        <table class="w-full text-left text-xs text-slate-300">
            <thead class="bg-slate-950 text-slate-400 uppercase font-extrabold border-b border-slate-800">
                <tr>
                    <th class="p-4">ID</th>
                    <th class="p-4">Level</th>
                    <th class="p-4">Title</th>
                    <th class="p-4">Course</th>
                    <th class="p-4">Rewards</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800">
                <?php foreach ($lessons as $les): ?>
                <tr>
                    <td class="p-4 font-mono text-slate-400">#<?= $les['id'] ?></td>
                    <td class="p-4 font-bold text-amber-400">LVL <?= $les['level_number'] ?></td>
                    <td class="p-4 font-bold text-white"><?= htmlspecialchars($les['title']) ?></td>
                    <td class="p-4"><?= htmlspecialchars($les['course_title'] ?? 'General') ?></td>
                    <td class="p-4 font-bold text-indigo-400">+<?= $les['xp_reward'] ?> XP &bull; +<?= $les['coin_reward'] ?> Coins</td>
                    <td class="p-4">
                        <form action="/admin/lessons/<?= $les['id'] ?>/delete" method="POST" onsubmit="return confirm('Delete this lesson?');">
                            <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">
                            <button type="submit" class="bg-red-600/20 text-red-400 hover:bg-red-600 hover:text-white font-bold px-2.5 py-1 rounded text-[10px] transition">DELETE</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>
