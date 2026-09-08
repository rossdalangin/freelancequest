<?php require __DIR__ . '/../layout/header.php'; ?>
<div class="space-y-8">
    <div class="flex items-center justify-between bg-slate-900 border border-slate-800 p-6 rounded-2xl">
        <div>
            <span class="text-xs font-bold text-amber-400 uppercase tracking-wider">ADMIN CONTROL CENTER</span>
            <h1 class="text-2xl font-black text-white">LESSON CURRICULUM MANAGEMENT</h1>
        </div>
        <a href="/admin" class="text-xs text-indigo-400 font-bold hover:underline">&larr; Back to Dashboard</a>
    </div>

    <!-- CREATE LESSON FORM -->
    <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl space-y-4">
        <h3 class="text-lg font-bold text-white">+ CREATE NEW LESSON</h3>
        <form action="/admin/lessons/create" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">

            <div>
                <label class="block text-xs font-bold text-slate-400 mb-1">Course</label>
                <select name="course_id" class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2.5">
                    <?php foreach ($courses as $c): ?>
                    <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['title']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-400 mb-1">Level Number</label>
                <input type="number" name="level_number" value="1" min="1" max="15" class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2.5">
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-slate-400 mb-1">Lesson Title</label>
                <input type="text" name="title" required placeholder="Mastering Advanced Calendar Management" class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2.5">
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-slate-400 mb-1">Summary</label>
                <input type="text" name="summary" required placeholder="Step-by-step SOP for managing multi-time-zone executive calendars." class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2.5">
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-slate-400 mb-1">Lesson Content (Markdown)</label>
                <textarea name="content" rows="4" required class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2.5" placeholder="## Blueprint SOP..."></textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-400 mb-1">XP Reward</label>
                <input type="number" name="xp_reward" value="100" class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2.5">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-400 mb-1">Coin Reward</label>
                <input type="number" name="coin_reward" value="20" class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2.5">
            </div>

            <div class="md:col-span-2">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white font-black py-2.5 px-6 rounded-xl text-xs transition">
                    + PUBLISH LESSON
                </button>
            </div>
        </form>
    </div>

    <!-- EXISTING LESSONS EDIT LIST -->
    <div class="space-y-6">
        <h3 class="text-xl font-bold text-white">EXISTING LESSONS (EDIT & UPDATE)</h3>

        <?php foreach ($lessons as $les): ?>
        <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl space-y-4">
            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                <div class="flex items-center gap-3">
                    <span class="bg-amber-500/20 text-amber-400 text-xs font-black px-2.5 py-1 rounded-full border border-amber-500/30">LVL <?= $les['level_number'] ?></span>
                    <h4 class="text-base font-bold text-white"><?= htmlspecialchars($les['title']) ?></h4>
                </div>
                <form action="/admin/lessons/<?= $les['id'] ?>/delete" method="POST" onsubmit="return confirm('Delete this lesson?');">
                    <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">
                    <button type="submit" class="bg-red-600/20 text-red-400 hover:bg-red-600 hover:text-white font-bold px-3 py-1 rounded text-xs transition">DELETE LESSON</button>
                </form>
            </div>

            <!-- EDIT LESSON FORM -->
            <form action="/admin/lessons/<?= $les['id'] ?>/edit" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">

                <div>
                    <label class="block text-[11px] font-bold text-slate-400 mb-1">Lesson Title</label>
                    <input type="text" name="title" required value="<?= htmlspecialchars($les['title']) ?>" class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-400 mb-1">Course</label>
                    <select name="course_id" class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2">
                        <?php foreach ($courses as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= $les['course_id'] == $c['id'] ? 'selected' : '' ?>><?= htmlspecialchars($c['title']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-400 mb-1">Level Number</label>
                    <input type="number" name="level_number" value="<?= $les['level_number'] ?>" min="1" max="15" class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2">
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-400 mb-1">XP Reward</label>
                        <input type="number" name="xp_reward" value="<?= $les['xp_reward'] ?>" class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-400 mb-1">Coin Reward</label>
                        <input type="number" name="coin_reward" value="<?= $les['coin_reward'] ?>" class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2">
                    </div>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-[11px] font-bold text-slate-400 mb-1">Summary</label>
                    <input type="text" name="summary" required value="<?= htmlspecialchars($les['summary']) ?>" class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-[11px] font-bold text-slate-400 mb-1">Content (Markdown / SOP)</label>
                    <textarea name="content" rows="4" required class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2"><?= htmlspecialchars($les['content']) ?></textarea>
                </div>

                <div class="md:col-span-2">
                    <button type="submit" class="bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold px-4 py-2 rounded-lg text-xs">
                        UPDATE LESSON DETAILS
                    </button>
                </div>
            </form>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>
