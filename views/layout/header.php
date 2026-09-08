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
                <a href="/dashboard" class="flex items-center gap-2 font-black text-xl tracking-wider text-indigo-400 hover:text-indigo-300">
                    <span class="bg-indigo-600 text-white p-2 rounded-lg text-lg shadow-lg shadow-indigo-500/30">⚔️</span>
                    <span>FREELANCE<span class="text-amber-400">QUEST</span></span>
                </a>
            </div>

            <?php if (isset($user)): ?>
            <div class="hidden md:flex items-center gap-6 bg-slate-800/80 px-4 py-1.5 rounded-full border border-slate-700/80 shadow-inner">
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
            <?php endif; ?>

            <nav class="flex items-center gap-4 text-sm font-semibold">
                <a href="/dashboard" class="text-slate-300 hover:text-indigo-400 transition"><i class="fa-solid fa-compass mr-1"></i> Dashboard</a>
                <a href="/learn" class="text-slate-300 hover:text-indigo-400 transition"><i class="fa-solid fa-book-open mr-1"></i> Academy</a>
                <a href="/resume-builder" class="text-slate-300 hover:text-indigo-400 transition"><i class="fa-solid fa-file-invoice mr-1"></i> Resume</a>
                <a href="/portfolio-builder" class="text-slate-300 hover:text-indigo-400 transition"><i class="fa-solid fa-briefcase mr-1"></i> Portfolio</a>
                <a href="/community" class="text-slate-300 hover:text-indigo-400 transition"><i class="fa-solid fa-users mr-1"></i> Community</a>
                <a href="/pricing" class="bg-amber-500 hover:bg-amber-400 text-slate-950 font-extrabold px-3 py-1.5 rounded-lg text-xs shadow-md shadow-amber-500/20 transition">⚡ UPGRADE</a>
                <a href="/admin" class="text-slate-400 hover:text-white text-xs border border-slate-700 px-2.5 py-1 rounded-md">Admin</a>
            </nav>
        </div>
    </header>

    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
