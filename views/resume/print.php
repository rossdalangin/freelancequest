<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resume - <?= htmlspecialchars($resume['full_name'] ?? 'Candidate', ENT_QUOTES, 'UTF-8') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body { background: white !important; color: black !important; }
            .no-print { display: none !important; }
            .resume-card { box-shadow: none !important; border: none !important; padding: 0 !important; }
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-900 min-h-screen py-8 px-4 font-sans">

    <div class="no-print max-w-4xl mx-auto mb-6 text-center">
        <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold px-6 py-2.5 rounded-xl shadow-lg cursor-pointer">
            🖨️ PRINT / SAVE RESUME PDF
        </button>
    </div>

    <div class="resume-card bg-white max-w-4xl mx-auto p-12 rounded-2xl shadow-2xl space-y-8 text-slate-800">
        <div class="border-b-4 border-indigo-900 pb-6 flex justify-between items-start">
            <div class="space-y-1">
                <h1 class="text-4xl font-black text-indigo-950 uppercase tracking-tight font-serif"><?= htmlspecialchars($resume['full_name'] ?? 'Your Name', ENT_QUOTES, 'UTF-8') ?></h1>
                <p class="text-indigo-700 font-extrabold text-lg uppercase tracking-wider"><?= htmlspecialchars($resume['professional_title'] ?? 'Virtual Assistant Specialist', ENT_QUOTES, 'UTF-8') ?></p>
            </div>
            <div class="text-right text-xs font-semibold text-slate-600 space-y-1 border-l-2 border-amber-500 pl-4">
                <p><i class="fa-solid fa-envelope text-amber-600 mr-1"></i> <?= htmlspecialchars($resume['email'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
                <p><i class="fa-solid fa-phone text-amber-600 mr-1"></i> <?= htmlspecialchars($resume['phone'] ?? '+1 (555) 019-2834', ENT_QUOTES, 'UTF-8') ?></p>
                <p><i class="fa-solid fa-location-dot text-amber-600 mr-1"></i> <?= htmlspecialchars($resume['location'] ?? 'Remote / Global Support', ENT_QUOTES, 'UTF-8') ?></p>
            </div>
        </div>

        <div class="space-y-2">
            <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider border-b border-slate-200 pb-1">Professional Summary</h3>
            <p class="text-xs text-slate-700 leading-relaxed"><?= htmlspecialchars($resume['summary'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
        </div>

        <div class="space-y-2">
            <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider border-b border-slate-200 pb-1">Core Competencies & Skills</h3>
            <div class="flex flex-wrap gap-1.5">
                <?php foreach (($resume['skills'] ?? []) as $sk): ?>
                    <span class="bg-indigo-50 text-indigo-900 border border-indigo-200 px-2.5 py-1 rounded text-xs font-semibold"><?= htmlspecialchars($sk, ENT_QUOTES, 'UTF-8') ?></span>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="space-y-2">
            <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider border-b border-slate-200 pb-1">Tools & Platforms</h3>
            <div class="flex flex-wrap gap-1.5">
                <?php foreach (($resume['tools'] ?? []) as $tl): ?>
                    <span class="bg-slate-100 text-slate-800 px-2.5 py-1 rounded text-xs font-semibold"><?= htmlspecialchars($tl, ENT_QUOTES, 'UTF-8') ?></span>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="space-y-3">
            <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider border-b border-slate-200 pb-1">Experience & Practical Missions</h3>
            <?php foreach (($resume['experience'] ?? []) as $exp): ?>
                <div class="text-xs space-y-1">
                    <div class="flex justify-between font-bold text-slate-900">
                        <span><?= htmlspecialchars($exp['role'] ?? '', ENT_QUOTES, 'UTF-8') ?> &bull; <?= htmlspecialchars($exp['company'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
                        <span class="text-slate-500"><?= htmlspecialchars($exp['period'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
                    </div>
                    <p class="text-slate-600 leading-relaxed"><?= htmlspecialchars($exp['details'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</body>
</html>
