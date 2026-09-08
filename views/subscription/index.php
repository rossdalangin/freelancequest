<?php require __DIR__ . '/../layout/header.php'; ?>
<div class="space-y-12 py-6">
    <div class="text-center space-y-4">
        <span class="text-xs font-black bg-amber-500/20 text-amber-400 border border-amber-500/30 px-4 py-1.5 rounded-full uppercase tracking-widest">UNLOCK YOUR FREELANCING CAREER</span>
        <h1 class="text-4xl font-black text-white">SELECT YOUR MEMBERSHIP PLAN</h1>
        <p class="text-slate-300 text-base max-w-2xl mx-auto">Choose a plan to level up your Virtual Assistant skills, unlock AI tools, and earn client-ready certificates.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
        <?php foreach ($plans as $plan): ?>
        <div class="bg-slate-900 border <?= $plan['name'] === 'pro' ? 'border-amber-500 shadow-2xl shadow-amber-500/10' : 'border-slate-800' ?> rounded-2xl p-8 space-y-6 flex flex-col justify-between">
            <div class="space-y-4">
                <?php if ($plan['name'] === 'pro'): ?>
                    <span class="bg-amber-500 text-slate-950 font-extrabold text-[10px] px-3 py-1 rounded-full uppercase tracking-wider">MOST POPULAR</span>
                <?php endif; ?>
                <h3 class="text-2xl font-black text-white"><?= htmlspecialchars($plan['title']) ?></h3>
                <div class="text-3xl font-black text-amber-400"><?= $plan['price'] ?></div>

                <ul class="space-y-3 pt-4 border-t border-slate-800 text-xs text-slate-300">
                    <?php foreach ($plan['features'] as $feat): ?>
                    <li class="flex items-center gap-2">
                        <span class="text-emerald-400 font-bold">✓</span>
                        <span><?= htmlspecialchars($feat) ?></span>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <?php if (($user['subscription_tier'] ?? 'free') === $plan['name']): ?>
                <button disabled class="w-full bg-slate-800 text-slate-400 font-bold py-3 rounded-xl text-xs cursor-not-allowed">
                    CURRENT PLAN
                </button>
            <?php else: ?>
                <form action="/pricing/subscribe" method="POST" class="space-y-3">
                    <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">
                    <input type="hidden" name="plan_name" value="<?= htmlspecialchars($plan['name']) ?>">

                    <?php if ($plan['name'] !== 'free'): ?>
                    <div>
                        <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1">Select Payment Gateway</label>
                        <select name="payment_gateway" class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2.5 focus:outline-none focus:border-amber-500 mb-2">
                            <option value="paypal">💳 PayPal (<?= htmlspecialchars($paypalEmail) ?>)</option>
                            <option value="stripe">💳 Stripe (Card Processing)</option>
                            <option value="gcash">📱 GCash (<?= htmlspecialchars($gcashNumber) ?> - <?= htmlspecialchars($gcashName) ?>)</option>
                        </select>

                        <div class="bg-slate-950 p-2.5 rounded-lg border border-slate-800 text-[10px] text-slate-400 space-y-1">
                            <p class="font-bold text-amber-400">Merchant Payment Destination:</p>
                            <p>• PayPal: <span class="text-white font-mono"><?= htmlspecialchars($paypalEmail) ?></span></p>
                            <p>• GCash: <span class="text-white font-mono"><?= htmlspecialchars($gcashNumber) ?></span> (<?= htmlspecialchars($gcashName) ?>)</p>
                        </div>
                    </div>
                    <?php endif; ?>

                    <button type="submit" class="w-full bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-400 hover:to-orange-400 text-slate-950 font-black py-3 rounded-xl text-xs transition shadow-lg shadow-amber-500/20">
                        <?= $plan['name'] === 'free' ? 'SELECT FREE PLAN' : 'UPGRADE WITH ' . strtoupper($plan['name']) ?> &rarr;
                    </button>
                </form>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>
