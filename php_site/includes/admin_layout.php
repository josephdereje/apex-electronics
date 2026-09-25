<?php
declare(strict_types=1);

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';

function admin_start(string $title, string $active = 'home'): void
{
    require_admin();
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= h($title) ?></title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= h(asset('css/site.css')) ?>">
  <link rel="stylesheet" href="<?= h(asset('css/admin.css')) ?>">
</head>
<body class="admin-body">
  <div class="admin-shell">
    <aside class="admin-side">
      <a class="brand" href="/admin/index.php">
        <img src="<?= h(asset('img/logo.png')) ?>" alt="APEX">
        <span>APEX Admin</span>
      </a>
      <p class="tiny">PHP catalog console</p>
      <a href="/admin/index.php" class="<?= $active === 'home' ? 'active' : '' ?>">Overview</a>
      <a href="/admin/products.php" class="<?= $active === 'products' ? 'active' : '' ?>">Product list</a>
      <a href="/admin/product-form.php" class="<?= $active === 'upload' ? 'active' : '' ?>">Upload product</a>
      <a href="/admin/inquiries.php" class="<?= $active === 'inquiries' ? 'active' : '' ?>">Quote requests</a>
      <a href="/products.php" target="_blank" rel="noopener">View public catalog</a>
      <a href="/admin/logout.php">Sign out</a>
    </aside>
    <section class="admin-main">
      <?php if ($flash): ?><div class="alert alert-ok"><?= h($flash) ?></div><?php endif; ?>
    <?php
}

function admin_end(): void
{
    ?>
    </section>
  </div>
  <script src="<?= h(asset('js/admin.js')) ?>"></script>
</body>
</html>
    <?php
}

function flash(string $message): void
{
    start_session();
    $_SESSION['flash'] = $message;
}
