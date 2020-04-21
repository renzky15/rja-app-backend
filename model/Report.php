<?php

    class Report {
        private $conn;
        private $table = 'invoice_order';

        // Post Properties
        public $invoice_id;
        public $invoice_order_id;
        public $product_id;
        public $qty;
        public $item_desc;
        public $price;
        public $total_amount;
        public $remaining_stocks;



        
            
        // Constructor with DB

        public function __construct($db){
            $this->conn = $db;

        }
            // GET
            public function readAll(){
                $query = 'SELECT
                        *
                        FROM invoice 
                        '
                        ;
    
                       
                // Prepare statement
    
                $stmt =$this->conn->prepare($query);
    
                // Execute
    
                $stmt->execute();
    
                return $stmt;
            }
            

            
        public function getInvoiceID() {
            $query = 'SELECT
                    invoice_id,
                    invoice_order_id
                    FROM '.$this->table. 
                    ' ORDER BY invoice_id LIMIT 1';
                   

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


        public function insertInvoiceOrder() {
            $query = 
            'INSERT INTO '.$this->table.'
            SET
            invoice_id = :invoice_id,
            qty = :qty,
            item_desc = :item_desc,
            price = :price,
            total_amount = :total_amount
            ';

            $stmt =$this->conn->prepare($query);

            // sanitize data
            $this->invoice_id = htmlspecialchars(strip_tags($this->invoice_id));
            $this->qty = htmlspecialchars(strip_tags($this->qty));
            $this->item_desc = htmlspecialchars(strip_tags($this->item_desc));
            $this->total_amount = htmlspecialchars(strip_tags($this->total_amount));
            $this->price = htmlspecialchars(strip_tags($this->price));

            // bind data
            $stmt->bindParam(':invoice_id', $this->invoice_id);
            $stmt->bindParam(':qty', $this->qty);
            $stmt->bindParam(':item_desc', $this->item_desc);
            $stmt->bindParam(':total_amount', $this->total_amount);
            $stmt->bindParam(':price', $this->price);

            if($stmt->execute()) {
                return true;
            }

            printf("Error: %s.\n", $stmt->error);
            return false;
        }

        public function readStockIn(){
            $query = 'SELECT
                    p.id,
                    p.item_desc,
                    p.item_price,
                    p.total_amount,
                    wpv.total_stock_in AS stocks_qty,
                    wpv.remaining_stocks

                    FROM '.$this->table.' 
                    io INNER JOIN products 
                    p ON io.product_id = p.id
                    INNER JOIN warehouse_product_pivot wpv
                    ON p.id = wpv.product_id
                    INNER JOIN warehouse w
                    ON wpv.warehouse_id = w.id';

                   
            // Prepare statement

            $stmt =$this->conn->prepare($query);

            // Execute

            $stmt->execute();

            return $stmt;
        }
        public function readInvoice(){
            $query = 'SELECT
                    io.product_id,
                    io.total_amount,
                    p.item_desc,
                    wpv.total_sales AS sales_qty,
                    wpv.remaining_stocks

                    FROM '.$this->table.' 
                    io INNER JOIN products 
                    p ON io.product_id = p.id
                    INNER JOIN warehouse_product_pivot wpv
                    ON p.id = wpv.product_id
                    INNER JOIN warehouse w
                    ON wpv.warehouse_id = w.id';

                   
            // Prepare statement

            $stmt =$this->conn->prepare($query);

            // Execute

            $stmt->execute();

            return $stmt;
        }
        public function getInvoiceItems() {

            $query ='SELECT
                    invoice_id,
                    invoice_order_id,
                    product_id,
                    qty,
                    item_desc,
                    price,
                    total_amount
                    FROM '.$this->table.' WHERE invoice_id = :invoice_id ';
                   

            // Prepare statement

            $stmt =$this->conn->prepare($query);

            $this->invoice_id = htmlspecialchars(strip_tags($this->invoice_id));

            // Bind the ID
            $stmt->bindParam(':invoice_id', $this->invoice_id);


            // Execute

            // if($stmt->execute()) {
            //     return $stmt;
            // }
                $stmt->execute();
            // printf("Error: %s.\n", $stmt->error);
            // return false;
            $row = $stmt->fetchALl(PDO::FETCH_ASSOC);

            // Set properties

            // foreach($row as $item => $value) {

            // }
            // $this->invoice_id = $row['invoice_id'];
            // $this->invoice_order_id = $row['invoice_order_id'];
            // $this->product_id = $row['product_id'];
            // $this->qty = $row['qty'];
            // $this->item_desc = $row['item_desc'];
            // $this->price = $row['price'];
            // $this->total_amount = $row['total_amount'];
            
            return $row;
                
        }



        // public function update() {
        //     $query = 'UPDATE '.$this->table.'
        //     SET
        //     job_role = :job_role
        //     WHERE job_id = :job_id';

        //     $stmt =$this->conn->prepare($query);

        //     // sanitize data
        //     // $this->job_title = htmlspecialchars(strip_tags($this->job_title));
        //     // $this->job_desc = htmlspecialchars(strip_tags($this->job_desc));
        //     $this->job_id = htmlspecialchars(strip_tags($this->job_id));
        //     $this->job_role = htmlspecialchars(strip_tags($this->job_role));
        //     // $this->company = htmlspecialchars(strip_tags($this->company));
           

        //     // bind data
        //     // $stmt->bindParam(':job_title', $this->job_title);
        //     // $stmt->bindParam(':job_desc', $this->job_desc);
        //     $stmt->bindParam(':job_id', $this->job_id);
        //     $stmt->bindParam(':job_role', $this->job_role);
        //     // $stmt->bindParam(':company', $this->company);
         

        //     if($stmt->execute()) {
        //         return true;
        //     }

        //     printf("Error: %s.\n", $stmt->error);
        //     return false;
        // }

        // public function delete() {

        //     $query = "DELETE FROM `job`
        //     INNER JOIN job_applicant ja ON job_id = ja.job_id INNER JOIN applicant a ON ja.applicant_id = a.applicant_id WHERE ja.job_code = :job_code";

        //     // Prepare stmt
        //     $stmt = $this->conn->prepare($query);

        //     // Clean data
        //     $this->job_code = htmlspecialchars(strip_tags($this->job_code));

        //     // Bind data
        //     $stmt->bindParam(':job_code', $this->job_code);

        //     // Execute query

        //     if ($stmt->execute()) {
        //         return true;
        //     }
            
        //     printf("Error: %s.\n", $stmt->error);
        //     return false;
        // }

    }