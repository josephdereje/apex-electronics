<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/includes/admin_layout.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    db()->prepare('UPDATE inquiries SET status = ? WHERE id = ?')->execute([
        $_POST['status'] ?? 'new',
        (int) ($_POST['inquiry_id'] ?? 0),
    ]);
    flash('Inquiry status updated.');
    redirect('/admin/inquiries.php');
}
$inquiries = db()->query('SELECT * FROM inquiries ORDER BY id DESC')->fetchAll();
$statuses = ['new' => 'New', 'reviewing' => 'Reviewing', 'quoted' => 'Quoted', 'closed' => 'Closed'];
admin_start('Quote requests | APEX Admin', 'inquiries');
?>
<div class="admin-top"><div><p class="eyebrow">Sales</p><h1>Quote requests</h1></div></div>
<table class="admin-table">
  <thead><tr><th>Buyer</th><th>Interest</th><th>Quantity</th><th>Message</th><th>Status</th></tr></thead>
  <tbody>
    <?php foreach ($inquiries as $inquiry): ?>
    <tr>
      <td><strong><?= h($inquiry['name']) ?></strong><div class="tiny"><?= h($inquiry['email']) ?> · <?= h($inquiry['country']) ?></div></td>
      <td><?= h($inquiry['product_interest']) ?></td>
      <td><?= h($inquiry['quantity']) ?></td>
      <td><?= h($inquiry['message']) ?></td>
      <td>
        <form method="post">
          <input type="hidden" name="inquiry_id" value="<?= (int) $inquiry['id'] ?>">
          <select class="field-input" name="status">
            <?php foreach ($statuses as $value => $label): ?>
            <option value="<?= h($value) ?>" <?= $inquiry['status'] === $value ? 'selected' : '' ?>><?= h($label) ?></option>
            <?php endforeach; ?>
          </select>
          <button class="btn btn-line" type="submit">Update</button>
        </form>
      </td>
    </tr>
    <?php endforeach; ?>
    <?php if (!$inquiries): ?><tr><td colspan="5">No inquiries yet.</td></tr><?php endif; ?>
  </tbody>
</table>
<?php admin_end(); ?>
