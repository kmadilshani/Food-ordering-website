<?php
require_once __DIR__ . '/../helpers/response.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Food.php';

$db = (new Database())->getConnection();
$model = new Food($db);
json_response(['foods' => $model->all()]);
