<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate Verification - FreelanceQuest</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-full font-sans bg-slate-950 text-slate-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto space-y-8">
        <?php if (!empty($certificate)): ?>
        <div class="bg-slate-900 border-2 border-emerald-500/50 p-8 sm:p-10 rounded-2xl space-y-6 shadow-2xl">
            <div class="flex justify-between items-center border-b border-slate-800 pb-4">
                <span class="bg-emerald-500/20 text-emerald-400 font-black text-xs px-3.5 py-1.5 rounded-full uppercase border border-emerald-500/40">✓ VERIFIED OFFICIAL CREDENTIAL</span>
                <span class="text-xs text-slate-400 font-mono"><?= htmlspecialchars($certificate['certificate_code']) ?></span>
            </div>
            <div class="text-center space-y-2 py-4">
                <p class="text-xs text-slate-400 uppercase tracking-widest font-bold">THIS CERTIFIES THAT</p>
                <h1 class="text-3xl font-black text-indigo-400 uppercase"><?= htmlspecialchars($certificate['user_name'] ?? 'Learner') ?></h1>
                <p class="text-xs text-slate-400">has completed requirements for</p>
                <h2 class="text-xl font-extrabold text-white pt-2"><?= htmlspecialchars($certificate['title']) ?></h2>
            </div>
        </div>
        <?php else: ?>
        <div class="bg-slate-900 border-2 border-rose-500/50 p-8 rounded-2xl text-center space-y-4">
            <h1 class="text-2xl font-black text-white">CERTIFICATE NOT FOUND</h1>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
