<?php
    $pageTitle = "Login"; 
    include('partials/header.php');
    include('partials/navbar.php');
?>

<div class="max-w-7xl mx-auto px-4">
    <div class="flex justify-center mt-12">
        <div class="w-full max-w-sm">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-xl font-semibold text-center mb-6"><i class="fa-solid fa-right-to-bracket mr-2"></i>Login</h3>

                <?php if (isset($error)): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form action="" method="post">
                    <div class="mb-4">
                        <label for="username" class="block text-sm font-semibold text-gray-700 mb-1">Username</label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" id="username" name="username" placeholder="Enter Username" required>
                    </div>
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                        <input type="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" id="password" name="password" placeholder="Enter Password" required>
                    </div>
                    <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition font-medium">Login</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include('partials/footer.php'); ?>