<?php
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
        // you want to allow, and if so:
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 1000');
    }
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
            // may also be using PUT, PATCH, HEAD etc
            header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
        }
    
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
            header("Access-Control-Allow-Headers: Accept, Content-Type, Content-Length, Accept-Encoding, X-CSRF-Token, Authorization");
        }
        exit(0);
    }


    include_once '../../config/Database.php';
    include_once '../../model/Report.php';

    
    
    $database = new Database();
    $db = $database->connect();

    // Instantiate POSt

    $stock_card = new Report($db);

    // Blog post query
    $result = $stock_card->readALl();

    // get row count
    $num = $result->rowCount();

    if($num > 0) {
        // POst array
        $posts_arr = array();
        $invoice_items =array();
        $posts_arr['response_array'] = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            
            $post_item = array( 
                    // Invoice
                    'inv_invoice_id' => $invoice_id,
                    'inv_customer_name' => $customer_name,
                    'inv_total_qty' => $total_qty,
                    'inv_total_amount' => $total_amount,
                    'inv_term' => $term,
                    'inv_delivery_date' => $delivery_date,
                    'inv_date_created' => $date_created
                   
            );
          
        
            
           

            // Push array to 'data'
            array_push($posts_arr['response_array'], $post_item);
        }

         echo json_encode($posts_arr);
        // $invoice_items  = array_unique($post_item);
        
    } 
    else {
        echo json_encode(
            array('message' => 'No data found.')
        );
    }
