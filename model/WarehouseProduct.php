<?php

    class WarehouseProduct {
        private $conn;
        private $table = 'warehouse_product_pivot';

        // Post Properties
        public $warehouse_id;
        public $product_id;
        public $total_stock_in;
        public $sales_qty;
        public $remaining_stocks;
        

        
        
        // Constructor with DB

        public function __construct($db){
            $this->conn = $db;

        }
            // GET
        public function read() {
            $query = 'SELECT
                    wp.product_id,
                    p.item_desc,
                    p.item_price,
                    p.total_amount,
                    wp.total_stock_in
                    
                    FROM '.$this->table.' 
                    wp INNER JOIN products p ON p.id = wp.product_id';
                   
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
            warehouse_id = :warehouse_id,
            product_id = :product_id,
            remaining_stocks = :remaining_stocks,
            total_stock_in = :total_stock_in
            ';

            $stmt =$this->conn->prepare($query);

            // sanitize data
            $this->warehouse_id = htmlspecialchars(strip_tags($this->warehouse_id));
            $this->product_id = htmlspecialchars(strip_tags($this->product_id));
            $this->remaining_stocks = htmlspecialchars(strip_tags($this->remaining_stocks));
            $this->total_stock_in = htmlspecialchars(strip_tags($this->total_stock_in));
            
            // bind data
            $stmt->bindParam(':product_id', $this->product_id);
            $stmt->bindParam(':warehouse_id', $this->warehouse_id);
            $stmt->bindParam(':remaining_stocks', $this->remaining_stocks);
            $stmt->bindParam(':total_stock_in', $this->total_stock_in);
            

            if($stmt->execute()) {
                return true;
            }

            printf("Error: %s.\n", $stmt->error);
            return false;
        }

        public function updateStocks() {
            $query = 'UPDATE '.$this->table.'
            SET
            total_sales = total_sales + :sales_qty,
            remaining_stocks = total_stock_in - total_sales
            WHERE product_id = :product_id';

            $stmt =$this->conn->prepare($query);

            // sanitize data
            // $this->job_title = htmlspecialchars(strip_tags($this->job_title));
            // $this->job_desc = htmlspecialchars(strip_tags($this->job_desc));
            $this->product_id = htmlspecialchars(strip_tags($this->product_id));
            $this->sales_qty = htmlspecialchars(strip_tags($this->sales_qty));
            // $this->company = htmlspecialchars(strip_tags($this->company));
           

            // bind data
            // $stmt->bindParam(':job_title', $this->job_title);
            // $stmt->bindParam(':job_desc', $this->job_desc);
            $stmt->bindParam(':product_id', $this->product_id);
            $stmt->bindParam(':sales_qty', $this->sales_qty);
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