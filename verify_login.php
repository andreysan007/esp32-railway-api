<?php
require_once 'connection.php';
require_once 'jsonconverter.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    send_json(["status" => false, "message" => "invalid_request"]);
}

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$deviceIp = $_POST['deviceIp'] ?? '';

if (empty($username) || empty($password)) {
    send_json(["status" => false, "message" => "username_or_password_missing"]);
}

$db = new Database();
$conn = $db->conn;

// Gunakan prepared statement
$query = "SELECT * FROM users WHERE username = ? AND password = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $username, $password); 

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {

    $query = "SELECT * FROM Devices WHERE IP_AP = ? AND username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $deviceIp, $username); 

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
    send_json([
            "status" => true,
            "approve" => true,
            "token" => bin2hex(random_bytes(16)),
            "message" => "Login berhasil"
        ]);
    }
    else{

        $query = "SELECT * FROM LogDevices WHERE IP_AP = ? AND username = ? AND remark = 'Menunggu Approve dari pemilik device'";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $deviceIp, $username); 

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            $query = "Insert INTO LogDevices(IP_AP,username,remark,action) VALUES(?,?,'Menunggu Approve dari pemilik device','N')";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ss", $deviceIp, $username); 

            $stmt->execute();
        }
        send_json([
            "status" => true,
            "approve" => false,
            "token" => bin2hex(random_bytes(16)),
            "message" => "Menunggu Approve dari pemilik device"
        ]);
    }

    
} else {
    send_json([
        "status" => false,
        "message" => "Username atau password salah"
    ]);
}

$stmt->close();
$conn->close();
?>
