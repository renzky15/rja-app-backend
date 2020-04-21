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


include_once '../config/Database.php';
include_once '../model/WarehouseProduct.php';
include_once '../model/Product.php';



$database = new Database();
$db = $database->connect();

// Instantiate POSt

$warehouse = new WarehouseProduct($db);
$product = new Product($db);

$data = json_decode(file_get_contents("php://input"));
$product_arr = array();
$warehouse_arr = array();

// $warehouse->item_price = 1400; 
$warehouse_arr = json_decode(json_encode($data->inventory_items), true);


foreach ($warehouse_arr as $key => $item) {



    $product->id = $item['product_id'];
    $product->item_desc = $item['item_desc'];
    $product->item_price = $item['item_price'];
    $product->qty = $item['qty'];
    $product->total_amount = $item['total_amount'];


    if ($product->insert()) {


        $warehouse->warehouse_id = $data->warehouse_id;
        $warehouse->product_id = $item['product_id'];
        $warehouse->total_stock_in = $item['qty'];
        if ($warehouse->insert()) {

            echo json_encode(
                array('message' => 'Post created.')
            );
        }
    } else {
        echo json_encode(
            array('message' => 'Post failed.')
        );
    }
}
