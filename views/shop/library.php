<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="space-y-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-900/80 border border-slate-800 p-6 rounded-2xl shadow-xl">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-3 py-1 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-semibold rounded-full uppercase tracking-wider">
                    📦 UNLOCKED DIGITAL ASSETS
                </span>
            </div>
            <h1 class="text-3xl font-black text-white mt-2">My Digital Library & Unlocked Features</h1>
            <p class="text-slate-400 text-xs mt-1">Access all your purchased SOP vaults, proposal bundles, templates, unlocked course levels, and vault files.</p>
        </div>
        <div>
            <a href="/shop" class="px-4 py-2.5 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold rounded-xl text-xs transition-all shadow-lg">
                🛒 Browse Digital Shop
            </a>
        </div>
    </div>

    <!-- Notifications -->
    <?php if ($success): ?>
        <div class="p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-xl text-emerald-400 text-xs flex items-center gap-2">
            ✓ <span><?= htmlspecialchars($success) ?></span>
        </div>
    <?php endif; ?>

    <!-- Purchased Products Section -->
    <div class="space-y-4">
        <h2 class="text-xl font-extrabold text-white flex items-center gap-2">
            <span>🛍️</span> Purchased Shop Products (<?= count($purchasedProducts) ?>)
        </h2>

        <?php if (empty($purchasedProducts)): ?>
            <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-8 text-center text-slate-400 text-xs">
                You have not purchased any digital shop products yet. <a href="/shop" class="text-amber-400 font-bold underline">Explore the shop</a> to unlock SOP vaults and pitch bundles!
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <?php foreach ($purchasedProducts as $item): ?>
                    <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-5 flex items-center justify-between gap-4 shadow-lg">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-2xl flex-shrink-0">
                                <?= htmlspecialchars($item['image_url'] ?? '📦') ?>
                            </div>
                            <div>
                                <span class="px-2 py-0.5 bg-slate-950 text-slate-400 border border-slate-800 rounded text-[10px] font-bold uppercase">
                                    <?= htmlspecialchars($item['item_cat']) ?>
                                </span>
                                <h3 class="text-xs font-bold text-white mt-1"><?= htmlspecialchars($item['item_title']) ?></h3>
                                <p class="text-[11px] text-slate-400 font-mono mt-0.5">Txn: <?= htmlspecialchars($item['transaction_id']) ?></p>
                            </div>
                        </div>
                        <a href="<?= htmlspecialchars($item['file_url']) ?>" download class="px-4 py-2 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-extrabold rounded-xl text-xs transition-all shadow-md flex-shrink-0">
                            📥 Download
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Coin-Unlocked Course Levels Section -->
    <div class="space-y-4 pt-4 border-t border-slate-800">
        <h2 class="text-xl font-extrabold text-white flex items-center gap-2">
            <span>🎓</span> Coin-Unlocked Course Levels (<?= count($unlockedLevels) ?>)
        </h2>

        <?php if (empty($unlockedLevels)): ?>
            <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-6 text-center text-slate-400 text-xs">
                No course levels unlocked using game coins yet.
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <?php foreach ($unlockedLevels as $lvl): ?>
                    <div class="bg-slate-900/80 border border-amber-500/30 rounded-2xl p-5 shadow-lg flex items-center justify-between">
                        <div>
                            <span class="px-2 py-0.5 bg-amber-500/20 text-amber-400 font-bold rounded text-[10px] uppercase">
                                Level <?= $lvl['item_level'] ?> Unlocked
                            </span>
                            <h3 class="text-xs font-bold text-white mt-2"><?= htmlspecialchars($lvl['item_title']) ?></h3>
                        </div>
                        <a href="/learn" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-lg text-xs transition-colors">
                            Go to Course &rarr;
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Coin-Unlocked Premium Resources Section -->
    <div class="space-y-4 pt-4 border-t border-slate-800">
        <h2 class="text-xl font-extrabold text-white flex items-center gap-2">
            <span>📁</span> Coin-Unlocked Vault Resources (<?= count($unlockedResources) ?>)
        </h2>

        <?php if (empty($unlockedResources)): ?>
            <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-6 text-center text-slate-400 text-xs">
                No vault resources unlocked using game coins yet.
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <?php foreach ($unlockedResources as $res): ?>
                    <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-5 flex items-center justify-between gap-4 shadow-lg">
                        <div>
                            <span class="px-2 py-0.5 bg-indigo-500/20 text-indigo-400 font-bold rounded text-[10px] uppercase">
                                Unlocked Vault Resource
                            </span>
                            <h3 class="text-xs font-bold text-white mt-1"><?= htmlspecialchars($res['item_title']) ?></h3>
                        </div>
                        <a href="<?= htmlspecialchars($res['file_url']) ?>" download class="px-4 py-2 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-extrabold rounded-xl text-xs transition-all shadow-md">
                            📥 Download File
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
