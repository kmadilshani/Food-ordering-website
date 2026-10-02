<?php
function json_response($data, int $status = 200) {
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function require_method(string $expected) {
    if (strtoupper($_SERVER['REQUEST_METHOD']) !== strtoupper($expected)) {
        json_response(['error' => 'Method Not Allowed'], 405);
    }
}

function require_fields(array $fields, array $source) {
    $missing = [];
    foreach ($fields as $f) {
        if (!isset($source[$f]) || trim($source[$f]) === '') {
            $missing[] = $f;
        }
    }
    if ($missing) {
        json_response(['error' => 'Missing required fields', 'fields' => $missing], 422);
    }
}
