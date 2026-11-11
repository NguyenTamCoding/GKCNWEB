<?php

class Database {
  
    private $host = 'localhost';
    private $dbname = 'QLHocPhan';
    private $username = 'root'; 
    private $password = 'thientam';    
    private $conn = null;


    public function connect() {
      
        if ($this->conn === null) {
            try {
                $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->dbname . ";charset=utf8mb4";
              
                $this->conn = new PDO($dsn, $this->username, $this->password);
               
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            } catch (PDOException $e) {
               
                die("Kết nối cơ sở dữ liệu thất bại: " . $e->getMessage());
            }
        }

        return $this->conn;
    }
}
?>