<?php require __DIR__ . '/../layout/header.php'; ?>
<div class="space-y-8">
    <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl flex flex-col md:flex-row justify-between items-start md:items-center gap-4 shadow-xl">
        <div>
            <span class="text-xs font-bold text-amber-400 uppercase tracking-wider">FREELANCER TOOLKIT</span>
            <h1 class="text-3xl font-black text-white">RESOURCE VAULT</h1>
            <p class="text-slate-400 text-xs mt-1">Downloadable SOPs, contracts, pitch scripts, and career templates.</p>
        </div>
        <span class="bg-indigo-500/20 text-indigo-400 font-extrabold text-xs px-3.5 py-1.5 rounded-full border border-indigo-500/30">
            <?= strtoupper($user['subscription_tier'] ?? 'free') ?> MEMBER ACCESS
        </span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($resources as $res): ?>
        <?php
            $isLocked = $res['is_premium'] && !in_array($user['subscription_tier'] ?? 'free', ['pro', 'master']);
        ?>
        <div class="bg-slate-900 border <?= $res['is_premium'] ? 'border-amber-500/30 shadow-lg shadow-amber-500/5' : 'border-slate-800' ?> p-6 rounded-2xl space-y-4 flex flex-col justify-between">
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] bg-slate-800 text-slate-300 font-extrabold px-2.5 py-1 rounded-full uppercase">
                        LEVEL <?= $res['level_number'] ?> &bull; <?= strtoupper($res['type']) ?>
                    </span>
                    <span class="text-[10px] font-bold <?= $res['is_premium'] ? 'text-amber-400' : 'text-emerald-400' ?>">
                        <?= $res['is_premium'] ? '🔒 PRO VAULT' : 'FREE' ?>
                    </span>
                </div>

                <h3 class="font-extrabold text-white text-base leading-snug"><?= htmlspecialchars($res['title']) ?></h3>
                <p class="text-xs text-slate-400 leading-relaxed"><?= htmlspecialchars($res['description']) ?></p>
            </div>

            <div class="pt-2 border-t border-slate-800/80">
                <?php if ($isLocked): ?>
                    <a href="/pricing" class="block text-center bg-amber-500/20 border border-amber-500/30 text-amber-400 font-bold py-2.5 px-4 rounded-xl text-xs hover:bg-amber-500 hover:text-slate-950 transition">
                        🔒 UNLOCK WITH PRO &rarr;
                    </a>
                <?php else: ?>
                    <a href="<?= htmlspecialchars($res['file_content_or_url']) ?>" target="_blank" class="block text-center bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-2.5 px-4 rounded-xl text-xs transition shadow-md shadow-indigo-600/20">
                        📥 DOWNLOAD RESOURCE &rarr;
                    </a>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>
