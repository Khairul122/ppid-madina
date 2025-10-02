<?php
class Database {
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "ppid_mandailing";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->database, $this->username, $this->password);
            $this->conn->exec("set names utf8");
            // Set the PDO error mode to exception
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch(PDOException $exception) {
            // Log error instead of echoing it (which breaks AJAX responses)
            error_log("Database connection error: " . $exception->getMessage());
            // Don't echo anything that would break JSON responses
        }
        return $this->conn;
    }
}
?>