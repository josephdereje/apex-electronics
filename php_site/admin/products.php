<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/admin_layout.php';

$slug = $_GET['category'] ?? '';
$sql = 'SELECT products.*, categories.name AS category_name, categories.slug AS category_slug FROM products JOIN categories ON categories.id = products.category_id';
$params = [];
if ($slug !== '') {
    $sql .= ' WHERE categories.slug = ?';
    $params[] = $slug;
}
$sql .= ' ORDER BY products.id DESC';
$stmt = db()->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

admin_start('Product list | APEX Admin', 'products');
?>
<div class="admin-top">
  <div>
    <p class="eyebrow">APEX company catalog</p>
    <h1>Product list</h1>
    <p class="muted">Tempered glass, Bluetooth headphones, power banks, and chargers &amp; cables.</p>
  </div>
  <a class="btn btn-primary" href="/admin/product-form.php">Upload product</a>
</div>
<div class="filters">
  <a class="chip <?= $slug === '' ? 'active' : '' ?>" href="/admin/products.php">All</a>
  <?php foreach (categories() as $category): ?>
  <a class="chip <?= $slug === $category['slug'] ? 'active' : '' ?>" href="/admin/products.php?category=<?= h($category['slug']) ?>"><?= h($category['name']) ?></a>
  <?php endforeach; ?>
</div>
<table class="admin-table">
  <thead><tr><th>Image</th><th>Product</th><th>Category</th><th>MOQ</th><th>Status</th><th></th></tr></thead>
  <tbody>
    <?php foreach ($products as $product): ?>
    <tr>
      <td><img src="<?= h(product_image_url($product['image'])) ?>" alt=""></td>
      <td><strong><?= h($product['name']) ?></strong><div class="tiny"><?= h($product['sku']) ?></div></td>
      <td><?= h($product['category_name']) ?></td>
      <td><?= h($product['moq']) ?></td>
      <td><?= $product['is_active'] ? 'Live' : 'Hidden' ?></td>
      <td class="row-actions">
        <a class="btn btn-line" href="/admin/product-form.php?id=<?= (int) $product['id'] ?>">Edit</a>
        <a class="btn btn-line" href="/admin/delete.php?id=<?= (int) $product['id'] ?>">Delete</a>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php admin_end(); ?>
