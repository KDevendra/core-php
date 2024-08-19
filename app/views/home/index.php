<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Users List</title>
</head>
<body>
    <h1>Users List</h1>
    <?php if (!empty($users)): ?>
        <ul>
            <?php foreach ($users as $user): ?>
                <li><?php echo htmlspecialchars($user['name']); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No users found.</p>
    <?php endif; ?>


    <form method="post" action="<?php echo $GLOBALS['config']['base_url']; ?>store">
    <label for="name">Name:</label>
    <input type="text" name="name" id="name">
    
    <label for="email">Email:</label>
    <input type="email" name="email" id="email">
    
    <label for="message">Message:</label>
    <textarea name="message" id="message"></textarea>
    
    <button type="submit">Send</button>
</form>

</body>
</html>
