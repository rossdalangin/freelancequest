<?php require __DIR__ . '/../layout/header.php'; ?>
<div class="max-w-4xl mx-auto space-y-8">
    <div class="bg-slate-900 border border-slate-800 p-6 sm:p-8 rounded-2xl space-y-4 shadow-xl">
        <div class="flex justify-between items-center text-xs font-bold">
            <a href="/marketing" class="text-indigo-400 hover:underline flex items-center gap-1">&larr; Back to Marketing Hub</a>
            <span class="bg-indigo-500/20 text-indigo-400 px-3 py-1 rounded-full border border-indigo-500/30">MARKETING & SALES COLLATERAL</span>
        </div>
        <h1 class="text-3xl font-extrabold text-white"><?= htmlspecialchars($docTitle) ?></h1>
    </div>

    <div class="bg-slate-900 border border-slate-800 p-6 sm:p-8 rounded-2xl text-slate-200 text-sm leading-relaxed space-y-4 shadow-xl">
        <pre class="whitespace-pre-wrap font-mono text-xs text-slate-300 leading-relaxed overflow-x-auto"><?= htmlspecialchars($content) ?></pre>
    </div>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>
