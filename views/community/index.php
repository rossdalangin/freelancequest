<?php require __DIR__ . '/../layout/header.php'; ?>
<div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
    <div class="lg:col-span-3 space-y-6">
        <!-- CREATE POST FORM -->
        <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl space-y-4 shadow-xl">
            <h2 class="text-xl font-black text-white flex items-center gap-2">
                <span>💬</span> CREATE COMMUNITY DISCUSSION
            </h2>
            <form action="/community/post" method="POST" class="space-y-4">
                <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">

                <div>
                    <input type="text" name="title" required placeholder="Discussion Topic / Question Title..." class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-xl p-3 focus:outline-none focus:border-indigo-500">
                </div>

                <div>
                    <textarea name="content" rows="3" required placeholder="Share your freelancing question, client success story, or tool tip..." class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-xl p-3 focus:outline-none focus:border-indigo-500"></textarea>
                </div>

                <div class="flex items-center justify-between">
                    <select name="group_id" class="bg-slate-950 border border-slate-800 text-xs text-slate-300 rounded-lg p-2.5">
                        <option value="">General Discussion</option>
                        <?php foreach ($groups as $g): ?>
                            <option value="<?= $g['id'] ?>"><?= htmlspecialchars($g['name']) ?></option>
                        <?php endforeach; ?>
                    </select>

                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white font-extrabold px-6 py-2.5 rounded-xl text-xs shadow-lg transition">
                        POST DISCUSSION (+50 XP)
                    </button>
                </div>
            </form>
        </div>

        <!-- POSTS FEED -->
        <div class="space-y-6">
            <?php foreach ($posts as $post): ?>
            <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl space-y-4 shadow-lg">
                <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-indigo-600/20 text-indigo-400 font-extrabold rounded-full flex items-center justify-center text-xs border border-indigo-500/30">
                            LVL <?= $post['user_level'] ?>
                        </div>
                        <div>
                            <h4 class="font-bold text-sm text-white"><?= htmlspecialchars($post['user_name']) ?></h4>
                            <span class="text-[10px] text-slate-500"><?= htmlspecialchars($post['created_at']) ?></span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <form action="/community/post/<?= $post['id'] ?>/upvote" method="POST" class="inline">
                            <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">
                            <button type="submit" class="bg-slate-800 hover:bg-slate-700 text-amber-400 font-bold px-3 py-1 rounded-lg text-xs border border-slate-700 transition flex items-center gap-1">
                                <span>👍</span>
                                <span><?= $post['upvotes'] ?? 0 ?></span>
                            </button>
                        </form>

                        <?php if ($post['user_id'] == $user['id'] || ($user['role'] ?? '') === 'admin'): ?>
                        <form action="/community/post/<?= $post['id'] ?>/delete" method="POST" class="inline" onsubmit="return confirm('Delete this discussion?');">
                            <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">
                            <button type="submit" class="text-red-400 hover:underline text-xs font-bold px-2 py-1">Delete</button>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="space-y-2">
                    <h3 class="text-base font-extrabold text-white"><?= htmlspecialchars($post['title']) ?></h3>
                    <p class="text-xs text-slate-300 leading-relaxed"><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                </div>

                <!-- COMMENTS LIST -->
                <div class="pt-4 border-t border-slate-800 space-y-3">
                    <h5 class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Replies (<?= count($post['comments']) ?>)</h5>

                    <?php foreach ($post['comments'] as $c): ?>
                    <div class="bg-slate-950 p-3 rounded-xl border border-slate-800 space-y-1">
                        <div class="flex items-center justify-between text-[11px]">
                            <span class="font-bold text-indigo-400"><?= htmlspecialchars($c['user_name']) ?></span>
                            <span class="text-[10px] text-slate-500"><?= htmlspecialchars($c['created_at']) ?></span>
                        </div>
                        <p class="text-xs text-slate-300"><?= htmlspecialchars($c['content']) ?></p>
                    </div>
                    <?php endforeach; ?>

                    <form action="/community/post/<?= $post['id'] ?>/comment" method="POST" class="flex gap-2 pt-2">
                        <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">
                        <input type="text" name="content" required placeholder="Write a reply..." class="flex-1 bg-slate-950 border border-slate-800 text-xs text-white rounded-xl px-3 py-2 focus:outline-none focus:border-indigo-500">
                        <button type="submit" class="bg-slate-800 hover:bg-slate-700 text-slate-100 font-extrabold px-4 py-2 rounded-xl text-xs transition border border-slate-700">
                            REPLY (+25 XP)
                        </button>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- SIDEBAR GROUPS -->
    <div class="space-y-6">
        <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl space-y-4 shadow-xl">
            <h3 class="text-sm font-extrabold text-white uppercase tracking-wider border-b border-slate-800 pb-3">COMMUNITY GROUPS</h3>
            <div class="space-y-2 text-xs font-semibold">
                <a href="/community" class="block bg-slate-950 p-3 rounded-xl border border-slate-800 hover:border-indigo-500 transition text-slate-200">
                    🌱 All Discussions
                </a>
                <?php foreach ($groups as $g): ?>
                <a href="/community?group=<?= urlencode($g['slug']) ?>" class="block bg-slate-950 p-3 rounded-xl border border-slate-800 hover:border-indigo-500 transition text-slate-200">
                    <?= $g['icon'] ?> <?= htmlspecialchars($g['name']) ?>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>
