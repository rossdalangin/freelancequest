<?php require __DIR__ . '/../layout/header.php'; ?>
<div class="space-y-8">
    <div class="flex items-center justify-between bg-slate-900 border border-slate-800 p-6 rounded-2xl">
        <div>
            <span class="text-xs font-bold text-amber-400 uppercase tracking-wider">ADMIN CONTROL CENTER</span>
            <h1 class="text-2xl font-black text-white">PAYMENTS & REFUNDS MANAGEMENT</h1>
        </div>
        <a href="/admin" class="text-xs text-indigo-400 font-bold hover:underline">&larr; Back to Dashboard</a>
    </div>

    <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden">
        <table class="w-full text-left text-xs text-slate-300">
            <thead class="bg-slate-950 text-slate-400 uppercase font-extrabold border-b border-slate-800">
                <tr>
                    <th class="p-4">Txn ID</th>
                    <th class="p-4">Customer</th>
                    <th class="p-4">Gateway</th>
                    <th class="p-4">Amount</th>
                    <th class="p-4">Status</th>
                    <th class="p-4">Date</th>
                    <th class="p-4">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800">
                <?php foreach ($payments as $p): ?>
                <tr>
                    <td class="p-4 font-mono text-indigo-400"><?= htmlspecialchars($p['transaction_id']) ?></td>
                    <td class="p-4 font-bold text-white"><?= htmlspecialchars($p['user_name']) ?> (<?= htmlspecialchars($p['user_email']) ?>)</td>
                    <td class="p-4 uppercase font-bold"><?= htmlspecialchars($p['payment_gateway']) ?></td>
                    <td class="p-4 font-bold text-emerald-400">$<?= number_format($p['amount'], 2) ?></td>
                    <td class="p-4 uppercase font-extrabold <?= $p['status'] === 'refunded' ? 'text-red-400' : 'text-emerald-400' ?>"><?= htmlspecialchars($p['status']) ?></td>
                    <td class="p-4 text-slate-400"><?= htmlspecialchars($p['created_at']) ?></td>
                    <td class="p-4">
                        <?php if ($p['status'] !== 'refunded'): ?>
                        <form action="/admin/payments/<?= $p['id'] ?>/refund" method="POST" onsubmit="return confirm('Are you sure you want to refund this transaction?');">
                            <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">
                            <button type="submit" class="bg-red-600/20 text-red-400 border border-red-500/30 hover:bg-red-600 hover:text-white font-bold px-3 py-1 rounded text-[10px] transition">REFUND</button>
                        </form>
                        <?php else: ?>
                        <span class="text-[10px] text-slate-500 font-bold">REFUNDED</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>
