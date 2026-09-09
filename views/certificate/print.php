<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate - <?= htmlspecialchars($certificate['user_name'] ?? 'Learner', ENT_QUOTES, 'UTF-8') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body { background: white !important; color: black !important; }
            .no-print { display: none !important; }
            .cert-card { border: 8px double #1e1b4b !important; box-shadow: none !important; }
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-900 min-h-screen flex flex-col justify-center items-center p-8 font-serif">

    <div class="no-print mb-6">
        <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-500 text-white font-sans font-bold px-6 py-2.5 rounded-xl shadow-lg cursor-pointer">
            🖨️ PRINT / DOWNLOAD PDF CERTIFICATE
        </button>
    </div>

    <?php if (!empty($certificate)): ?>
    <div class="cert-card bg-amber-50 text-slate-900 border-[12px] border-double border-indigo-950 p-12 max-w-4xl w-full text-center space-y-8 shadow-2xl relative rounded-xl my-auto">
        <!-- Gold Decorative Corners -->
        <div class="absolute top-3 left-3 w-8 h-8 border-t-2 border-l-2 border-amber-600"></div>
        <div class="absolute top-3 right-3 w-8 h-8 border-t-2 border-r-2 border-amber-600"></div>
        <div class="absolute bottom-3 left-3 w-8 h-8 border-b-2 border-l-2 border-amber-600"></div>
        <div class="absolute bottom-3 right-3 w-8 h-8 border-b-2 border-r-2 border-amber-600"></div>

        <div class="flex justify-between items-center font-sans border-b-2 border-indigo-950 pb-4">
            <div class="text-left">
                <span class="font-black text-3xl tracking-wider text-indigo-950">FREELANCE<span class="text-amber-600">QUEST</span></span>
                <p class="text-[11px] text-slate-600 uppercase font-bold tracking-widest">Global Virtual Assistant Guild & Career Academy</p>
            </div>
            <div class="text-right text-xs font-mono">
                <p class="font-bold text-indigo-950 text-sm">ID: <?= htmlspecialchars($certificate['certificate_code'], ENT_QUOTES, 'UTF-8') ?></p>
                <p class="text-slate-600">Date Issued: <?= date('F d, Y', strtotime($certificate['issued_at'])) ?></p>
            </div>
        </div>

        <div class="space-y-4 py-4">
            <p class="text-xs uppercase tracking-[0.3em] text-amber-700 font-sans font-black">OFFICIAL CERTIFICATE OF MASTERY & COMPETENCY</p>
            <p class="text-sm italic text-slate-700 font-serif">This credential is formally awarded to</p>
            <h1 class="text-4xl sm:text-5xl font-black text-indigo-950 uppercase border-b-4 border-amber-500 inline-block pb-2 px-10 tracking-wide font-serif"><?= htmlspecialchars($certificate['user_name'], ENT_QUOTES, 'UTF-8') ?></h1>
            <p class="text-sm italic text-slate-700 pt-2 font-serif">for successfully mastering all curriculum requirements and practical missions for</p>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-amber-800 uppercase tracking-tight font-sans"><?= htmlspecialchars($certificate['title'], ENT_QUOTES, 'UTF-8') ?></h2>
        </div>

        <div class="pt-8 border-t-2 border-slate-300 flex justify-between items-end font-sans">
            <div class="text-left text-xs text-slate-600 space-y-1">
                <p class="font-bold text-indigo-950">VERIFICATION URL:</p>
                <p class="font-mono text-[11px] text-indigo-700 underline">freelancequest.com/verify/<?= htmlspecialchars($certificate['certificate_code'], ENT_QUOTES, 'UTF-8') ?></p>
            </div>
            <div class="text-center space-y-1">
                <div class="w-16 h-16 bg-gradient-to-tr from-amber-500 to-yellow-400 rounded-full mx-auto flex items-center justify-center text-slate-950 font-black text-2xl border-2 border-indigo-950 shadow-md">
                    🏛️
                </div>
                <p class="font-black text-[11px] text-indigo-950 tracking-wider">VERIFIED GUILD SEAL</p>
            </div>
            <div class="text-right text-xs text-slate-600 space-y-1">
                <p class="font-serif italic text-base border-b border-slate-400 pb-1 text-indigo-950">Admin Board</p>
                <p class="font-bold text-indigo-950 uppercase text-[10px] tracking-wider">Academic Governing Board</p>
            </div>
        </div>
    </div>
    <?php endif; ?>

</body>
</html>
