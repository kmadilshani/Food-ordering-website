<?php
session_start();

function current_user_id() {
    return $_SESSION['user_id'] ?? null;
}

function require_auth() {
    if (!current_user_id()) {
        json_response(['error' => 'Authentication required'], 401);
    }
}
