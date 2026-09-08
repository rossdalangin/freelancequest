<?php require __DIR__ . '/../layout/header.php'; ?>
<div class="space-y-8">
    <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl flex justify-between items-center">
        <h1 class="text-3xl font-extrabold text-white">COMMUNITY FORUM</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl space-y-4">
                <h3 class="font-extrabold text-white text-base">Start a Discussion</h3>
                <form action="/community/post" method="POST" class="space-y-4">
                    <input type="text" name="title" required placeholder="Title..." class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-sm text-slate-100">
                    <textarea name="content" rows="3" required placeholder="Message..." class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-sm text-slate-100"></textarea>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white font-extrabold px-5 py-2.5 rounded-xl text-xs">POST (+50 XP)</button>
                </form>
            </div>

            <?php foreach ($posts as $post): ?>
            <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl space-y-4">
                <div class="flex justify-between items-center text-xs">
                    <span class="font-bold text-indigo-400"><?= htmlspecialchars($post['user_name'] ?? 'Member') ?></span>
                </div>
                <h3 class="font-extrabold text-white text-lg"><?= htmlspecialchars($post['title']) ?></h3>
                <p class="text-slate-300 text-sm"><?= htmlspecialchars($post['content']) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>
