<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($portfolio['title'] ?? 'Portfolio') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-full font-sans bg-slate-950 text-slate-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto space-y-10">
        <div class="bg-gradient-to-r from-indigo-950 via-slate-900 to-slate-900 border border-indigo-500/40 p-8 sm:p-12 rounded-3xl text-center space-y-4 shadow-2xl relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-indigo-500/10 rounded-full blur-2xl pointer-events-none"></div>
            <span class="bg-indigo-500/20 text-indigo-400 font-extrabold text-xs px-4 py-1.5 rounded-full border border-indigo-500/30 uppercase tracking-widest inline-block">✓ VERIFIED VIRTUAL ASSISTANT PORTFOLIO</span>
            <h1 class="text-3xl sm:text-5xl font-black text-white tracking-tight"><?= htmlspecialchars($portfolio['title'] ?? '') ?></h1>
            <p class="text-indigo-400 text-base sm:text-lg font-bold max-w-2xl mx-auto"><?= htmlspecialchars($portfolio['tagline'] ?? '') ?></p>
        </div>

        <div class="bg-slate-900 border border-slate-800 p-8 rounded-2xl space-y-4 shadow-xl">
            <h2 class="text-lg font-extrabold text-white border-b border-slate-800 pb-3">ABOUT ME & VALUE PROPOSITION</h2>
            <p class="text-slate-300 text-sm leading-relaxed whitespace-pre-line"><?= htmlspecialchars($portfolio['about'] ?? '') ?></p>
        </div>

        <?php if (!empty($portfolio['services'])): ?>
        <div class="bg-slate-900 border border-slate-800 p-8 rounded-2xl space-y-6 shadow-xl">
            <h2 class="text-lg font-extrabold text-white border-b border-slate-800 pb-3">SERVICES OFFERED</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <?php foreach ($portfolio['services'] as $s): ?>
                <div class="bg-slate-950 border border-slate-800 p-5 rounded-xl space-y-2 hover:border-indigo-500/40 transition">
                    <h3 class="font-extrabold text-indigo-400 text-sm"><?= htmlspecialchars($s['title'] ?? '', ENT_QUOTES, 'UTF-8') ?></h3>
                    <p class="text-xs text-slate-300 leading-relaxed"><?= htmlspecialchars($s['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if (!empty($portfolio['contact_info'])): ?>
        <div class="bg-gradient-to-r from-slate-900 to-indigo-950 border border-indigo-500/30 p-8 rounded-2xl text-center space-y-4 shadow-xl">
            <h2 class="text-xl font-extrabold text-white">HIRE & WORK WITH ME</h2>
            <p class="text-xs text-slate-300 max-w-md mx-auto">Interested in streamlining your business operations? Contact me directly to discuss your project requirements.</p>
            <div class="flex flex-wrap justify-center gap-4 pt-2">
                <?php if (!empty($portfolio['contact_info']['email'])): ?>
                    <a href="mailto:<?= htmlspecialchars($portfolio['contact_info']['email'], ENT_QUOTES, 'UTF-8') ?>" class="bg-indigo-600 hover:bg-indigo-500 text-white font-extrabold px-6 py-3 rounded-xl text-xs shadow-lg transition">
                        ✉️ EMAIL ME NOW
                    </a>
                <?php endif; ?>
                <?php if (!empty($portfolio['contact_info']['linkedin'])): ?>
                    <a href="<?= htmlspecialchars($portfolio['contact_info']['linkedin'], ENT_QUOTES, 'UTF-8') ?>" target="_blank" class="bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold px-6 py-3 rounded-xl text-xs border border-slate-700 transition">
                        💼 LINKEDIN PROFILE
                    </a>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
