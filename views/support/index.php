<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="space-y-8">
    <!-- Header Banner -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-3 py-1 bg-amber-500/10 border border-amber-500/20 text-amber-400 text-xs font-semibold rounded-full uppercase tracking-wider">
                    🎧 Help Center & Support
                </span>
            </div>
            <h1 class="text-3xl font-extrabold text-white mt-2">Support Tickets & Helpdesk</h1>
            <p class="text-slate-400 text-sm mt-1">Get fast assistance from our technical team, course mentors, and student support specialist.</p>
        </div>
        <div>
            <a href="/support/create" class="inline-flex items-center gap-2 px-5 py-3 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold rounded-xl shadow-lg shadow-amber-500/20 transition-all text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Create New Support Ticket
            </a>
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

    <!-- Overview Stats -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
        <div class="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-5">
            <div class="text-xs text-slate-400 font-medium">Open Tickets</div>
            <div class="text-2xl font-black text-amber-400 mt-1"><?= number_format($stats['open']) ?></div>
        </div>
        <div class="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-5">
            <div class="text-xs text-slate-400 font-medium">In Progress</div>
            <div class="text-2xl font-black text-sky-400 mt-1"><?= number_format($stats['in_progress']) ?></div>
        </div>
        <div class="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-5">
            <div class="text-xs text-slate-400 font-medium">Resolved</div>
            <div class="text-2xl font-black text-emerald-400 mt-1"><?= number_format($stats['resolved']) ?></div>
        </div>
        <div class="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-5">
            <div class="text-xs text-slate-400 font-medium">Total Submitted</div>
            <div class="text-2xl font-black text-indigo-400 mt-1"><?= number_format($stats['total']) ?></div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="flex flex-wrap items-center gap-2 mb-6 border-b border-slate-800 pb-4">
        <a href="/support" class="px-4 py-2 rounded-xl text-xs font-semibold transition-all <?= !$status ? 'bg-indigo-600 text-white' : 'bg-slate-900 text-slate-400 hover:text-white border border-slate-800' ?>">
            All Tickets (<?= $stats['total'] ?>)
        </a>
        <a href="/support?status=open" class="px-4 py-2 rounded-xl text-xs font-semibold transition-all <?= $status === 'open' ? 'bg-amber-500 text-slate-950 font-bold' : 'bg-slate-900 text-slate-400 hover:text-white border border-slate-800' ?>">
            Open (<?= $stats['open'] ?>)
        </a>
        <a href="/support?status=in_progress" class="px-4 py-2 rounded-xl text-xs font-semibold transition-all <?= $status === 'in_progress' ? 'bg-sky-500 text-slate-950 font-bold' : 'bg-slate-900 text-slate-400 hover:text-white border border-slate-800' ?>">
            In Progress (<?= $stats['in_progress'] ?>)
        </a>
        <a href="/support?status=resolved" class="px-4 py-2 rounded-xl text-xs font-semibold transition-all <?= $status === 'resolved' ? 'bg-emerald-500 text-slate-950 font-bold' : 'bg-slate-900 text-slate-400 hover:text-white border border-slate-800' ?>">
            Resolved (<?= $stats['resolved'] ?>)
        </a>
        <a href="/support?status=closed" class="px-4 py-2 rounded-xl text-xs font-semibold transition-all <?= $status === 'closed' ? 'bg-slate-700 text-white' : 'bg-slate-900 text-slate-400 hover:text-white border border-slate-800' ?>">
            Closed (<?= $stats['closed'] ?>)
        </a>
    </div>

    <!-- Tickets List Table / Empty State -->
    <?php if (empty($tickets)): ?>
        <div class="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-12 text-center">
            <div class="w-16 h-16 mx-auto bg-amber-500/10 border border-amber-500/20 rounded-2xl flex items-center justify-center text-amber-400 text-2xl mb-4">
                🎫
            </div>
            <h3 class="text-lg font-bold text-white">No Support Tickets Found</h3>
            <p class="text-slate-400 text-sm mt-1 max-w-md mx-auto">Need help with your account, lesson access, certificates, or subscription? Submit a support ticket and our team will get back to you!</p>
            <a href="/support/create" class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-500 text-slate-950 font-bold rounded-xl text-xs mt-6 hover:bg-amber-400 transition-all">
                Submit Your First Ticket
            </a>
        </div>
    <?php else: ?>
        <div class="bg-slate-900/60 border border-slate-800/80 rounded-2xl overflow-hidden shadow-xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-800/80 bg-slate-950/50 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                            <th class="p-4">Ticket ID</th>
                            <th class="p-4">Subject</th>
                            <th class="p-4">Category</th>
                            <th class="p-4">Priority</th>
                            <th class="p-4">Status</th>
                            <th class="p-4">Replies</th>
                            <th class="p-4">Updated</th>
                            <th class="p-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/50 text-xs">
                        <?php foreach ($tickets as $t): ?>
                            <tr class="hover:bg-slate-800/30 transition-colors">
                                <td class="p-4 font-mono font-bold text-amber-400">
                                    #<?= htmlspecialchars($t['ticket_number']) ?>
                                </td>
                                <td class="p-4 font-semibold text-white">
                                    <a href="/support/tickets/<?= $t['id'] ?>" class="hover:text-amber-400 transition-colors">
                                        <?= htmlspecialchars($t['subject']) ?>
                                    </a>
                                </td>
                                <td class="p-4 text-slate-300">
                                    <span class="px-2.5 py-1 bg-slate-800 text-slate-300 rounded-lg text-[11px] border border-slate-700">
                                        <?= htmlspecialchars($t['category']) ?>
                                    </span>
                                </td>
                                <td class="p-4">
                                    <?php if ($t['priority'] === 'High' || $t['priority'] === 'Urgent'): ?>
                                        <span class="px-2 py-0.5 bg-rose-500/10 text-rose-400 border border-rose-500/20 rounded text-[10px] font-bold">
                                            <?= htmlspecialchars($t['priority']) ?>
                                        </span>
                                    <?php elseif ($t['priority'] === 'Medium'): ?>
                                        <span class="px-2 py-0.5 bg-amber-500/10 text-amber-400 border border-amber-500/20 rounded text-[10px] font-bold">
                                            Medium
                                        </span>
                                    <?php else: ?>
                                        <span class="px-2 py-0.5 bg-slate-800 text-slate-400 rounded text-[10px] font-medium">
                                            Low
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="p-4">
                                    <?php if ($t['status'] === 'open'): ?>
                                        <span class="px-2.5 py-1 bg-amber-500/10 text-amber-400 border border-amber-500/20 rounded-full text-[11px] font-bold inline-flex items-center gap-1.5">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                                            Open
                                        </span>
                                    <?php elseif ($t['status'] === 'in_progress'): ?>
                                        <span class="px-2.5 py-1 bg-sky-500/10 text-sky-400 border border-sky-500/20 rounded-full text-[11px] font-bold inline-flex items-center gap-1.5">
                                            <span class="w-1.5 h-1.5 rounded-full bg-sky-400"></span>
                                            In Progress
                                        </span>
                                    <?php elseif ($t['status'] === 'resolved'): ?>
                                        <span class="px-2.5 py-1 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-full text-[11px] font-bold inline-flex items-center gap-1.5">
                                            ✓ Resolved
                                        </span>
                                    <?php else: ?>
                                        <span class="px-2.5 py-1 bg-slate-800 text-slate-400 border border-slate-700 rounded-full text-[11px] font-medium">
                                            Closed
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="p-4 text-slate-400">
                                    💬 <?= $t['replies_count'] ?>
                                </td>
                                <td class="p-4 text-slate-400 font-mono text-[11px]">
                                    <?= date('M d, Y g:i A', strtotime($t['updated_at'])) ?>
                                </td>
                                <td class="p-4 text-right">
                                    <a href="/support/tickets/<?= $t['id'] ?>" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-white rounded-lg text-[11px] font-medium transition-colors border border-slate-700">
                                        View Thread &rarr;
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
