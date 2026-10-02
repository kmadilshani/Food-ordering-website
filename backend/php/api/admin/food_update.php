<?php
require_once __DIR__ . '/../../helpers/admin.php';
require_once __DIR__ . '/../../models/Food.php';
require_method('POST');
require_admin();
$data = $_POST ?: json_decode(file_get_contents('php://input'), true) ?: [];
require_fields(['id','name','price'], $data);
$db = (new Database())->getConnection();
$model = new Food($db);
$ok = $model->update($data['id'], $data['category_id'] ?? null, $data['name'], $data['price'], $data['description'] ?? '', $data['image'] ?? '');
if(!$ok) json_response(['error'=>'Update failed'],500);
json_response(['message'=>'Updated']);
