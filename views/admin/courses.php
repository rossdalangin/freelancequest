<?php require __DIR__ . '/../layout/header.php'; ?>
<div class="space-y-8">
    <div class="flex items-center justify-between bg-slate-900 border border-slate-800 p-6 rounded-2xl">
        <div>
            <span class="text-xs font-bold text-amber-400 uppercase tracking-wider">ADMIN CONTROL CENTER</span>
            <h1 class="text-2xl font-black text-white">COURSE MANAGEMENT</h1>
        </div>
        <a href="/admin" class="text-xs text-indigo-400 font-bold hover:underline">&larr; Back to Dashboard</a>
    </div>

    <!-- CREATE COURSE -->
    <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl space-y-4">
        <h3 class="text-lg font-bold text-white">+ CREATE NEW COURSE</h3>
        <form action="/admin/courses/create" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">

            <div>
                <label class="block text-xs font-bold text-slate-400 mb-1">Course Title</label>
                <input type="text" name="title" required placeholder="Advanced Lead Generation & Prospecting" class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2.5">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-400 mb-1">Category</label>
                <select name="category" class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2.5">
                    <option value="Foundations">Foundations</option>
                    <option value="Tools">Tools & Software</option>
                    <option value="Admin">Admin Support</option>
                    <option value="Specialist">Specialist Tracks</option>
                    <option value="Business">Business & Sales</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-400 mb-1">Career Level Number</label>
                <input type="number" name="level_number" value="1" min="1" max="15" class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2.5">
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-slate-400 mb-1">Description</label>
                <input type="text" name="description" required placeholder="Comprehensive course covering lead extraction, verification, and CRM." class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2.5">
            </div>

            <div class="md:col-span-2">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white font-black py-2.5 px-6 rounded-xl text-xs transition">
                    + PUBLISH COURSE
                </button>
            </div>
        </form>
    </div>

    <!-- EXISTING COURSES LIST & EDIT -->
    <div class="space-y-6">
        <h3 class="text-xl font-bold text-white">EXISTING COURSES (EDIT & UPDATE)</h3>

        <?php foreach ($courses as $c): ?>
        <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl space-y-4">
            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                <div class="flex items-center gap-3">
                    <span class="bg-indigo-500/20 text-indigo-400 text-xs font-black px-2.5 py-1 rounded-full border border-indigo-500/30">LVL <?= $c['level_number'] ?></span>
                    <h4 class="text-base font-bold text-white"><?= htmlspecialchars($c['title']) ?></h4>
                </div>
                <form action="/admin/courses/<?= $c['id'] ?>/delete" method="POST" onsubmit="return confirm('Delete this course?');">
                    <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">
                    <button type="submit" class="bg-red-600/20 text-red-400 hover:bg-red-600 hover:text-white font-bold px-3 py-1 rounded text-xs transition">DELETE COURSE</button>
                </form>
            </div>

            <form action="/admin/courses/<?= $c['id'] ?>/edit" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">

                <div>
                    <label class="block text-[11px] font-bold text-slate-400 mb-1">Course Title</label>
                    <input type="text" name="title" value="<?= htmlspecialchars($c['title']) ?>" required class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-400 mb-1">Category</label>
                    <input type="text" name="category" value="<?= htmlspecialchars($c['category'] ?? 'General') ?>" required class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-400 mb-1">Level Number</label>
                    <input type="number" name="level_number" value="<?= $c['level_number'] ?>" min="1" max="15" class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-[11px] font-bold text-slate-400 mb-1">Description</label>
                    <input type="text" name="description" value="<?= htmlspecialchars($c['description'] ?? '') ?>" required class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2">
                </div>

                <div class="md:col-span-2">
                    <button type="submit" class="bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold px-4 py-2 rounded-lg text-xs">
                        UPDATE COURSE DETAILS
                    </button>
                </div>
            </form>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>
