<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <a href="/shop/<?= $product['id'] ?>" class="text-xs text-slate-400 hover:text-white transition-colors inline-flex items-center gap-1">
            &larr; Back to Product Details
        </a>
        <h1 class="text-2xl font-black text-white mt-2">Digital Product Checkout</h1>
        <p class="text-xs text-slate-400">Complete your payment to instantly download <?= htmlspecialchars($product['title']) ?>.</p>
    </div>

    <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6 sm:p-8 shadow-2xl space-y-6">
        <!-- Summary Box -->
        <div class="bg-slate-950 p-4 rounded-xl border border-slate-800 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="text-2xl"><?= htmlspecialchars($product['image_url'] ?? '📦') ?></span>
                <div>
                    <h3 class="text-xs font-bold text-white"><?= htmlspecialchars($product['title']) ?></h3>
                    <span class="text-[10px] text-slate-400"><?= htmlspecialchars($product['category']) ?></span>
                </div>
            </div>
            <div class="text-right">
                <span class="text-xs text-slate-500 block font-semibold">Total Amount</span>
                <span class="text-lg font-black text-emerald-400">$<?= number_format($product['price_usd'], 2) ?> USD</span>
            </div>
        </div>

        <form action="/shop/<?= $product['id'] ?>/process-payment" method="POST" class="space-y-6">
            <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">

            <div>
                <label class="block text-xs font-extrabold text-slate-300 uppercase tracking-wider mb-3">Select Payment Method</label>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <label class="bg-slate-950 p-4 rounded-xl border border-slate-800 flex flex-col items-center justify-center cursor-pointer hover:border-amber-500 transition-colors">
                        <input type="radio" name="payment_gateway" value="paypal" checked class="w-4 h-4 text-amber-500 mb-2">
                        <span class="text-xs font-bold text-white">PayPal</span>
                        <span class="text-[10px] text-slate-400 mt-0.5"><?= htmlspecialchars($paypalEmail) ?></span>
                    </label>

                    <label class="bg-slate-950 p-4 rounded-xl border border-slate-800 flex flex-col items-center justify-center cursor-pointer hover:border-amber-500 transition-colors">
                        <input type="radio" name="payment_gateway" value="stripe" class="w-4 h-4 text-amber-500 mb-2">
                        <span class="text-xs font-bold text-white">Credit Card (Stripe)</span>
                        <span class="text-[10px] text-slate-400 mt-0.5">Secure Checkout</span>
                    </label>

                    <label class="bg-slate-950 p-4 rounded-xl border border-slate-800 flex flex-col items-center justify-center cursor-pointer hover:border-amber-500 transition-colors">
                        <input type="radio" name="payment_gateway" value="gcash" class="w-4 h-4 text-amber-500 mb-2">
                        <span class="text-xs font-bold text-white">GCash</span>
                        <span class="text-[10px] text-slate-400 mt-0.5"><?= htmlspecialchars($gcashNumber) ?></span>
                    </label>
                </div>
            </div>

            <div class="bg-slate-950 p-4 rounded-xl border border-slate-800/60 text-xs text-slate-400 space-y-1">
                <p class="font-bold text-slate-300">💡 Instant Product Access Guarantee</p>
                <p>Upon clicking complete payment, your order will be processed instantly and added to your Digital Library with lifetime download access.</p>
            </div>

            <button type="submit" class="w-full py-3.5 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black rounded-xl text-sm transition-all shadow-xl shadow-emerald-500/20">
                COMPLETE PAYMENT & DOWNLOAD &rarr;
            </button>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
