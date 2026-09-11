<?php require __DIR__ . '/../layout/header.php'; ?>
<div class="max-w-4xl mx-auto space-y-8 py-4">
    <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 shadow-xl">
        <div>
            <span class="text-xs font-bold text-amber-400 uppercase tracking-wider">CAREER ASSET BUILDER</span>
            <h1 class="text-3xl font-black text-white">COVER LETTER GENERATOR</h1>
            <p class="text-slate-400 text-xs mt-1">Generate high-converting, personalized cover letter proposals for client applications.</p>
        </div>
        <span class="bg-indigo-500/20 text-indigo-400 font-extrabold text-xs px-3.5 py-1.5 rounded-full border border-indigo-500/30">
            LEVEL 8 &bull; PROPOSAL MASTER
        </span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- FORM INPUTS -->
        <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl space-y-4 shadow-xl">
            <h3 class="text-lg font-bold text-white border-b border-slate-800 pb-3">PROPOSAL INPUTS</h3>

            <form action="/cover-letter-builder/generate" method="POST" class="space-y-4">
                <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">

                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Target Job Title</label>
                    <input type="text" name="job_title" required placeholder="Executive Virtual Assistant" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Client / Company Name</label>
                    <input type="text" name="company" required placeholder="Acme Tech Solutions" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Key Skills to Highlight</label>
                    <input type="text" name="skills" value="Google Workspace, Email Management, Asana, Slack" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase mb-1">Relevant Experience & Value</label>
                    <textarea name="experience" rows="3" required placeholder="1+ years in executive administrative support and inbox management" class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-xs text-white focus:outline-none focus:border-indigo-500">Managed C-suite calendar scheduling, travel itineraries, and inbox zero organization with 99% accuracy.</textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                    <button type="submit" name="mode" value="kiss" class="w-full bg-gradient-to-r from-amber-500 to-yellow-500 hover:from-amber-400 hover:to-yellow-400 text-slate-950 font-black py-3 px-4 rounded-xl text-xs transition shadow-lg shadow-amber-500/20 border border-amber-400/50 flex items-center justify-center gap-1.5">
                        <span>⚡</span> GENERATE KISS METHOD LETTER (+100 XP)
                    </button>
                    <button type="submit" name="mode" value="standard" class="w-full bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold py-3 px-4 rounded-xl text-xs transition border border-slate-700 flex items-center justify-center gap-1.5">
                        <span>📄</span> Standard Proposal Letter
                    </button>
                </div>
            </form>
        </div>

        <!-- GENERATED COVER LETTER OUTPUT -->
        <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl space-y-4 shadow-xl flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between border-b border-slate-800 pb-3 mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-white">GENERATED PROPOSAL COPY</h3>
                        <?php if (!empty($generatedLetter)): ?>
                            <span class="text-[10px] font-extrabold text-amber-400 bg-amber-500/20 px-2 py-0.5 rounded border border-amber-500/30 uppercase tracking-wider">
                                <?= htmlspecialchars($letterMode ?? 'KISS Method') ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    <?php if (!empty($generatedLetter)): ?>
                    <button id="copyBtn" onclick="copyCoverLetterText();" class="bg-slate-800 hover:bg-slate-700 text-amber-400 font-bold px-3 py-1 rounded text-xs border border-slate-700 transition">
                        📋 COPY TEXT
                    </button>
                    <?php endif; ?>
                </div>

                <?php if (!empty($generatedLetter)): ?>
                    <div id="letterOutput" class="bg-slate-950 border border-slate-800 p-5 rounded-xl font-mono text-xs text-slate-200 whitespace-pre-line leading-relaxed">
                        <?= htmlspecialchars($generatedLetter) ?>
                    </div>
                <?php else: ?>
                    <div class="bg-slate-950/60 border border-dashed border-slate-800 p-8 rounded-xl text-center space-y-2">
                        <span class="text-3xl">📄</span>
                        <p class="text-xs text-slate-400 font-bold">Fill in the target job details on the left and click 'Generate Proposal Letter' to build your custom pitch!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function copyCoverLetterText() {
    const outputElem = document.getElementById('letterOutput');
    const btn = document.getElementById('copyBtn');
    if (!outputElem) return;

    const textToCopy = outputElem.innerText || outputElem.textContent;

    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(textToCopy).then(() => {
            showCopySuccess(btn);
        }).catch(err => {
            fallbackCopyText(textToCopy, btn);
        });
    } else {
        fallbackCopyText(textToCopy, btn);
    }
}

function fallbackCopyText(text, btn) {
    const textArea = document.createElement("textarea");
    textArea.value = text;
    textArea.style.position = "fixed";
    textArea.style.left = "-999999px";
    textArea.style.top = "-999999px";
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();

    try {
        document.execCommand('copy');
        showCopySuccess(btn);
    } catch (err) {
        alert('Copying failed. Please manually select and copy the text.');
    } finally {
        document.body.removeChild(textArea);
    }
}

function showCopySuccess(btn) {
    if (!btn) return;
    const originalText = btn.innerHTML;
    btn.innerHTML = '✅ COPIED!';
    btn.classList.add('bg-emerald-600', 'text-white');
    btn.classList.remove('bg-slate-800', 'text-amber-400');

    setTimeout(() => {
        btn.innerHTML = originalText;
        btn.classList.remove('bg-emerald-600', 'text-white');
        btn.classList.add('bg-slate-800', 'text-amber-400');
    }, 2000);
}
</script>

<?php require __DIR__ . '/../layout/footer.php'; ?>
