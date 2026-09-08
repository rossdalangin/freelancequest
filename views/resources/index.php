<?php require __DIR__ . '/../layout/header.php'; ?>
<div class="space-y-8">
    <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl">
        <h1 class="text-3xl font-extrabold text-white">RESOURCE VAULT</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($resources as $res): ?>
        <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl space-y-4">
            <span class="text-xs text-indigo-400 font-bold">LEVEL <?= $res['level_number'] ?></span>
            <h3 class="font-extrabold text-white text-base"><?= htmlspecialchars($res['title']) ?></h3>
            <p class="text-xs text-slate-400"><?= htmlspecialchars($res['description']) ?></p>
            <a href="<?= htmlspecialchars($res['file_content_or_url']) ?>" target="_blank" class="block text-center bg-slate-800 hover:bg-slate-700 text-slate-100 font-bold py-2 px-4 rounded-xl text-xs">DOWNLOAD &rarr;</a>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>
