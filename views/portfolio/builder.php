<?php require __DIR__ . '/../layout/header.php'; ?>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <div class="bg-slate-900 border border-slate-800 p-6 sm:p-8 rounded-2xl space-y-6 shadow-xl">
        <div class="border-b border-slate-800 pb-4">
            <span class="text-xs font-bold text-indigo-400 uppercase tracking-widest">LEVEL 6 CAREER ASSET</span>
            <h1 class="text-2xl font-extrabold text-white">PUBLIC PORTFOLIO BUILDER</h1>
            <p class="text-xs text-slate-400">Build your public showcase website to display services, case studies, and work samples.</p>
        </div>

        <form action="/portfolio-builder" method="POST" class="space-y-4">
            <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">
            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1">Portfolio Title</label>
                <input type="text" name="title" value="<?= htmlspecialchars($portfolio['title'] ?? '', ENT_QUOTES, 'UTF-8') ?>" class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 text-sm text-slate-100">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1">Tagline</label>
                <input type="text" name="tagline" value="<?= htmlspecialchars($portfolio['tagline'] ?? '', ENT_QUOTES, 'UTF-8') ?>" class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 text-sm text-slate-100">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1">About Me / Value Proposition</label>
                <textarea name="about" rows="5" class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 text-sm text-slate-100"><?= htmlspecialchars($portfolio['about'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
            </div>
            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-extrabold py-3 px-4 rounded-xl text-sm transition shadow-lg shadow-indigo-600/30">SAVE & PUBLISH PORTFOLIO (+250 XP)</button>
        </form>

        <div class="pt-4 border-t border-slate-800 flex items-center justify-between text-xs">
            <span class="text-slate-400">Public Link:</span>
            <a href="/p/<?= htmlspecialchars($portfolio['slug'] ?? 'user', ENT_QUOTES, 'UTF-8') ?>" target="_blank" class="text-indigo-400 font-bold hover:underline">freelancequest.com/p/<?= htmlspecialchars($portfolio['slug'] ?? 'user', ENT_QUOTES, 'UTF-8') ?> &rarr;</a>
        </div>
    </div>

    <div class="bg-slate-900 border border-slate-800 p-8 rounded-2xl shadow-2xl space-y-6">
        <h2 class="text-2xl font-black text-white"><?= htmlspecialchars($portfolio['title'] ?? '', ENT_QUOTES, 'UTF-8') ?></h2>
        <p class="text-indigo-400 font-semibold text-sm"><?= htmlspecialchars($portfolio['tagline'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
        <p class="text-xs text-slate-300 leading-relaxed"><?= htmlspecialchars($portfolio['about'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
    </div>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>
