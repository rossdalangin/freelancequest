<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($portfolio['title'] ?? 'Portfolio') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-full font-sans bg-slate-950 text-slate-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto space-y-12">
        <div class="text-center space-y-4">
            <span class="bg-indigo-500/20 text-indigo-400 font-extrabold text-xs px-4 py-1.5 rounded-full border border-indigo-500/30 uppercase tracking-widest">VERIFIED PORTFOLIO</span>
            <h1 class="text-4xl font-black text-white tracking-tight"><?= htmlspecialchars($portfolio['title'] ?? '') ?></h1>
            <p class="text-indigo-400 text-lg font-semibold"><?= htmlspecialchars($portfolio['tagline'] ?? '') ?></p>
        </div>

        <div class="bg-slate-900 border border-slate-800 p-8 rounded-2xl space-y-4 shadow-xl">
            <h2 class="text-lg font-extrabold text-white border-b border-slate-800 pb-3">ABOUT ME</h2>
            <p class="text-slate-300 text-sm leading-relaxed"><?= htmlspecialchars($portfolio['about'] ?? '') ?></p>
        </div>
    </div>
</body>
</html>
