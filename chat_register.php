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
    <title>gchanger Chat-Register</title>
    <link rel="icon" type="image/png" href="assets/images/123.png"  />
    <link rel="stylesheet" href="assets/css/auth.css">
</head>
<body>
    <div class="auth-container">
        <h2>Register</h2>
        <form action="auth.php" method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="hidden" name="action" value="register">
            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="chat_login.php">Login here</a></p>
    </div>
</body>
</html>
