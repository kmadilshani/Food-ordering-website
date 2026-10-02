<?php
require_once __DIR__ . '/../helpers/response.php';
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';

require_auth();
$db = (new Database())->getConnection();
$userModel = new User($db);
$user = $userModel->findById(current_user_id());
json_response(['user' => $user]);
