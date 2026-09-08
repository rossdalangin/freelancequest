<?php require __DIR__ . '/../layout/header.php'; ?>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <div class="bg-slate-900 border border-slate-800 p-6 sm:p-8 rounded-2xl space-y-6 shadow-xl">
        <div class="border-b border-slate-800 pb-4">
            <span class="text-xs font-bold text-indigo-400 uppercase tracking-widest">LEVEL 5 CAREER ASSET</span>
            <h1 class="text-2xl font-extrabold text-white">INTERACTIVE RESUME BUILDER</h1>
            <p class="text-xs text-slate-400">Build an ATS-optimized professional resume to win real client projects.</p>
        </div>

        <form action="/resume-builder" method="POST" class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1">Full Name</label>
                <input type="text" name="full_name" value="<?= htmlspecialchars($resume['full_name'] ?? '') ?>" class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 text-sm text-slate-100">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1">Professional Headline / Title</label>
                <input type="text" name="professional_title" value="<?= htmlspecialchars($resume['professional_title'] ?? '') ?>" class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 text-sm text-slate-100">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1">Email</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($resume['email'] ?? '') ?>" class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 text-sm text-slate-100">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1">Phone</label>
                    <input type="text" name="phone" value="<?= htmlspecialchars($resume['phone'] ?? '') ?>" class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 text-sm text-slate-100">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1">Location</label>
                <input type="text" name="location" value="<?= htmlspecialchars($resume['location'] ?? '') ?>" class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 text-sm text-slate-100">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1">Professional Bio Summary</label>
                <textarea name="summary" rows="4" class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 text-sm text-slate-100"><?= htmlspecialchars($resume['summary'] ?? '') ?></textarea>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1">Key Skills (Comma Separated)</label>
                <input type="text" name="skills" value="<?= htmlspecialchars(implode(', ', $resume['skills'] ?? [])) ?>" class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 text-sm text-slate-100">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1">Tools & Platforms (Comma Separated)</label>
                <input type="text" name="tools" value="<?= htmlspecialchars(implode(', ', $resume['tools'] ?? [])) ?>" class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 text-sm text-slate-100">
            </div>
            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-extrabold py-3 px-4 rounded-xl text-sm transition shadow-lg shadow-indigo-600/30">SAVE & UPDATE RESUME (+200 XP)</button>
        </form>
    </div>

    <!-- Live Preview -->
    <div class="bg-white text-slate-900 p-8 rounded-2xl shadow-2xl space-y-6 font-sans">
        <div class="border-b-2 border-indigo-600 pb-4 flex justify-between items-start">
            <div>
                <h2 class="text-3xl font-black text-slate-900 uppercase tracking-tight"><?= htmlspecialchars($resume['full_name'] ?? 'Your Name') ?></h2>
                <p class="text-indigo-600 font-bold text-base mt-1"><?= htmlspecialchars($resume['professional_title'] ?? 'Virtual Assistant') ?></p>
            </div>
            <div class="text-right text-xs text-slate-600 space-y-0.5">
                <p><?= htmlspecialchars($resume['email'] ?? '') ?></p>
                <p><?= htmlspecialchars($resume['phone'] ?? '') ?></p>
                <p><?= htmlspecialchars($resume['location'] ?? '') ?></p>
            </div>
        </div>

        <div class="space-y-2">
            <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider border-b border-slate-200 pb-1">Professional Summary</h3>
            <p class="text-xs text-slate-700 leading-relaxed"><?= htmlspecialchars($resume['summary'] ?? '') ?></p>
        </div>

        <div class="space-y-2">
            <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider border-b border-slate-200 pb-1">Core Competencies</h3>
            <div class="flex flex-wrap gap-1.5">
                <?php foreach (($resume['skills'] ?? []) as $sk): ?>
                    <span class="bg-indigo-50 text-indigo-800 px-2.5 py-1 rounded text-xs font-semibold"><?= htmlspecialchars($sk) ?></span>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>
