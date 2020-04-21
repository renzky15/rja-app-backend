<?php
    class Database {
        private $host = 'us-cdbr-iron-east-01.cleardb.net';
        private $db_name = 'heroku_1834801e7b0005f';
        private $username = 'b5163eabc6045a';
        private $db_port = 3306;
        private $password = '977db3d9';
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