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
include_once '../../model/Invoice.php';
include_once '../../model/InvoiceOrder.php';
include_once '../../model/WarehouseProduct.php';



$database = new Database();
$db = $database->connect();

// Instantiate POSt

$invoice = new Invoice($db);
$invoice_order = new InvoiceOrder($db);
$wp = new WarehouseProduct($db);

$data = json_decode(file_get_contents("php://input"));
$inv_order = array();

$invoice->invoice_id = $data->invoice_id;
$invoice->customer_name= $data->customer_name;
$invoice->total_qty = $data->total_qty;
$invoice->total_amount = $data->total_amount;
$invoice->term = $data->term;
$invoice->delivery_date = $data->delivery_date;
$inv_order = json_decode(json_encode($data->invoice_order), true);;




if ($invoice->insertInvoice()) {
    
        foreach ($inv_order as $key => $order) {
            
            $invoice_order->invoice_id = $data->invoice_id;
            $invoice_order->product_id = $order['product_id'];
            $invoice_order->qty = $order['qty'];
            $invoice_order->item_desc = $order['item_description'];
            $invoice_order->price = $order['item_price'];
            $invoice_order->total_amount = $order['total'];
            $wp->sales_qty = $order['qty'];
            $wp->product_id = $order['product_id'];

            if ($invoice_order->insertInvoiceOrder()) {
                if($wp->updateStocks()){
                    echo json_encode(
                        array('message' => 'Post created.')
                    );
                }
                
            }
        }
    
    
} else {
    echo json_encode(
        array('message' => 'Post not created.')
    );
}
