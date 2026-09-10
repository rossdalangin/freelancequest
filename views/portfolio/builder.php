<?php require __DIR__ . '/../layout/header.php'; ?>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <div class="bg-slate-900 border border-slate-800 p-6 sm:p-8 rounded-2xl space-y-6 shadow-xl">
        <div class="border-b border-slate-800 pb-4">
            <span class="text-xs font-bold text-indigo-400 uppercase tracking-widest">LEVEL 6 CAREER ASSET</span>
            <h1 class="text-2xl font-extrabold text-white">PUBLIC PORTFOLIO BUILDER</h1>
            <p class="text-xs text-slate-400">Build your public showcase website to display services, case studies, and work samples.</p>
        </div>

        <form action="/portfolio-builder" method="POST" id="portfolioForm" class="space-y-4">
            <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">
            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1">Portfolio Title</label>
                <input type="text" id="input_title" name="title" value="<?= htmlspecialchars($portfolio['title'] ?? '', ENT_QUOTES, 'UTF-8') ?>" class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 text-sm text-slate-100">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1">Tagline</label>
                <input type="text" id="input_tagline" name="tagline" value="<?= htmlspecialchars($portfolio['tagline'] ?? '', ENT_QUOTES, 'UTF-8') ?>" class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 text-sm text-slate-100">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1">About Me / Value Proposition</label>
                <textarea id="input_about" name="about" rows="4" class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 text-sm text-slate-100"><?= htmlspecialchars($portfolio['about'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1">Services (Format: Title | Description - One per line)</label>
                <?php
                    $servicesText = [];
                    foreach (($portfolio['services'] ?? []) as $s) {
                        $servicesText[] = ($s['title'] ?? '') . ' | ' . ($s['description'] ?? '');
                    }
                ?>
                <textarea id="input_services" name="services_text" rows="4" placeholder="Email Management | Inbox zero and priority triage&#10;Lead Generation | Verified B2B contact lists" class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 text-xs text-slate-100"><?= htmlspecialchars(implode("\n", $servicesText), ENT_QUOTES, 'UTF-8') ?></textarea>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1">Contact Email</label>
                    <input type="email" id="input_email" name="email" value="<?= htmlspecialchars(($portfolio['contact_info']['email'] ?? $user['email']), ENT_QUOTES, 'UTF-8') ?>" class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 text-sm text-slate-100">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1">LinkedIn Profile URL</label>
                    <input type="text" id="input_linkedin" name="linkedin" value="<?= htmlspecialchars(($portfolio['contact_info']['linkedin'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" placeholder="https://linkedin.com/in/username" class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 text-sm text-slate-100">
                </div>
            </div>
            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-extrabold py-3 px-4 rounded-xl text-sm transition shadow-lg shadow-indigo-600/30">SAVE & PUBLISH PORTFOLIO (+250 XP)</button>
        </form>

        <div class="pt-4 border-t border-slate-800 flex items-center justify-between text-xs">
            <span class="text-slate-400">Public Link:</span>
            <a href="/p/<?= htmlspecialchars($portfolio['slug'] ?? 'user', ENT_QUOTES, 'UTF-8') ?>" target="_blank" class="text-indigo-400 font-bold hover:underline">freelancequest.com/p/<?= htmlspecialchars($portfolio['slug'] ?? 'user', ENT_QUOTES, 'UTF-8') ?> &rarr;</a>
        </div>
    </div>

    <!-- Live Preview -->
    <div class="bg-slate-900 border border-slate-800 p-8 rounded-2xl shadow-2xl space-y-6">
        <div class="border-b border-slate-800 pb-4">
            <h2 id="preview_title" class="text-2xl font-black text-white"><?= htmlspecialchars($portfolio['title'] ?? 'Portfolio Title', ENT_QUOTES, 'UTF-8') ?></h2>
            <p id="preview_tagline" class="text-indigo-400 font-semibold text-sm mt-1"><?= htmlspecialchars($portfolio['tagline'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
        </div>

        <div class="space-y-2">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">About Me</h3>
            <p id="preview_about" class="text-xs text-slate-300 leading-relaxed"><?= htmlspecialchars($portfolio['about'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
        </div>

        <div class="space-y-3">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-800 pb-1">Services Offered</h3>
            <div id="preview_services" class="space-y-3">
                <?php foreach (($portfolio['services'] ?? []) as $s): ?>
                    <div class="bg-slate-950 p-3.5 rounded-xl border border-slate-800 space-y-1">
                        <h4 class="font-bold text-xs text-indigo-400"><?= htmlspecialchars($s['title'] ?? '', ENT_QUOTES, 'UTF-8') ?></h4>
                        <p class="text-[11px] text-slate-400 leading-normal"><?= htmlspecialchars($s['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="border-t border-slate-800 pt-4 flex flex-col sm:flex-row justify-between text-xs text-slate-400 gap-2">
            <div>
                <span class="font-bold text-slate-300">Email:</span>
                <span id="preview_email"><?= htmlspecialchars(($portfolio['contact_info']['email'] ?? $user['email']), ENT_QUOTES, 'UTF-8') ?></span>
            </div>
            <div>
                <span class="font-bold text-slate-300">LinkedIn:</span>
                <span id="preview_linkedin"><?= htmlspecialchars(($portfolio['contact_info']['linkedin'] ?? ''), ENT_QUOTES, 'UTF-8') ?></span>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fields = ['title', 'tagline', 'about', 'email', 'linkedin'];
    fields.forEach(field => {
        const input = document.getElementById('input_' + field);
        const preview = document.getElementById('preview_' + field);
        if (input && preview) {
            input.addEventListener('input', function() {
                preview.textContent = this.value || (field === 'title' ? 'Portfolio Title' : '');
            });
        }
    });

    const servicesInput = document.getElementById('input_services');
    const servicesPreview = document.getElementById('preview_services');
    if (servicesInput && servicesPreview) {
        servicesInput.addEventListener('input', function() {
            const lines = this.value.split('\n').map(l => l.trim()).filter(l => l.length > 0);
            servicesPreview.innerHTML = lines.map(line => {
                const parts = line.split('|').map(p => p.trim());
                let title = parts[0] || '';
                let desc = parts[1] || (parts.length === 1 ? 'Professional Virtual Assistant Service' : '');
                return `<div class="bg-slate-950 p-3.5 rounded-xl border border-slate-800 space-y-1">
                    <h4 class="font-bold text-xs text-indigo-400">${escapeHtml(title)}</h4>
                    <p class="text-[11px] text-slate-400 leading-normal">${escapeHtml(desc)}</p>
                </div>`;
            }).join('');
        });
    }

    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }
});
</script>

<?php require __DIR__ . '/../layout/footer.php'; ?>
