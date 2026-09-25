<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/admin_layout.php';

$pdo = db();
$productCount = (int) $pdo->query('SELECT COUNT(*) FROM products')->fetchColumn();
$activeCount = (int) $pdo->query('SELECT COUNT(*) FROM products WHERE is_active = 1')->fetchColumn();
$inquiryCount = (int) $pdo->query('SELECT COUNT(*) FROM inquiries')->fetchColumn();
$newCount = (int) $pdo->query('SELECT COUNT(*) FROM inquiries WHERE status = "new"')->fetchColumn();
$recent = $pdo->query('SELECT products.*, categories.name AS category_name FROM products JOIN categories ON categories.id = products.category_id ORDER BY products.id DESC LIMIT 6')->fetchAll();
$inquiries = $pdo->query('SELECT * FROM inquiries ORDER BY id DESC LIMIT 5')->fetchAll();

admin_start('APEX Admin', 'home');
?>
<div class="admin-top">
  <div><p class="eyebrow">Dashboard</p><h1>Catalog overview</h1></div>
  <a class="btn btn-primary" href="/admin/product-form.php">Upload product</a>
</div>
<div class="stat-grid">
  <article class="stat"><span>Products</span><strong><?= $productCount ?></strong></article>
  <article class="stat"><span>Live on site</span><strong><?= $activeCount ?></strong></article>
  <article class="stat"><span>Inquiries</span><strong><?= $inquiryCount ?></strong></article>
  <article class="stat"><span>New quotes</span><strong><?= $newCount ?></strong></article>
</div>
<div class="cards-2">
  <article class="card">
    <h3>Recent products</h3>
    <?php foreach ($recent as $product): ?>
      <p><a href="/admin/product-form.php?id=<?= (int) $product['id'] ?>"><?= h($product['name']) ?></a> · <?= h($product['category_name']) ?></p>
    <?php endforeach; ?>
  </article>
  <article class="card">
    <h3>Latest inquiries</h3>
    <?php foreach ($inquiries as $inquiry): ?>
      <p><?= h($inquiry['name']) ?> · <?= h($inquiry['product_interest']) ?></p>
    <?php endforeach; ?>
    <?php if (!$inquiries): ?><p class="muted">No inquiries yet.</p><?php endif; ?>
  </article>
</div>
<?php admin_end(); ?>
