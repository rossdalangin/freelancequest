<?php require __DIR__ . '/../layout/header.php'; ?>
<div class="space-y-8">
    <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl flex flex-col md:flex-row justify-between items-start md:items-center gap-4 shadow-xl">
        <div>
            <span class="text-xs font-bold text-amber-400 uppercase tracking-wider">FREELANCE MARKETPLACE</span>
            <h1 class="text-3xl font-black text-white">CLIENT OPPORTUNITIES & JOB BOARD</h1>
            <p class="text-slate-400 text-xs mt-1">Apply for real simulated client roles and build real client acquisition experience.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="/my-applications" class="bg-slate-800 hover:bg-slate-700 text-slate-100 font-bold px-4 py-2 rounded-xl text-xs border border-slate-700 transition">
                📋 My Applications
            </a>
            <a href="/jobs/create" class="bg-amber-500 hover:bg-amber-400 text-slate-950 font-black px-4 py-2 rounded-xl text-xs shadow-lg shadow-amber-500/20 transition">
                + POST A JOB
            </a>
        </div>
    </div>

    <!-- JOB CATEGORY FILTERS -->
    <div class="flex flex-wrap gap-2 text-xs font-bold">
        <a href="/jobs" class="bg-indigo-600 text-white px-3.5 py-1.5 rounded-xl">All Categories</a>
        <a href="/jobs?category=Executive Admin" class="bg-slate-900 hover:bg-slate-800 text-slate-300 px-3.5 py-1.5 rounded-xl border border-slate-800">Executive Admin</a>
        <a href="/jobs?category=Social Media" class="bg-slate-900 hover:bg-slate-800 text-slate-300 px-3.5 py-1.5 rounded-xl border border-slate-800">Social Media</a>
        <a href="/jobs?category=Lead Generation" class="bg-slate-900 hover:bg-slate-800 text-slate-300 px-3.5 py-1.5 rounded-xl border border-slate-800">Lead Generation</a>
        <a href="/jobs?category=Web & WordPress" class="bg-slate-900 hover:bg-slate-800 text-slate-300 px-3.5 py-1.5 rounded-xl border border-slate-800">WordPress & Web</a>
    </div>

    <!-- JOBS FEED -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <?php foreach ($jobs as $j): ?>
        <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl space-y-4 hover:border-indigo-500/50 transition shadow-lg flex flex-col justify-between">
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-black bg-indigo-500/20 text-indigo-400 px-2.5 py-1 rounded-full border border-indigo-500/30 uppercase">
                        <?= htmlspecialchars($j['category']) ?>
                    </span>
                    <span class="text-xs font-bold text-amber-400"><?= htmlspecialchars($j['budget']) ?></span>
                </div>

                <div>
                    <h3 class="text-lg font-black text-white hover:text-indigo-400 transition">
                        <a href="/jobs/<?= $j['id'] ?>"><?= htmlspecialchars($j['title']) ?></a>
                    </h3>
                    <p class="text-xs text-slate-400 font-bold"><?= htmlspecialchars($j['company']) ?> &bull; <?= htmlspecialchars($j['job_type']) ?></p>
                </div>

                <p class="text-xs text-slate-300 line-clamp-3 leading-relaxed"><?= htmlspecialchars($j['description']) ?></p>
            </div>

            <div class="pt-4 border-t border-slate-800/80 flex items-center justify-between">
                <span class="text-[11px] text-slate-500 font-bold"><?= $j['applicants_count'] ?? 0 ?> Applicants</span>
                <a href="/jobs/<?= $j['id'] ?>" class="bg-indigo-600 hover:bg-indigo-500 text-white font-extrabold px-4 py-2 rounded-xl text-xs shadow-md transition">
                    VIEW & APPLY &rarr;
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>
