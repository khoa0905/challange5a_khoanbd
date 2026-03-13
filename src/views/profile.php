<?php
    $pageTitle = "User Profile"; 
    include('partials/header.php');
    include('partials/navbar.php');
?>

<div class="max-w-7xl mx-auto px-4">
    <?php if (!$profile): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4"><strong>User not found.</strong></div>
        <a href="/" class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition"><i class="fa-solid fa-arrow-left mr-1"></i>Back to Dashboard</a>
    <?php else: ?>

        <?php if (isset($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Profile Card -->
            <div class="md:col-span-1">
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6 text-center">
                        <div class="mb-3">
                            <?php if (!empty($profile['avatar_path'])): ?>
                                <img src="<?= htmlspecialchars($profile['avatar_path']) ?>" alt="<?= htmlspecialchars($profile['username']) ?>'s avatar" class="w-24 h-24 rounded-full object-cover mx-auto">
                            <?php else: ?>
                                <img src="uploads/avatars/default.png" alt="Default avatar" class="w-24 h-24 rounded-full object-cover mx-auto">
                            <?php endif; ?>
                        </div>
                        <h5 class="text-lg font-semibold mb-1"><?= htmlspecialchars($profile['full_name']) ?></h5>
                        <p class="text-gray-500 mb-2">@<?= htmlspecialchars($profile['username']) ?></p>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $profile['role'] === 'teacher' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' ?>"><?= htmlspecialchars(ucfirst($profile['role'])) ?></span>
                    </div>

                    <hr class="border-gray-200">

                    <?php if ($_SESSION['id'] == $profile['id']): ?>
                        <div class="p-6">
                            <form action="/profile?id=<?= urlencode($profile['id']) ?>" method="POST" enctype="multipart/form-data">
                                <?= csrf_field() ?>
                                <div class="mb-4">
                                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                                    <input type="email" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" id="email" name="email" value="<?= htmlspecialchars($profile['email']) ?>" required>
                                </div>
                                <div class="mb-4">
                                    <label for="phone" class="block text-sm font-semibold text-gray-700 mb-1">Phone</label>
                                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" id="phone" name="phone" value="<?= htmlspecialchars($profile['phone']) ?>" required>
                                </div>
                                <hr class="my-4 border-gray-200">
                                <div class="mb-4">
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Avatar URL</label>
                                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" name="avatar_url" placeholder="https://...">
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Or Upload File</label>
                                    <input type="file" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" name="avatar_file" accept="image/*">
                                </div>
                                <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition font-medium"><i class="fa-solid fa-check mr-1"></i>Save Changes</button>
                            </form>
                        </div>
                    <?php else: ?>
                        <div class="p-6">
                            <p class="mb-1"><i class="fa-solid fa-envelope mr-2"></i><?= htmlspecialchars($profile['email']) ?></p>
                            <p class="mb-0"><i class="fa-solid fa-phone mr-2"></i><?= htmlspecialchars($profile['phone']) ?></p>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if ($_SESSION['id'] != $profile['id']): ?>
                    <a href="/" class="inline-flex items-center px-4 py-2 border border-gray-400 text-gray-600 rounded-lg hover:bg-gray-50 transition mt-4"><i class="fa-solid fa-arrow-left mr-1"></i>Back to Dashboard</a>
                <?php endif; ?>
            </div>

            <!-- Message Board -->
            <div class="md:col-span-2">
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h5 class="font-semibold"><i class="fa-solid fa-comments mr-2"></i>Message Board</h5>
                    </div>
                    <div class="p-6">
                        <?php if ($_SESSION['id'] != $profile['id']): ?>
                            <form action="/messages" method="POST" class="mb-6">
                                <?= csrf_field() ?>
                                <input type="hidden" name="action" value="send">
                                <input type="hidden" name="receiver_id" value="<?= htmlspecialchars($profile['id']) ?>">
                                <div class="mb-2">
                                    <textarea name="message" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" rows="3" placeholder="Leave a message..." required></textarea>
                                </div>
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium"><i class="fa-solid fa-paper-plane mr-1"></i>Send Message</button>
                            </form>
                        <?php endif; ?>

                        <?php if (empty($messages)): ?>
                            <p class="text-gray-500 text-center py-6">No messages yet. Be the first to say hello!</p>
                        <?php else: ?>
                            <?php foreach ($messages as $msg): ?>
                                <div class="border border-gray-200 rounded-lg mb-3">
                                    <div class="py-2 px-4">
                                        <div class="flex justify-between items-center mb-1">
                                            <strong><?= htmlspecialchars($msg['sender_username']) ?></strong>
                                            <small class="text-gray-500"><?= htmlspecialchars($msg['created_at']) ?></small>
                                        </div>
                                        <p class="mb-1"><?= nl2br(htmlspecialchars($msg['message'])) ?></p>

                                        <?php if ($_SESSION['id'] == $msg['sender_id']): ?>
                                            <div class="flex gap-2 mt-2">
                                                <form action="/messages" method="POST" onsubmit="return confirm('Are you sure you want to delete this message?');">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="message_id" value="<?= $msg['id'] ?>">
                                                    <input type="hidden" name="receiver_id" value="<?= $profile['id'] ?>">
                                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-red-500 text-red-500 text-sm rounded-lg hover:bg-red-50 transition"><i class="fa-solid fa-trash mr-1"></i>Delete</button>
                                                </form>

                                                <form action="/messages" method="POST" onsubmit="let newMsg = prompt('Edit your message:', '<?= htmlspecialchars(addslashes($msg['message'])) ?>'); if(newMsg) { this.new_message.value = newMsg; return true; } return false;">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="action" value="edit">
                                                    <input type="hidden" name="message_id" value="<?= $msg['id'] ?>">
                                                    <input type="hidden" name="receiver_id" value="<?= $profile['id'] ?>">
                                                    <input type="hidden" name="new_message" value="">
                                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-gray-400 text-gray-600 text-sm rounded-lg hover:bg-gray-50 transition"><i class="fa-solid fa-pencil mr-1"></i>Edit</button>
                                                </form>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

    <?php endif; ?>
</div>

<?php include('partials/footer.php'); ?>