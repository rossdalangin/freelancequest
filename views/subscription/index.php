<?php require __DIR__ . '/../layout/header.php'; ?>
<div class="space-y-12 py-6">
    <div class="text-center space-y-4 max-w-3xl mx-auto">
        <h1 class="text-4xl font-black text-white">SIMPLE, TRANSPARENT MEMBERSHIP PLANS</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
        <?php foreach ($plans as $plan): ?>
        <div class="bg-slate-900 border border-slate-800 p-8 rounded-2xl space-y-6 flex flex-col justify-between">
            <div class="space-y-4">
                <h3 class="text-xl font-extrabold text-white"><?= htmlspecialchars($plan['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                <span class="text-4xl font-black text-white"><?= htmlspecialchars($plan['price'], ENT_QUOTES, 'UTF-8') ?></span>
                <ul class="space-y-3 pt-4 border-t border-slate-800 text-xs text-slate-300">
                    <?php foreach ($plan['features'] as $feat): ?>
                        <li>✓ <?= htmlspecialchars($feat, ENT_QUOTES, 'UTF-8') ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <form action="/pricing/subscribe" method="POST">
                <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">
                <input type="hidden" name="plan_name" value="<?= htmlspecialchars($plan['name'], ENT_QUOTES, 'UTF-8') ?>">
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-extrabold py-3 px-4 rounded-xl text-xs">SELECT <?= strtoupper(htmlspecialchars($plan['title'], ENT_QUOTES, 'UTF-8')) ?></button>
            </form>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>
