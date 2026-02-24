<?php include('partials/header.php'); ?>
<?php include('partials/navbar.php'); ?>

<body>
    <h1>Class Assignments</h1>

    <?php if (isset($error)): ?>
        <p style="color: red;"><strong><?= htmlspecialchars($error) ?></strong></p>
    <?php endif; ?>
    <?php if (isset($success)): ?>
        <p style="color: green;"><strong><?= htmlspecialchars($success) ?></strong></p>
    <?php endif; ?>

    <?php if ($_SESSION['role'] === 'teacher'): ?>
        <div style="border: 1px dashed #000; padding: 15px; margin-bottom: 20px; width: 400px;">
            <h3>Upload New Assignment</h3>
            <form action="" method="POST" enctype="multipart/form-data">
                <div>
                    <label for="title"><strong>Assignment Title:</strong></label><br>
                    <input type="text" id="title" name="title" required style="width: 100%;">
                </div>
                <br>
                <div>
                    <label for="assignment_file"><strong>File (PDF, DOCX, ZIP, TXT):</strong></label><br>
                    <input type="file" id="assignment_file" name="assignment_file" required>
                </div>
                <br>
                <button type="submit">Upload Assignment</button>
            </form>
        </div>
    <?php endif; ?>

    <h3>All Assignments</h3>
    <table border="1" cellpadding="10" cellspacing="0" style="width: 80%; text-align: left;">
        <thead>
            <tr>
                <th>Title</th>
                <th>Teacher</th>
                <th>Date Uploaded</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($assignments)): ?>
                <tr>
                    <td colspan="4">No assignments have been uploaded yet.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($assignments as $task): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($task['title']) ?></strong></td>
                        <td><?= htmlspecialchars($task['teacher_name']) ?></td>
                        <td><?= htmlspecialchars($task['created_at']) ?></td>
                        <td>
                            <a href="<?= htmlspecialchars($task['file_path']) ?>" download>Download File</a> | 
                            <a href="assignment_detail.php?id=<?= $task['id'] ?>">View / Submit</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>