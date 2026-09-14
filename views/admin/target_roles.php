<?php require __DIR__ . '/../layout/header.php'; ?>
<div class="space-y-8 py-6">
    <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <span class="text-xs font-bold text-indigo-400 uppercase tracking-wider">ADMIN CONTROL CENTER</span>
            <h1 class="text-3xl font-extrabold text-white">TARGET CAREER ROLES MANAGEMENT</h1>
            <p class="text-slate-400 text-sm">Add, toggle, and manage target virtual assistant and freelancing career roles for registration and onboarding.</p>
        </div>
        <a href="/admin" class="bg-slate-800 hover:bg-slate-700 text-white font-bold px-4 py-2 rounded-xl text-xs transition">
            &larr; Back to Admin Dashboard
        </a>
    </div>

    <?php if (!empty($_SESSION['flash_success'])): ?>
        <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs p-4 rounded-xl font-bold">
            ✓ <?= htmlspecialchars($_SESSION['flash_success'], ENT_QUOTES, 'UTF-8') ?>
        </div>
        <?php unset($_SESSION['flash_success']); ?>
    <?php endif; ?>

    <!-- Add New Target Role Form -->
    <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl space-y-4 shadow-xl">
        <h2 class="text-xl font-extrabold text-white flex items-center gap-2">
            <span>➕</span> Add New Target Career Role
        </h2>
        <form action="/admin/target-roles/store" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">
            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase mb-2">Role Name</label>
                <input type="text" name="name" required placeholder="e.g. Lead Generation Specialist" class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-xs text-white focus:outline-none focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase mb-2">Category</label>
                <input type="text" name="category" required placeholder="e.g. Sales / Marketing / Tech" class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-xs text-white focus:outline-none focus:border-indigo-500">
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-slate-300 uppercase mb-2">Description</label>
                <textarea name="description" rows="2" placeholder="Brief summary of skills and responsibilities..." class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-xs text-white focus:outline-none focus:border-indigo-500"></textarea>
            </div>
            <div class="md:col-span-2">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white font-extrabold py-3 px-6 rounded-xl text-xs shadow-lg transition">
                    + ADD TARGET ROLE &rarr;
                </button>
            </div>
        </form>
    </div>

    <!-- Active Target Roles List -->
    <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
        <div class="p-6 border-b border-slate-800 flex justify-between items-center">
            <h2 class="text-xl font-extrabold text-white">Active Target Roles (<?= count($targetRoles) ?>)</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-950 border-b border-slate-800 text-slate-400 font-bold uppercase tracking-wider">
                        <th class="p-4">ID</th>
                        <th class="p-4">Role Name</th>
                        <th class="p-4">Category</th>
                        <th class="p-4">Description</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800 text-slate-300 font-medium">
                    <?php foreach ($targetRoles as $role): ?>
                        <tr class="hover:bg-slate-800/50 transition">
                            <td class="p-4 font-mono text-slate-500">#<?= $role['id'] ?></td>
                            <td class="p-4 font-bold text-white"><?= htmlspecialchars($role['name'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td class="p-4"><span class="bg-indigo-500/10 text-indigo-400 px-2.5 py-1 rounded-full text-[10px] font-bold border border-indigo-500/20"><?= htmlspecialchars($role['category'], ENT_QUOTES, 'UTF-8') ?></span></td>
                            <td class="p-4 text-slate-400 max-w-xs truncate"><?= htmlspecialchars($role['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                            <td class="p-4">
                                <?php if ($role['is_active']): ?>
                                    <span class="bg-emerald-500/10 text-emerald-400 px-2.5 py-1 rounded-full text-[10px] font-bold border border-emerald-500/20">Active</span>
                                <?php else: ?>
                                    <span class="bg-rose-500/10 text-rose-400 px-2.5 py-1 rounded-full text-[10px] font-bold border border-rose-500/20">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td class="p-4 text-right">
                                <form action="/admin/target-roles/delete/<?= $role['id'] ?>" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this target role?');">
                                    <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">
                                    <button type="submit" class="bg-rose-600/20 hover:bg-rose-600 text-rose-300 hover:text-white font-bold px-3 py-1.5 rounded-lg text-[11px] border border-rose-500/30 transition">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>
