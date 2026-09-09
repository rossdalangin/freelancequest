<?php require __DIR__ . '/../layout/header.php'; ?>
<div class="space-y-8">
    <div class="flex items-center justify-between bg-slate-900 border border-slate-800 p-6 rounded-2xl">
        <div>
            <span class="text-xs font-bold text-amber-400 uppercase tracking-wider">ADMIN CONTROL CENTER</span>
            <h1 class="text-2xl font-black text-white">QUIZ & QUESTION MANAGEMENT</h1>
        </div>
        <a href="/admin" class="text-xs text-indigo-400 font-bold hover:underline">&larr; Back to Dashboard</a>
    </div>

    <!-- CREATE QUIZ -->
    <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl space-y-4">
        <h3 class="text-lg font-bold text-white">+ CREATE NEW QUIZ</h3>
        <form action="/admin/quizzes/create" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">

            <div>
                <label class="block text-xs font-bold text-slate-400 mb-1">Associated Lesson</label>
                <select name="lesson_id" class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2.5">
                    <?php foreach ($lessons as $les): ?>
                    <option value="<?= $les['id'] ?>"><?= htmlspecialchars($les['title']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-400 mb-1">Quiz Title</label>
                <input type="text" name="title" required value="Knowledge Check Quiz" class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2.5">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-400 mb-1">XP Reward</label>
                <input type="number" name="xp_reward" value="100" class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2.5">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-400 mb-1">Coin Reward</label>
                <input type="number" name="coin_reward" value="25" class="w-full bg-slate-950 border border-slate-800 text-xs text-white rounded-lg p-2.5">
            </div>

            <div class="md:col-span-2">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white font-black py-2.5 px-6 rounded-xl text-xs transition">
                    + CREATE QUIZ
                </button>
            </div>
        </form>
    </div>

    <!-- EXISTING QUIZZES LIST & EDIT -->
    <div class="space-y-6">
        <h3 class="text-xl font-bold text-white">EXISTING QUIZZES & QUESTION EDITING</h3>

        <?php foreach ($quizzes as $q): ?>
        <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl space-y-4">
            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                <div>
                    <span class="text-xs text-indigo-400 font-bold">LESSON: <?= htmlspecialchars($q['lesson_title'] ?? 'General') ?></span>
                    <h4 class="text-lg font-bold text-white"><?= htmlspecialchars($q['title']) ?></h4>
                </div>
                <form action="/admin/quizzes/<?= $q['id'] ?>/delete" method="POST" onsubmit="return confirm('Delete this quiz?');">
                    <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">
                    <button type="submit" class="bg-red-600/20 text-red-400 hover:bg-red-600 hover:text-white font-bold px-3 py-1.5 rounded-lg text-xs transition">DELETE QUIZ</button>
                </form>
            </div>

            <!-- EDIT QUIZ FORM -->
            <form action="/admin/quizzes/<?= $q['id'] ?>/edit" method="POST" class="grid grid-cols-1 sm:grid-cols-3 gap-3 bg-slate-950 p-4 rounded-xl border border-slate-800">
                <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">

                <div>
                    <label class="block text-[11px] font-bold text-slate-400 mb-1">Quiz Title</label>
                    <input type="text" name="title" value="<?= htmlspecialchars($q['title']) ?>" required class="w-full bg-slate-900 border border-slate-800 text-xs text-white rounded p-2">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-400 mb-1">Associated Lesson</label>
                    <select name="lesson_id" class="w-full bg-slate-900 border border-slate-800 text-xs text-white rounded p-2">
                        <?php foreach ($lessons as $les): ?>
                        <option value="<?= $les['id'] ?>" <?= $q['lesson_id'] == $les['id'] ? 'selected' : '' ?>><?= htmlspecialchars($les['title']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-400 mb-1">XP Reward</label>
                        <input type="number" name="xp_reward" value="<?= $q['xp_reward'] ?>" class="w-full bg-slate-900 border border-slate-800 text-xs text-white rounded p-2">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-400 mb-1">Coin Reward</label>
                        <input type="number" name="coin_reward" value="<?= $q['coin_reward'] ?? 25 ?>" class="w-full bg-slate-900 border border-slate-800 text-xs text-white rounded p-2">
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <button type="submit" class="bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold px-3 py-1.5 rounded text-xs">UPDATE QUIZ DETAILS</button>
                </div>
            </form>

            <!-- QUESTIONS FOR THIS QUIZ (WITH EDIT FORMS) -->
            <div class="space-y-4 pt-2">
                <h5 class="text-xs font-extrabold text-slate-400 uppercase tracking-wider">Quiz Questions (<?= count($q['questions']) ?>)</h5>

                <?php foreach ($q['questions'] as $qn): ?>
                <div class="bg-slate-950 border border-slate-800 p-4 rounded-xl space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-indigo-400">Question #<?= $qn['id'] ?></span>
                        <form action="/admin/questions/<?= $qn['id'] ?>/delete" method="POST" onsubmit="return confirm('Remove question?');">
                            <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">
                            <button type="submit" class="text-red-400 hover:underline text-xs font-bold">Remove Question</button>
                        </form>
                    </div>

                    <!-- EDIT QUESTION FORM -->
                    <form action="/admin/questions/<?= $qn['id'] ?>/edit" method="POST" class="space-y-3">
                        <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 mb-1">Question Text</label>
                            <input type="text" name="question_text" value="<?= htmlspecialchars($qn['question_text']) ?>" required class="w-full bg-slate-900 border border-slate-800 text-xs text-white rounded p-2">
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 mb-1">Options (One per line)</label>
                            <textarea name="options" rows="3" required class="w-full bg-slate-900 border border-slate-800 text-xs text-white rounded p-2"><?= htmlspecialchars(implode("\n", $qn['options'] ?? [])) ?></textarea>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 mb-1">Exact Correct Option Match</label>
                                <input type="text" name="correct_option" value="<?= htmlspecialchars($qn['correct_option']) ?>" required class="w-full bg-slate-900 border border-slate-800 text-xs text-white rounded p-2">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 mb-1">Explanation</label>
                                <input type="text" name="explanation" value="<?= htmlspecialchars($qn['explanation'] ?? '') ?>" class="w-full bg-slate-900 border border-slate-800 text-xs text-white rounded p-2">
                            </div>
                        </div>

                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold px-3 py-1.5 rounded text-[11px]">
                            UPDATE QUESTION
                        </button>
                    </form>
                </div>
                <?php endforeach; ?>

                <!-- ADD QUESTION FORM -->
                <form action="/admin/quizzes/<?= $q['id'] ?>/question/add" method="POST" class="bg-slate-950/60 border border-slate-800/80 p-4 rounded-xl space-y-3 pt-4">
                    <input type="hidden" name="csrf_token" value="<?= \App\Services\SecurityService::getCsrfToken() ?>">
                    <h6 class="text-xs font-bold text-amber-400">+ Add Question to Quiz</h6>

                    <div>
                        <input type="text" name="question_text" required placeholder="Question Text" class="w-full bg-slate-900 border border-slate-800 text-xs text-white rounded-lg p-2">
                    </div>

                    <div>
                        <textarea name="options" rows="3" required placeholder="Option 1 (One per line)&#10;Option 2&#10;Option 3&#10;Option 4" class="w-full bg-slate-900 border border-slate-800 text-xs text-white rounded-lg p-2"></textarea>
                    </div>

                    <div>
                        <input type="text" name="correct_option" required placeholder="Exact Correct Option Match" class="w-full bg-slate-900 border border-slate-800 text-xs text-white rounded-lg p-2">
                    </div>

                    <button type="submit" class="bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold px-3 py-1.5 rounded-lg text-xs">
                        SAVE NEW QUESTION
                    </button>
                </form>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>
