<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/layout.php';

$categories = categories();
$featured = db()->query('SELECT products.*, categories.name AS category_name FROM products JOIN categories ON categories.id = products.category_id WHERE is_active = 1 AND is_featured = 1 ORDER BY products.id DESC LIMIT 8')->fetchAll();

page_start('Mobile Accessories Built For Your Brand | APEX', 'APEX is a Guangzhou and Hong Kong OEM/ODM manufacturer of tempered glass, Bluetooth headphones, power banks and charging solutions for global export.', 'home');
?>
<section class="ads-banner" data-banner>
  <div class="ads-track">
    <a class="ads-slide is-active" href="/products.php?category=tempered-glass" style="--ads-image:url('<?= h(asset('img/banners/banner-360cc.jpg')) ?>')">
      <div class="ads-copy"><p class="eyebrow">Catalog campaign</p><h2>360CC Large-Arc Glass</h2><p>High-clarity full-coverage protectors with ESD anti-static coating and private-label packaging.</p><span class="btn btn-primary">Shop tempered glass</span></div>
    </a>
    <a class="ads-slide" href="/products.php?category=tempered-glass" style="--ads-image:url('<?= h(asset('img/banners/banner-privacy.jpg')) ?>')">
      <div class="ads-copy"><p class="eyebrow">Privacy range</p><h2>28° Anti-Peep Glass</h2><p>Retail-ready privacy film and glass with holographic packaging from the APEX catalog.</p><span class="btn btn-primary">View privacy glass</span></div>
    </a>
    <a class="ads-slide" href="/products.php?category=bluetooth-headphones" style="--ads-image:url('<?= h(asset('img/banners/banner-audio.jpg')) ?>')">
      <div class="ads-copy"><p class="eyebrow">Bluetooth 6.0</p><h2>TWS Earbuds. Your Colors.</h2><p>B64 to B88 models with display cases, ear-hook options and retail box artwork.</p><span class="btn btn-primary">Explore audio</span></div>
    </a>
    <a class="ads-slide" href="/products.php?category=power-banks" style="--ads-image:url('<?= h(asset('img/banners/banner-power.jpg')) ?>')">
      <div class="ads-copy"><p class="eyebrow">5,000–20,000 mAh</p><h2>PD + QC Power Banks</h2><p>Slim, magnetic and digital-display formats with CE, FCC and RoHS designs.</p><span class="btn btn-primary">View power banks</span></div>
    </a>
    <a class="ads-slide" href="/products.php?category=chargers-cables" style="--ads-image:url('<?= h(asset('img/banners/banner-charge.jpg')) ?>')">
      <div class="ads-copy"><p class="eyebrow">20–65W / global plugs</p><h2>Chargers & Cables</h2><p>Wall, car and GaN chargers plus braided Lightning, USB-C and Micro USB cables.</p><span class="btn btn-primary">View charging</span></div>
    </a>
  </div>
  <div class="ads-nav">
    <button type="button" data-banner-prev aria-label="Previous banner">‹</button>
    <div class="ads-dots" data-banner-dots></div>
    <button type="button" data-banner-next aria-label="Next banner">›</button>
  </div>
  <div class="ads-progress"><span data-banner-progress></span></div>
</section>
<div class="marquee" aria-hidden="true">
  <div class="marquee-track">
    <span>Tempered Glass</span><span>360CC Large-Arc</span><span>28° Privacy</span><span>SuperX Membrane</span><span>Bluetooth 6.0</span><span>Power Banks</span><span>Chargers & Cables</span><span>OEM / ODM</span>
    <span>Tempered Glass</span><span>360CC Large-Arc</span><span>28° Privacy</span><span>SuperX Membrane</span><span>Bluetooth 6.0</span><span>Power Banks</span><span>Chargers & Cables</span><span>OEM / ODM</span>
  </div>
</div>
<section class="hero">
  <div class="orb orb-1"></div>
  <div class="orb orb-2"></div>
  <div class="container hero-grid">
    <div class="reveal">
      <p class="eyebrow">OEM & ODM Manufacturing · Global Export</p>
      <h1>Mobile Accessories, Built For Your Brand</h1>
      <p class="lead">Direct manufacturing. Flexible customization. Reliable export. APEX supplies tempered glass, Bluetooth headphones, power banks and charging solutions from Guangzhou and Hong Kong.</p>
      <div class="hero-actions">
        <a class="btn btn-primary" href="/products.php">Explore Products</a>
        <a class="btn btn-ghost" href="/oem.php">Our OEM / ODM Services</a>
        <a class="btn btn-ghost" href="<?= h(CATALOG_URL) ?>" download="APEX-Company-Catalog.pdf">Download Catalog</a>
      </div>
      <div class="proof">
        <div><strong data-count="4">4</strong><span>Core categories</span></div>
        <div><strong data-count="50" data-suffix="+">50+</strong><span>Export markets</span></div>
        <div><strong>24h</strong><span>Quote response</span></div>
        <div><strong>100%</strong><span>Final inspection</span></div>
      </div>
    </div>
    <aside class="hero-card reveal float-card">
      <h3>Four catalog lines</h3>
      <div class="cat-row"><span>Tempered glass</span><b>360CC · privacy · SuperX</b></div>
      <div class="cat-row"><span>Bluetooth audio</span><b>TWS · Bluetooth 6.0</b></div>
      <div class="cat-row"><span>Power banks</span><b>5,000–20,000 mAh</b></div>
      <div class="cat-row"><span>Chargers & cables</span><b>20–65W · global plugs</b></div>
    </aside>
  </div>
</section>
<section class="section">
  <div class="container">
    <div class="section-head reveal">
      <p class="eyebrow">Company catalog</p>
      <h2>Four core categories. One export partner.</h2>
    </div>
    <div class="cards-6 cards-4">
      <?php foreach ($categories as $category): ?>
      <a class="card reveal tilt" href="/products.php?category=<?= h($category['slug']) ?>">
        <div class="card-kicker"><?= h($category['eyebrow']) ?></div>
        <h3><?= h($category['name']) ?></h3>
        <p class="muted"><?= h($category['summary']) ?></p>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<section class="section" style="padding-top:0">
  <div class="container">
    <div class="section-head reveal"><p class="eyebrow">From the APEX catalog</p><h2>Featured products now in the database</h2></div>
    <div class="product-grid">
      <?php foreach ($featured as $product): ?>
      <a class="product-card reveal tilt" href="/product.php?slug=<?= h($product['slug']) ?>">
        <div class="thumb"><img src="<?= h(product_image_url($product['image'])) ?>" alt="<?= h($product['name']) ?>"></div>
        <div class="body"><span class="badge"><?= h($product['category_name']) ?></span><h3><?= h($product['name']) ?></h3><p class="muted"><?= h($product['short_description']) ?></p></div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<section class="section" style="padding-top:0">
  <div class="container">
    <div class="cta reveal">
      <div>
        <h2>Let's build your next order together.</h2>
        <p>Send your product requirements, target price and quantity. Our export team will reply within 24 hours.</p>
      </div>
      <div class="hero-actions" style="margin:0">
        <a class="btn btn-primary" href="/contact.php">Start Your Project</a>
        <a class="btn btn-ghost" href="<?= h(CATALOG_URL) ?>" download="APEX-Company-Catalog.pdf">Download Catalog</a>
      </div>
    </div>
  </div>
</section>
<?php page_end(); ?>
