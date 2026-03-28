<?php
session_start();
header("Content-Type: application/json");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "chat_db";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Session-based authentication
    if (!isset($_SESSION['username'])) {
        die(json_encode(["error" => "Unauthorized - Please login first"]));
    }
    
    $sender = $_SESSION['username']; // Get username from session
    $message = $_POST['message'];

    // Grammar correction API call
    $api_url = "https://api.languagetool.org/v2/check";
    $post_data = http_build_query([
        "text" => $message,
        "language" => "en-US"
    ]);
    
    $opts = ['http' => [
        'method' => "POST",
        'header' => "Content-Type: application/x-www-form-urlencoded",
        'content' => $post_data
    ]];
    
    $context = stream_context_create($opts);
    $response = file_get_contents($api_url, false, $context);
    $result = json_decode($response, true);
    
    // Apply grammar corrections
    foreach ($result['matches'] as $match) {
        $offset = $match['offset'];
        $length = $match['length'];
        $replacement = $match['replacements'][0]['value'] ?? "";
        $message = substr_replace($message, $replacement, $offset, $length);
    }

    // Insert corrected message
    $stmt = $conn->prepare("INSERT INTO messages (sender, message) VALUES (?, ?)");
    $stmt->bind_param("ss", $sender, $message);
    
    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["error" => "Message sending failed"]);
    }
} else {
    // Retrieve messages
    $result = $conn->query("
        SELECT sender, message
        FROM messages
        ORDER BY created_at DESC
    ");
    
    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = [
            'sender' => $row['sender'],
            'message' => $row['message']
        ];
    }
    echo json_encode(array_reverse($messages)); // Reverse to show oldest first
}

$conn->close();
?>
