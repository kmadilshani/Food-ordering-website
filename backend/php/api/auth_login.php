<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/response.php';
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../models/User.php';

require_method('POST');
$data = $_POST ?: json_decode(file_get_contents('php://input'), true) ?: [];
require_fields(['email','password'], $data);

$db = (new Database())->getConnection();
$userModel = new User($db);
$user = $userModel->findByEmail($data['email']);
if (!$user || !password_verify($data['password'], $user['password_hash'])) {
    json_response(['error' => 'Invalid credentials'], 401);
}
$_SESSION['user_id'] = $user['id'];
json_response(['message' => 'Login successful', 'user' => ['id'=>$user['id'],'name'=>$user['name'],'email'=>$user['email']]]);
