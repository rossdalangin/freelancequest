<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="space-y-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-2">
                <a href="/admin" class="text-xs text-slate-400 hover:text-white transition-colors">&larr; Admin Dashboard</a>
                <span class="text-slate-600">•</span>
                <span class="px-2.5 py-0.5 bg-amber-500/10 text-amber-400 text-[10px] font-bold rounded uppercase tracking-wider">Help Desk Management</span>
            </div>
            <h1 class="text-3xl font-extrabold text-white mt-2">Support Tickets Center</h1>
            <p class="text-slate-400 text-xs mt-1">Review student inquiries, answer support tickets, and manage platform helpdesk SLAs.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="/support/create" class="px-4 py-2 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold rounded-xl text-xs transition-all">
                + Create Ticket As Admin
            </a>
        </div>
    </div>

    <!-- Overview Metrics Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-4 mb-8">
        <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-4">
            <div class="text-xs text-slate-400 font-medium">Open</div>
            <div class="text-2xl font-black text-amber-400 mt-1"><?= number_format($openCount) ?></div>
        </div>
        <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-4">
            <div class="text-xs text-slate-400 font-medium">In Progress</div>
            <div class="text-2xl font-black text-sky-400 mt-1"><?= number_format($inProgressCount) ?></div>
        </div>
        <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-4">
            <div class="text-xs text-slate-400 font-medium">Resolved</div>
            <div class="text-2xl font-black text-emerald-400 mt-1"><?= number_format($resolvedCount) ?></div>
        </div>
        <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-4">
            <div class="text-xs text-slate-400 font-medium">Closed</div>
            <div class="text-2xl font-black text-slate-500 mt-1"><?= number_format($closedCount) ?></div>
        </div>
        <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-4">
            <div class="text-xs text-slate-400 font-medium">Total Tickets</div>
            <div class="text-2xl font-black text-indigo-400 mt-1"><?= number_format($totalCount) ?></div>
        </div>
    </div>

    <!-- Admin Success / Error Alerts -->
    <?php if ($success): ?>
        <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-xl text-emerald-400 text-xs flex items-center gap-3">
            ✓ <span><?= htmlspecialchars($success) ?></span>
        </div>
    <?php endif; ?>

    <!-- Search & Filter Controls -->
    <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-4 mb-6">
        <form action="/admin/tickets" method="GET" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="<?= htmlspecialchars($search ?? '') ?>" placeholder="Search ticket #, subject, student name or email..." class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-amber-500">
            </div>
            <div>
                <select name="status" class="w-full sm:w-auto bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-amber-500">
                    <option value="">All Statuses</option>
                    <option value="open" <?= ($statusFilter ?? '') === 'open' ? 'selected' : '' ?>>Open</option>
                    <option value="in_progress" <?= ($statusFilter ?? '') === 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                    <option value="resolved" <?= ($statusFilter ?? '') === 'resolved' ? 'selected' : '' ?>>Resolved</option>
                    <option value="closed" <?= ($statusFilter ?? '') === 'closed' ? 'selected' : '' ?>>Closed</option>
                </select>
            </div>
            <div>
                <select name="priority" class="w-full sm:w-auto bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-amber-500">
                    <option value="">All Priorities</option>
                    <option value="Low" <?= ($priorityFilter ?? '') === 'Low' ? 'selected' : '' ?>>Low</option>
                    <option value="Medium" <?= ($priorityFilter ?? '') === 'Medium' ? 'selected' : '' ?>>Medium</option>
                    <option value="High" <?= ($priorityFilter ?? '') === 'High' ? 'selected' : '' ?>>High</option>
                    <option value="Urgent" <?= ($priorityFilter ?? '') === 'Urgent' ? 'selected' : '' ?>>Urgent</option>
                </select>
            </div>
            <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl text-xs transition-colors">
                Filter
            </button>
            <?php if ($search || $statusFilter || $priorityFilter): ?>
                <a href="/admin/tickets" class="px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold rounded-xl text-xs transition-colors flex items-center justify-center">
                    Reset
                </a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Tickets Table -->
    <?php if (empty($tickets)): ?>
        <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-12 text-center text-slate-400 text-sm">
            No support tickets match the specified criteria.
        </div>
    <?php else: ?>
        <div class="bg-slate-900/60 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-800 bg-slate-950/50 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                            <th class="p-4">Ticket ID</th>
                            <th class="p-4">Student Name / Email</th>
                            <th class="p-4">Subject</th>
                            <th class="p-4">Category</th>
                            <th class="p-4">Priority</th>
                            <th class="p-4">Status & Update</th>
                            <th class="p-4">Updated</th>
                            <th class="p-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60 text-xs">
                        <?php foreach ($tickets as $t): ?>
                            <tr class="hover:bg-slate-800/30 transition-colors">
                                <td class="p-4 font-mono font-bold text-amber-400">
                                    #<?= htmlspecialchars($t['ticket_number']) ?>
                                </td>
                                <td class="p-4">
                                    <div class="font-bold text-white"><?= htmlspecialchars($t['user_name']) ?></div>
                                    <div class="text-[11px] text-slate-400 font-mono"><?= htmlspecialchars($t['user_email']) ?></div>
                                </td>
                                <td class="p-4 font-semibold text-white max-w-xs truncate">
                                    <a href="/support/tickets/<?= $t['id'] ?>" class="hover:text-amber-400 transition-colors">
                                        <?= htmlspecialchars($t['subject']) ?>
                                    </a>
                                </td>
                                <td class="p-4 text-slate-300">
                                    <span class="px-2 py-0.5 bg-slate-800 text-slate-300 rounded text-[11px] border border-slate-700">
                                        <?= htmlspecialchars($t['category']) ?>
                                    </span>
                                </td>
                                <td class="p-4">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold border <?= $t['priority'] === 'High' || $t['priority'] === 'Urgent' ? 'bg-rose-500/10 text-rose-400 border-rose-500/20' : 'bg-amber-500/10 text-amber-400 border-amber-500/20' ?>">
                                        <?= htmlspecialchars($t['priority']) ?>
                                    </span>
                                </td>
                                <td class="p-4">
                                    <form action="/admin/tickets/<?= $t['id'] ?>/status" method="POST" class="flex items-center gap-2">
                                        <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">
                                        <select name="status" onchange="this.form.submit()" class="bg-slate-950 border border-slate-800 rounded-lg text-[11px] px-2 py-1 text-slate-200 focus:outline-none focus:border-amber-500">
                                            <option value="open" <?= $t['status'] === 'open' ? 'selected' : '' ?>>Open</option>
                                            <option value="in_progress" <?= $t['status'] === 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                                            <option value="resolved" <?= $t['status'] === 'resolved' ? 'selected' : '' ?>>Resolved</option>
                                            <option value="closed" <?= $t['status'] === 'closed' ? 'selected' : '' ?>>Closed</option>
                                        </select>
                                        <input type="hidden" name="priority" value="<?= htmlspecialchars($t['priority']) ?>">
                                    </form>
                                </td>
                                <td class="p-4 text-slate-400 font-mono text-[11px]">
                                    <?= date('M d, Y g:i A', strtotime($t['updated_at'])) ?>
                                </td>
                                <td class="p-4 text-right">
                                    <a href="/support/tickets/<?= $t['id'] ?>" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg text-[11px] font-bold transition-colors">
                                        Reply / Open Thread &rarr;
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
