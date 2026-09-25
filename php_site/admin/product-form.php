<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/admin_layout.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$product = [
    'category_id' => '',
    'name' => '',
    'sku' => '',
    'short_description' => '',
    'description' => '',
    'specifications' => '',
    'moq' => '',
    'lead_time' => '',
    'image' => '',
    'is_featured' => 0,
    'is_active' => 1,
];
if ($id) {
    $stmt = db()->prepare('SELECT * FROM products WHERE id = ?');
    $stmt->execute([$id]);
    $found = $stmt->fetch();
    if ($found) {
        $product = $found;
    }
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $categoryId = (int) ($_POST['category_id'] ?? 0);
    if ($name === '' || $categoryId < 1) {
        $error = 'Name and category are required.';
    } else {
        $slug = $id && !empty($product['slug']) ? $product['slug'] : slugify($name);
        $image = $product['image'] ?? '';
        if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
            $info = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($info, $_FILES['image']['tmp_name']);
            finfo_close($info);
            if (!isset($allowed[$mime])) {
                $error = 'Please upload a JPG, PNG, or WebP image.';
            } else {
                $image = $slug . '-' . time() . '.' . $allowed[$mime];
                move_uploaded_file($_FILES['image']['tmp_name'], dirname(__DIR__) . '/uploads/products/' . $image);
            }
        }
        if ($error === '') {
            $fields = [
                $categoryId,
                $name,
                $slug,
                trim($_POST['sku'] ?? ''),
                trim($_POST['short_description'] ?? ''),
                trim($_POST['description'] ?? ''),
                trim($_POST['specifications'] ?? ''),
                trim($_POST['moq'] ?? ''),
                trim($_POST['lead_time'] ?? ''),
                $image,
                isset($_POST['is_featured']) ? 1 : 0,
                isset($_POST['is_active']) ? 1 : 0,
            ];
            if ($id) {
                $fields[] = $id;
                db()->prepare('UPDATE products SET category_id=?, name=?, slug=?, sku=?, short_description=?, description=?, specifications=?, moq=?, lead_time=?, image=?, is_featured=?, is_active=? WHERE id=?')->execute($fields);
                flash('Product updated.');
            } else {
                db()->prepare('INSERT INTO products (category_id, name, slug, sku, short_description, description, specifications, moq, lead_time, image, is_featured, is_active) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)')->execute($fields);
                flash('Product uploaded and saved to the catalog.');
            }
            redirect('/admin/products.php');
        }
    }
    $product = array_merge($product, $_POST);
}

admin_start($id ? 'Edit product' : 'Upload product', $id ? 'products' : 'upload');
?>
<div class="admin-top">
  <div><p class="eyebrow">Catalog</p><h1><?= $id ? 'Edit product' : 'Upload product' ?></h1></div>
  <a class="btn btn-line" href="/admin/products.php">Back to list</a>
</div>
<?php if ($error): ?><div class="alert alert-err"><?= h($error) ?></div><?php endif; ?>
<form class="quote-card" method="post" enctype="multipart/form-data">
  <div class="form-grid">
    <div>
      <label for="category_id">Category</label>
      <select class="field-input" id="category_id" name="category_id" required>
        <?php foreach (categories() as $category): ?>
        <option value="<?= (int) $category['id'] ?>" <?= (string) $product['category_id'] === (string) $category['id'] ? 'selected' : '' ?>><?= h($category['name']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div><label for="name">Product name</label><input class="field-input" id="name" name="name" value="<?= h((string) $product['name']) ?>" required></div>
    <div><label for="sku">SKU</label><input class="field-input" id="sku" name="sku" value="<?= h((string) $product['sku']) ?>"></div>
    <div><label for="moq">MOQ</label><input class="field-input" id="moq" name="moq" value="<?= h((string) $product['moq']) ?>"></div>
    <div class="full"><label for="short_description">Short description</label><input class="field-input" id="short_description" name="short_description" value="<?= h((string) $product['short_description']) ?>"></div>
    <div class="full"><label for="description">Full description</label><textarea class="field-input" id="description" name="description" rows="5"><?= h((string) $product['description']) ?></textarea></div>
    <div class="full"><label for="specifications">Specifications</label><textarea class="field-input" id="specifications" name="specifications" rows="5"><?= h((string) $product['specifications']) ?></textarea></div>
    <div><label for="lead_time">Lead time</label><input class="field-input" id="lead_time" name="lead_time" value="<?= h((string) $product['lead_time']) ?>"></div>
    <div>
      <label for="image">Product image</label>
      <div class="dropzone">
        <input class="file-input" id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp">
        <p class="tiny">JPG, PNG, or WebP. This image appears on the public product list.</p>
        <img class="preview-img" data-image-preview <?= !empty($product['image']) ? 'src="' . h(product_image_url((string) $product['image'])) . '"' : 'hidden' ?> alt="Preview">
      </div>
    </div>
    <div>
      <label><input type="checkbox" name="is_featured" <?= !empty($product['is_featured']) ? 'checked' : '' ?>> Featured on homepage</label>
      <div style="height:8px"></div>
      <label><input type="checkbox" name="is_active" <?= !isset($product['is_active']) || $product['is_active'] ? 'checked' : '' ?>> Visible on public product list</label>
    </div>
  </div>
  <div style="height:18px"></div>
  <button class="btn btn-primary" type="submit">Save product</button>
</form>
<?php admin_end(); ?>
