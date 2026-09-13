<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="space-y-8">
    <!-- Header Banner -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-900/80 border border-slate-800 p-6 rounded-2xl shadow-xl">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-3 py-1 bg-amber-500/10 border border-amber-500/20 text-amber-400 text-xs font-semibold rounded-full uppercase tracking-wider">
                    🛒 DIGITAL STORE & VA ASSETS
                </span>
            </div>
            <h1 class="text-3xl font-black text-white mt-2">Freelancer Marketplace & Digital Shop</h1>
            <p class="text-slate-400 text-xs mt-1">Unlock high-converting proposal bundles, client onboarding SOPs, pitch scripts, and Canva social media kits using cash or earned game coins!</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="bg-slate-950 border border-slate-800 px-4 py-2.5 rounded-xl flex items-center gap-2 text-amber-400 font-bold text-xs">
                <i class="fa-solid fa-coins text-amber-400 text-base"></i>
                <span>Your Balance: <?= number_format($user['coins'] ?? 0) ?> Coins</span>
            </div>
            <a href="/shop/library" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl text-xs transition-colors flex items-center gap-1.5 shadow-lg">
                📦 My Digital Library
            </a>
        </div>
    </div>

    <!-- Notifications -->
    <?php if ($success): ?>
        <div class="p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-xl text-emerald-400 text-xs flex items-center gap-2">
            ✓ <span><?= htmlspecialchars($success) ?></span>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="p-4 bg-rose-500/10 border border-rose-500/30 rounded-xl text-rose-400 text-xs flex items-center gap-2">
            ⚠️ <span><?= htmlspecialchars($error) ?></span>
        </div>
    <?php endif; ?>

    <!-- Category Filter Bar -->
    <div class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-800 pb-4">
        <div class="flex flex-wrap items-center gap-2">
            <a href="/shop" class="px-4 py-2 rounded-xl text-xs font-semibold transition-all <?= !$category ? 'bg-amber-500 text-slate-950 font-bold' : 'bg-slate-900 text-slate-400 hover:text-white border border-slate-800' ?>">
                All Digital Assets
            </a>
            <a href="/shop?category=SOP+Vault" class="px-4 py-2 rounded-xl text-xs font-semibold transition-all <?= $category === 'SOP Vault' ? 'bg-amber-500 text-slate-950 font-bold' : 'bg-slate-900 text-slate-400 hover:text-white border border-slate-800' ?>">
                SOP Vaults
            </a>
            <a href="/shop?category=Proposal+Templates" class="px-4 py-2 rounded-xl text-xs font-semibold transition-all <?= $category === 'Proposal Templates' ? 'bg-amber-500 text-slate-950 font-bold' : 'bg-slate-900 text-slate-400 hover:text-white border border-slate-800' ?>">
                Proposal Bundles
            </a>
            <a href="/shop?category=Outreach+Scripts" class="px-4 py-2 rounded-xl text-xs font-semibold transition-all <?= $category === 'Outreach Scripts' ? 'bg-amber-500 text-slate-950 font-bold' : 'bg-slate-900 text-slate-400 hover:text-white border border-slate-800' ?>">
                Pitch & Outreach
            </a>
            <a href="/shop?category=Social+Media+Templates" class="px-4 py-2 rounded-xl text-xs font-semibold transition-all <?= $category === 'Social Media Templates' ? 'bg-amber-500 text-slate-950 font-bold' : 'bg-slate-900 text-slate-400 hover:text-white border border-slate-800' ?>">
                Social Media Kits
            </a>
        </div>

        <form action="/shop" method="GET" class="flex items-center gap-2 w-full sm:w-auto">
            <input type="text" name="search" value="<?= htmlspecialchars($search ?? '') ?>" placeholder="Search digital products..." class="bg-slate-950 border border-slate-800 rounded-xl px-4 py-2 text-xs text-white focus:outline-none focus:border-amber-500">
            <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-xl text-xs font-bold transition">
                Search
            </button>
        </form>
    </div>

    <!-- Products Grid -->
    <?php if (empty($products)): ?>
        <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-12 text-center text-slate-400 text-sm">
            No digital products found matching your search.
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($products as $p): ?>
                <?php $isOwned = in_array($p['id'], $purchasedProductIds ?? []); ?>
                <div class="bg-slate-900/80 border border-slate-800/80 rounded-2xl p-6 flex flex-col justify-between hover:border-amber-500/40 transition-all duration-300 shadow-xl group">
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">
                            <?= htmlspecialchars($p['image_url'] ?? '📦') ?>
                        </div>
                        <span class="px-2.5 py-1 bg-slate-950 text-slate-400 border border-slate-800 rounded-lg text-[10px] font-bold uppercase tracking-wider">
                            <?= htmlspecialchars($p['category']) ?>
                        </span>
                        <h3 class="text-base font-extrabold text-white mt-3 line-clamp-2 hover:text-amber-400 transition-colors">
                            <a href="/shop/<?= $p['id'] ?>"><?= htmlspecialchars($p['title']) ?></a>
                        </h3>
                        <p class="text-xs text-slate-400 mt-2 line-clamp-3 leading-relaxed">
                            <?= htmlspecialchars($p['description']) ?>
                        </p>
                    </div>

                    <div class="mt-6 pt-4 border-t border-slate-800/80 space-y-3">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-[10px] text-slate-500 block uppercase font-bold">USD Price</span>
                                <span class="text-lg font-black text-emerald-400">$<?= number_format($p['price_usd'], 2) ?></span>
                            </div>
                            <div class="text-right">
                                <span class="text-[10px] text-slate-500 block uppercase font-bold">Coin Price</span>
                                <span class="text-sm font-extrabold text-amber-400">🪙 <?= number_format($p['price_coins']) ?></span>
                            </div>
                        </div>

                        <?php if ($isOwned): ?>
                            <a href="/shop/library" class="w-full py-2.5 bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 font-bold rounded-xl text-xs transition-colors flex items-center justify-center gap-1">
                                ✓ OWNED (Download in Library)
                            </a>
                        <?php else: ?>
                            <div class="grid grid-cols-2 gap-2">
                                <form action="/shop/<?= $p['id'] ?>/buy-coins" method="POST">
                                    <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">
                                    <button type="submit" class="w-full py-2 bg-amber-500 hover:bg-amber-400 text-slate-950 font-extrabold rounded-xl text-xs transition-all shadow-md">
                                        🪙 Use Coins
                                    </button>
                                </form>
                                <a href="/shop/<?= $p['id'] ?>/checkout" class="w-full py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl text-xs transition-colors text-center block shadow-md">
                                    💳 Pay Cash
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
