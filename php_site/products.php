<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/layout.php';

$slug = $_GET['category'] ?? '';
$all = categories();
$active = null;
$sql = 'SELECT products.*, categories.name AS category_name, categories.slug AS category_slug FROM products JOIN categories ON categories.id = products.category_id WHERE is_active = 1';
$params = [];
if ($slug !== '') {
    $sql .= ' AND categories.slug = ?';
    $params[] = $slug;
    foreach ($all as $category) {
        if ($category['slug'] === $slug) {
            $active = $category;
        }
    }
}
$sql .= ' ORDER BY is_featured DESC, name';
$stmt = db()->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

page_start('Products | APEX Mobile Accessories', 'Four categories of mobile accessories manufactured to export quality standards, with private branding, packaging, and custom specifications.', 'products');
?>
<section class="page-hero">
  <div class="container reveal">
    <p class="eyebrow">Catalog</p>
    <h1>Four catalog lines. Export quality. Your brand.</h1>
    <p>Tempered glass, Bluetooth headphones, power banks, and chargers &amp; cables from the APEX company catalog. Every line supports private branding, packaging, and custom specifications.</p>
    <div class="hero-actions">
      <a class="btn btn-primary" href="<?= h(CATALOG_URL) ?>" download="APEX-Company-Catalog.pdf">Download Company Catalog</a>
    </div>
  </div>
</section>
<section class="section">
  <div class="container">
    <div class="filters reveal">
      <a class="chip <?= $active ? '' : 'active' ?>" href="/products.php">All products</a>
      <?php foreach ($all as $category): ?>
      <a class="chip <?= $active && $active['slug'] === $category['slug'] ? 'active' : '' ?>" href="/products.php?category=<?= h($category['slug']) ?>"><?= h($category['name']) ?></a>
      <?php endforeach; ?>
    </div>
    <div class="product-grid">
      <?php foreach ($products as $product): ?>
      <a class="product-card reveal" href="/product.php?slug=<?= h($product['slug']) ?>">
        <div class="thumb"><img src="<?= h(product_image_url($product['image'])) ?>" alt="<?= h($product['name']) ?>"></div>
        <div class="body">
          <span class="badge"><?= h($product['category_name']) ?></span>
          <h3><?= h($product['name']) ?></h3>
          <p class="muted"><?= h($product['short_description']) ?></p>
          <div class="meta"><span>MOQ <?= h($product['moq']) ?></span><span><?= h($product['lead_time']) ?></span></div>
        </div>
      </a>
      <?php endforeach; ?>
      <?php if (!$products): ?><p class="muted">No products in this category yet. Upload items from the admin panel.</p><?php endif; ?>
    </div>
  </div>
</section>
<?php page_end(); ?>
