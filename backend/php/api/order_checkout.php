<?php
require_once __DIR__ . '/../helpers/response.php';
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Order.php';

require_auth();
if (!isset($_SESSION['cart']) || !$_SESSION['cart']) json_response(['error' => 'Cart empty'], 400);

$db = (new Database())->getConnection();
$orderModel = new Order($db);
try {
    $orderId = $orderModel->create(current_user_id(), array_values($_SESSION['cart']));
    $_SESSION['cart'] = [];
    json_response(['message' => 'Order placed', 'order_id' => $orderId]);
} catch (Exception $e) {
    json_response(['error' => 'Checkout failed', 'details' => $e->getMessage()], 500);
}
