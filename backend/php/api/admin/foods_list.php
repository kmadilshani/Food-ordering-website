<?php
require_once __DIR__ . '/../../helpers/admin.php';
require_once __DIR__ . '/../../models/Food.php';
require_admin();
$db = (new Database())->getConnection();
$model = new Food($db);
json_response(['foods'=>$model->all()]);
