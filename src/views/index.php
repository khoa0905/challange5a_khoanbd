<?php
    $pageTitle = "Home"; 
    include('partials/header.php');
    include('partials/navbar.php');
?>

<div class="max-w-7xl mx-auto px-4">
    <div class="flex justify-center mt-12">
        <div class="max-w-2xl text-center">
            <h1 class="text-4xl font-bold mb-3"><i class="fa-solid fa-house mr-2"></i>Welcome</h1>
            <p class="text-lg text-gray-500">Manage your classroom — assignments, students, and messages — all in one place.</p>
            <hr class="my-6 border-gray-300">
            <div class="flex justify-center gap-3">
                <a href="/assignments" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition"><i class="fa-solid fa-book mr-1"></i>Assignments</a>
                <a href="/users" class="inline-flex items-center px-6 py-3 border border-gray-400 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition"><i class="fa-solid fa-users mr-1"></i>Users</a>
            </div>
        </div>
    </div>
</div>

<?php include('partials/footer.php'); ?>