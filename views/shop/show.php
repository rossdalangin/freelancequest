<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="max-w-4xl mx-auto space-y-8">
    <div>
        <a href="/shop" class="text-xs text-slate-400 hover:text-white transition-colors inline-flex items-center gap-1">
            &larr; Back to Digital Shop
        </a>
    </div>

    <?php if ($error): ?>
        <div class="p-4 bg-rose-500/10 border border-rose-500/30 rounded-xl text-rose-400 text-xs flex items-center gap-2">
            ⚠️ <span><?= htmlspecialchars($error) ?></span>
        </div>
    <?php endif; ?>

    <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-8 shadow-2xl grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="md:col-span-1 flex flex-col items-center justify-center bg-slate-950 border border-slate-800 rounded-2xl p-8 text-center">
            <div class="w-24 h-24 rounded-3xl bg-amber-500/10 border border-amber-500/30 flex items-center justify-center text-5xl mb-4 shadow-lg shadow-amber-500/10">
                <?= htmlspecialchars($product['image_url'] ?? '📦') ?>
            </div>
            <span class="px-3 py-1 bg-slate-800 text-amber-400 border border-slate-700 rounded-lg text-xs font-bold uppercase tracking-wider">
                <?= htmlspecialchars($product['category']) ?>
            </span>
        </div>

        <div class="md:col-span-2 space-y-6">
            <div>
                <h1 class="text-2xl font-black text-white"><?= htmlspecialchars($product['title']) ?></h1>
                <p class="text-slate-300 text-xs mt-3 leading-relaxed"><?= htmlspecialchars($product['description']) ?></p>
            </div>

            <div class="bg-slate-950 p-4 rounded-xl border border-slate-800/80 grid grid-cols-2 gap-4">
                <div>
                    <span class="text-[10px] text-slate-500 uppercase font-bold block">Direct Cash Price</span>
                    <span class="text-2xl font-black text-emerald-400">$<?= number_format($product['price_usd'], 2) ?> USD</span>
                </div>
                <div>
                    <span class="text-[10px] text-slate-500 uppercase font-bold block">Game Coin Price</span>
                    <span class="text-2xl font-black text-amber-400">🪙 <?= number_format($product['price_coins']) ?> Coins</span>
                </div>
            </div>

            <?php if (!empty($purchaseRecord)): ?>
                <div class="bg-emerald-500/10 border border-emerald-500/30 rounded-xl p-5 text-center">
                    <p class="text-emerald-400 text-sm font-bold">✓ You own this digital asset!</p>
                    <a href="<?= htmlspecialchars($product['file_url']) ?>" download class="inline-flex items-center gap-2 px-6 py-2.5 bg-emerald-500 text-slate-950 font-black rounded-xl text-xs mt-3 hover:bg-emerald-400 transition-all shadow-lg">
                        📥 Download Asset File Now
                    </a>
                </div>
            <?php else: ?>
                <div class="space-y-4 pt-2">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Option A: Unlock with Game Coins -->
                        <div class="bg-slate-950 p-5 rounded-2xl border border-slate-800 flex flex-col justify-between space-y-4">
                            <div>
                                <span class="text-xs font-bold text-amber-400 block">Option 1: Unlock with Coins</span>
                                <p class="text-[11px] text-slate-400 mt-1">Deduct <?= number_format($product['price_coins']) ?> coins from your balance.</p>
                            </div>
                            <form action="/shop/<?= $product['id'] ?>/buy-coins" method="POST">
                                <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">
                                <button type="submit" class="w-full py-3 bg-amber-500 hover:bg-amber-400 text-slate-950 font-extrabold rounded-xl text-xs transition-all shadow-lg">
                                    🪙 Unlock for <?= number_format($product['price_coins']) ?> Coins
                                </button>
                            </form>
                        </div>

                        <!-- Option B: Direct Cash Payment -->
                        <div class="bg-slate-950 p-5 rounded-2xl border border-slate-800 flex flex-col justify-between space-y-4">
                            <div>
                                <span class="text-xs font-bold text-indigo-400 block">Option 2: Pay Cash</span>
                                <p class="text-[11px] text-slate-400 mt-1">Pay $<?= number_format($product['price_usd'], 2) ?> via PayPal, Stripe, or GCash.</p>
                            </div>
                            <a href="/shop/<?= $product['id'] ?>/checkout" class="w-full py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl text-xs transition-colors text-center block shadow-lg">
                                💳 Pay $<?= number_format($product['price_usd'], 2) ?> USD
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
