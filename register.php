<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");

// Database connection
$conn = new mysqli("localhost", "root", "", "gchanger");

if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Database Connection Failed"]));
}

$data = json_decode(file_get_contents('php://input'), true);

$username = trim($data['username'] ?? '');
$email = trim($data['email'] ?? '');
$password = trim($data['password'] ?? '');

// Validate inputs
if (empty($username) || empty($email) || empty($password)) {
    echo json_encode(["success" => false, "message" => "All fields are required"]);
    exit;
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["success" => false, "message" => "Invalid email format"]);
    exit;
}

// List of allowed email domains
$allowedDomains = ['gmail.com', 'outlook.com', 'yahoo.com'];

// Extract domain from email
$emailParts = explode('@', $email);
if (count($emailParts) !== 2) {
    echo json_encode(["success" => false, "message" => "Invalid email address"]);
    exit;
}

$domain = strtolower(end($emailParts));

// Check against allowed domains
if (!in_array($domain, $allowedDomains)) {
    echo json_encode(["success" => false, "message" => "Only Gmail, Outlook, and Yahoo emails are allowed"]);
    exit;
}

// Rest of your existing code for duplicate check and registration
$check = $conn->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
$check->bind_param("ss", $email, $username);
$check->execute();

if ($check->get_result()->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "Email or username already exists"]);
    exit;
}

$hashed_password = password_hash($password, PASSWORD_BCRYPT);

$stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $email, $hashed_password);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Registration successful!", "redirect" => "login.html"]);
} else {
    echo json_encode(["success" => false, "message" => "Registration failed: " . $conn->error]);
}

// Close connections and exit
$stmt->close();
$check->close();
$conn->close();
?>
