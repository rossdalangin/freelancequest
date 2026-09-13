<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="space-y-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <a href="/admin" class="text-xs text-slate-400 hover:text-white transition-colors">&larr; Admin Dashboard</a>
                <span class="text-slate-600">•</span>
                <span class="px-2.5 py-0.5 bg-amber-500/10 text-amber-400 text-[10px] font-bold rounded uppercase tracking-wider">Digital Shop Management</span>
            </div>
            <h1 class="text-3xl font-extrabold text-white mt-2">Digital Products & Assets Catalog</h1>
            <p class="text-slate-400 text-xs mt-1">Add, edit, or manage digital SOP bundles, proposal templates, and downloadable assets available in the shop.</p>
        </div>
        <div>
            <a href="/shop" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl text-xs transition-colors">
                View Public Shop &rarr;
            </a>
        </div>
    </div>

    <!-- Create New Digital Product Card -->
    <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl space-y-4 shadow-xl">
        <h2 class="text-xl font-extrabold text-white flex items-center gap-2">
            <span>🛍️</span> CREATE NEW DIGITAL PRODUCT
        </h2>
        <form action="/admin/products/create" method="POST" class="space-y-4">
            <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1">Product Title *</label>
                    <input type="text" name="title" required placeholder="e.g., Executive VA Client Agreement Template" class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-xs text-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1">Category *</label>
                    <select name="category" class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-xs text-white">
                        <option value="SOP Vault">SOP Vault</option>
                        <option value="Proposal Templates">Proposal Templates</option>
                        <option value="Outreach Scripts">Outreach Scripts</option>
                        <option value="Social Media Templates">Social Media Templates</option>
                        <option value="E-Books & Playbooks">E-Books & Playbooks</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1">Price ($ USD)</label>
                    <input type="number" step="0.01" name="price_usd" value="19.00" class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-xs text-white font-bold">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1">Price (Game Coins)</label>
                    <input type="number" name="price_coins" value="300" class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-xs text-white font-bold">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1">Icon / Emoji</label>
                    <input type="text" name="image_url" value="📦" class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-xs text-white">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1">Product File Download URL *</label>
                <input type="text" name="file_url" required placeholder="/downloads/sop_vault_pack.pdf" class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-xs text-white font-mono">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1">Detailed Description *</label>
                <textarea name="description" rows="3" required placeholder="Describe what digital files, templates, or SOPs are included..." class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-xs text-white"></textarea>
            </div>

            <button type="submit" class="bg-amber-500 hover:bg-amber-400 text-slate-950 font-black py-3 px-6 rounded-xl text-xs transition shadow-lg">
                + PUBLISH DIGITAL PRODUCT
            </button>
        </form>
    </div>

    <!-- Existing Products Table -->
    <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl space-y-4 shadow-xl">
        <h2 class="text-xl font-extrabold text-white">📦 STORE PRODUCTS CATALOG (<?= count($products) ?>)</h2>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-slate-800 text-slate-400 uppercase tracking-wider font-bold">
                        <th class="py-3 px-4">Icon</th>
                        <th class="py-3 px-4">Title</th>
                        <th class="py-3 px-4">Category</th>
                        <th class="py-3 px-4">USD Price</th>
                        <th class="py-3 px-4">Coin Price</th>
                        <th class="py-3 px-4">File Path</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800 text-slate-200">
                    <?php foreach ($products as $p): ?>
                        <tr class="hover:bg-slate-950/50 transition">
                            <td class="py-3 px-4 text-xl"><?= htmlspecialchars($p['image_url'] ?? '📦') ?></td>
                            <td class="py-3 px-4 font-bold text-white"><?= htmlspecialchars($p['title']) ?></td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 bg-slate-800 text-slate-300 border border-slate-700 rounded text-[10px] font-bold">
                                    <?= htmlspecialchars($p['category']) ?>
                                </span>
                            </td>
                            <td class="py-3 px-4 text-emerald-400 font-bold">$<?= number_format($p['price_usd'], 2) ?></td>
                            <td class="py-3 px-4 text-amber-400 font-bold">🪙 <?= number_format($p['price_coins']) ?></td>
                            <td class="py-3 px-4 font-mono text-[11px] text-slate-400 max-w-xs truncate"><?= htmlspecialchars($p['file_url']) ?></td>
                            <td class="py-3 px-4 text-right">
                                <form action="/admin/products/<?= $p['id'] ?>/delete" method="POST" class="inline" onsubmit="return confirm('Delete this digital product?');">
                                    <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">
                                    <button type="submit" class="bg-rose-500/20 hover:bg-rose-500/40 text-rose-300 border border-rose-500/30 px-3 py-1 rounded-lg text-xs font-bold transition">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
