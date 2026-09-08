<?php require __DIR__ . '/../layout/header.php'; ?>
<div class="space-y-8">
    <div class="flex items-center justify-between bg-slate-900 border border-slate-800 p-6 rounded-2xl">
        <div>
            <span class="text-xs font-bold text-amber-400 uppercase tracking-wider">ADMIN CONTROL CENTER</span>
            <h1 class="text-2xl font-black text-white">RESOURCE VAULT MANAGEMENT</h1>
        </div>
        <a href="/admin" class="text-xs text-indigo-400 font-bold hover:underline">&larr; Back to Dashboard</a>
    </div>

    <!-- CREATE NEW RESOURCE -->
    <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl space-y-4">
        <h3 class="text-lg font-bold text-white">ADD NEW DOWNLOADABLE RESOURCE</h3>
        <form action="/admin/resources/create" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">

            <div>
                <label class="block text-xs font-bold text-slate-400 mb-1">Resource Title</label>
                <input type="text" name="title" required placeholder="Virtual Assistant Starter Playbook" class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2.5">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-400 mb-1">Career Level Number</label>
                <input type="number" name="level_number" value="1" min="1" max="15" class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2.5">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-400 mb-1">Type</label>
                <select name="type" class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2.5">
                    <option value="pdf">PDF Playbook</option>
                    <option value="template">SOP / Doc Template</option>
                    <option value="script">Outreach Script</option>
                    <option value="manual">Manual</option>
                    <option value="calculator">Excel / Calculator</option>
                    <option value="cheat_sheet">Cheat Sheet</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-400 mb-1">File URL or Path</label>
                <input type="text" name="file_content_or_url" required placeholder="/docs/PLATFORM_MANUAL.md" class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2.5">
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-slate-400 mb-1">Description</label>
                <input type="text" name="description" required placeholder="Complete beginner guide to setting up your VA career." class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2.5">
            </div>

            <div class="md:col-span-2 flex items-center gap-2">
                <input type="checkbox" name="is_premium" value="1" id="is_premium" class="rounded border-slate-800">
                <label for="is_premium" class="text-xs text-amber-400 font-bold">Require Pro/Master Subscription (Premium)</label>
            </div>

            <div class="md:col-span-2">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white font-black py-2.5 px-6 rounded-xl text-xs transition">
                    + PUBLISH RESOURCE
                </button>
            </div>
        </form>
    </div>

    <!-- EXISTING RESOURCES -->
    <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden">
        <table class="w-full text-left text-xs text-slate-300">
            <thead class="bg-slate-950 text-slate-400 uppercase font-extrabold border-b border-slate-800">
                <tr>
                    <th class="p-4">Level</th>
                    <th class="p-4">Title & Description</th>
                    <th class="p-4">Type</th>
                    <th class="p-4">Access</th>
                    <th class="p-4">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800">
                <?php foreach ($resources as $res): ?>
                <tr>
                    <td class="p-4 font-bold text-amber-400">LVL <?= $res['level_number'] ?></td>
                    <td class="p-4">
                        <p class="font-bold text-white"><?= htmlspecialchars($res['title']) ?></p>
                        <p class="text-[10px] text-slate-400"><?= htmlspecialchars($res['description']) ?></p>
                    </td>
                    <td class="p-4 uppercase font-bold text-indigo-400"><?= htmlspecialchars($res['type']) ?></td>
                    <td class="p-4 font-bold <?= $res['is_premium'] ? 'text-amber-400' : 'text-emerald-400' ?>">
                        <?= $res['is_premium'] ? '🔒 PRO' : 'FREE' ?>
                    </td>
                    <td class="p-4">
                        <form action="/admin/resources/<?= $res['id'] ?>/delete" method="POST" onsubmit="return confirm('Delete this resource?');">
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
