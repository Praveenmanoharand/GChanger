<?php
header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_message = trim($_POST['message'] ?? '');

    if (!empty($user_message)) {
        $encoded_message = urlencode($user_message);
        $url = "https://api.duckduckgo.com/?q=$encoded_message&format=json";

        $response = file_get_contents($url);
        $data = json_decode($response, true);

        $bot_reply = $data["AbstractText"] ?: "I couldn't find an answer. Try asking something else.";

        echo json_encode(["response" => $bot_reply]);
        exit;
    }
}

echo json_encode(["response" => "Please enter a valid message."]);
?>

