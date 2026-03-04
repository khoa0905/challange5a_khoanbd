<?php include('partials/header.php'); ?>
<?php include('partials/navbar.php'); ?>

<body>
    <div style="margin-bottom: 20px;">
        <a href="/assignments">&larr; Back to Assignments</a>
    </div>

    <h1><?= htmlspecialchars($assignment['title']) ?></h1>
    
    <div style="background-color: #f9f9f9; padding: 15px; border: 1px solid #ddd; margin-bottom: 20px;">
        <p><strong>Date Posted:</strong> <?= htmlspecialchars($assignment['created_at']) ?></p>
        <p>
            <strong>Attached File:</strong> 
            <a href="<?= htmlspecialchars($assignment['file_path']) ?>" download>
                <button type="button">Download Assignment File</button>
            </a>
        </p>
    </div>

    <?php if (isset($error)): ?>
        <p style="color: red;"><strong><?= htmlspecialchars($error) ?></strong></p>
    <?php endif; ?>

    <hr>

    <?php if ($_SESSION['role'] === 'teacher'): ?>
        <h2>Student Submissions</h2>
        
        <table border="1" cellpadding="10" cellspacing="0" style="width: 80%; text-align: left;">
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Username</th>
                    <th>Date Submitted</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($submissions)): ?>
                    <tr>
                        <td colspan="4">No students have submitted this assignment yet.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($submissions as $sub): ?>
                        <tr>
                            <td><?= htmlspecialchars($sub['full_name']) ?></td>
                            <td><?= htmlspecialchars($sub['username']) ?></td>
                            <td><?= htmlspecialchars($sub['submitted_at']) ?></td>
                            <td>
                                <a href="<?= htmlspecialchars($sub['file_path']) ?>" download>Download Work</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

    <?php else: ?>
        <h2>Submit Your Work</h2>

        <?php if ($my_submission): ?>
            <div style="border: 1px solid green; padding: 15px; background-color: #eaffea;">
                <p style="color: green;"><strong>&#10003; You have successfully submitted this assignment.</strong></p>
                <p><strong>Submitted on:</strong> <?= htmlspecialchars($my_submission['submitted_at']) ?></p>
                <a href="<?= htmlspecialchars($my_submission['file_path']) ?>" download>Download my submitted file</a>
            </div>
        <?php else: ?>
            <form action="/assignment?id=<?= urlencode($assignment['id']) ?>" method="POST" enctype="multipart/form-data">
                <div>
                    <label for="submission_file"><strong>Upload your answer (PDF, DOCX, ZIP):</strong></label><br><br>
                    <input type="file" id="submission_file" name="submission_file" required>
                </div>
                <br>
                <button type="submit">Submit Homework</button>
            </form>
        <?php endif; ?>

    <?php endif; ?>

</body>
</html>