<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/db.php';

header('Content-Type: application/json');
$slug = $_GET['category'] ?? '';
$sql = 'SELECT products.*, categories.name AS category_name, categories.slug AS category_slug FROM products JOIN categories ON categories.id = products.category_id WHERE is_active = 1';
$params = [];
if ($slug !== '') {
    $sql .= ' AND categories.slug = ?';
    $params[] = $slug;
}
$stmt = db()->prepare($sql);
$stmt->execute($params);
$items = [];
foreach ($stmt as $product) {
    $items[] = [
        'id' => (int) $product['id'],
        'name' => $product['name'],
        'slug' => $product['slug'],
        'sku' => $product['sku'],
        'category' => $product['category_slug'],
        'category_name' => $product['category_name'],
        'short_description' => $product['short_description'],
        'moq' => $product['moq'],
        'lead_time' => $product['lead_time'],
        'image' => product_image_url($product['image']),
        'featured' => (bool) $product['is_featured'],
    ];
}
echo json_encode(['products' => $items], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
