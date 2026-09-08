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
                    <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">
                    <input type="text" name="title" required placeholder="Title..." class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-sm text-slate-100">
                    <textarea name="content" rows="3" required placeholder="Message..." class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-sm text-slate-100"></textarea>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white font-extrabold px-5 py-2.5 rounded-xl text-xs">POST (+50 XP)</button>
                </form>
            </div>

            <?php foreach ($posts as $post): ?>
            <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl space-y-4">
                <div class="flex justify-between items-center text-xs">
                    <span class="font-bold text-indigo-400"><?= htmlspecialchars($post['user_name'] ?? 'Member', ENT_QUOTES, 'UTF-8') ?></span>
                </div>
                <h3 class="font-extrabold text-white text-lg"><?= htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                <p class="text-slate-300 text-sm"><?= htmlspecialchars($post['content'], ENT_QUOTES, 'UTF-8') ?></p>

                <!-- Comments -->
                <div class="space-y-2 pt-2 border-t border-slate-800">
                    <?php foreach ($post['comments'] as $comment): ?>
                        <div class="bg-slate-950 p-2.5 rounded-lg border border-slate-800 text-xs">
                            <span class="font-bold text-indigo-400"><?= htmlspecialchars($comment['user_name'] ?? 'User', ENT_QUOTES, 'UTF-8') ?>:</span>
                            <span class="text-slate-300"><?= htmlspecialchars($comment['content'], ENT_QUOTES, 'UTF-8') ?></span>
                        </div>
                    <?php endforeach; ?>

                    <form action="/community/post/<?= $post['id'] ?>/comment" method="POST" class="flex gap-2 pt-1">
                        <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">
                        <input type="text" name="content" required placeholder="Reply..." class="flex-1 bg-slate-950 border border-slate-800 rounded-lg p-2 text-xs text-slate-100">
                        <button type="submit" class="bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold px-3 py-1.5 rounded-lg text-xs">Reply (+25 XP)</button>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>
