<?php require __DIR__ . '/../layout/header.php'; ?>
<div class="space-y-8">
    <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl flex flex-col md:flex-row justify-between items-start md:items-center gap-4 shadow-xl">
        <div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold text-amber-400 uppercase tracking-wider">FREELANCER TOOLKIT</span>
                <span class="bg-slate-800 text-slate-300 text-[10px] font-extrabold px-2 py-0.5 rounded-full">32 MASTER ASSETS</span>
            </div>
            <h1 class="text-3xl font-black text-white mt-1">RESOURCE VAULT</h1>
            <p class="text-slate-400 text-xs mt-1">Downloadable SOPs, contracts, pitch scripts, calculators, and career templates.</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="bg-indigo-500/20 text-indigo-400 font-extrabold text-xs px-3.5 py-1.5 rounded-full border border-indigo-500/30">
                <?= strtoupper($user['subscription_tier'] ?? 'free') ?> MEMBER ACCESS
            </span>
        </div>
    </div>

    <?php if (isset($success)): ?>
        <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 p-4 rounded-xl text-xs font-bold">
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div class="bg-rose-500/10 border border-rose-500/30 text-rose-400 p-4 rounded-xl text-xs font-bold">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($resources as $res): ?>
        <?php
            $isUnlockedByCoins = in_array($res['id'], $unlockedResourceIds ?? []);
            $isProOrMaster = in_array($user['subscription_tier'] ?? 'free', ['pro', 'master']);
            $isLocked = $res['is_premium'] && !$isProOrMaster && !$isUnlockedByCoins;
        ?>
        <div class="bg-slate-900 border <?= $res['is_premium'] ? 'border-amber-500/30 shadow-lg shadow-amber-500/5' : 'border-slate-800' ?> p-6 rounded-2xl space-y-4 flex flex-col justify-between hover:border-slate-700 transition">
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] bg-slate-800 text-indigo-300 font-extrabold px-2.5 py-1 rounded-full uppercase">
                        LEVEL <?= $res['level_number'] ?> &bull; <?= strtoupper($res['type']) ?>
                    </span>
                    <span class="text-[10px] font-bold <?= $res['is_premium'] ? 'text-amber-400' : 'text-emerald-400' ?>">
                        <?php if (!$res['is_premium']): ?>
                            FREE ACCESS
                        <?php elseif ($isUnlockedByCoins): ?>
                            🔓 COIN UNLOCKED
                        <?php else: ?>
                            🔒 PRO VAULT
                        <?php endif; ?>
                    </span>
                </div>

                <h3 class="font-extrabold text-white text-base leading-snug"><?= htmlspecialchars($res['title']) ?></h3>
                <p class="text-xs text-slate-400 leading-relaxed"><?= htmlspecialchars($res['description']) ?></p>
            </div>

            <div class="pt-3 border-t border-slate-800/80 space-y-2">
                <?php if ($isLocked): ?>
                    <div class="flex flex-col gap-2">
                        <a href="/pricing" class="block text-center bg-amber-500/20 border border-amber-500/30 text-amber-400 font-bold py-2 px-3 rounded-xl text-xs hover:bg-amber-500 hover:text-slate-950 transition">
                            🔒 UNLOCK WITH PRO &rarr;
                        </a>
                        <form action="/resources/<?= $res['id'] ?>/unlock-coins" method="POST" class="block">
                            <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">
                            <button type="submit" onclick="return confirm('Unlock <?= htmlspecialchars(addslashes($res['title'])) ?> for 150 Coins?');" class="w-full text-center bg-slate-800 hover:bg-slate-700 border border-slate-700 text-amber-300 font-bold py-1.5 px-3 rounded-xl text-[11px] transition">
                                <i class="fa-solid fa-coins text-amber-400 mr-1"></i> Unlock for 150 Coins
                            </button>
                        </form>
                    </div>
                <?php else: ?>
                    <a href="<?= htmlspecialchars($res['file_content_or_url']) ?>" target="_blank" class="block text-center bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-2.5 px-4 rounded-xl text-xs transition shadow-md shadow-indigo-600/20">
                        📥 DOWNLOAD MASTER ASSET &rarr;
                    </a>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>
