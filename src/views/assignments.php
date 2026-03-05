<?php include('partials/header.php'); ?>
<?php include('partials/navbar.php'); ?>

<div class="max-w-7xl mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold"><i class="fa-solid fa-book mr-2"></i>Class Assignments</h2>
    </div>

    <?php if (isset($error)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if (isset($success)): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <?php if ($_SESSION['role'] === 'teacher'): ?>
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h5 class="font-semibold"><i class="fa-solid fa-cloud-arrow-up mr-2"></i>Upload New Assignment</h5>
            </div>
            <div class="p-6">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                        <div class="md:col-span-5">
                            <label for="title" class="block text-sm font-semibold text-gray-700 mb-1">Assignment Title</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" id="title" name="title" required>
                        </div>
                        <div class="md:col-span-5">
                            <label for="assignment_file" class="block text-sm font-semibold text-gray-700 mb-1">File (PDF, DOCX, ZIP, TXT)</label>
                            <input type="file" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" id="assignment_file" name="assignment_file" required>
                        </div>
                        <div class="md:col-span-2">
                            <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition font-medium"><i class="fa-solid fa-upload mr-1"></i>Upload</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h5 class="font-semibold">All Assignments</h5>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-900 text-white">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Teacher</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Date Uploaded</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (empty($assignments)): ?>
                        <tr>
                            <td colspan="4" class="text-center text-gray-500 py-8">No assignments have been uploaded yet.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($assignments as $task): ?>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap font-semibold"><?= htmlspecialchars($task['title']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($task['teacher_name']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($task['created_at']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="<?= htmlspecialchars($task['file_path']) ?>" download class="inline-flex items-center px-3 py-1.5 border border-gray-400 text-gray-600 text-sm rounded-lg hover:bg-gray-50 transition mr-1"><i class="fa-solid fa-download mr-1"></i>Download</a>
                                    <a href="/assignment?id=<?= $task['id'] ?>" class="inline-flex items-center px-3 py-1.5 border border-blue-600 text-blue-600 text-sm rounded-lg hover:bg-blue-50 transition"><i class="fa-solid fa-eye mr-1"></i>View / Submit</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include('partials/footer.php'); ?>