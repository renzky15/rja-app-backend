<?php

    class Invoice {
        private $conn;
        private $table = 'invoice';

        // Post Properties
        public $invoice_id;
        public $customer_name;
        public $total_qty;
        public $total_amount;
        public $term;
        public $delivery_date;

        
        
        // Constructor with DB

        public function __construct($db){
            $this->conn = $db;

        }
            // GET
        public function read() {
            $query = 'SELECT
                    invoice_id,
                    invoice_order_id
                    
                    FROM '.$this->table 
                    ;
                   

            // Prepare statement

            $stmt =$this->conn->prepare($query);

            // Execute

            $stmt->execute();

            return $stmt;
        }
        public function getInvoiceID() {
            $query = 'SELECT
                    invoice_id
                    
                    FROM '.$this->table. 
                    ' ORDER BY invoice_id DESC LIMIT 1';
                   

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


        public function insertInvoice() {
            $query = 
            'INSERT INTO '.$this->table.'
            SET
            invoice_id = :invoice_id,
            customer_name = :customer_name,
            total_qty = :total_qty,
            total_amount = :total_amount,
            term = :term,
            delivery_date = :delivery_date
            ';

            $stmt =$this->conn->prepare($query);

            // sanitize data
            $this->invoice_id = htmlspecialchars(strip_tags($this->invoice_id));
            $this->customer_name = htmlspecialchars(strip_tags($this->customer_name));
            $this->total_qty = htmlspecialchars(strip_tags($this->total_qty));
            $this->total_amount = htmlspecialchars(strip_tags($this->total_amount));
            $this->term = htmlspecialchars(strip_tags($this->term));
            $this->delivery_date = htmlspecialchars(strip_tags($this->delivery_date));

            // bind data
            $stmt->bindParam(':invoice_id', $this->invoice_id);
            $stmt->bindParam(':customer_name', $this->customer_name);
            $stmt->bindParam(':total_qty', $this->total_qty);
            $stmt->bindParam(':delivery_date', $this->delivery_date);
            $stmt->bindParam(':total_amount', $this->total_amount);
            $stmt->bindParam(':term', $this->term);

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