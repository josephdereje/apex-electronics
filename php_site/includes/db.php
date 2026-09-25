<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

function db(): PDO
{
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }
    $dataDir = dirname(__DIR__) . '/data';
    $uploadDir = dirname(__DIR__) . '/uploads/products';
    if (!is_dir($dataDir)) {
        mkdir($dataDir, 0775, true);
    }
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0775, true);
    }
    $pdo = new PDO('sqlite:' . $dataDir . '/apex.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->exec('PRAGMA foreign_keys = ON');
    migrate($pdo);
    seed($pdo);
    return $pdo;
}

function migrate(PDO $pdo): void
{
    $pdo->exec('
        CREATE TABLE IF NOT EXISTS categories (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            slug TEXT NOT NULL UNIQUE,
            eyebrow TEXT,
            summary TEXT,
            highlights TEXT,
            sort_order INTEGER DEFAULT 0
        );
        CREATE TABLE IF NOT EXISTS products (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            category_id INTEGER NOT NULL,
            name TEXT NOT NULL,
            slug TEXT NOT NULL UNIQUE,
            sku TEXT,
            short_description TEXT,
            description TEXT,
            specifications TEXT,
            moq TEXT,
            lead_time TEXT,
            image TEXT,
            is_featured INTEGER DEFAULT 0,
            is_active INTEGER DEFAULT 1,
            created_at TEXT DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (category_id) REFERENCES categories(id)
        );
        CREATE TABLE IF NOT EXISTS inquiries (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            email TEXT NOT NULL,
            country TEXT,
            product_interest TEXT NOT NULL,
            quantity TEXT,
            message TEXT NOT NULL,
            status TEXT DEFAULT "new",
            created_at TEXT DEFAULT CURRENT_TIMESTAMP
        );
    ');
}

function seed(PDO $pdo): void
{
    $hasOld = (int) $pdo->query("SELECT COUNT(*) FROM categories WHERE slug IN ('lcd-screens','chargers','cables')")->fetchColumn();
    if ($hasOld > 0) {
        $pdo->exec('DELETE FROM products');
        $pdo->exec('DELETE FROM categories');
    }
    $count = (int) $pdo->query('SELECT COUNT(*) FROM categories')->fetchColumn();
    if ($count > 0) {
        return;
    }

    $categories = [
        ['Tempered Glass', 'tempered-glass', '01', 'High-clarity tempered glass and protective-film options, including 360CC large-arc, 28° privacy glass, and SuperX flexible membrane.', "360CC large-arc\n28° privacy\nSuperX membrane", 1],
        ['Bluetooth Headphones', 'bluetooth-headphones', '02', 'True-wireless earbuds with retail packaging and multiple case formats: compact stem, ear-hook and lifestyle designs.', "Bluetooth 6.0\nB60–B88 models\nColor programs", 2],
        ['Power Banks', 'power-banks', '03', 'Portable chargers from 5,000 to 20,000 mAh with PD and QC fast charging, digital displays and multi-layer safety.', "5,000–20,000 mAh\nPD + QC\nMagnetic and slim", 3],
        ['Chargers & Cables', 'chargers-cables', '04', 'GaN and standard wall chargers, car chargers, and braided or PVC cables in Lightning, USB-C and Micro USB formats.', "20–65W\nGlobal plugs\nBraided and PVC", 4],
    ];
    $insertCategory = $pdo->prepare('INSERT INTO categories (name, slug, eyebrow, summary, highlights, sort_order) VALUES (?,?,?,?,?,?)');
    foreach ($categories as $category) {
        $insertCategory->execute($category);
    }

    $ids = [];
    foreach ($pdo->query('SELECT id, slug FROM categories') as $row) {
        $ids[$row['slug']] = $row['id'];
    }

    $source = dirname(__DIR__, 2) . '/assets/img/products';
    $products = [
        [$ids['tempered-glass'], '360CC Large-Arc Glass', '360cc-large-arc-glass', 'APX-TG-360CC', 'Rinda 360CC ultra large-arc glass with ESD anti-static coating and retail-ready packaging.', 'High-clarity large-arc tempered glass for current flagship models.', "360CC large-arc\nESD anti-static\nCustom retail box", '2,000 pcs / model', 'Samples 3–7 days', 'glass-360cc.jpg', 1],
        [$ids['tempered-glass'], '28° Privacy Glass', '28-privacy-glass', 'APX-TG-PRIV28', '28-degree anti-peep privacy glass with holographic retail packaging.', 'Privacy-film packaging and product presentation from the APEX catalog.', "28° privacy filter\nRetail set packaging", '2,000 pcs / model', 'Samples 3–7 days', 'glass-privacy.jpg', 1],
        [$ids['tempered-glass'], 'SuperX Protective Membrane', 'superx-protective-membrane', 'APX-TG-SUPERX', 'Flexible unbreakable membrane with full-screen coverage.', 'SuperX HD-ESD flexible protection for impact-resistant film programs.', "Flexible membrane\nMulti-model cutting", '2,000 pcs / model', '15–30 days', 'glass-superx.jpg', 0],
        [$ids['bluetooth-headphones'], 'B68 Wireless Earbuds', 'b68-wireless-earbuds', 'APX-BT-B68', 'Bluetooth 6.0 TWS with digital display case and multiple colorways.', 'Catalog model B68 from Wireless Earbuds Range I.', "Model B68\nBluetooth 6.0\nDisplay case", '1,000 pcs', 'Samples 3–7 days', 'audio-b68.jpg', 1],
        [$ids['bluetooth-headphones'], 'B67 True Wireless Earbuds', 'b67-true-wireless-earbuds', 'APX-BT-B67', 'Short-stem TWS with 400mAh case, display and cream, black and white programs.', 'Catalog model B67.', "Model B67\n400mAh case", '1,000 pcs', 'Samples 3–7 days', 'audio-b67.jpg', 1],
        [$ids['bluetooth-headphones'], 'B64 Wireless Earbuds', 'b64-wireless-earbuds', 'APX-BT-B64', 'Range I TWS model for volume channels with retail packaging.', 'Catalog model B64.', "Model B64\nBluetooth 6.0", '1,000 pcs', '15–30 days', 'audio-b64.jpg', 0],
        [$ids['bluetooth-headphones'], 'B70 Wireless Earbuds', 'b70-wireless-earbuds', 'APX-BT-B70', 'Range I companion model with retail packaging.', 'Catalog model B70.', "Model B70\nRetail packaging", '1,000 pcs', '15–30 days', 'audio-b70.jpg', 0],
        [$ids['bluetooth-headphones'], 'B66-B Ear-Hook Earbuds', 'b66b-ear-hook-earbuds', 'APX-BT-B66B', 'Sport ear-hook TWS with 400mAh case and gold, white and black finishes.', 'Catalog model B66-B from Range II.', "Model B66-B\nEar-hook fit", '1,000 pcs', 'Samples 3–7 days', 'audio-b66b.jpg', 1],
        [$ids['bluetooth-headphones'], 'B60 Lifestyle Earbuds', 'b60-lifestyle-earbuds', 'APX-BT-B60', 'Compact stem TWS with champagne, black and silver cases.', 'Catalog model B60 from Range III.', "Model B60\nLifestyle retail", '1,000 pcs', 'Samples 3–7 days', 'audio-b60.jpg', 1],
        [$ids['bluetooth-headphones'], 'B88 Compact Earbuds', 'b88-compact-earbuds', 'APX-BT-B88', 'Compact Range III model with retail-packaging views.', 'Catalog model B88.', "Model B88\nCompact TWS", '1,000 pcs', '15–30 days', 'audio-b88.jpg', 0],
        [$ids['power-banks'], 'PD + QC Power Bank Range', 'pd-qc-power-bank-range', 'APX-PB-PDQC', '5,000 to 20,000 mAh portable chargers in slim, magnetic and digital-display formats.', 'Reliable portable chargers with PD and QC fast charging.', "5,000–20,000 mAh\nPD + QC", '1,000 pcs', 'Samples 3–7 days', 'power-banks.jpg', 1],
        [$ids['chargers-cables'], '20–65W Chargers & Cables Set', '20-65w-chargers-cables-set', 'APX-CHG-SET', 'Wall chargers, car chargers and braided or PVC cables with global plug options.', 'A complete charging range from the APEX catalog.', "20–65W\nGlobal plugs", '2,000 pcs', 'Samples 3–7 days', 'chargers-cables.jpg', 1],
    ];

    $insert = $pdo->prepare('INSERT INTO products (category_id, name, slug, sku, short_description, description, specifications, moq, lead_time, image, is_featured, is_active) VALUES (?,?,?,?,?,?,?,?,?,?,?,1)');
    foreach ($products as $product) {
        $filename = $product[9];
        $from = $source . '/' . $filename;
        $to = dirname(__DIR__) . '/uploads/products/' . $filename;
        if (is_file($from) && !is_file($to)) {
            copy($from, $to);
        }
        $insert->execute($product);
    }
}

function categories(): array
{
    return db()->query('SELECT * FROM categories ORDER BY sort_order, name')->fetchAll();
}

function product_image_url(?string $image): string
{
    if (!$image) {
        return asset('img/logo.png');
    }
    if (str_starts_with($image, 'http')) {
        return $image;
    }
    return UPLOAD_URL . '/' . ltrim($image, '/');
}

function slugify(string $value): string
{
    $slug = strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/', '-', $value) ?? '', '-'));
    return $slug !== '' ? $slug : 'product-' . time();
}
