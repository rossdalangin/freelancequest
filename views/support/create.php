<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="max-w-4xl mx-auto space-y-6">
    <div class="mb-6">
        <a href="/support" class="text-xs text-slate-400 hover:text-white transition-colors inline-flex items-center gap-1">
            &larr; Back to Support Center
        </a>
        <h1 class="text-2xl font-extrabold text-white mt-2">Submit a Support Ticket</h1>
        <p class="text-slate-400 text-xs mt-1">Please describe your inquiry or technical issue in detail. Our help desk specialists will respond promptly.</p>
    </div>

    <?php if ($error): ?>
        <div class="mb-6 p-4 bg-rose-500/10 border border-rose-500/30 rounded-xl text-rose-400 text-sm flex items-center gap-3">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span><?= htmlspecialchars($error) ?></span>
        </div>
    <?php endif; ?>

    <div class="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-6 sm:p-8 shadow-2xl">
        <form action="/support/create" method="POST" class="space-y-6">
            <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">

            <!-- Subject -->
            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-2 uppercase tracking-wider">
                    Ticket Subject <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="subject" required placeholder="e.g., Cannot access Level 4 Social Media VA lesson video or Quiz error" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-amber-500 transition-colors">
            </div>

            <!-- Category & Priority -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-2 uppercase tracking-wider">Category</label>
                    <select name="category" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-xs text-white focus:outline-none focus:border-amber-500 transition-colors">
                        <option value="General Inquiry">General Inquiry</option>
                        <option value="Technical & Platform Issue">Technical & Platform Issue</option>
                        <option value="Course & Learning Support">Course & Learning Support</option>
                        <option value="Certificates & Verification">Certificates & Verification</option>
                        <option value="Billing & Subscription">Billing & Subscription</option>
                        <option value="Resume & Portfolio Builder">Resume & Portfolio Builder</option>
                        <option value="Community & Coaching">Community & Coaching</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-2 uppercase tracking-wider">Priority Level</label>
                    <select name="priority" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-xs text-white focus:outline-none focus:border-amber-500 transition-colors">
                        <option value="Low">Low - Minor question or feedback</option>
                        <option value="Medium" selected>Medium - Normal assistance required</option>
                        <option value="High">High - Urgent issue blocking course progress</option>
                        <option value="Urgent">Urgent - Account or billing emergency</option>
                    </select>
                </div>
            </div>

            <!-- Detailed Message -->
            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-2 uppercase tracking-wider">
                    Detailed Message & Description <span class="text-rose-500">*</span>
                </label>
                <textarea name="message" rows="6" required placeholder="Please provide exact details, error messages, or steps to reproduce the issue..." class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-amber-500 transition-colors"></textarea>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-800">
                <a href="/support" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold rounded-xl text-xs transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-amber-500 hover:bg-amber-400 text-slate-950 font-extrabold rounded-xl text-xs shadow-lg shadow-amber-500/20 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    Submit Support Ticket
                </button>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
