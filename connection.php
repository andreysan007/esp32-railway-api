<?php
class Database {
    private $host = 'metro.proxy.rlwy.net';
    private $port = 21860;
    private $user = 'root';
    private $pass = 'TCGIVbDfxlkBQsDKNISxwRQTTIiaDzeW';
    private $dbname = 'railway';
    public $conn;

    public function __construct() {
        $this->connect();
    }

    public function connect() {
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->dbname, $this->port);
        if ($this->conn->connect_error) {
            $this->respond_error("db_error");
        }
    }

    private function respond_error($msg) {
        header('Content-Type: application/json');
        echo json_encode(["success" => false, "message" => $msg]);
        exit;
    }
}
?>
