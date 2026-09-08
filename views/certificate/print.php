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
    <div class="cert-card bg-amber-50 text-slate-900 border-8 border-indigo-950 p-12 max-w-4xl w-full text-center space-y-8 shadow-2xl relative rounded-xl">
        <div class="flex justify-between items-center font-sans border-b-2 border-indigo-900 pb-4">
            <div class="text-left">
                <span class="font-black text-2xl tracking-wider text-indigo-950">FREELANCE<span class="text-amber-600">QUEST</span></span>
                <p class="text-[10px] text-slate-600 uppercase font-semibold">Global Virtual Assistant Guild</p>
            </div>
            <div class="text-right text-xs font-mono">
                <p class="font-bold text-indigo-950">ID: <?= htmlspecialchars($certificate['certificate_code'], ENT_QUOTES, 'UTF-8') ?></p>
                <p class="text-slate-600">Issued: <?= date('F d, Y', strtotime($certificate['issued_at'])) ?></p>
            </div>
        </div>

        <div class="space-y-4 py-6">
            <p class="text-xs uppercase tracking-widest text-slate-600 font-sans font-bold">OFFICIAL CERTIFICATE OF COMPETENCY</p>
            <p class="text-sm italic text-slate-700">This credential is proudly awarded to</p>
            <h1 class="text-4xl font-black text-indigo-950 uppercase border-b-2 border-amber-500 inline-block pb-1 px-8"><?= htmlspecialchars($certificate['user_name'], ENT_QUOTES, 'UTF-8') ?></h1>
            <p class="text-sm italic text-slate-700 pt-2">for successfully completing all requirements and practical missions for</p>
            <h2 class="text-2xl font-black text-amber-700 uppercase tracking-tight"><?= htmlspecialchars($certificate['title'], ENT_QUOTES, 'UTF-8') ?></h2>
        </div>

        <div class="pt-8 border-t border-slate-300 flex justify-between items-end font-sans">
            <div class="text-left text-xs text-slate-600">
                <p class="font-bold text-slate-900">Verification URL:</p>
                <p class="font-mono">freelancequest.com/verify/<?= htmlspecialchars($certificate['certificate_code'], ENT_QUOTES, 'UTF-8') ?></p>
            </div>
            <div class="text-center">
                <div class="text-3xl mb-1">🏛️</div>
                <p class="font-bold text-xs text-slate-900">VERIFIED GUILD SEAL</p>
            </div>
        </div>
    </div>
    <?php endif; ?>

</body>
</html>
