<?php require __DIR__ . '/../layout/header.php'; ?>
<div class="space-y-8">
    <div class="flex items-center justify-between bg-slate-900 border border-slate-800 p-6 rounded-2xl">
        <div>
            <span class="text-xs font-bold text-amber-400 uppercase tracking-wider">ADMIN CONTROL CENTER</span>
            <h1 class="text-2xl font-black text-white">USER & MEMBERSHIP MANAGEMENT</h1>
        </div>
        <a href="/admin" class="text-xs text-indigo-400 font-bold hover:underline">&larr; Back to Dashboard</a>
    </div>

    <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden">
        <table class="w-full text-left text-xs text-slate-300">
            <thead class="bg-slate-950 text-slate-400 uppercase font-extrabold border-b border-slate-800">
                <tr>
                    <th class="p-4">User</th>
                    <th class="p-4">Email</th>
                    <th class="p-4">Level</th>
                    <th class="p-4">Plan Tier</th>
                    <th class="p-4">Role</th>
                    <th class="p-4">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800">
                <?php foreach ($users as $u): ?>
                <tr>
                    <td class="p-4 font-bold text-white"><?= htmlspecialchars($u['name']) ?></td>
                    <td class="p-4"><?= htmlspecialchars($u['email']) ?></td>
                    <td class="p-4 font-bold text-amber-400">LVL <?= $u['level'] ?></td>
                    <td class="p-4 uppercase font-extrabold text-indigo-400"><?= htmlspecialchars($u['subscription_tier'] ?? 'free') ?></td>
                    <td class="p-4 font-bold"><?= htmlspecialchars($u['role']) ?></td>
                    <td class="p-4">
                        <form action="/admin/users/<?= $u['id'] ?>/plan" method="POST" class="flex items-center gap-2">
                            <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">
                            <select name="subscription_tier" class="bg-slate-950 border border-slate-800 text-white rounded p-1 text-[11px]">
                                <option value="free" <?= $u['subscription_tier'] === 'free' ? 'selected' : '' ?>>Free</option>
                                <option value="pro" <?= $u['subscription_tier'] === 'pro' ? 'selected' : '' ?>>Pro</option>
                                <option value="master" <?= $u['subscription_tier'] === 'master' ? 'selected' : '' ?>>Master</option>
                            </select>
                            <select name="role" class="bg-slate-950 border border-slate-800 text-white rounded p-1 text-[11px]">
                                <option value="student" <?= $u['role'] === 'student' ? 'selected' : '' ?>>Student</option>
                                <option value="admin" <?= $u['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                            </select>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold px-2 py-1 rounded text-[10px]">SAVE</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>
