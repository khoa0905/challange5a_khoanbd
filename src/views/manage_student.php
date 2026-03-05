<?php
    $pageTitle = "Manage Students"; 
    include('partials/header.php');
    include('partials/navbar.php');
?>

<div class="max-w-7xl mx-auto px-4">
    <div class="flex justify-center">
        <div class="w-full max-w-lg">
            <a href="/" class="inline-flex items-center px-4 py-2 border border-gray-400 text-gray-600 rounded-lg hover:bg-gray-50 transition mb-4"><i class="fa-solid fa-arrow-left mr-1"></i>Back to Dashboard</a>
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h5 class="font-semibold"><i class="fa-solid fa-user-plus mr-2"></i>Add New Student</h5>
                </div>
                <div class="p-6">
                    <?php if (isset($error)): ?>
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    <?php if (isset($success)): ?>
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4"><?= htmlspecialchars($success) ?></div>
                    <?php endif; ?>

                    <form action="" method="POST">
                        <div class="mb-4">
                            <label for="username" class="block text-sm font-semibold text-gray-700 mb-1">Username</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" id="username" name="username" required>
                        </div>
                        <div class="mb-4">
                            <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                            <input type="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" id="password" name="password" required>
                        </div>
                        <div class="mb-4">
                            <label for="full_name" class="block text-sm font-semibold text-gray-700 mb-1">Full Name</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" id="full_name" name="full_name" required>
                        </div>
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                            <input type="email" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" id="email" name="email" required>
                        </div>
                        <div class="mb-4">
                            <label for="phone" class="block text-sm font-semibold text-gray-700 mb-1">Phone Number</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" id="phone" name="phone">
                        </div>
                        <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition font-medium"><i class="fa-solid fa-check mr-1"></i>Create Student</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('partials/footer.php'); ?>