<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/response.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';

function require_admin() {
    if (!current_user_id()) {
        json_response(['error' => 'Authentication required'], 401);
    }
    $db = (new Database())->getConnection();
    $userModel = new User($db);
    $user = $userModel->findById(current_user_id());
    if (!$user || (int)($user['is_admin'] ?? 0) !== 1) {
        json_response(['error' => 'Admin only'], 403);
    }
}
