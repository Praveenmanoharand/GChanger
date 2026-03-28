<?php
session_start();
if(isset($_SESSION['user_id'])) {
    header("Location: chat_project.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>gchanger Chat-Login</title>
    <link rel="icon" type="image/png" href="assets/images/123.png"  />
    <link rel="stylesheet" href="assets/css/auth.css">
</head>
<body>
    <div class="auth-container">
        <h2>Login</h2>
        <form action="auth.php" method="post">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="hidden" name="action" value="login">
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="chat_register.php">Register here</a></p>
    </div>
</body>
</html>
