<?php require __DIR__ . '/../layout/header.php'; ?>
<div class="max-w-4xl mx-auto space-y-8 py-4">
    <div class="flex items-center justify-between text-xs font-bold">
        <a href="/jobs" class="text-indigo-400 hover:underline flex items-center gap-1">&larr; Back to Job Board</a>
        <span class="bg-indigo-500/20 text-indigo-400 px-3 py-1 rounded-full border border-indigo-500/30">
            <?= htmlspecialchars($job['job_type']) ?> &bull; <?= htmlspecialchars($job['category']) ?>
        </span>
    </div>

    <?php if (!empty($success)): ?>
        <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs p-4 rounded-xl font-bold">
            ✓ <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="bg-red-500/10 border border-red-500/30 text-red-400 text-xs p-4 rounded-xl font-bold">
            ⚠️ <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <!-- JOB DESCRIPTION HEADER -->
    <div class="bg-slate-900 border border-slate-800 p-8 rounded-2xl space-y-6 shadow-xl">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-800 pb-6">
            <div>
                <h1 class="text-3xl font-black text-white"><?= htmlspecialchars($job['title']) ?></h1>
                <p class="text-slate-400 text-sm font-bold mt-1"><?= htmlspecialchars($job['company']) ?> &bull; <?= htmlspecialchars($job['category']) ?></p>
            </div>
            <div class="text-left sm:text-right">
                <div class="text-2xl font-black text-amber-400"><?= htmlspecialchars($job['budget']) ?></div>
                <span class="text-[11px] text-slate-500 font-bold"><?= $job['applicants_count'] ?? 0 ?> Applicants</span>
            </div>
        </div>

        <div class="space-y-4 text-sm text-slate-300 leading-relaxed">
            <h3 class="text-base font-bold text-white uppercase tracking-wider">Role Description</h3>
            <p><?= nl2br(htmlspecialchars($job['description'])) ?></p>

            <?php if (!empty($job['requirements'])): ?>
            <h3 class="text-base font-bold text-white uppercase tracking-wider pt-4">Requirements & Expectations</h3>
            <p class="bg-slate-950 p-4 rounded-xl border border-slate-800 font-mono text-xs text-slate-300 whitespace-pre-line"><?= htmlspecialchars($job['requirements']) ?></p>
            <?php endif; ?>
        </div>
    </div>

    <!-- PROMINENT APPLICATION FORM: "APPLY FOR THIS ROLE" -->
    <div id="apply" class="bg-gradient-to-br from-slate-900 via-indigo-950/40 to-slate-900 border-2 border-indigo-500/50 p-8 rounded-2xl space-y-6 shadow-2xl">
        <div class="flex items-center gap-3 border-b border-slate-800 pb-4">
            <span class="text-3xl">✍️</span>
            <div>
                <h2 class="text-2xl font-black text-white uppercase tracking-wide">APPLY FOR THIS ROLE</h2>
                <p class="text-xs text-slate-400">Submit your tailored proposal, proposed rate, and portfolio link to the client.</p>
            </div>
        </div>

        <?php if (!empty($existingApplication)): ?>
            <div class="bg-emerald-500/10 border border-emerald-500/30 p-6 rounded-xl space-y-2">
                <span class="text-xs font-black text-emerald-400 uppercase tracking-wider">✓ APPLICATION SUBMITTED</span>
                <p class="text-xs text-slate-300">You submitted an application for this role on <?= htmlspecialchars($existingApplication['created_at']) ?>.</p>
                <div class="pt-2 text-xs text-slate-400">
                    <p><strong>Proposed Rate:</strong> <?= htmlspecialchars($existingApplication['proposed_rate']) ?></p>
                    <p><strong>Status:</strong> <span class="text-amber-400 uppercase font-bold"><?= htmlspecialchars($existingApplication['status']) ?></span></p>
                </div>
            </div>
        <?php else: ?>
            <form action="/jobs/<?= $job['id'] ?>/apply" method="POST" class="space-y-6">
                <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">

                <div>
                    <label class="block text-xs font-extrabold text-slate-300 uppercase tracking-wider mb-2">
                        Cover Letter / Proposal Pitch <span class="text-red-400">*</span>
                    </label>
                    <p class="text-[11px] text-slate-400 mb-2">Pro-Tip: Address the client by name, state your relevant experience, and propose clear value!</p>
                    <textarea name="cover_letter" rows="6" required placeholder="Hi {{Company_Name}} Team,&#10;&#10;I read your job posting for {{Role_Title}} with great interest. I am an experienced Virtual Assistant specializing in handling executive operations..." class="w-full bg-slate-950 border border-slate-800 rounded-xl p-4 text-xs text-white focus:outline-none focus:border-indigo-500 transition leading-relaxed"></textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-extrabold text-slate-300 uppercase tracking-wider mb-2">
                            Proposed Rate ($/hr or Fixed) <span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="proposed_rate" required placeholder="$20.00 / hour" value="$20.00 / hr" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-xs text-white focus:outline-none focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-xs font-extrabold text-slate-300 uppercase tracking-wider mb-2">
                            Public Portfolio Showcase URL
                        </label>
                        <input type="text" name="portfolio_url" value="/p/<?= htmlspecialchars($user['username'] ?? $user['id']) ?>" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-xs text-white focus:outline-none focus:border-indigo-500">
                    </div>
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-400 hover:to-orange-400 text-slate-950 font-black py-4 rounded-xl text-sm transition shadow-xl shadow-amber-500/20 transform hover:-translate-y-0.5">
                    SUBMIT APPLICATION NOW (+150 XP) &rarr;
                </button>
            </form>
        <?php endif; ?>
    </div>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>
