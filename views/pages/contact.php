<?php require __DIR__ . '/../layout/header.php'; ?>
<div class="max-w-3xl mx-auto space-y-8 py-6">
    <div class="text-center space-y-3">
        <span class="text-xs font-black bg-indigo-500/20 text-indigo-400 border border-indigo-500/30 px-4 py-1.5 rounded-full uppercase tracking-widest">HELPDESK & SUPPORT</span>
        <h1 class="text-4xl font-black text-white">CONTACT US</h1>
        <p class="text-slate-400 text-xs">Have questions about FREELANCEQUEST? Send a message to our support team.</p>
    </div>

    <?php if (!empty($submitted)): ?>
        <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs p-4 rounded-xl font-bold">
            ✓ <?= htmlspecialchars($submitted, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <div class="bg-slate-900 border border-slate-800 p-8 rounded-2xl space-y-4 shadow-xl">
        <form action="/contact" method="POST" class="space-y-4">
            <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Your Name</label>
                <input type="text" name="name" required value="<?= htmlspecialchars($user['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="Maria Santos" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-xs text-white">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Email Address</label>
                <input type="email" name="email" required value="<?= htmlspecialchars($user['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="maria@example.com" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-xs text-white">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Subject</label>
                <input type="text" name="subject" required placeholder="Account or Billing Inquiry" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-xs text-white">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Message</label>
                <textarea name="message" rows="4" required placeholder="How can we help you..." class="w-full bg-slate-950 border border-slate-800 rounded-xl p-4 text-xs text-white"></textarea>
            </div>

            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-extrabold py-3.5 rounded-xl text-xs shadow-lg">
                SEND MESSAGE &rarr;
            </button>
        </form>
    </div>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>
