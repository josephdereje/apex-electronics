<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/layout.php';

$ok = false;
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $country = trim($_POST['country'] ?? '');
    $interest = trim($_POST['product_interest'] ?? '');
    $quantity = trim($_POST['quantity'] ?? '');
    $message = trim($_POST['message'] ?? '');
    if ($name === '' || $email === '' || $interest === '' || $message === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please complete the required fields with a valid company email.';
    } else {
        $stmt = db()->prepare('INSERT INTO inquiries (name, email, country, product_interest, quantity, message) VALUES (?,?,?,?,?,?)');
        $stmt->execute([$name, $email, $country, $interest, $quantity, $message]);
        $ok = true;
    }
}

$interests = [
    'tempered-glass' => 'Tempered glass',
    'bluetooth-headphones' => 'Bluetooth headphones',
    'power-banks' => 'Power banks',
    'chargers-cables' => 'Chargers & cables',
    'mixed-container' => 'Multiple / mixed container',
    'custom-odm' => 'Custom ODM project',
];

page_start('Contact APEX | Request a Quote', 'Send product requirements, target price and quantity. APEX replies within 24 hours from Guangzhou and Hong Kong.', 'contact');
?>
<section class="page-hero">
  <div class="container reveal">
    <p class="eyebrow">Let's build your next order together</p>
    <h1>Contact APEX</h1>
    <p>Send your product requirements, target price and quantity. Our export team will reply within 24 hours.</p>
  </div>
</section>
<section class="section">
  <div class="container contact-grid">
    <aside class="quote-card reveal">
      <h3>Main office — Guangzhou</h3>
      <p class="muted">Room 621, Garden Building, 368 Huanshi East Road, Yuexiu District, Guangzhou, China</p>
      <h3>Assembly line — Hong Kong</h3>
      <p class="muted">H020, 3/F, Phase 2, Kwai Shing Industrial Building, 42–46 Tai Lin Pai Road, Kwai Chung, N.T., Hong Kong</p>
      <p><strong>Phone / WhatsApp / WeChat</strong><br>+86 136 4066 6344</p>
      <p><strong>Email</strong><br>info@apextechnology.com</p>
      <p><strong>Website</strong><br><a href="https://www.apextechnology.com">www.apextechnology.com</a></p>
      <p class="muted">OEM &amp; ODM: tempered glass, Bluetooth audio, power banks, chargers &amp; cables.</p>
      <a class="btn btn-dark" href="<?= h(CATALOG_URL) ?>" download="APEX-Company-Catalog.pdf">Download Company Catalog</a>
      <div class="wechat-card">
        <p><strong>WeChat — scan to chat</strong></p>
        <img src="<?= h(asset('img/wechat-qr.png')) ?>" alt="APEX WeChat QR code">
      </div>
    </aside>
    <div class="quote-card reveal">
      <?php if ($ok): ?><div class="alert alert-ok">Inquiry received. The export team replies within 24 hours on business days.</div><?php endif; ?>
      <?php if ($error): ?><div class="alert alert-err"><?= h($error) ?></div><?php endif; ?>
      <form method="post">
        <div class="form-grid">
          <div><label for="name">Name</label><input class="field-input" id="name" name="name" required></div>
          <div><label for="email">Company email</label><input class="field-input" id="email" name="email" type="email" required></div>
          <div><label for="country">Country or region</label><input class="field-input" id="country" name="country"></div>
          <div>
            <label for="product_interest">Product interest</label>
            <select class="field-input" id="product_interest" name="product_interest" required>
              <?php foreach ($interests as $value => $label): ?>
              <option value="<?= h($value) ?>"><?= h($label) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="full"><label for="quantity">Estimated quantity</label><input class="field-input" id="quantity" name="quantity"></div>
          <div class="full"><label for="message">Message</label><textarea class="field-input" id="message" name="message" rows="5" required placeholder="Product, target price, specifications, branding or packaging"></textarea></div>
        </div>
        <p class="muted">By submitting, you agree that APEX may use this information to respond to your wholesale inquiry.</p>
        <button class="btn btn-primary" type="submit">Submit Inquiry</button>
      </form>
    </div>
  </div>
</section>
<?php page_end(); ?>
