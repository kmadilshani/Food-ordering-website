<?php
require_once __DIR__ . '/../helpers/response.php';
require_once __DIR__ . '/../helpers/auth.php';

require_method('POST');
session_destroy();
json_response(['message' => 'Logged out']);
