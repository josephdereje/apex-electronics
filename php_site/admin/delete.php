<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/admin_layout.php';

$id = (int) ($_GET['id'] ?? 0);
$stmt = db()->prepare('SELECT * FROM products WHERE id = ?');
$stmt->execute([$id]);
$product = $stmt->fetch();
if (!$product) {
    redirect('/admin/products.php');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    db()->prepare('DELETE FROM products WHERE id = ?')->execute([$id]);
    flash('Product removed from the catalog.');
    redirect('/admin/products.php');
}
admin_start('Delete product', 'products');
?>
<h1>Delete <?= h($product['name']) ?>?</h1>
<p class="muted">This removes the product from the database and the public catalog.</p>
<form method="post">
  <button class="btn btn-primary" type="submit">Delete product</button>
  <a class="btn btn-line" href="/admin/products.php">Cancel</a>
</form>
<?php admin_end(); ?>
