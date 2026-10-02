<?php
require_once __DIR__ . '/../helpers/response.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Food.php';

$categoryId = $_GET['category_id'] ?? null;
if (!$categoryId) json_response(['error' => 'Missing category_id'], 422);
$db = (new Database())->getConnection();
$model = new Food($db);
$foods = $model->byCategory($categoryId);
$categoryName = null;
if ($foods && count($foods)) {
	$categoryName = $foods[0]['category_name'] ?? null;
} else {
	// Fallback fetch category name only
	$stmt = $db->prepare('SELECT name FROM categories WHERE id=? LIMIT 1');
	$stmt->execute([$categoryId]);
	$row = $stmt->fetch();
	if ($row) $categoryName = $row['name'];
}
json_response(['foods' => $foods, 'category' => ['id' => (int)$categoryId, 'name' => $categoryName]]);
