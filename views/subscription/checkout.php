<?php require __DIR__ . '/../layout/header.php'; ?>
<div class="max-w-3xl mx-auto space-y-8 py-6">
    <div class="flex items-center justify-between bg-slate-900 border border-slate-800 p-6 rounded-2xl shadow-xl">
        <div>
            <span class="text-xs font-bold text-amber-400 uppercase tracking-wider">SECURE CHECKOUT</span>
            <h1 class="text-3xl font-black text-white">UPGRADE TO <?= strtoupper($planName) ?> PLAN</h1>
            <p class="text-slate-400 text-xs mt-1">Complete your transaction via <?= strtoupper($paymentGateway) ?></p>
        </div>
        <div class="text-right">
            <span class="text-3xl font-black text-emerald-400">$<?= number_format($amount, 2) ?></span>
            <p class="text-[10px] text-slate-400 uppercase">Monthly Subscription</p>
        </div>
    </div>

    <?php if (!empty($error)): ?>
        <div class="bg-red-500/10 border border-red-500/30 text-red-400 text-xs p-4 rounded-xl font-bold">
            ⚠️ <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <div class="bg-slate-900 border border-slate-800 p-8 rounded-2xl space-y-6 shadow-2xl">
        <div class="border-b border-slate-800 pb-4">
            <h3 class="text-lg font-bold text-white uppercase tracking-wider">MERCHANT PAYMENT INSTRUCTIONS</h3>
        </div>

        <?php if ($paymentGateway === 'paypal'): ?>
            <div class="bg-slate-950 p-6 rounded-xl border border-slate-800 space-y-3">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">💳</span>
                    <h4 class="font-bold text-white text-sm">PayPal Merchant Account Details</h4>
                </div>
                <p class="text-xs text-slate-300">Send your subscription payment of <strong class="text-emerald-400">$<?= number_format($amount, 2) ?> USD</strong> to the admin PayPal address below:</p>
                <div class="bg-slate-900 p-3 rounded-lg border border-slate-800 font-mono text-xs text-amber-400">
                    <?= htmlspecialchars($paypalEmail) ?>
                </div>
                <p class="text-[11px] text-slate-400">After sending, copy the PayPal Transaction ID / Receipt Number and enter it below.</p>
            </div>
        <?php elseif ($paymentGateway === 'gcash'): ?>
            <div class="bg-slate-950 p-6 rounded-xl border border-slate-800 space-y-3">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">📱</span>
                    <h4 class="font-bold text-white text-sm">GCash / E-Wallet Merchant Account Details</h4>
                </div>
                <p class="text-xs text-slate-300">Send your payment equivalent of <strong class="text-emerald-400">$<?= number_format($amount, 2) ?> USD</strong> to the GCash account below:</p>
                <div class="bg-slate-900 p-4 rounded-lg border border-slate-800 space-y-1 font-mono text-xs">
                    <p class="text-slate-300">Mobile Number: <span class="text-amber-400 font-bold"><?= htmlspecialchars($gcashNumber) ?></span></p>
                    <p class="text-slate-300">Account Name: <span class="text-amber-400 font-bold"><?= htmlspecialchars($gcashName) ?></span></p>
                </div>
                <p class="text-[11px] text-slate-400">After sending, enter your 13-digit GCash Reference Number below to complete your upgrade.</p>
            </div>
        <?php else: ?>
            <div class="bg-slate-950 p-6 rounded-xl border border-slate-800 space-y-3">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">💳</span>
                    <h4 class="font-bold text-white text-sm">Stripe Card Processing</h4>
                </div>
                <p class="text-xs text-slate-300">Stripe Merchant Key: <span class="font-mono text-amber-400"><?= htmlspecialchars($stripeKey) ?></span></p>
                <p class="text-[11px] text-slate-400">Enter your card transaction confirmation / authorization code below.</p>
            </div>
        <?php endif; ?>

        <!-- CHECKOUT VERIFICATION FORM -->
        <form action="/checkout/process" method="POST" class="space-y-4 pt-2">
            <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">
            <input type="hidden" name="plan_name" value="<?= htmlspecialchars($planName) ?>">
            <input type="hidden" name="payment_gateway" value="<?= htmlspecialchars($paymentGateway) ?>">

            <div>
                <label class="block text-xs font-extrabold text-slate-300 uppercase mb-2">
                    Payment Reference / Receipt Transaction ID <span class="text-red-400">*</span>
                </label>
                <input type="text" id="refInput" name="reference_number" required placeholder="e.g., 900123456789 or PAYPAL-TXN-88392" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-xs text-white focus:outline-none focus:border-amber-500 font-mono">
            </div>

            <div class="bg-slate-950/60 p-4 rounded-xl border border-slate-800 space-y-2">
                <span class="text-[11px] font-bold text-amber-400 uppercase tracking-wider block">⚡ Instant Demo / Simulator One-Click Authorization</span>
                <p class="text-[11px] text-slate-400">Testing in sandbox mode? Click below to auto-generate a verified test transaction reference and activate instantly:</p>
                <button type="button" onclick="document.getElementById('refInput').value = 'DEMO-AUTH-' + Math.floor(100000 + Math.random() * 900000);" class="bg-slate-800 hover:bg-slate-700 text-amber-300 text-xs font-bold px-3 py-1.5 rounded-lg border border-slate-700 transition">
                    🎲 Generate Sandbox Test Reference
                </button>
            </div>

            <button type="submit" class="w-full bg-gradient-to-r from-amber-500 via-yellow-500 to-orange-500 hover:from-amber-400 hover:to-orange-400 text-slate-950 font-black py-4 rounded-xl text-sm transition shadow-xl shadow-amber-500/20 border border-amber-400/50">
                COMPLETE TRANSACTION & ACTIVATE <?= strtoupper($planName) ?> PLAN &rarr;
            </button>
        </form>
    </div>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>
