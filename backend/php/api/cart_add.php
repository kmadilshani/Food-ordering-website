<?php
require_once __DIR__ . '/../helpers/response.php';
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Food.php';

require_method('POST');
$data = $_POST ?: json_decode(file_get_contents('php://input'), true) ?: [];
require_fields(['food_id','qty'], $data);

if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
$db = (new Database())->getConnection();
$model = new Food($db);
$food = $model->find($data['food_id']);
if (!$food) json_response(['error' => 'Food not found'], 404);
$qty = max(1, (int)$data['qty']);
if (isset($_SESSION['cart'][$food['id']])) {
    $_SESSION['cart'][$food['id']]['qty'] += $qty;
} else {
    $_SESSION['cart'][$food['id']] = [
        'food_id' => $food['id'],
        'name' => $food['name'],
        'price' => (float)$food['price'],
        'qty' => $qty,
        'image' => $food['image'] ?? null
    ];
}
json_response(['cart' => array_values($_SESSION['cart'])]);
