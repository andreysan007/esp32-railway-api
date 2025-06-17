<?php
header('Content-Type: text/plain');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari POST
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Koneksi ke database
    $host = 'metro.proxy.rlwy.net';
    $port = 21860;
    $db = 'railway';
    $db_user = 'root'; 
    $db_pass = 'TCGIVbDfxlkBQsDKNISxwRQTTIiaDzeW';     

    $conn = new mysqli($host, $db_user, $db_pass, $db, $port);

    if ($conn->connect_error) {
        echo 'db_error';
        exit;
    }

    // Cegah SQL Injection (gunakan prepared statement)
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password); // gunakan hash untuk produksi

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $response = [
            "success" => true,
            "token" => bin2hex(random_bytes(16)), // contoh token random
            "message" => "Login berhasil"
        ];
    } else {
        $response = [
            "success" => false,
            "message" => "Username atau password salah"
        ];
    }
    echo json_encode($response);
    $stmt->close();
    $conn->close();
} else {
    $response = [
            "success" => false,
            "message" => "invalid_request"
        ];
     echo json_encode($response);
}
?>
