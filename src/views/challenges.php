<?php if ($_SESSION['role'] === 'teacher'): ?>
    <fieldset style="margin-bottom: 20px;">
        <legend>Create New Challenge</legend>
        <form action="/challenges" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="create_challenge">
            
            <label>Hint:</label><br>
            <textarea name="hint" required cols="40" rows="3"></textarea><br>
            
            <label>Upload Poem (.txt):</label><br>
            <input type="file" name="challenge_file" required><br>
            
            <button type="submit">Start Challenge</button>
        </form>
    </fieldset>
<?php endif; ?>

<h3>Active Challenges</h3>

<?php if (empty($challenges)): ?>
    <p>No challenges available right now.</p>
<?php else: ?>
    <?php foreach ($challenges as $c): ?>
        <div style="border: 1px solid #ccc; padding: 15px; margin-bottom: 15px;">
            <p><strong>Challenge #<?= $c['id'] ?></strong> (by <?= htmlspecialchars($c['teacher_name']) ?>)</p>
            <p><strong>Hint:</strong> <?= nl2br(htmlspecialchars($c['hint'])) ?></p>
            
            <form action="/challenges" method="POST">
                <input type="hidden" name="action" value="submit_guess">
                <input type="hidden" name="challenge_id" value="<?= $c['id'] ?>">
                
                <input type="text" name="guess" placeholder="Your guess..." required>
                <button type="submit">Submit Guess</button>
            </form>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php if ($poem_content): ?>
    <div style="border: 2px solid green; padding: 15px; margin-top: 20px;">
        <h3 style="color: green;">Success! Here is the content:</h3>
        <p><pre><?= htmlspecialchars($poem_content) ?></pre></p>
        <a href="<?= htmlspecialchars($download_link) ?>" download>
            <button>Download Answer File</button>
        </a>
    </div>
<?php endif; ?>