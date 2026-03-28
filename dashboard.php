<?php
session_start();
// If user is Successfully logged in, redirect to dashboard page
if (isset($_SESSION['user_id'])) {
    header("Location: welcome.html");
    exit;
}

// If user is not logged in, redirect to login page
else {
    header("Location: login.html");
    exit;
}
echo "<hr><a href='logout.php'>Logout</a>";
?>
