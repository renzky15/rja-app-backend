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

    $inv_order = new Report($db);
    $data = json_decode(file_get_contents("php://input"));
    
    $inv_order->invoice_id = $data->invoice_id;

    


   $result = $inv_order->getInvoiceItems();
   echo json_encode($result);
   
    // if($num > 0) {
    // //     // POst array
    //     $posts_arr = array();
    //     $posts_arr['response_array'] = array();

    //     while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    //         extract($row);
    //         // $formatted_date = date('Y-m-d',strtotime($date_created));
    //         $post_item = array( 
                
    //             'invoice_order_id' => $inv_order->invoice_order_id,
    //             'invoice_id' => $inv_order->invoice_id,
    //             'product_id' => $inv_order->product_id,
    //             'qty' => $inv_order->qty,
    //             'item_desc' => $inv_order->item_desc,
    //             'price' => $inv_order->price,
    //             'total_amount' => $inv_order->total_amount
                
    //         );

    //         // Push array to 'data'
    //         array_push($posts_arr['response_array'], $post_item);
    //     }

         
    // } else {
    //     echo json_encode(
    //         array('message' => 'No data found.')
    //     );
    // }

    // $posts_arr = array();
    // $posts_arr['response_array'] = array();


    // $post_item = array(
    //             'invoice_order_id' => $inv_order->invoice_order_id,
    //             'invoice_id' => $inv_order->invoice_id,
    //             'product_id' => $inv_order->product_id,
    //             'qty' => $inv_order->qty,
    //             'item_desc' => $inv_order->item_desc,
    //             'price' => $inv_order->price,
    //             'total_amount' => $inv_order->total_amount
    // );
    // array_push($posts_arr['response_array'], $post_item);
    // echo json_encode($post_item);
