<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="max-w-5xl mx-auto space-y-6">
    <!-- Top Navigation -->
    <div class="flex items-center justify-between mb-6">
        <a href="/support" class="text-xs text-slate-400 hover:text-white transition-colors inline-flex items-center gap-1">
            &larr; Back to Tickets List
        </a>
        <div class="flex items-center gap-2">
            <?php if (($user['role'] ?? 'student') === 'admin'): ?>
                <a href="/admin/tickets" class="px-3 py-1.5 bg-indigo-600/20 text-indigo-400 border border-indigo-500/30 rounded-lg text-xs font-semibold hover:bg-indigo-600/30 transition-colors">
                    🛡️ Admin Help Desk Panel
                </a>
            <?php endif; ?>
            <?php if ($ticket['status'] !== 'closed'): ?>
                <form action="/support/tickets/<?= $ticket['id'] ?>/close" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to mark this ticket as closed?');">
                    <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">
                    <button type="submit" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 border border-slate-700 rounded-lg text-xs font-semibold transition-colors">
                        Close Ticket
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <!-- Ticket Summary Banner -->
    <div class="bg-slate-900/80 border border-slate-800/80 rounded-2xl p-6 mb-8 shadow-xl">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b border-slate-800/80">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="font-mono text-amber-400 font-extrabold text-sm">#<?= htmlspecialchars($ticket['ticket_number']) ?></span>
                    <span class="px-2.5 py-0.5 bg-slate-800 text-slate-300 rounded text-[11px] font-medium border border-slate-700">
                        <?= htmlspecialchars($ticket['category']) ?>
                    </span>
                </div>
                <h1 class="text-2xl font-black text-white"><?= htmlspecialchars($ticket['subject']) ?></h1>
            </div>
            <div class="flex items-center gap-3">
                <!-- Status Badge -->
                <?php if ($ticket['status'] === 'open'): ?>
                    <span class="px-3 py-1 bg-amber-500/10 text-amber-400 border border-amber-500/20 rounded-full text-xs font-bold inline-flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span> Open
                    </span>
                <?php elseif ($ticket['status'] === 'in_progress'): ?>
                    <span class="px-3 py-1 bg-sky-500/10 text-sky-400 border border-sky-500/20 rounded-full text-xs font-bold inline-flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-sky-400"></span> In Progress
                    </span>
                <?php elseif ($ticket['status'] === 'resolved'): ?>
                    <span class="px-3 py-1 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-full text-xs font-bold inline-flex items-center gap-1.5">
                        ✓ Resolved
                    </span>
                <?php else: ?>
                    <span class="px-3 py-1 bg-slate-800 text-slate-400 border border-slate-700 rounded-full text-xs font-medium">
                        Closed
                    </span>
                <?php endif; ?>

                <!-- Priority Badge -->
                <span class="px-3 py-1 bg-slate-800 text-slate-300 border border-slate-700 rounded-full text-xs font-semibold">
                    Priority: <?= htmlspecialchars($ticket['priority']) ?>
                </span>
            </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-4 text-xs">
            <div>
                <span class="text-slate-500">Submitted By:</span>
                <p class="text-slate-300 font-semibold mt-0.5"><?= htmlspecialchars($ticket['user_name']) ?> (<?= htmlspecialchars($ticket['user_email']) ?>)</p>
            </div>
            <div>
                <span class="text-slate-500">Created Date:</span>
                <p class="text-slate-300 font-mono mt-0.5"><?= date('M d, Y g:i A', strtotime($ticket['created_at'])) ?></p>
            </div>
            <div>
                <span class="text-slate-500">Last Activity:</span>
                <p class="text-slate-300 font-mono mt-0.5"><?= date('M d, Y g:i A', strtotime($ticket['updated_at'])) ?></p>
            </div>
            <div>
                <span class="text-slate-500">Total Replies:</span>
                <p class="text-slate-300 font-bold mt-0.5">💬 <?= count($replies) ?></p>
            </div>
        </div>
    </div>

    <!-- Notifications -->
    <?php if ($success): ?>
        <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-xl text-emerald-400 text-sm flex items-center gap-3">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span><?= htmlspecialchars($success) ?></span>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="mb-6 p-4 bg-rose-500/10 border border-rose-500/30 rounded-xl text-rose-400 text-sm flex items-center gap-3">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span><?= htmlspecialchars($error) ?></span>
        </div>
    <?php endif; ?>

    <!-- Conversation Thread -->
    <div class="space-y-6 mb-8">
        <?php foreach ($replies as $index => $r): ?>
            <div class="bg-slate-900/60 border <?= $r['is_admin_reply'] ? 'border-amber-500/30 bg-amber-500/[0.02]' : 'border-slate-800/80' ?> rounded-2xl p-6 shadow-md">
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-800/60">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center font-bold text-sm <?= $r['is_admin_reply'] ? 'bg-amber-500 text-slate-950' : 'bg-indigo-600 text-white' ?>">
                            <?= strtoupper(substr($r['author_name'] ?? 'U', 0, 1)) ?>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-white text-sm"><?= htmlspecialchars($r['author_name']) ?></span>
                                <?php if ($r['is_admin_reply']): ?>
                                    <span class="px-2 py-0.5 bg-amber-500/20 text-amber-400 border border-amber-500/30 text-[10px] font-extrabold uppercase rounded tracking-wider">
                                        🛡️ Support Specialist / Admin
                                    </span>
                                <?php else: ?>
                                    <span class="px-2 py-0.5 bg-slate-800 text-slate-400 text-[10px] font-medium rounded">
                                        Student
                                    </span>
                                <?php endif; ?>
                            </div>
                            <span class="text-[11px] text-slate-500 font-mono">
                                Posted <?= date('M d, Y \a\t g:i A', strtotime($r['created_at'])) ?>
                            </span>
                        </div>
                    </div>
                    <?php if ($index === 0): ?>
                        <span class="px-2.5 py-1 bg-slate-800/80 text-slate-400 rounded-lg text-[10px] uppercase tracking-wider font-semibold">
                            Original Ticket Details
                        </span>
                    <?php endif; ?>
                </div>

                <div class="text-xs leading-relaxed text-slate-300 space-y-2 whitespace-pre-line">
                    <?= nl2br(htmlspecialchars($r['message'])) ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Post Reply Form -->
    <?php if ($ticket['status'] !== 'closed'): ?>
        <div class="bg-slate-900/80 border border-slate-800/80 rounded-2xl p-6 shadow-xl">
            <h3 class="text-sm font-bold text-white mb-4 flex items-center gap-2">
                💬 Post Reply to Support Thread
            </h3>
            <form action="/support/tickets/<?= $ticket['id'] ?>/reply" method="POST" class="space-y-4">
                <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::generateCsrfToken() ?>">
                <div>
                    <textarea name="message" rows="4" required placeholder="Type your response or additional information here..." class="w-full bg-slate-950 border border-slate-800 rounded-xl p-4 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-amber-500 transition-colors"></textarea>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-[11px] text-slate-500">Our support team receives notifications for all replies instantly.</span>
                    <button type="submit" class="px-6 py-2.5 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold rounded-xl text-xs shadow-lg shadow-amber-500/20 transition-all flex items-center gap-2">
                        Send Reply
                    </button>
                </div>
            </form>
        </div>
    <?php else: ?>
        <div class="bg-slate-900/40 border border-slate-800/80 rounded-2xl p-6 text-center text-slate-400 text-xs">
            🔒 This support ticket is closed. If you still require assistance, please <a href="/support/create" class="text-amber-400 font-semibold underline hover:text-amber-300">submit a new ticket</a>.
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
