<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header("Location: chat_login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat with Grammar Correction</title>
    <link rel="icon" type="image/png" href="image\123.png"  />
    <link rel="stylesheet" href="assets/css/chat_project.css">
    <link rel="stylesheet" href="assets/css/sidebar.css"> <!-- Link Sidebar CSS -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>  
<!-- Add this inside body -->
<div class="username-display">
    User Name : <?php echo $_SESSION['username'] ?? 'Guest'; ?>
</div>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <h2>GChanger Menu</h2>
        <ul>
            <li><a href="welcome.html"><i class="fas fa-home"></i>Home</a></li>
            <li><a href="chatbot/index.html"><i class="fas fa-user"></i>Chatbot</a></li>
            <li><a href="chat_project.html"><i class="fas fa-comments"></i>----</a></li>
            <li><a href="grammar_corrector.php"><i class="fas fa-cog"></i>Grammar Corrector</a></li>
            <li><a href="gweb/gweb.html"><i class="fas fa-cog"></i>Mistake Tracker</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a></li>
        </ul>
    </div>

    <!-- Sidebar Toggle Button -->
    <div class="sidebar-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </div>

    <div class="chat-container">
        <h2>Welcome, <?php echo $_SESSION['username']; ?></h2>
        <div id="chat-box"></div>
        <input type="hidden" id="sender" value="<?php echo $_SESSION['username']; ?>">
        <input type="text" id="message" placeholder="Type a message...">
        <button onclick="sendMessage()">Send</button>
    </div>
    
    <script src="assets/js/sidebar.js"></script> <!-- Sidebar JS -->
    <script src="assets/js/chat_project.js"></script>  <!-- Chat JS -->
</body>
</html>


