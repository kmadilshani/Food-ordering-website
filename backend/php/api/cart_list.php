<?php
require_once __DIR__ . '/../helpers/response.php';
require_once __DIR__ . '/../helpers/auth.php';

if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
$total = 0;
foreach ($_SESSION['cart'] as $item) { $total += $item['price'] * $item['qty']; }
json_response(['items' => array_values($_SESSION['cart']), 'total' => $total]);
