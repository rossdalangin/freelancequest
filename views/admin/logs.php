<?php require __DIR__ . '/../layout/header.php'; ?>
<div class="space-y-8">
    <div class="flex items-center justify-between bg-slate-900 border border-slate-800 p-6 rounded-2xl">
        <div>
            <span class="text-xs font-bold text-amber-400 uppercase tracking-wider">ADMIN CONTROL CENTER</span>
            <h1 class="text-2xl font-black text-white">SYSTEM AUDIT LOGS & HISTORY</h1>
        </div>
        <a href="/admin" class="text-xs text-indigo-400 font-bold hover:underline">&larr; Back to Dashboard</a>
    </div>

    <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden">
        <table class="w-full text-left text-xs text-slate-300">
            <thead class="bg-slate-950 text-slate-400 uppercase font-extrabold border-b border-slate-800">
                <tr>
                    <th class="p-4">Timestamp</th>
                    <th class="p-4">User ID</th>
                    <th class="p-4">Action</th>
                    <th class="p-4">Details</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800 font-mono">
                <?php foreach ($auditLogs as $log): ?>
                <tr>
                    <td class="p-4 text-slate-400"><?= htmlspecialchars($log['created_at']) ?></td>
                    <td class="p-4 text-amber-400">User #<?= htmlspecialchars($log['user_id'] ?? '0') ?></td>
                    <td class="p-4 font-bold text-indigo-400"><?= htmlspecialchars($log['action']) ?></td>
                    <td class="p-4 text-slate-300"><?= htmlspecialchars($log['details']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>
