<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/response.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../helpers/auth.php';

require_method('POST');
$data = $_POST ?: json_decode(file_get_contents('php://input'), true) ?: [];
require_fields(['name','email','password'], $data);

$db = (new Database())->getConnection();
$userModel = new User($db);

if ($userModel->findByEmail($data['email'])) {
    json_response(['error' => 'Email already registered'], 409);
}

$ok = $userModel->create($data['name'], $data['email'], $data['password']);
if (!$ok) {
    json_response(['error' => 'Registration failed'], 500);
}
$new = $userModel->findByEmail($data['email']);
$_SESSION['user_id'] = $new['id'];
json_response(['message' => 'User registered', 'user' => [
    'id' => $new['id'],
    'name' => $new['name'],
    'email' => $new['email']
]]);
