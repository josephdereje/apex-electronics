<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/auth.php';

start_session();
if (!empty($_SESSION['apex_admin'])) {
    redirect('/admin/index.php');
}
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (attempt_login($_POST['username'] ?? '', $_POST['password'] ?? '')) {
        redirect('/admin/index.php');
    }
    $error = 'Invalid username or password.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>APEX Admin Login</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= h(asset('css/site.css')) ?>">
</head>
<body class="login-wrap">
  <form class="login-card" method="post">
    <img src="<?= h(asset('img/logo.png')) ?>" alt="APEX" width="48" height="48" style="border-radius:12px">
    <h1>Admin panel</h1>
    <p class="muted">Upload products, manage the catalog, and review quote requests.</p>
    <?php if ($error): ?><div class="alert alert-err"><?= h($error) ?></div><?php endif; ?>
    <label for="username">Username</label>
    <input class="field-input" id="username" name="username" required>
    <div style="height:12px"></div>
    <label for="password">Password</label>
    <input class="field-input" id="password" name="password" type="password" required>
    <div style="height:18px"></div>
    <button class="btn btn-primary" type="submit">Sign in</button>
  </form>
</body>
</html>
