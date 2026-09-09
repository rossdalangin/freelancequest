<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="space-y-8">

    <div class="bg-gradient-to-r from-amber-900/60 via-slate-900 to-slate-900 border border-amber-500/30 rounded-2xl p-6 sm:p-8 relative overflow-hidden shadow-2xl">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 relative z-10">
            <div class="space-y-2 max-w-2xl">
                <div class="flex items-center gap-3">
                    <span class="bg-amber-500/20 text-amber-400 font-extrabold text-xs px-3 py-1 rounded-full border border-amber-500/30 uppercase tracking-widest">
                        🏆 FREELANCEQUEST RANKINGS
                    </span>
                </div>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">
                    GLOBAL <span class="text-amber-400 uppercase">LEADERBOARD</span> ⚔️
                </h1>
                <p class="text-slate-300 text-sm leading-relaxed">
                    Compete with fellow freelancers, earn XP through completed masterclass lessons and client missions, level up, and reach the top tier of the FreelanceQuest hall of fame.
                </p>
            </div>

            <div class="flex items-center gap-2 bg-slate-950/80 p-1.5 rounded-xl border border-slate-800">
                <a href="/leaderboard?sort=level" class="px-3 py-1.5 rounded-lg text-xs font-bold transition <?= $sortBy === 'level' ? 'bg-amber-500 text-slate-950 shadow' : 'text-slate-400 hover:text-white' ?>">
                    ⭐ By Level
                </a>
                <a href="/leaderboard?sort=xp" class="px-3 py-1.5 rounded-lg text-xs font-bold transition <?= $sortBy === 'xp' ? 'bg-indigo-600 text-white shadow' : 'text-slate-400 hover:text-white' ?>">
                    ⚡ By XP
                </a>
                <a href="/leaderboard?sort=coins" class="px-3 py-1.5 rounded-lg text-xs font-bold transition <?= $sortBy === 'coins' ? 'bg-amber-400 text-slate-950 shadow' : 'text-slate-400 hover:text-white' ?>">
                    🪙 By Coins
                </a>
            </div>
        </div>
    </div>

    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl space-y-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-800 text-xs font-extrabold text-slate-400 uppercase tracking-wider">
                        <th class="py-4 px-4">RANK</th>
                        <th class="py-4 px-4">PLAYER / FREELANCER</th>
                        <th class="py-4 px-4">LEVEL</th>
                        <th class="py-4 px-4">XP</th>
                        <th class="py-4 px-4">COINS</th>
                        <th class="py-4 px-4">STREAK</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 text-sm">
                    <?php
                    $rankOffset = $offset + 1;
                    foreach ($rankings as $index => $player):
                        $rank = $rankOffset + $index;
                        $isCurrentUser = $player['id'] == $user['id'];
                    ?>
                    <tr class="transition hover:bg-slate-800/40 <?= $isCurrentUser ? 'bg-indigo-950/40 border-l-4 border-indigo-500' : '' ?>">
                        <td class="py-4 px-4 font-black">
                            <?php if ($rank === 1): ?>
                                <span class="text-xl">🥇</span> <span class="text-amber-400">#1</span>
                            <?php elseif ($rank === 2): ?>
                                <span class="text-xl">🥈</span> <span class="text-slate-300">#2</span>
                            <?php elseif ($rank === 3): ?>
                                <span class="text-xl">🥉</span> <span class="text-amber-600">#3</span>
                            <?php else: ?>
                                <span class="text-slate-400">#<?= $rank ?></span>
                            <?php endif; ?>
                        </td>

                        <td class="py-4 px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-indigo-600/30 border border-indigo-500/40 flex items-center justify-center font-black text-indigo-400 text-sm overflow-hidden">
                                    <?php if (!empty($player['avatar_url'])): ?>
                                        <img src="<?= htmlspecialchars($player['avatar_url']) ?>" alt="Avatar" class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <?= strtoupper(substr($player['name'], 0, 2)) ?>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <div class="font-extrabold text-white flex items-center gap-2">
                                        <span><?= htmlspecialchars($player['name']) ?></span>
                                        <?php if ($isCurrentUser): ?>
                                            <span class="bg-indigo-500/20 text-indigo-400 text-[10px] px-2 py-0.5 rounded-full border border-indigo-500/30">YOU</span>
                                        <?php endif; ?>
                                    </div>
                                    <p class="text-xs text-slate-400 line-clamp-1"><?= htmlspecialchars($player['headline'] ?? '@' . $player['username']) ?></p>
                                </div>
                            </div>
                        </td>

                        <td class="py-4 px-4">
                            <span class="bg-indigo-500/20 text-indigo-400 font-extrabold text-xs px-2.5 py-1 rounded-full border border-indigo-500/30">
                                LVL <?= $player['level'] ?>
                            </span>
                        </td>

                        <td class="py-4 px-4 font-bold text-indigo-400">
                            ⚡ <?= number_format($player['xp']) ?>
                        </td>

                        <td class="py-4 px-4 font-bold text-amber-400">
                            🪙 <?= number_format($player['coins']) ?>
                        </td>

                        <td class="py-4 px-4 font-bold text-orange-400">
                            🔥 <?= $player['streak_count'] ?> Days
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination Controls -->
        <?php if ($totalPages > 1): ?>
        <div class="flex justify-between items-center pt-4 border-t border-slate-800 text-xs font-bold text-slate-400">
            <span>Page <?= $page ?> of <?= $totalPages ?> (Total <?= number_format($totalUsers) ?> Gamers)</span>

            <div class="flex gap-2">
                <?php if ($page > 1): ?>
                    <a href="/leaderboard?page=<?= $page - 1 ?>&sort=<?= $sortBy ?>" class="bg-slate-800 hover:bg-slate-700 text-white px-3 py-1.5 rounded-lg border border-slate-700">&larr; Previous</a>
                <?php endif; ?>

                <?php if ($page < $totalPages): ?>
                    <a href="/leaderboard?page=<?= $page + 1 ?>&sort=<?= $sortBy ?>" class="bg-slate-800 hover:bg-slate-700 text-white px-3 py-1.5 rounded-lg border border-slate-700">Next &rarr;</a>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
