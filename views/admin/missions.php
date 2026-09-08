<?php require __DIR__ . '/../layout/header.php'; ?>
<div class="space-y-8">
    <div class="flex items-center justify-between bg-slate-900 border border-slate-800 p-6 rounded-2xl">
        <div>
            <span class="text-xs font-bold text-amber-400 uppercase tracking-wider">ADMIN CONTROL CENTER</span>
            <h1 class="text-2xl font-black text-white">MISSION MANAGEMENT</h1>
        </div>
        <a href="/admin" class="text-xs text-indigo-400 font-bold hover:underline">&larr; Back to Dashboard</a>
    </div>

    <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl space-y-4">
        <h3 class="text-lg font-bold text-white">CREATE NEW MISSION</h3>
        <form action="/admin/missions/create" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">

            <div>
                <label class="block text-xs font-bold text-slate-400 mb-1">Mission Title</label>
                <input type="text" name="title" required placeholder="Executive Calendar Management Challenge" class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2.5">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-400 mb-1">Level Number</label>
                <input type="number" name="level_number" value="1" min="1" max="15" class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2.5">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-400 mb-1">Type</label>
                <select name="type" class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2.5">
                    <option value="interactive">Interactive Task</option>
                    <option value="proposal">Proposal Challenge</option>
                    <option value="client_sim">Client Simulation</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-400 mb-1">XP Reward</label>
                <input type="number" name="xp_reward" value="250" class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2.5">
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-slate-400 mb-1">Scenario Description</label>
                <textarea name="scenario" rows="3" required placeholder="Client Alex needs an urgent calendar schedule across 3 time zones..." class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2.5"></textarea>
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-slate-400 mb-1">Instructions</label>
                <textarea name="instructions" rows="2" required placeholder="Analyze the constraints, format the output cleanly, and submit..." class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2.5"></textarea>
            </div>

            <div class="md:col-span-2">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white font-black py-2.5 px-6 rounded-xl text-xs transition">
                    + CREATE MISSION
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
                    <th class="p-4">Mission Title</th>
                    <th class="p-4">Type</th>
                    <th class="p-4">Rewards</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800">
                <?php foreach ($missions as $m): ?>
                <tr>
                    <td class="p-4 font-mono text-slate-400">#<?= $m['id'] ?></td>
                    <td class="p-4 font-bold text-amber-400">LVL <?= $m['level_number'] ?></td>
                    <td class="p-4 font-bold text-white"><?= htmlspecialchars($m['title']) ?></td>
                    <td class="p-4 font-bold uppercase text-indigo-400"><?= htmlspecialchars($m['type']) ?></td>
                    <td class="p-4 font-bold text-emerald-400">+<?= $m['xp_reward'] ?> XP &bull; +<?= $m['coin_reward'] ?> Coins</td>
                    <td class="p-4">
                        <form action="/admin/missions/<?= $m['id'] ?>/delete" method="POST" onsubmit="return confirm('Delete this mission?');">
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
