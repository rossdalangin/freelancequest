<?php require __DIR__ . '/../layout/header.php'; ?>
<div class="max-w-3xl mx-auto space-y-8 py-4">
    <div class="flex items-center justify-between">
        <div>
            <span class="text-xs font-bold text-amber-400 uppercase tracking-wider">EMPLOYER PORTAL</span>
            <h1 class="text-2xl font-black text-white">POST A NEW CLIENT OPPORTUNITY</h1>
        </div>
        <a href="/jobs" class="text-xs text-indigo-400 font-bold hover:underline">&larr; Back to Job Board</a>
    </div>

    <?php if (!empty($_SESSION['job_create_error'])): ?>
        <div class="bg-red-500/10 border border-red-500/30 text-red-400 text-xs p-4 rounded-xl font-bold">
            ⚠️ <?= htmlspecialchars($_SESSION['job_create_error'], ENT_QUOTES, 'UTF-8') ?>
            <?php unset($_SESSION['job_create_error']); ?>
        </div>
    <?php endif; ?>

    <div class="bg-slate-900 border border-slate-800 p-8 rounded-2xl space-y-6 shadow-xl">
        <form action="/jobs/create" method="POST" class="space-y-4">
            <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Job / Role Title</label>
                <input type="text" name="title" required placeholder="Executive Virtual Assistant for SaaS Founder" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-xs text-white focus:outline-none focus:border-indigo-500">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Company / Client Name</label>
                    <input type="text" name="company" required placeholder="TechFlow Agency" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-xs text-white focus:outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Category</label>
                    <select name="category" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-xs text-white focus:outline-none focus:border-indigo-500">
                        <option value="Executive Admin">Executive Admin</option>
                        <option value="Social Media">Social Media</option>
                        <option value="Lead Generation">Lead Generation</option>
                        <option value="Web & WordPress">Web & WordPress</option>
                        <option value="Customer Support">Customer Support</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Budget / Hourly Rate</label>
                    <input type="text" name="budget" value="$18 - $25/hour" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-xs text-white focus:outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Job Type</label>
                    <select name="job_type" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-xs text-white focus:outline-none focus:border-indigo-500">
                        <option value="Part-Time (15-20 hrs/week)">Part-Time (15-20 hrs/wk)</option>
                        <option value="Full-Time (30-40 hrs/week)">Full-Time (30-40 hrs/wk)</option>
                        <option value="Project-Based">Project-Based</option>
                        <option value="Monthly Retainer">Monthly Retainer</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Detailed Role Description</label>
                <textarea name="description" rows="5" required placeholder="Describe responsibilities, team context, and expected deliverables..." class="w-full bg-slate-950 border border-slate-800 rounded-xl p-4 text-xs text-white focus:outline-none focus:border-indigo-500"></textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Requirements & Skill Expectations</label>
                <textarea name="requirements" rows="3" placeholder="• Proficiency with Google Workspace&#10;• Experience with Asana and Slack..." class="w-full bg-slate-950 border border-slate-800 rounded-xl p-4 text-xs text-white focus:outline-none focus:border-indigo-500"></textarea>
            </div>

            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-extrabold py-3.5 rounded-xl text-xs transition shadow-lg">
                PUBLISH JOB POSTING &rarr;
            </button>
        </form>
    </div>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>
