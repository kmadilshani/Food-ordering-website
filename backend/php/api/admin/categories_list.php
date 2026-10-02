<?php
require_once __DIR__ . '/../../helpers/admin.php';
require_once __DIR__ . '/../../models/Category.php';

require_admin();
$db = (new Database())->getConnection();
$model = new Category($db);
json_response(['categories' => $model->all()]);
