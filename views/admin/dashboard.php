<?php require __DIR__ . '/../layout/header.php'; ?>
<div class="space-y-8">
    <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl">
        <h1 class="text-3xl font-extrabold text-white">ADMIN CONTROL CENTER</h1>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-slate-900 border border-slate-800 p-5 rounded-2xl">
            <span class="text-xs font-bold text-slate-400">Total Learners</span>
            <div class="text-3xl font-black text-indigo-400"><?= $usersCount ?></div>
        </div>
        <div class="bg-slate-900 border border-slate-800 p-5 rounded-2xl">
            <span class="text-xs font-bold text-slate-400">Lessons</span>
            <div class="text-3xl font-black text-emerald-400"><?= $lessonsCount ?></div>
        </div>
        <div class="bg-slate-900 border border-slate-800 p-5 rounded-2xl">
            <span class="text-xs font-bold text-slate-400">Missions</span>
            <div class="text-3xl font-black text-purple-400"><?= $missionsCount ?></div>
        </div>
        <div class="bg-slate-900 border border-slate-800 p-5 rounded-2xl">
            <span class="text-xs font-bold text-slate-400">Quizzes</span>
            <div class="text-3xl font-black text-amber-400"><?= $quizzesCount ?></div>
        </div>
    </div>

    <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl space-y-4">
        <h2 class="text-xl font-extrabold text-white">AI COURSE BUILDER</h2>
        <form action="/admin/ai-course-builder" method="POST" class="space-y-4">
            <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">
            <input type="text" name="topic" required placeholder="Topic..." class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-sm text-slate-100">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white font-extrabold py-3 px-6 rounded-xl text-sm">GENERATE MODULE &rarr;</button>
        </form>
    </div>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>
