<?php require __DIR__ . '/../layout/header.php'; ?>
<div class="space-y-6">
    <div class="flex items-center justify-between bg-slate-900 border border-slate-800 p-6 rounded-2xl">
        <div>
            <span class="text-xs font-bold text-amber-400 uppercase tracking-widest">ADMINISTRATION</span>
            <h1 class="text-2xl font-extrabold text-white">CERTIFICATE MANAGEMENT</h1>
            <p class="text-xs text-slate-400">View, verify, and revoke issued level completion certificates.</p>
        </div>
        <a href="/admin" class="text-xs text-indigo-400 font-bold hover:underline">&larr; Back to Dashboard</a>
    </div>

    <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-300">
                <thead class="bg-slate-950 text-slate-400 uppercase text-[10px] tracking-wider border-b border-slate-800">
                    <tr>
                        <th class="p-4">ID / CODE</th>
                        <th class="p-4">STUDENT</th>
                        <th class="p-4">CERTIFICATE TITLE</th>
                        <th class="p-4">LEVEL</th>
                        <th class="p-4">ISSUED AT</th>
                        <th class="p-4 text-right">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    <?php foreach (($certificates ?? []) as $c): ?>
                    <tr class="hover:bg-slate-950/50 transition">
                        <td class="p-4 font-mono font-bold text-amber-400"><?= htmlspecialchars($c['certificate_code'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td class="p-4 font-bold text-white"><?= htmlspecialchars($c['user_name'], ENT_QUOTES, 'UTF-8') ?><br><span class="text-[10px] text-slate-400 font-normal"><?= htmlspecialchars($c['user_email'], ENT_QUOTES, 'UTF-8') ?></span></td>
                        <td class="p-4 font-semibold text-slate-200"><?= htmlspecialchars($c['title'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td class="p-4"><span class="bg-indigo-500/20 text-indigo-400 font-bold px-2 py-1 rounded border border-indigo-500/30">Lvl <?= $c['level_number'] ?></span></td>
                        <td class="p-4 text-slate-400"><?= htmlspecialchars($c['issued_at'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td class="p-4 text-right space-x-2">
                            <a href="/verify/<?= htmlspecialchars($c['certificate_code'], ENT_QUOTES, 'UTF-8') ?>" target="_blank" class="bg-indigo-600 hover:bg-indigo-500 text-white font-extrabold px-3 py-1.5 rounded-lg transition text-[10px]">VIEW &rarr;</a>
                            <form action="/admin/certificates/<?= $c['id'] ?>/delete" method="POST" class="inline" onsubmit="return confirm('Revoke certificate <?= htmlspecialchars($c['certificate_code'], ENT_QUOTES, 'UTF-8') ?>?');">
                                <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">
                                <button type="submit" class="bg-red-500/20 text-red-400 border border-red-500/30 hover:bg-red-500/30 font-bold px-3 py-1.5 rounded-lg transition text-[10px]">REVOKE</button>
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
