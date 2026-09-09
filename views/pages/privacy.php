<?php require __DIR__ . '/../layout/header.php'; ?>
<div class="max-w-4xl mx-auto space-y-8 py-6">
    <div class="text-center space-y-3">
        <span class="text-xs font-black bg-indigo-500/20 text-indigo-400 border border-indigo-500/30 px-4 py-1.5 rounded-full uppercase tracking-widest">DATA PROTECTION</span>
        <h1 class="text-4xl font-black text-white">PRIVACY POLICY</h1>
        <p class="text-slate-400 text-xs">Last updated: <?= date('F d, Y') ?></p>
    </div>

    <div class="bg-slate-900 border border-slate-800 p-8 rounded-2xl text-slate-300 text-xs leading-relaxed space-y-6 shadow-xl">
        <section class="space-y-2">
            <h2 class="text-base font-bold text-white uppercase tracking-wider">1. Information We Collect</h2>
            <p>We collect essential account details including your name, email address, username, onboarding preferences, and payment reference logs required for platform operation.</p>
        </section>

        <section class="space-y-2">
            <h2 class="text-base font-bold text-white uppercase tracking-wider">2. How We Use Your Data</h2>
            <p>Your data is strictly used to maintain your player progression, issue verifiable level certificates, display public portfolio showcases (only if enabled), and process subscription upgrades.</p>
        </section>

        <section class="space-y-2">
            <h2 class="text-base font-bold text-white uppercase tracking-wider">3. Data Security & Storage</h2>
            <p>All sensitive information is handled using secure server architecture, PDO parameterized queries, and CSRF token protections. We never sell user data to third-party advertisers.</p>
        </section>

        <section class="space-y-2">
            <h2 class="text-base font-bold text-white uppercase tracking-wider">4. User Rights & Deletion</h2>
            <p>Users have the right to request export or total deletion of their account records at any time by contacting support@freelancequest.com.</p>
        </section>
    </div>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>
