<?php
require_once __DIR__ . '/../helpers/response.php';
require_once __DIR__ . '/../helpers/auth.php';

require_method('POST');
$data = $_POST ?: json_decode(file_get_contents('php://input'), true) ?: [];
require_fields(['food_id','qty'], $data);
if (!isset($_SESSION['cart'][$data['food_id']])) json_response(['error'=>'Item not in cart'],404);
$qty = (int)$data['qty'];
if ($qty <= 0) { unset($_SESSION['cart'][$data['food_id']]); }
else { $_SESSION['cart'][$data['food_id']]['qty'] = $qty; }
json_response(['cart' => array_values($_SESSION['cart'])]);
