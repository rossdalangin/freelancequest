<?php require __DIR__ . '/../layout/header.php'; ?>
<div class="space-y-8">
    <div class="flex items-center justify-between bg-slate-900 border border-slate-800 p-6 rounded-2xl shadow-xl">
        <div>
            <span class="text-xs font-bold text-amber-400 uppercase tracking-wider">APPLICANT TRACKING DASHBOARD</span>
            <h1 class="text-3xl font-black text-white">MY SUBMITTED APPLICATIONS</h1>
            <p class="text-slate-400 text-xs mt-1">Track the status of your client proposals and job applications.</p>
        </div>
        <a href="/jobs" class="bg-indigo-600 hover:bg-indigo-500 text-white font-extrabold px-4 py-2 rounded-xl text-xs transition">
            + BROWSE MORE JOBS
        </a>
    </div>

    <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
        <table class="w-full text-left text-xs text-slate-300">
            <thead class="bg-slate-950 text-slate-400 uppercase font-extrabold border-b border-slate-800">
                <tr>
                    <th class="p-4">Job Title</th>
                    <th class="p-4">Company</th>
                    <th class="p-4">Proposed Rate</th>
                    <th class="p-4">Status</th>
                    <th class="p-4">Date Applied</th>
                    <th class="p-4">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800">
                <?php if (empty($applications)): ?>
                <tr>
                    <td colspan="6" class="p-8 text-center text-slate-400">
                        You have not submitted any job applications yet. <a href="/jobs" class="text-amber-400 font-bold hover:underline">Explore the Job Board &rarr;</a>
                    </td>
                </tr>
                <?php else: ?>
                    <?php foreach ($applications as $app): ?>
                    <tr>
                        <td class="p-4 font-bold text-white"><?= htmlspecialchars($app['job_title']) ?></td>
                        <td class="p-4 text-slate-300"><?= htmlspecialchars($app['job_company']) ?></td>
                        <td class="p-4 font-bold text-emerald-400"><?= htmlspecialchars($app['proposed_rate']) ?></td>
                        <td class="p-4 font-extrabold text-amber-400 uppercase"><?= htmlspecialchars($app['status']) ?></td>
                        <td class="p-4 text-slate-400"><?= htmlspecialchars($app['created_at']) ?></td>
                        <td class="p-4">
                            <a href="/jobs/<?= $app['job_id'] ?>" class="text-indigo-400 hover:underline font-bold">View Job &rarr;</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>
