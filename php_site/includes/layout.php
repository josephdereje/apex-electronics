<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';

function page_start(string $title, string $description, string $active = 'home'): void
{
    $nav = [
        'home' => ['/', 'Home'],
        'products' => ['/products.php', 'Products'],
        'oem' => ['/oem.php', 'OEM / ODM'],
        'about' => ['/about.php', 'About'],
        'contact' => ['/contact.php', 'Contact'],
    ];
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= h($title) ?></title>
  <meta name="description" content="<?= h($description) ?>">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= h(asset('css/site.css')) ?>">
</head>
<body>
  <header class="site-header">
    <div class="header-inner">
      <a class="brand" href="/">
        <img src="<?= h(asset('img/logo.png')) ?>" alt="APEX logo">
        <span>APEX</span>
      </a>
      <button class="menu-btn" type="button" aria-label="Open menu">Menu</button>
      <nav class="nav">
        <?php foreach ($nav as $key => [$href, $label]): ?>
          <a href="<?= h($href) ?>" class="<?= $active === $key ? 'active' : '' ?>"><?= h($label) ?></a>
        <?php endforeach; ?>
        <a class="btn btn-primary" href="/contact.php">Get a Quote</a>
      </nav>
    </div>
  </header>
  <main>
    <?php
}

function page_end(): void
{
    ?>
  </main>
  <footer class="site-footer">
    <div class="container footer-grid">
      <div>
        <a class="brand" href="/">
          <img src="<?= h(asset('img/logo.png')) ?>" alt="APEX logo">
          <span>APEX</span>
        </a>
        <p>Hong Kong and Guangzhou OEM/ODM supplier of tempered glass, Bluetooth headphones, power banks, and chargers &amp; cables, produced and delivered under the customer's brand.</p>
      </div>
      <div>
        <h4>Products</h4>
        <p><a href="/products.php?category=tempered-glass">Tempered Glass</a></p>
        <p><a href="/products.php?category=bluetooth-headphones">Bluetooth Headphones</a></p>
        <p><a href="/products.php?category=power-banks">Power Banks</a></p>
        <p><a href="/products.php?category=chargers-cables">Chargers &amp; Cables</a></p>
      </div>
      <div>
        <h4>Company</h4>
        <p><a href="/about.php">About APEX</a></p>
        <p><a href="/oem.php">OEM / ODM Services</a></p>
        <p><a href="/contact.php">Contact Us</a></p>
        <p><a href="/contact.php">Request a Quote</a></p>
        <p><a href="<?= h(CATALOG_URL) ?>" download="APEX-Company-Catalog.pdf">Download Catalog</a></p>
      </div>
      <div>
        <h4>Contact</h4>
        <p>Room 621, Garden Building, 368 Huanshi East Road, Yuexiu District, Guangzhou, China</p>
        <p>H020, 3/F, Phase 2, Kwai Shing Industrial Building, 42–46 Tai Lin Pai Road, Kwai Chung, N.T., Hong Kong</p>
        <p>+86 136 4066 6344<br>info@apextechnology.com<br><a href="https://www.apextechnology.com">www.apextechnology.com</a></p>
      </div>
    </div>
    <div class="container footer-social">
      <p>Follow APEX</p>
      <div class="social-tags">
        <a class="social-tag whatsapp" href="<?= h(APEX_WHATSAPP) ?>" target="_blank" rel="noopener" aria-label="WhatsApp">
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20.5 3.5A11 11 0 0 0 2.1 17.2L1 23l5.9-1.1A11 11 0 0 0 20.5 3.5zm-8.5 17a9 9 0 0 1-4.6-1.3l-.3-.2-3.5.6.7-3.4-.2-.3A9 9 0 1 1 12 20.5zm5-6.7c-.3-.1-1.6-.8-1.9-.9s-.4-.1-.6.1-.7.9-.8 1-.3.2-.6.1a7.4 7.4 0 0 1-2.2-1.4 8.2 8.2 0 0 1-1.5-1.9c-.2-.3 0-.4.1-.6l.5-.6.2-.4a.5.5 0 0 0 0-.5c0-.1-.6-1.5-.8-2s-.4-.5-.6-.5h-.5a1 1 0 0 0-.7.3 3 3 0 0 0-.9 2.2 5.2 5.2 0 0 0 1.1 2.8 11.8 11.8 0 0 0 4.5 4 15 15 0 0 0 1.5.6 3.6 3.6 0 0 0 1.6.1 2.9 2.9 0 0 0 1.9-1.3 2.3 2.3 0 0 0 .2-1.3c-.1-.1-.3-.2-.6-.3z"/></svg>
          WhatsApp
        </a>
        <a class="social-tag facebook" href="https://www.facebook.com/apextechnology" target="_blank" rel="noopener" aria-label="Facebook">
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14 9h3V6h-3c-2.2 0-4 1.8-4 4v2H8v3h2v7h3v-7h3l1-3h-4V10c0-.6.4-1 1-1z"/></svg>
          Facebook
        </a>
        <a class="social-tag instagram" href="https://www.instagram.com/apextechnology" target="_blank" rel="noopener" aria-label="Instagram">
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 3h10a4 4 0 0 1 4 4v10a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4V7a4 4 0 0 1 4-4zm10 2H7a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2zm-5 3.2A3.8 3.8 0 1 1 8.2 12 3.8 3.8 0 0 1 12 8.2zm0 2A1.8 1.8 0 1 0 13.8 12 1.8 1.8 0 0 0 12 10.2zM17.2 6.6a1 1 0 1 1-1 1 1 1 0 0 1 1-1z"/></svg>
          Instagram
        </a>
        <a class="social-tag linkedin" href="https://www.linkedin.com/company/apex-import-and-export" target="_blank" rel="noopener" aria-label="LinkedIn">
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6.5 9H4V20h2.5zm.2-4.2A1.7 1.7 0 1 0 5 6.5a1.7 1.7 0 0 0 1.7-1.7zM20 20h-2.5v-5.6c0-1.6-.6-2.6-2-2.6s-1.8.9-2.1 1.8V20H11v-7.4c0-2.2-.1-4-3.1-4H4.8A3.4 3.4 0 0 1 8 6.7c2.2 0 3.7 1.4 4.3 2.8h.1c.7-1.3 2.1-2.8 4.5-2.8 3.1 0 5.1 2 5.1 6.3z"/></svg>
          LinkedIn
        </a>
        <a class="social-tag wechat" href="/contact.php" aria-label="WeChat">
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9.5 4C5.9 4 3 6.5 3 9.6c0 1.8 1 3.4 2.6 4.5L5 16.2l2.4-1.2c.7.2 1.4.3 2.1.3.3 0 .5 0 .8-.1A4.8 4.8 0 0 1 10 13.6c0-3 2.9-5.4 6.4-5.5C15.4 5.8 12.7 4 9.5 4zm1.3 2.6a.9.9 0 1 1 0 1.8.9.9 0 0 1 0-1.8zm4.3 0a.9.9 0 1 1 0 1.8.9.9 0 0 1 0-1.8zM16.4 8.6c-2.9 0-5.3 2-5.3 4.5s2.4 4.5 5.3 4.5c.5 0 1.1-.1 1.6-.2l1.8.9-.4-1.5c1.2-.8 1.9-2 1.9-3.7 0-2.5-2.4-4.5-5-4.5zm-1.7 3.2a.7.7 0 1 1 0 1.4.7.7 0 0 1 0-1.4zm3.4 0a.7.7 0 1 1 0 1.4.7.7 0 0 1 0-1.4z"/></svg>
          WeChat
        </a>
        <a class="social-tag email" href="mailto:info@apextechnology.com" aria-label="Email">
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6h16a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1zm8 6.2L4.8 7.4h14.4zm0 1.6-8-5.2V17h16V8.6z"/></svg>
          Email
        </a>
      </div>
    </div>
    <div class="container legal">© <span data-year><?= current_year() ?></span> <?= h(APEX_NAME) ?>. All rights reserved.</div>
  </footer>
  <aside class="side-dock" data-dock>
    <div class="dock-toggles">
      <button type="button" class="dock-toggle" data-dock-open="chat">Chat</button>
      <button type="button" class="dock-toggle" data-dock-open="blog">Blog</button>
    </div>
    <div class="dock-panel" data-dock-panel="chat">
      <div class="dock-head">
        <h3>Chat with APEX</h3>
        <button type="button" data-dock-close aria-label="Close">×</button>
      </div>
      <p class="tiny">Export team in the Hong Kong time zone. Replies within 24 hours.</p>
      <div class="dock-actions">
        <a class="btn btn-primary" href="<?= h(APEX_WHATSAPP) ?>" target="_blank" rel="noopener">WhatsApp</a>
        <a class="btn btn-line" href="mailto:info@apextechnology.com">Email</a>
      </div>
      <img class="dock-qr" src="<?= h(asset('img/wechat-qr.png')) ?>" alt="WeChat QR — scan to chat">
      <p class="tiny">WeChat / WhatsApp / phone: +86 136 4066 6344</p>
      <form method="post" action="/contact.php">
        <input class="field-input" name="name" placeholder="Your name" required>
        <input class="field-input" name="email" type="email" placeholder="Company email" required>
        <select class="field-input" name="product_interest">
          <option value="tempered-glass">Tempered glass</option>
          <option value="bluetooth-headphones">Bluetooth headphones</option>
          <option value="power-banks">Power banks</option>
          <option value="chargers-cables">Chargers &amp; cables</option>
          <option value="mixed-container">Mixed container</option>
          <option value="custom-odm">Custom ODM</option>
        </select>
        <textarea class="field-input" name="message" rows="3" placeholder="Product, quantity, target price" required></textarea>
        <button class="btn btn-primary" type="submit">Send message</button>
      </form>
    </div>
    <div class="dock-panel" data-dock-panel="blog">
      <div class="dock-head">
        <h3>Catalog notes</h3>
        <button type="button" data-dock-close aria-label="Close">×</button>
      </div>
      <article class="blog-item"><p class="eyebrow">Glass</p><h4>360CC vs privacy vs SuperX</h4><p>Large-arc glass, 28° privacy, and flexible SuperX membrane from the company catalog.</p></article>
      <article class="blog-item"><p class="eyebrow">Audio</p><h4>How to pick a TWS model</h4><p>Range I covers B64–B68. Range II adds ear-hook B66-B. Range III is lifestyle B60 and compact B88.</p></article>
      <article class="blog-item"><p class="eyebrow">Power</p><h4>5,000 to 20,000 mAh</h4><p>PD + QC, digital display, magnetic and slim formats for mixed containers.</p></article>
      <article class="blog-item"><p class="eyebrow">Export</p><h4>FOB, CIF or DDP</h4><p>Hong Kong assembly and banking plus Guangzhou factory speed.</p></article>
    </div>
  </aside>
  <script src="<?= h(asset('js/site.js')) ?>"></script>
</body>
</html>
    <?php
}
