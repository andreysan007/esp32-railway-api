<?php
require_once 'connection.php';
require_once 'jsonconverter.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    send_json(["status" => false, "message" => "invalid_request"]);
}

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($username) || empty($password)) {
    send_json(["status" => false, "message" => "username_or_password_missing"]);
}

$db = new Database();
$conn = $db->conn;

// Gunakan prepared statement
$query = "SELECT * FROM users WHERE username = ? AND password = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $username, $password); // sebaiknya password sudah di-hash

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    send_json([
        "status" => true,
        "token" => bin2hex(random_bytes(16)),
        "message" => "Login berhasil"
    ]);
} else {
    send_json([
        "status" => false,
        "message" => "Username atau password salah"
    ]);
}

$stmt->close();
$conn->close();
?>
