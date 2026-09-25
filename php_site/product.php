<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/layout.php';

$slug = $_GET['slug'] ?? '';
$stmt = db()->prepare('SELECT products.*, categories.name AS category_name FROM products JOIN categories ON categories.id = products.category_id WHERE products.slug = ? AND is_active = 1');
$stmt->execute([$slug]);
$product = $stmt->fetch();
if (!$product) {
    http_response_code(404);
    page_start('Product not found | APEX', 'The requested product is not in the catalog.', 'products');
    echo '<section class="section"><div class="container"><h1>Product not found</h1><a href="/products.php">Back to catalog</a></div></section>';
    page_end();
    exit;
}
$relatedStmt = db()->prepare('SELECT * FROM products WHERE category_id = ? AND id != ? AND is_active = 1 LIMIT 3');
$relatedStmt->execute([$product['category_id'], $product['id']]);
$related = $relatedStmt->fetchAll();
$specs = array_filter(array_map('trim', explode("\n", (string) $product['specifications'])));

page_start($product['name'] . ' | APEX', $product['short_description'], 'products');
?>
<section class="section">
  <div class="container detail">
    <div class="detail-media reveal"><img src="<?= h(product_image_url($product['image'])) ?>" alt="<?= h($product['name']) ?>"></div>
    <div class="detail-copy reveal">
      <p class="eyebrow"><?= h($product['category_name']) ?></p>
      <h1><?= h($product['name']) ?></h1>
      <p class="muted"><?= h($product['description']) ?></p>
      <p><strong>SKU</strong> <?= h($product['sku']) ?> · <strong>MOQ</strong> <?= h($product['moq']) ?> · <strong>Lead time</strong> <?= h($product['lead_time']) ?></p>
      <?php if ($specs): ?>
      <ul class="spec-list"><?php foreach ($specs as $spec): ?><li><?= h($spec) ?></li><?php endforeach; ?></ul>
      <?php endif; ?>
      <a class="btn btn-primary" href="/contact.php">Request a Quote</a>
    </div>
  </div>
</section>
<?php if ($related): ?>
<section class="section" style="padding-top:0">
  <div class="container">
    <h2 class="reveal">Related products</h2>
    <div class="product-grid">
      <?php foreach ($related as $item): ?>
      <a class="product-card reveal" href="/product.php?slug=<?= h($item['slug']) ?>">
        <div class="thumb"><img src="<?= h(product_image_url($item['image'])) ?>" alt="<?= h($item['name']) ?>"></div>
        <div class="body"><h3><?= h($item['name']) ?></h3></div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>
<?php page_end(); ?>
