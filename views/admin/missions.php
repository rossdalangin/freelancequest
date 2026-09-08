<?php require __DIR__ . '/../layout/header.php'; ?>
<div class="space-y-8">
    <div class="flex items-center justify-between bg-slate-900 border border-slate-800 p-6 rounded-2xl">
        <div>
            <span class="text-xs font-bold text-amber-400 uppercase tracking-wider">ADMIN CONTROL CENTER</span>
            <h1 class="text-2xl font-black text-white">MISSION MANAGEMENT</h1>
        </div>
        <a href="/admin" class="text-xs text-indigo-400 font-bold hover:underline">&larr; Back to Dashboard</a>
    </div>

    <!-- CREATE MISSION -->
    <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl space-y-4">
        <h3 class="text-lg font-bold text-white">+ CREATE NEW MISSION</h3>
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

            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-xs font-bold text-slate-400 mb-1">XP Reward</label>
                    <input type="number" name="xp_reward" value="250" class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2.5">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 mb-1">Coin Reward</label>
                    <input type="number" name="coin_reward" value="50" class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2.5">
                </div>
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

    <!-- EXISTING MISSIONS EDIT LIST -->
    <div class="space-y-6">
        <h3 class="text-xl font-bold text-white">EXISTING MISSIONS (EDIT & UPDATE)</h3>

        <?php foreach ($missions as $m): ?>
        <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl space-y-4">
            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                <div class="flex items-center gap-3">
                    <span class="bg-amber-500/20 text-amber-400 text-xs font-black px-2.5 py-1 rounded-full border border-amber-500/30">LVL <?= $m['level_number'] ?></span>
                    <h4 class="text-base font-bold text-white"><?= htmlspecialchars($m['title']) ?></h4>
                </div>
                <form action="/admin/missions/<?= $m['id'] ?>/delete" method="POST" onsubmit="return confirm('Delete this mission?');">
                    <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">
                    <button type="submit" class="bg-red-600/20 text-red-400 hover:bg-red-600 hover:text-white font-bold px-3 py-1 rounded text-xs transition">DELETE MISSION</button>
                </form>
            </div>

            <!-- EDIT MISSION FORM -->
            <form action="/admin/missions/<?= $m['id'] ?>/edit" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">

                <div>
                    <label class="block text-[11px] font-bold text-slate-400 mb-1">Mission Title</label>
                    <input type="text" name="title" required value="<?= htmlspecialchars($m['title']) ?>" class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-400 mb-1">Level Number</label>
                    <input type="number" name="level_number" value="<?= $m['level_number'] ?>" min="1" max="15" class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-400 mb-1">Type</label>
                    <select name="type" class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2">
                        <option value="interactive" <?= $m['type'] === 'interactive' ? 'selected' : '' ?>>Interactive Task</option>
                        <option value="proposal" <?= $m['type'] === 'proposal' ? 'selected' : '' ?>>Proposal Challenge</option>
                        <option value="client_sim" <?= $m['type'] === 'client_sim' ? 'selected' : '' ?>>Client Simulation</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-400 mb-1">XP Reward</label>
                        <input type="number" name="xp_reward" value="<?= $m['xp_reward'] ?>" class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-400 mb-1">Coin Reward</label>
                        <input type="number" name="coin_reward" value="<?= $m['coin_reward'] ?>" class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2">
                    </div>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-[11px] font-bold text-slate-400 mb-1">Scenario Description</label>
                    <textarea name="scenario" rows="3" required class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2"><?= htmlspecialchars($m['scenario']) ?></textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-[11px] font-bold text-slate-400 mb-1">Instructions</label>
                    <textarea name="instructions" rows="2" required class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2"><?= htmlspecialchars($m['instructions']) ?></textarea>
                </div>

                <div class="md:col-span-2">
                    <button type="submit" class="bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold px-4 py-2 rounded-lg text-xs">
                        UPDATE MISSION DETAILS
                    </button>
                </div>
            </form>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>
