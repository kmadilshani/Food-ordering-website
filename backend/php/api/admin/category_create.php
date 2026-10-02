<?php
require_once __DIR__ . '/../../helpers/admin.php';
require_once __DIR__ . '/../../models/Category.php';
require_method('POST');
require_admin();
$data = $_POST ?: json_decode(file_get_contents('php://input'), true) ?: [];
require_fields(['name','slug'], $data);
$db = (new Database())->getConnection();
$model = new Category($db);
if (!$model->create($data['name'], $data['slug'])) json_response(['error'=>'Create failed'],500);
json_response(['message'=>'Created']);
