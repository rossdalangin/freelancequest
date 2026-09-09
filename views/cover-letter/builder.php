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

                <button type="submit" class="w-full bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-400 hover:to-orange-400 text-slate-950 font-black py-3 rounded-xl text-xs transition shadow-lg shadow-amber-500/20">
                    GENERATE PROPOSAL LETTER (+100 XP) &rarr;
                </button>
            </form>
        </div>

        <!-- GENERATED COVER LETTER OUTPUT -->
        <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl space-y-4 shadow-xl flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between border-b border-slate-800 pb-3 mb-4">
                    <h3 class="text-lg font-bold text-white">GENERATED PROPOSAL COPY</h3>
                    <?php if (!empty($generatedLetter)): ?>
                    <button onclick="navigator.clipboard.writeText(document.getElementById('letterOutput').innerText); alert('Cover letter copied to clipboard!');" class="bg-slate-800 hover:bg-slate-700 text-amber-400 font-bold px-3 py-1 rounded text-xs border border-slate-700">
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
<?php require __DIR__ . '/../layout/footer.php'; ?>
