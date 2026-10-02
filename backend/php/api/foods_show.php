<?php
require_once __DIR__ . '/../helpers/response.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Food.php';

$id = $_GET['id'] ?? null;
if (!$id) json_response(['error' => 'Missing id'], 422);
$db = (new Database())->getConnection();
$model = new Food($db);
$food = $model->find($id);
if (!$food) json_response(['error' => 'Not found'], 404);
json_response(['food' => $food]);
