<?php
    $pageTitle = "User Directory"; 
    include('partials/header.php');
    include('partials/navbar.php');
?>

<div class="max-w-7xl mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold"><i class="fa-solid fa-users mr-2"></i>User Directory</h2>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'teacher'): ?>
            <a href="/manage-students" class="inline-flex items-center px-4 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition"><i class="fa-solid fa-user-plus mr-1"></i>Add New Student</a>
        <?php endif; ?>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-900 text-white">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Avatar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Username</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Full Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Phone</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $user): ?>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if (!empty($user['avatar_path'])): ?>
                                        <img src="<?= htmlspecialchars($user['avatar_path']) ?>" alt="<?= htmlspecialchars($user['username']) ?>'s avatar" class="w-10 h-10 rounded-full object-cover">
                                    <?php else: ?>
                                        <img src="uploads/avatars/default.jpg" alt="Default avatar" class="w-10 h-10 rounded-full object-cover">
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($user['username']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($user['full_name']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($user['email']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($user['phone']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $user['role'] === 'teacher' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' ?>">
                                        <?= htmlspecialchars(ucfirst($user['role'])) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap flex gap-2">
                                    <a href="/profile?id=<?= urlencode($user['id']) ?>" class="inline-flex items-center px-3 py-1.5 border border-blue-600 text-blue-600 text-sm rounded-lg hover:bg-blue-50 transition">
                                        <i class="fa-solid fa-eye mr-1"></i>View
                                    </a>
                                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'teacher' && $user['role'] === 'student'): ?>
                                        <a href="/edit-student?id=<?= urlencode($user['id']) ?>" class="inline-flex items-center px-3 py-1.5 border border-yellow-600 text-yellow-600 text-sm rounded-lg hover:bg-yellow-50 transition">
                                            <i class="fa-solid fa-pencil mr-1"></i>Edit
                                        </a>
                                        <form action="/delete-student" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this student?');">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']) ?>">
                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-red-600 text-red-600 text-sm rounded-lg hover:bg-red-50 transition">
                                                <i class="fa-solid fa-trash mr-1"></i>Delete
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-gray-500 py-8">No users found in the database.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include('partials/footer.php'); ?>