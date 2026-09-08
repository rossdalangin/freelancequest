<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Official Certificate Verification - FreelanceQuest</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="min-h-full font-sans bg-slate-950 text-slate-100 py-12 px-4 sm:px-6 lg:px-8">

    <div class="max-w-3xl mx-auto space-y-8">

        <?php if (!empty($certificate)): ?>
        <div class="bg-slate-900 border-2 border-emerald-500/50 p-8 sm:p-12 rounded-3xl space-y-8 shadow-2xl relative overflow-hidden">
            <div class="absolute -right-12 -top-12 w-48 h-48 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-800 pb-6">
                <div class="flex items-center gap-3">
                    <span class="bg-indigo-600 text-white p-2 rounded-xl text-xl shadow-lg">⚔️</span>
                    <span class="font-black text-xl tracking-wider text-indigo-400">FREELANCE<span class="text-amber-400">QUEST</span></span>
                </div>
                <span class="bg-emerald-500/20 text-emerald-400 font-black text-xs px-3.5 py-1.5 rounded-full border border-emerald-500/40 uppercase tracking-widest">
                    ✓ OFFICIAL VERIFIED CREDENTIAL
                </span>
            </div>

            <div class="text-center space-y-3 py-4">
                <p class="text-xs text-slate-400 uppercase tracking-widest font-bold">THIS OFFICIAL CERTIFICATE CONFIRMS THAT</p>
                <h1 class="text-3xl sm:text-4xl font-black text-indigo-400 uppercase tracking-tight"><?= htmlspecialchars($certificate['user_name'] ?? 'Learner', ENT_QUOTES, 'UTF-8') ?></h1>
                <p class="text-xs text-slate-400">has successfully demonstrated competency and completed all requirements for</p>
                <h2 class="text-2xl font-extrabold text-white pt-2"><?= htmlspecialchars($certificate['title'], ENT_QUOTES, 'UTF-8') ?></h2>
                <p class="text-xs text-amber-400 font-bold">Level <?= $certificate['level_number'] ?> Career Milestone Completed</p>
            </div>

            <?php if (!empty($certificate['skills_breakdown'])): ?>
            <div class="bg-slate-950 p-5 rounded-2xl border border-slate-800 space-y-3">
                <h3 class="text-xs font-extrabold text-slate-400 uppercase tracking-wider">Verified Skill Competencies:</h3>
                <div class="grid grid-cols-2 gap-3 text-xs font-semibold">
                    <?php foreach ($certificate['skills_breakdown'] as $skill => $score): ?>
                        <div class="flex justify-between items-center bg-slate-900 p-2.5 rounded-xl border border-slate-800">
                            <span class="text-slate-300"><?= htmlspecialchars($skill, ENT_QUOTES, 'UTF-8') ?></span>
                            <span class="text-emerald-400 font-mono font-bold"><?= $score ?>%</span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <div class="pt-6 border-t border-slate-800 flex flex-col sm:flex-row justify-between items-center gap-4 text-xs">
                <div class="space-y-1 text-slate-400 text-center sm:text-left">
                    <p>Credential ID: <code class="text-indigo-400 font-mono font-bold"><?= htmlspecialchars($certificate['certificate_code'], ENT_QUOTES, 'UTF-8') ?></code></p>
                    <p>Issued Date: <?= date('F d, Y', strtotime($certificate['issued_at'])) ?></p>
                </div>
                <a href="/verify/<?= htmlspecialchars($certificate['certificate_code'], ENT_QUOTES, 'UTF-8') ?>/print" target="_blank" class="bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black px-5 py-2.5 rounded-xl transition shadow-lg flex items-center gap-2">
                    <i class="fa-solid fa-print"></i> PRINT / SAVE PDF CERTIFICATE
                </a>
            </div>
        </div>
        <?php else: ?>
        <div class="bg-slate-900 border-2 border-rose-500/50 p-8 rounded-2xl text-center space-y-4 shadow-2xl">
            <h1 class="text-2xl font-black text-white">CERTIFICATE NOT FOUND</h1>
            <p class="text-xs text-slate-400">No official record exists for certificate code <code class="text-rose-400 font-mono"><?= htmlspecialchars($code, ENT_QUOTES, 'UTF-8') ?></code>.</p>
        </div>
        <?php endif; ?>

        <div class="text-center">
            <a href="/dashboard" class="text-xs font-bold text-indigo-400 hover:underline">&larr; Return to FreelanceQuest Homepage</a>
        </div>

    </div>

</body>
</html>
