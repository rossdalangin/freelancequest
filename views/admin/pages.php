<?php require __DIR__ . '/../layout/header.php'; ?>
<div class="space-y-8 py-6">
    <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <span class="text-xs font-bold text-indigo-400 uppercase tracking-wider">ADMIN CONTROL CENTER</span>
            <h1 class="text-3xl font-extrabold text-white">PAGE CONTENT MANAGEMENT SYSTEM</h1>
            <p class="text-slate-400 text-sm">Edit and manage the live HTML/text content of all public website pages directly from your dashboard.</p>
        </div>
        <a href="/admin" class="bg-slate-800 hover:bg-slate-700 text-white font-bold px-4 py-2 rounded-xl text-xs transition">
            &larr; Back to Admin Dashboard
        </a>
    </div>

    <?php if (!empty($_SESSION['flash_success'])): ?>
        <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs p-4 rounded-xl font-bold">
            ✓ <?= htmlspecialchars($_SESSION['flash_success'], ENT_QUOTES, 'UTF-8') ?>
        </div>
        <?php unset($_SESSION['flash_success']); ?>
    <?php endif; ?>

    <div class="grid grid-cols-1 gap-8">
        <?php foreach ($pagesList as $slug => $pageMeta): ?>
            <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl space-y-4 shadow-xl">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 border-b border-slate-800 pb-3">
                    <div>
                        <h2 class="text-xl font-extrabold text-white flex items-center gap-2">
                            <span>📄</span> <?= htmlspecialchars($pageMeta['title'], ENT_QUOTES, 'UTF-8') ?>
                        </h2>
                        <span class="text-xs text-indigo-400 font-mono">Public URL: <?= htmlspecialchars($pageMeta['url'], ENT_QUOTES, 'UTF-8') ?></span>
                    </div>
                    <a href="<?= htmlspecialchars($pageMeta['url'], ENT_QUOTES, 'UTF-8') ?>" target="_blank" class="text-xs font-bold text-amber-400 hover:underline">
                        View Live Page &rarr;
                    </a>
                </div>

                <form action="/admin/pages/update" method="POST" class="space-y-4">
                    <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">
                    <input type="hidden" name="page_slug" value="<?= htmlspecialchars($slug, ENT_QUOTES, 'UTF-8') ?>">

                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase mb-2">Page Body Content (HTML / Structured Text)</label>
                        <textarea name="page_content" rows="8" class="w-full bg-slate-950 border border-slate-800 rounded-xl p-4 text-xs text-slate-100 font-mono focus:outline-none focus:border-indigo-500 leading-relaxed"><?= htmlspecialchars($pageMeta['content'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                    </div>

                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white font-extrabold py-3 px-6 rounded-xl text-xs shadow-lg transition">
                        SAVE & PUBLISH <?= strtoupper(htmlspecialchars($pageMeta['title'], ENT_QUOTES, 'UTF-8')) ?> CONTENT &rarr;
                    </button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>
