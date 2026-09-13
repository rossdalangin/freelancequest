<?php require __DIR__ . '/../layout/header.php'; ?>
<div class="space-y-8">
    <div class="flex items-center justify-between bg-slate-900 border border-slate-800 p-6 rounded-2xl shadow-xl">
        <div>
            <span class="text-xs font-bold text-amber-400 uppercase tracking-wider">ADMIN CONTROL CENTER</span>
            <h1 class="text-2xl font-black text-white">USER & MEMBERSHIP MANAGEMENT</h1>
        </div>
        <a href="/admin" class="text-xs text-indigo-400 font-bold hover:underline">&larr; Back to Dashboard</a>
    </div>

    <?php if (!empty($success)): ?>
        <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs p-4 rounded-xl font-bold">
            <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="bg-red-500/10 border border-red-500/30 text-red-400 text-xs p-4 rounded-xl font-bold">
            ⚠️ <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <!-- ADD NEW USER / LEARNER FORM CARD -->
    <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl space-y-4 shadow-xl">
        <div class="flex items-center gap-2 border-b border-slate-800 pb-3">
            <span class="text-xl">👤</span>
            <h2 class="text-lg font-extrabold text-white">ADD NEW USER / LEARNER ACCOUNT</h2>
        </div>

        <form action="/admin/users/create" method="POST" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Full Name <span class="text-red-400">*</span></label>
                <input type="text" name="name" required placeholder="John Doe" class="w-full bg-slate-950 border border-slate-800 rounded-xl p-2.5 text-xs text-white">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Email Address <span class="text-red-400">*</span></label>
                <input type="email" name="email" required placeholder="john@example.com" class="w-full bg-slate-950 border border-slate-800 rounded-xl p-2.5 text-xs text-white">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Username</label>
                <input type="text" name="username" placeholder="johndoe" class="w-full bg-slate-950 border border-slate-800 rounded-xl p-2.5 text-xs text-white">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Password <span class="text-red-400">*</span></label>
                <input type="password" name="password" required placeholder="••••••••" class="w-full bg-slate-950 border border-slate-800 rounded-xl p-2.5 text-xs text-white">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Role</label>
                <select name="role" class="w-full bg-slate-950 border border-slate-800 rounded-xl p-2.5 text-xs text-white">
                    <option value="student">Student / Learner</option>
                    <option value="admin">System Admin</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Subscription Plan Tier</label>
                <select name="subscription_tier" class="w-full bg-slate-950 border border-slate-800 rounded-xl p-2.5 text-xs text-white">
                    <option value="free">Free Starter</option>
                    <option value="pro">Pro Freelancer</option>
                    <option value="master">Master Agency</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Career Starting Level</label>
                <input type="number" name="level" min="0" max="15" value="0" class="w-full bg-slate-950 border border-slate-800 rounded-xl p-2.5 text-xs text-white">
            </div>

            <div class="sm:col-span-2 lg:col-span-2 flex items-end">
                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-extrabold py-2.5 px-6 rounded-xl text-xs transition shadow-md">
                    + CREATE USER ACCOUNT &rarr;
                </button>
            </div>
        </form>
    </div>

    <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
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
