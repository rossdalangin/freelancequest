<?php require __DIR__ . '/../layout/header.php'; ?>
<div class="space-y-8 py-4">
    <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl flex flex-col md:flex-row justify-between items-start md:items-center gap-4 shadow-xl">
        <div>
            <span class="text-xs font-bold text-amber-400 uppercase tracking-wider">CAREER PIPELINE ANALYTICS</span>
            <h1 class="text-3xl font-black text-white">APPLICATION TRACKER DASHBOARD</h1>
            <p class="text-slate-400 text-xs mt-1">Track job search applications, interview conversion rates, and client offers.</p>
        </div>
        <span class="bg-indigo-500/20 text-indigo-400 font-extrabold text-xs px-3.5 py-1.5 rounded-full border border-indigo-500/30">
            RESPONSE RATE: <?= $responseRate ?>%
        </span>
    </div>

    <!-- PIPELINE ANALYTICS METRICS -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-slate-900 border border-slate-800 p-5 rounded-2xl text-center space-y-1 shadow-lg">
            <div class="text-3xl font-black text-indigo-400"><?= $totalApps ?></div>
            <div class="text-[11px] font-bold text-slate-400 uppercase">Applications Sent</div>
        </div>
        <div class="bg-slate-900 border border-slate-800 p-5 rounded-2xl text-center space-y-1 shadow-lg">
            <div class="text-3xl font-black text-amber-400"><?= $interviews ?></div>
            <div class="text-[11px] font-bold text-slate-400 uppercase">Interviews Scheduled</div>
        </div>
        <div class="bg-slate-900 border border-slate-800 p-5 rounded-2xl text-center space-y-1 shadow-lg">
            <div class="text-3xl font-black text-purple-400"><?= $offers ?></div>
            <div class="text-[11px] font-bold text-slate-400 uppercase">Offers Received</div>
        </div>
        <div class="bg-slate-900 border border-slate-800 p-5 rounded-2xl text-center space-y-1 shadow-lg">
            <div class="text-3xl font-black text-emerald-400"><?= $hired ?></div>
            <div class="text-[11px] font-bold text-slate-400 uppercase">Clients Hired</div>
        </div>
    </div>

    <!-- ADD NEW APPLICATION FORM -->
    <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl space-y-4 shadow-xl">
        <h3 class="text-lg font-bold text-white">+ TRACK NEW APPLICATION</h3>
        <form action="/application-tracker/add" method="POST" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">

            <div>
                <label class="block text-xs font-bold text-slate-400 mb-1">Company / Prospect</label>
                <input type="text" name="company" required placeholder="Acme Startups Inc" class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2.5">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-400 mb-1">Position / Role Title</label>
                <input type="text" name="position" required placeholder="Executive Virtual Assistant" class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2.5">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-400 mb-1">Current Status</label>
                <select name="status" class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2.5">
                    <option value="Saved">Saved</option>
                    <option value="Applied" selected>Applied</option>
                    <option value="Viewed">Viewed</option>
                    <option value="Interview">Interview</option>
                    <option value="Offer">Offer</option>
                    <option value="Hired">Hired</option>
                    <option value="Rejected">Rejected</option>
                </select>
            </div>

            <div class="sm:col-span-2">
                <input type="text" name="notes" placeholder="Notes (e.g. Follow up on Thursday)..." class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2.5">
            </div>

            <div>
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-extrabold py-2.5 rounded-lg text-xs transition shadow-md">
                    + ADD TO PIPELINE
                </button>
            </div>
        </form>
    </div>

    <!-- TRACKER PIPELINE TABLE -->
    <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
        <table class="w-full text-left text-xs text-slate-300">
            <thead class="bg-slate-950 text-slate-400 uppercase font-extrabold border-b border-slate-800">
                <tr>
                    <th class="p-4">Company</th>
                    <th class="p-4">Position</th>
                    <th class="p-4">Applied Date</th>
                    <th class="p-4">Status</th>
                    <th class="p-4">Notes</th>
                    <th class="p-4">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800">
                <?php if (empty($apps)): ?>
                <tr>
                    <td colspan="6" class="p-8 text-center text-slate-400">
                        No applications tracked yet. Start applying on the <a href="/jobs" class="text-amber-400 font-bold hover:underline">Job Board</a> or track direct client outreach above!
                    </td>
                </tr>
                <?php else: ?>
                    <?php foreach ($apps as $a): ?>
                    <tr>
                        <td class="p-4 font-bold text-white"><?= htmlspecialchars($a['company']) ?></td>
                        <td class="p-4 text-slate-300"><?= htmlspecialchars($a['position']) ?></td>
                        <td class="p-4 text-slate-400"><?= htmlspecialchars($a['applied_date']) ?></td>
                        <td class="p-4">
                            <form action="/application-tracker/<?= $a['id'] ?>/status" method="POST" class="inline">
                                <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">
                                <select name="status" onchange="this.form.submit();" class="bg-slate-950 border border-slate-800 text-[11px] font-bold rounded p-1 text-amber-400 uppercase focus:outline-none">
                                    <option value="Saved" <?= $a['status'] === 'Saved' ? 'selected' : '' ?>>Saved</option>
                                    <option value="Applied" <?= $a['status'] === 'Applied' ? 'selected' : '' ?>>Applied</option>
                                    <option value="Viewed" <?= $a['status'] === 'Viewed' ? 'selected' : '' ?>>Viewed</option>
                                    <option value="Interview" <?= $a['status'] === 'Interview' ? 'selected' : '' ?>>Interview</option>
                                    <option value="Offer" <?= $a['status'] === 'Offer' ? 'selected' : '' ?>>Offer</option>
                                    <option value="Hired" <?= $a['status'] === 'Hired' ? 'selected' : '' ?>>Hired</option>
                                    <option value="Rejected" <?= $a['status'] === 'Rejected' ? 'selected' : '' ?>>Rejected</option>
                                </select>
                            </form>
                        </td>
                        <td class="p-4 text-slate-400"><?= htmlspecialchars($a['notes'] ?? '-') ?></td>
                        <td class="p-4">
                            <form action="/application-tracker/<?= $a['id'] ?>/delete" method="POST" class="inline">
                                <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">
                                <button type="submit" class="text-red-400 hover:underline font-bold text-[10px]">Remove</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>
