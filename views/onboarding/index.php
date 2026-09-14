<?php require __DIR__ . '/../layout/header.php'; ?>
<div class="max-w-2xl mx-auto space-y-8">
    <div class="text-center space-y-3">
        <h1 class="text-3xl font-black text-white">BUILD YOUR PERSONAL CAREER ROADMAP</h1>
    </div>

    <div class="bg-slate-900 border border-slate-800 p-8 rounded-2xl shadow-2xl">
        <form action="/onboarding" method="POST" class="space-y-6">
            <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">
            <div>
                <label class="block text-xs font-bold text-slate-300 mb-2">Experience Level</label>
                <select name="experience_level" required class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-sm text-slate-100">
                    <option value="beginner">Complete Beginner</option>
                    <option value="some_experience">Some Knowledge</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-300 mb-2">Target Role / Career Specialization</label>
                <select name="target_career" required class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-sm text-slate-100">
                    <?php if (!empty($targetRoles)): ?>
                        <?php foreach ($targetRoles as $role): ?>
                            <option value="<?= htmlspecialchars($role['slug'], ENT_QUOTES, 'UTF-8') ?>">
                                <?= htmlspecialchars($role['name'], ENT_QUOTES, 'UTF-8') ?> (<?= htmlspecialchars($role['category'], ENT_QUOTES, 'UTF-8') ?>)
                            </option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="admin-va">General Administrative VA</option>
                        <option value="social-media-va">Social Media & Graphic VA</option>
                        <option value="lead-gen-va">B2B Lead Generation Specialist</option>
                    <?php endif; ?>
                </select>
            </div>
            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-extrabold py-3.5 px-6 rounded-xl text-sm">GENERATE ROADMAP &rarr;</button>
        </form>
    </div>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>
