<?php
    class Database {
        private $host = 'localhost:3308';
        private $db_name = 'db_rja';
        private $username = 'root';
        private $db_port = 3308;
        private $password = '';
        private $conn;

        public function connect() {
            $this->conn = null;

            try {
                $this->conn = new PDO('mysql:host='.$this->host.';dbname='.$this->db_name,$this->username,$this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                
            } catch (PDOException $e) {
                echo 'Connection Error: '.$e->getMessage();
            }
            return $this->conn;
        }
    }
?>