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
        <h3 class="text-lg font-bold text-white">+ ADD NEW DOWNLOADABLE RESOURCE</h3>
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

    <!-- EXISTING RESOURCES EDIT LIST -->
    <div class="space-y-6">
        <h3 class="text-xl font-bold text-white">EXISTING RESOURCES (EDIT & UPDATE)</h3>

        <?php foreach ($resources as $res): ?>
        <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl space-y-4">
            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                <div class="flex items-center gap-3">
                    <span class="bg-amber-500/20 text-amber-400 text-xs font-black px-2.5 py-1 rounded-full border border-amber-500/30">LVL <?= $res['level_number'] ?></span>
                    <h4 class="text-base font-bold text-white"><?= htmlspecialchars($res['title']) ?></h4>
                    <span class="text-xs font-bold <?= $res['is_premium'] ? 'text-amber-400' : 'text-emerald-400' ?>"><?= $res['is_premium'] ? '🔒 PRO' : 'FREE' ?></span>
                </div>
                <form action="/admin/resources/<?= $res['id'] ?>/delete" method="POST" onsubmit="return confirm('Delete this resource?');">
                    <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">
                    <button type="submit" class="bg-red-600/20 text-red-400 hover:bg-red-600 hover:text-white font-bold px-3 py-1 rounded text-xs transition">DELETE RESOURCE</button>
                </form>
            </div>

            <!-- EDIT RESOURCE FORM -->
            <form action="/admin/resources/<?= $res['id'] ?>/edit" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">

                <div>
                    <label class="block text-[11px] font-bold text-slate-400 mb-1">Resource Title</label>
                    <input type="text" name="title" required value="<?= htmlspecialchars($res['title']) ?>" class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-400 mb-1">Level Number</label>
                    <input type="number" name="level_number" value="<?= $res['level_number'] ?>" min="1" max="15" class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-400 mb-1">Type</label>
                    <select name="type" class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2">
                        <option value="pdf" <?= $res['type'] === 'pdf' ? 'selected' : '' ?>>PDF Playbook</option>
                        <option value="template" <?= $res['type'] === 'template' ? 'selected' : '' ?>>SOP / Doc Template</option>
                        <option value="script" <?= $res['type'] === 'script' ? 'selected' : '' ?>>Outreach Script</option>
                        <option value="manual" <?= $res['type'] === 'manual' ? 'selected' : '' ?>>Manual</option>
                        <option value="calculator" <?= $res['type'] === 'calculator' ? 'selected' : '' ?>>Excel / Calculator</option>
                        <option value="cheat_sheet" <?= $res['type'] === 'cheat_sheet' ? 'selected' : '' ?>>Cheat Sheet</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-400 mb-1">File URL / Path</label>
                    <input type="text" name="file_content_or_url" required value="<?= htmlspecialchars($res['file_content_or_url']) ?>" class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-[11px] font-bold text-slate-400 mb-1">Description</label>
                    <input type="text" name="description" required value="<?= htmlspecialchars($res['description']) ?>" class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2">
                </div>

                <div class="md:col-span-2 flex items-center gap-2">
                    <input type="checkbox" name="is_premium" value="1" id="res_prem_<?= $res['id'] ?>" <?= $res['is_premium'] ? 'checked' : '' ?> class="rounded border-slate-800">
                    <label for="res_prem_<?= $res['id'] ?>" class="text-xs text-amber-400 font-bold">Require Pro/Master Subscription (Premium)</label>
                </div>

                <div class="md:col-span-2">
                    <button type="submit" class="bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold px-4 py-2 rounded-lg text-xs">
                        UPDATE RESOURCE DETAILS
                    </button>
                </div>
            </form>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>
