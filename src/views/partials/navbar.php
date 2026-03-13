<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$role = $_SESSION['role'] ?? 'guest'; 
?>

<nav class="bg-gray-900 text-white mb-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <a class="text-xl font-bold text-white hover:text-gray-300" href="/"><i class="fa-solid fa-graduation-cap mr-2"></i>ClassManager</a>
            <button id="navToggle" class="sm:hidden inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700" onclick="document.getElementById('mainNav').classList.toggle('hidden')">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <div class="hidden sm:flex sm:items-center sm:space-x-4" id="mainNav">
                <a class="text-gray-300 hover:text-white px-3 py-2 text-sm font-medium" href="/">Home</a>
                <a class="text-gray-300 hover:text-white px-3 py-2 text-sm font-medium" href="/users">Users</a>
                <a class="text-gray-300 hover:text-white px-3 py-2 text-sm font-medium" href="/assignments">Assignments</a>
                <a class="text-gray-300 hover:text-white px-3 py-2 text-sm font-medium" href="/challenges">Challenges</a>
                <?php if ($role === 'teacher'): ?>
                    <a class="text-gray-300 hover:text-white px-3 py-2 text-sm font-medium" href="/manage-students">Manage Students</a>
                <?php endif; ?>
                <span class="border-l border-gray-600 h-6"></span>
                <?php if ($role !== 'guest'): ?>
                    <a class="text-gray-300 hover:text-white px-3 py-2 text-sm font-medium" href="/profile"><i class="fa-solid fa-circle-user mr-1"></i>My Profile</a>
                    <form action="/logout" method="POST" class="inline">
                        <?= csrf_field() ?>
                        <button type="submit" class="text-red-400 hover:text-red-300 px-3 py-2 text-sm font-medium cursor-pointer bg-transparent border-none"><i class="fa-solid fa-right-from-bracket mr-1"></i>Logout</button>
                    </form>
                <?php else: ?>
                    <a class="text-gray-300 hover:text-white px-3 py-2 text-sm font-medium" href="/login"><i class="fa-solid fa-right-to-bracket mr-1"></i>Login</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>