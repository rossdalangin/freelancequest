<?php require __DIR__ . '/../layout/header.php'; ?>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <div class="bg-slate-900 border border-slate-800 p-6 sm:p-8 rounded-2xl space-y-6 shadow-xl">
        <div class="border-b border-slate-800 pb-4 flex justify-between items-start">
            <div>
                <span class="text-xs font-bold text-indigo-400 uppercase tracking-widest">LEVEL 5 CAREER ASSET</span>
                <h1 class="text-2xl font-extrabold text-white">INTERACTIVE RESUME BUILDER</h1>
                <p class="text-xs text-slate-400">Build an ATS-optimized professional resume to win real client projects.</p>
            </div>
            <a href="/resume-builder/print" target="_blank" class="bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black px-3.5 py-2 rounded-xl text-xs shadow-lg transition">
                🖨️ PRINT / PDF
            </a>
        </div>

        <form action="/resume-builder" method="POST" id="resumeForm" class="space-y-4">
            <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">
            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1">Full Name</label>
                <input type="text" id="input_full_name" name="full_name" value="<?= htmlspecialchars($resume['full_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 text-sm text-slate-100">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1">Professional Headline / Title</label>
                <input type="text" id="input_professional_title" name="professional_title" value="<?= htmlspecialchars($resume['professional_title'] ?? '', ENT_QUOTES, 'UTF-8') ?>" class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 text-sm text-slate-100">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1">Email</label>
                    <input type="email" id="input_email" name="email" value="<?= htmlspecialchars($resume['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>" class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 text-sm text-slate-100">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1">Phone</label>
                    <input type="text" id="input_phone" name="phone" value="<?= htmlspecialchars($resume['phone'] ?? '', ENT_QUOTES, 'UTF-8') ?>" class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 text-sm text-slate-100">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1">Location</label>
                <input type="text" id="input_location" name="location" value="<?= htmlspecialchars($resume['location'] ?? '', ENT_QUOTES, 'UTF-8') ?>" class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 text-sm text-slate-100">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1">Professional Bio Summary</label>
                <textarea id="input_summary" name="summary" rows="4" class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 text-sm text-slate-100"><?= htmlspecialchars($resume['summary'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1">Key Skills (Comma Separated)</label>
                <input type="text" id="input_skills" name="skills" value="<?= htmlspecialchars(implode(', ', $resume['skills'] ?? []), ENT_QUOTES, 'UTF-8') ?>" class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 text-sm text-slate-100">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1">Tools & Platforms (Comma Separated)</label>
                <input type="text" id="input_tools" name="tools" value="<?= htmlspecialchars(implode(', ', $resume['tools'] ?? []), ENT_QUOTES, 'UTF-8') ?>" class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 text-sm text-slate-100">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1">Work Experience (Format: Role | Company | Period | Details - One per line)</label>
                <?php
                    $expText = [];
                    foreach (($resume['experience'] ?? []) as $ex) {
                        $expText[] = ($ex['role'] ?? '') . ' | ' . ($ex['company'] ?? '') . ' | ' . ($ex['period'] ?? '') . ' | ' . ($ex['details'] ?? '');
                    }
                ?>
                <textarea id="input_experience" name="experience_text" rows="3" placeholder="Virtual Assistant Apprentice | FreelanceQuest | 2025 - Present | Managed calendar scheduling and inbox zero triage" class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 text-xs text-slate-100"><?= htmlspecialchars(implode("\n", $expText), ENT_QUOTES, 'UTF-8') ?></textarea>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1">Education & Certifications (Format: Degree | Institution | Year)</label>
                <?php
                    $eduText = [];
                    foreach (($resume['education'] ?? []) as $ed) {
                        $eduText[] = ($ed['degree'] ?? '') . ' | ' . ($ed['institution'] ?? '') . ' | ' . ($ed['year'] ?? '');
                    }
                ?>
                <textarea id="input_education" name="education_text" rows="2" placeholder="Virtual Assistant Certification | FreelanceQuest Academy | 2025" class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2.5 text-xs text-slate-100"><?= htmlspecialchars(implode("\n", $eduText), ENT_QUOTES, 'UTF-8') ?></textarea>
            </div>
            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-extrabold py-3 px-4 rounded-xl text-sm transition shadow-lg shadow-indigo-600/30">SAVE & UPDATE RESUME (+200 XP)</button>
        </form>
    </div>

    <!-- Live Preview -->
    <div class="bg-white text-slate-900 p-8 rounded-2xl shadow-2xl space-y-6 font-sans">
        <div class="border-b-2 border-indigo-600 pb-4 flex justify-between items-start">
            <div>
                <h2 id="preview_full_name" class="text-3xl font-black text-slate-900 uppercase tracking-tight"><?= htmlspecialchars($resume['full_name'] ?? 'Your Name', ENT_QUOTES, 'UTF-8') ?></h2>
                <p id="preview_professional_title" class="text-indigo-600 font-bold text-base mt-1"><?= htmlspecialchars($resume['professional_title'] ?? 'Virtual Assistant', ENT_QUOTES, 'UTF-8') ?></p>
            </div>
            <div class="text-right text-xs text-slate-600 space-y-0.5">
                <p id="preview_email"><?= htmlspecialchars($resume['email'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
                <p id="preview_phone"><?= htmlspecialchars($resume['phone'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
                <p id="preview_location"><?= htmlspecialchars($resume['location'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
            </div>
        </div>

        <div class="space-y-2">
            <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider border-b border-slate-200 pb-1">Professional Summary</h3>
            <p id="preview_summary" class="text-xs text-slate-700 leading-relaxed"><?= htmlspecialchars($resume['summary'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
        </div>

        <div class="space-y-2">
            <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider border-b border-slate-200 pb-1">Core Competencies</h3>
            <div id="preview_skills" class="flex flex-wrap gap-1.5">
                <?php foreach (($resume['skills'] ?? []) as $sk): ?>
                    <?php if (trim($sk) !== ''): ?>
                        <span class="bg-indigo-50 text-indigo-800 px-2.5 py-1 rounded text-xs font-semibold"><?= htmlspecialchars($sk, ENT_QUOTES, 'UTF-8') ?></span>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="space-y-2">
            <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider border-b border-slate-200 pb-1">Tools & Platforms</h3>
            <div id="preview_tools" class="flex flex-wrap gap-1.5">
                <?php foreach (($resume['tools'] ?? []) as $tl): ?>
                    <?php if (trim($tl) !== ''): ?>
                        <span class="bg-slate-100 text-slate-800 px-2.5 py-1 rounded text-xs font-semibold"><?= htmlspecialchars($tl, ENT_QUOTES, 'UTF-8') ?></span>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="space-y-2">
            <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider border-b border-slate-200 pb-1">Work Experience</h3>
            <div id="preview_experience" class="space-y-3">
                <?php foreach (($resume['experience'] ?? []) as $ex): ?>
                    <div class="space-y-0.5">
                        <div class="flex justify-between items-baseline text-xs font-bold text-slate-900">
                            <span><?= htmlspecialchars($ex['role'] ?? '', ENT_QUOTES, 'UTF-8') ?> — <span class="text-indigo-600"><?= htmlspecialchars($ex['company'] ?? '', ENT_QUOTES, 'UTF-8') ?></span></span>
                            <span class="text-slate-500 font-normal text-[11px]"><?= htmlspecialchars($ex['period'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
                        </div>
                        <?php if (!empty($ex['details'])): ?>
                            <p class="text-xs text-slate-600 leading-normal"><?= htmlspecialchars($ex['details'], ENT_QUOTES, 'UTF-8') ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="space-y-2">
            <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider border-b border-slate-200 pb-1">Education & Certifications</h3>
            <div id="preview_education" class="space-y-2">
                <?php foreach (($resume['education'] ?? []) as $ed): ?>
                    <div class="flex justify-between items-baseline text-xs text-slate-800">
                        <span class="font-bold"><?= htmlspecialchars($ed['degree'] ?? '', ENT_QUOTES, 'UTF-8') ?> <span class="text-slate-500 font-normal">| <?= htmlspecialchars($ed['institution'] ?? '', ENT_QUOTES, 'UTF-8') ?></span></span>
                        <span class="text-slate-500 text-[11px]"><?= htmlspecialchars($ed['year'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fields = ['full_name', 'professional_title', 'email', 'phone', 'location', 'summary'];
    fields.forEach(field => {
        const input = document.getElementById('input_' + field);
        const preview = document.getElementById('preview_' + field);
        if (input && preview) {
            input.addEventListener('input', function() {
                preview.textContent = this.value || (field === 'full_name' ? 'Your Name' : (field === 'professional_title' ? 'Virtual Assistant' : ''));
            });
        }
    });

    const skillsInput = document.getElementById('input_skills');
    const skillsPreview = document.getElementById('preview_skills');
    if (skillsInput && skillsPreview) {
        skillsInput.addEventListener('input', function() {
            const items = this.value.split(',').map(s => s.trim()).filter(s => s.length > 0);
            skillsPreview.innerHTML = items.map(s => `<span class="bg-indigo-50 text-indigo-800 px-2.5 py-1 rounded text-xs font-semibold">${escapeHtml(s)}</span>`).join('');
        });
    }

    const toolsInput = document.getElementById('input_tools');
    const toolsPreview = document.getElementById('preview_tools');
    if (toolsInput && toolsPreview) {
        toolsInput.addEventListener('input', function() {
            const items = this.value.split(',').map(t => t.trim()).filter(t => t.length > 0);
            toolsPreview.innerHTML = items.map(t => `<span class="bg-slate-100 text-slate-800 px-2.5 py-1 rounded text-xs font-semibold">${escapeHtml(t)}</span>`).join('');
        });
    }

    const expInput = document.getElementById('input_experience');
    const expPreview = document.getElementById('preview_experience');
    if (expInput && expPreview) {
        expInput.addEventListener('input', function() {
            const lines = this.value.split('\n').map(l => l.trim()).filter(l => l.length > 0);
            expPreview.innerHTML = lines.map(line => {
                const parts = line.split('|').map(p => p.trim());
                let role = parts[0] || '';
                let company = parts[1] || '';
                let period = parts[2] || '';
                let details = parts[3] || (parts.length === 1 ? parts[0] : '');
                if (parts.length === 1) { role = 'Virtual Assistant Specialist'; company = 'Remote Client Operations'; period = '2025 - Present'; }
                return `<div class="space-y-0.5">
                    <div class="flex justify-between items-baseline text-xs font-bold text-slate-900">
                        <span>${escapeHtml(role)} — <span class="text-indigo-600">${escapeHtml(company)}</span></span>
                        <span class="text-slate-500 font-normal text-[11px]">${escapeHtml(period)}</span>
                    </div>
                    ${details ? `<p class="text-xs text-slate-600 leading-normal">${escapeHtml(details)}</p>` : ''}
                </div>`;
            }).join('');
        });
    }

    const eduInput = document.getElementById('input_education');
    const eduPreview = document.getElementById('preview_education');
    if (eduInput && eduPreview) {
        eduInput.addEventListener('input', function() {
            const lines = this.value.split('\n').map(l => l.trim()).filter(l => l.length > 0);
            eduPreview.innerHTML = lines.map(line => {
                const parts = line.split('|').map(p => p.trim());
                let degree = parts[0] || '';
                let institution = parts[1] || 'FreelanceQuest Academy';
                let year = parts[2] || '2025';
                return `<div class="flex justify-between items-baseline text-xs text-slate-800">
                    <span class="font-bold">${escapeHtml(degree)} <span class="text-slate-500 font-normal">| ${escapeHtml(institution)}</span></span>
                    <span class="text-slate-500 text-[11px]">${escapeHtml(year)}</span>
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
