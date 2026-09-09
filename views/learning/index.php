<?php require __DIR__ . '/../layout/header.php'; ?>
<div class="space-y-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-slate-900 border border-slate-800 p-6 rounded-2xl">
        <div>
            <h1 class="text-3xl font-extrabold text-white">FREELANCE ACADEMY</h1>
            <p class="text-slate-400 text-sm">Master Virtual Assistant skills, pass knowledge checks, and complete missions to earn XP and certificates.</p>
        </div>
    </div>

    <?php foreach ($courses as $course): ?>
    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl space-y-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-slate-800 pb-4 gap-4">
            <div>
                <span class="text-xs font-bold text-indigo-400 uppercase tracking-widest">COURSE LEVEL <?= $course['level_number'] ?></span>
                <h2 class="text-xl font-extrabold text-white"><?= htmlspecialchars($course['title']) ?></h2>
                <p class="text-xs text-slate-400"><?= htmlspecialchars($course['description']) ?></p>
            </div>
            <div class="flex items-center gap-3">
                <?php if (!empty($course['certificate'])): ?>
                    <a href="/verify/<?= htmlspecialchars($course['certificate']['certificate_code']) ?>" target="_blank" class="bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black px-4 py-2 rounded-xl text-xs shadow-lg transition flex items-center gap-1.5">
                        📜 DOWNLOAD LEVEL CERTIFICATE
                    </a>
                <?php endif; ?>
                <span class="text-xs bg-slate-800 text-slate-300 font-bold px-3 py-1.5 rounded-full border border-slate-700">
                    <?= count($course['lessons']) ?> Lessons
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php foreach ($course['lessons'] as $lesson): ?>
            <div class="bg-slate-950 border border-slate-800 p-5 rounded-xl space-y-3 hover:border-indigo-500/40 transition flex flex-col justify-between">
                <div class="space-y-2">
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-indigo-400 font-bold uppercase">Lesson <?= $lesson['sort_order'] ?></span>
                        <span class="text-emerald-400 font-extrabold">+<?= $lesson['xp_reward'] ?> XP</span>
                    </div>
                    <h3 class="font-extrabold text-base text-slate-100 line-clamp-1"><?= htmlspecialchars($lesson['title']) ?></h3>
                    <p class="text-xs text-slate-400 line-clamp-2"><?= htmlspecialchars($lesson['summary']) ?></p>
                </div>

                <div class="pt-3 border-t border-slate-900 flex justify-between items-center">
                    <span class="text-[10px] text-amber-400 font-bold"><i class="fa-solid fa-coins mr-1"></i> +<?= $lesson['coin_reward'] ?> Coins</span>
                    <a href="/learn/<?= $lesson['slug'] ?>" class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs px-3.5 py-1.5 rounded-lg transition shadow-md shadow-indigo-600/30">
                        LEARN &rarr;
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>
