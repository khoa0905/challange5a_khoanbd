<div class="max-w-7xl mx-auto px-4">
    <?php if ($_SESSION['role'] === 'teacher'): ?>
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h5 class="font-semibold"><i class="fa-solid fa-circle-plus mr-2"></i>Create New Challenge</h5>
            </div>
            <div class="p-6">
                <form action="/challenges" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="create_challenge">
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Hint</label>
                        <textarea name="hint" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" rows="3"></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Upload Poem (.txt)</label>
                        <input type="file" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" name="challenge_file" required>
                    </div>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium"><i class="fa-solid fa-bolt mr-1"></i>Start Challenge</button>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <h4 class="text-xl font-semibold mb-4"><i class="fa-solid fa-trophy mr-2"></i>Active Challenges</h4>

    <?php if (empty($challenges)): ?>
        <p class="text-gray-500">No challenges available right now.</p>
    <?php else: ?>
        <?php foreach ($challenges as $c): ?>
            <div class="bg-white rounded-lg shadow mb-4">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-2">
                        <h6 class="font-semibold">Challenge #<?= $c['id'] ?></h6>
                        <small class="text-gray-500">by <?= htmlspecialchars($c['teacher_name']) ?></small>
                    </div>
                    <p class="mb-3"><strong>Hint:</strong> <?= nl2br(htmlspecialchars($c['hint'])) ?></p>
                    <form action="/challenges" method="POST" class="flex gap-2">
                        <input type="hidden" name="action" value="submit_guess">
                        <input type="hidden" name="challenge_id" value="<?= $c['id'] ?>">
                        <input type="text" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" name="guess" placeholder="Your guess..." required>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium whitespace-nowrap"><i class="fa-solid fa-paper-plane mr-1"></i>Guess</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if ($poem_content): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-4 rounded mt-6">
            <h5 class="font-semibold mb-2"><i class="fa-solid fa-circle-check mr-1"></i>Success! Here is the content:</h5>
            <pre class="mb-3 whitespace-pre-wrap"><?= htmlspecialchars($poem_content) ?></pre>
            <a href="<?= htmlspecialchars($download_link) ?>" download class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition"><i class="fa-solid fa-download mr-1"></i>Download Answer File</a>
        </div>
    <?php endif; ?>
</div>

<?php include('partials/footer.php'); ?>