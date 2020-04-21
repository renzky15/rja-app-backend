<?php

    class Product {
        private $conn;
        private $table = 'products';

        // Post Properties
        
        public $id;
        public $qty;
        public $item_desc;
        public $item_price;
        public $total_amount;

        
        
        // Constructor with DB

        public function __construct($db){
            $this->conn = $db;

        }
            // GET
        public function read() {
            $query = 'SELECT
                    id,
                    qty,
                    item_desc,
                    item_price,
                    (qty * item_price) AS total_amount
                    
                    FROM '.$this->table.' 
                    ORDER BY (total_amount) DESC';
                   

            // Prepare statement

            $stmt =$this->conn->prepare($query);

            // Execute

            $stmt->execute();

            return $stmt;
        }
        public function read_single() {
            $query = 'SELECT
                    
                    
                    price
                    
                    
                    FROM '.$this->table.' WHERE ph_id = ?';
                   

            // Prepare statement

            $stmt =$this->conn->prepare($query);

            // Bind the ID
            $stmt->bindParam(1, $this->ph_id);


            // Execute

            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Set properties

            
            $this->price = $row['price'];
            


        }


        public function insert() {
            $query = 
            'INSERT INTO '.$this->table.'
            SET
            
            id = :id,
            item_desc = :item_desc,
            item_price = :item_price,
            total_amount = :item_price * :qty
            
            ';

            $stmt =$this->conn->prepare($query);

            // sanitize data
           
            $this->id = htmlspecialchars(strip_tags($this->id));
            $this->qty = htmlspecialchars(strip_tags($this->qty));
            $this->item_desc = htmlspecialchars(strip_tags($this->item_desc));
            $this->item_price = htmlspecialchars(strip_tags($this->item_price));
            $this->total_amount = htmlspecialchars(strip_tags($this->total_amount));

            // bind data
            $stmt->bindParam(':id', $this->id);
            $stmt->bindParam(':qty', $this->qty);
            $stmt->bindParam(':item_desc', $this->item_desc);
            $stmt->bindParam(':item_price', $this->item_price);
            $stmt->bindParam(':total_amount', $this->total_amount);

            if($stmt->execute()) {
                return true;
            }

            printf("Error: %s.\n", $stmt->error);
            return false;
        }

        public function update() {
            $query = 'UPDATE '.$this->table.'
            SET
            job_role = :job_role
            WHERE job_id = :job_id';

            $stmt =$this->conn->prepare($query);

            // sanitize data
            // $this->job_title = htmlspecialchars(strip_tags($this->job_title));
            // $this->job_desc = htmlspecialchars(strip_tags($this->job_desc));
            $this->job_id = htmlspecialchars(strip_tags($this->job_id));
            $this->job_role = htmlspecialchars(strip_tags($this->job_role));
            // $this->company = htmlspecialchars(strip_tags($this->company));
           

            // bind data
            // $stmt->bindParam(':job_title', $this->job_title);
            // $stmt->bindParam(':job_desc', $this->job_desc);
            $stmt->bindParam(':job_id', $this->job_id);
            $stmt->bindParam(':job_role', $this->job_role);
            // $stmt->bindParam(':company', $this->company);
         

            if($stmt->execute()) {
                return true;
            }

            printf("Error: %s.\n", $stmt->error);
            return false;
        }

        public function delete() {

            $query = "DELETE FROM `job`
            INNER JOIN job_applicant ja ON job_id = ja.job_id INNER JOIN applicant a ON ja.applicant_id = a.applicant_id WHERE ja.job_code = :job_code";

            // Prepare stmt
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->job_code = htmlspecialchars(strip_tags($this->job_code));

            // Bind data
            $stmt->bindParam(':job_code', $this->job_code);

            // Execute query

            if ($stmt->execute()) {
                return true;
            }
            
            printf("Error: %s.\n", $stmt->error);
            return false;
        }

    }
?>