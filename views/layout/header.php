<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FreelanceQuest - Gamified Virtual Assistant & Career Simulator</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="h-full flex flex-col font-sans bg-slate-950 text-slate-100 selection:bg-indigo-500 selection:text-white">

    <header class="bg-slate-900 border-b border-slate-800 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="<?= isset($user) ? '/dashboard' : '/' ?>" class="flex items-center gap-2 font-black text-xl tracking-wider text-indigo-400 hover:text-indigo-300">
                    <span class="bg-indigo-600 text-white p-2 rounded-lg text-lg shadow-lg shadow-indigo-500/30">⚔️</span>
                    <span>FREELANCE<span class="text-amber-400">QUEST</span></span>
                </a>
            </div>

            <?php if (isset($user) && $user): ?>
            <div class="hidden lg:flex items-center gap-6 bg-slate-800/80 px-4 py-1.5 rounded-full border border-slate-700/80 shadow-inner">
                <div class="flex items-center gap-2">
                    <span class="bg-indigo-500/20 text-indigo-400 font-extrabold text-xs px-2.5 py-1 rounded-full border border-indigo-500/30">
                        LVL <?= $user['level'] ?? 1 ?>
                    </span>
                </div>

                <div class="w-32 flex flex-col gap-0.5">
                    <div class="flex justify-between text-[10px] font-bold text-slate-400">
                        <span>XP</span>
                        <span><?= number_format($user['xp'] ?? 0) ?></span>
                    </div>
                    <div class="w-full bg-slate-950 rounded-full h-2 overflow-hidden p-0.5 border border-slate-800">
                        <div class="bg-gradient-to-r from-indigo-500 to-purple-500 h-full rounded-full transition-all duration-500" style="width: <?= min(100, (($user['xp'] ?? 0) % 1000) / 10) ?>%;"></div>
                    </div>
                </div>

                <div class="h-4 w-px bg-slate-700"></div>

                <div class="flex items-center gap-1.5 text-amber-400 font-bold text-sm">
                    <i class="fa-solid fa-coins text-amber-400"></i>
                    <span><?= number_format($user['coins'] ?? 100) ?></span>
                </div>

                <div class="h-4 w-px bg-slate-700"></div>

                <div class="flex items-center gap-1.5 text-orange-400 font-bold text-sm">
                    <i class="fa-solid fa-fire text-orange-500"></i>
                    <span><?= $user['streak_count'] ?? 0 ?> Days</span>
                </div>
            </div>

            <nav class="flex items-center gap-4 text-xs font-semibold">
                <a href="/dashboard" class="text-slate-300 hover:text-indigo-400 transition"><i class="fa-solid fa-compass mr-1"></i> Dashboard</a>
                <a href="/learn" class="text-slate-300 hover:text-indigo-400 transition"><i class="fa-solid fa-book-open mr-1"></i> Academy</a>
                <a href="/jobs" class="text-amber-400 hover:text-amber-300 font-bold transition"><i class="fa-solid fa-briefcase mr-1"></i> Job Board</a>
                <a href="/cover-letter-builder" class="text-slate-300 hover:text-indigo-400 transition"><i class="fa-solid fa-envelope-open-text mr-1"></i> Cover Letter</a>
                <a href="/application-tracker" class="text-slate-300 hover:text-indigo-400 transition"><i class="fa-solid fa-chart-line mr-1"></i> Tracker</a>
                <a href="/resume-builder" class="text-slate-300 hover:text-indigo-400 transition"><i class="fa-solid fa-file-invoice mr-1"></i> Resume</a>
                <a href="/portfolio-builder" class="text-slate-300 hover:text-indigo-400 transition"><i class="fa-solid fa-user-gear mr-1"></i> Portfolio</a>
                <a href="/community" class="text-slate-300 hover:text-indigo-400 transition"><i class="fa-solid fa-users mr-1"></i> Community</a>
                <a href="/pricing" class="bg-amber-500 hover:bg-amber-400 text-slate-950 font-extrabold px-3 py-1.5 rounded-lg shadow-md transition">⚡ PRO</a>
                <a href="/settings" class="text-slate-300 hover:text-white transition"><i class="fa-solid fa-gear"></i> Settings</a>

                <form action="/logout" method="POST" class="inline">
                    <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">
                    <button type="submit" class="text-slate-400 hover:text-red-400 transition ml-1">Logout</button>
                </form>
            </nav>
            <?php else: ?>
            <nav class="flex items-center gap-5 text-sm font-semibold">
                <a href="/about" class="text-slate-300 hover:text-white transition">About</a>
                <a href="/features" class="text-slate-300 hover:text-white transition">Features</a>
                <a href="/faq" class="text-slate-300 hover:text-white transition">FAQ</a>
                <a href="/pricing" class="text-slate-300 hover:text-white transition">Pricing</a>
                <a href="/login" class="text-indigo-400 hover:text-indigo-300 font-bold">Log In</a>
                <a href="/register" class="bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-400 hover:to-orange-400 text-slate-950 font-black px-4 py-2 rounded-xl text-xs shadow-lg shadow-amber-500/20 transition">
                    PLAY NOW &rarr;
                </a>
            </nav>
            <?php endif; ?>
        </div>
    </header>

    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
