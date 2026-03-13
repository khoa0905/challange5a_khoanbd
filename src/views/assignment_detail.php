<?php include('partials/header.php'); ?>
<?php include('partials/navbar.php'); ?>

<div class="max-w-7xl mx-auto px-4">
    <a href="/assignments" class="inline-flex items-center px-4 py-2 border border-gray-400 text-gray-600 rounded-lg hover:bg-gray-50 transition mb-4"><i class="fa-solid fa-arrow-left mr-1"></i>Back to Assignments</a>

    <div class="bg-white rounded-lg shadow mb-6 p-6">
        <h3 class="text-xl font-semibold mb-3"><?= htmlspecialchars($assignment['title']) ?></h3>
        <?php if (!empty($assignment['description'])): ?>
            <p class="text-gray-700 mb-3"><?= nl2br(htmlspecialchars($assignment['description'])) ?></p>
        <?php endif; ?>
        <p class="text-gray-500 mb-2"><i class="fa-solid fa-calendar mr-1"></i><strong>Date Posted:</strong> <?= htmlspecialchars($assignment['created_at']) ?></p>
        <a href="<?= htmlspecialchars($assignment['file_path']) ?>" download class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"><i class="fa-solid fa-download mr-1"></i>Download Assignment File</a>
    </div>

    <?php if (isset($error)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($_SESSION['role'] === 'teacher'): ?>
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h5 class="font-semibold"><i class="fa-solid fa-users mr-2"></i>Student Submissions</h5>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-900 text-white">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Student Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Username</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Date Submitted</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (empty($submissions)): ?>
                            <tr>
                                <td colspan="4" class="text-center text-gray-500 py-8">No students have submitted this assignment yet.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($submissions as $sub): ?>
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($sub['full_name']) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($sub['username']) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($sub['submitted_at']) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="<?= htmlspecialchars($sub['file_path']) ?>" download class="inline-flex items-center px-3 py-1.5 border border-blue-600 text-blue-600 text-sm rounded-lg hover:bg-blue-50 transition"><i class="fa-solid fa-download mr-1"></i>Download</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    <?php else: ?>
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h5 class="font-semibold"><i class="fa-solid fa-upload mr-2"></i>Submit Your Work</h5>
            </div>
            <div class="p-6">
                <?php if ($my_submission): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        <p class="mb-1"><i class="fa-solid fa-circle-check mr-1"></i><strong>You have successfully submitted this assignment.</strong></p>
                        <p class="mb-2"><strong>Submitted on:</strong> <?= htmlspecialchars($my_submission['submitted_at']) ?></p>
                        <a href="<?= htmlspecialchars($my_submission['file_path']) ?>" download class="inline-flex items-center px-3 py-1.5 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition"><i class="fa-solid fa-download mr-1"></i>Download my submitted file</a>
                    </div>
                <?php else: ?>
                    <form action="/assignment?id=<?= urlencode($assignment['id']) ?>" method="POST" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <div class="mb-4">
                            <label for="submission_file" class="block text-sm font-semibold text-gray-700 mb-1">Upload your answer (PDF, DOCX, ZIP)</label>
                            <input type="file" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" id="submission_file" name="submission_file" required>
                        </div>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium"><i class="fa-solid fa-paper-plane mr-1"></i>Submit Homework</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include('partials/footer.php'); ?>