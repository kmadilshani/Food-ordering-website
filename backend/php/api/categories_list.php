<?php
require_once __DIR__ . '/../helpers/response.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Category.php';

$db = (new Database())->getConnection();
$model = new Category($db);
json_response(['categories' => $model->all()]);
