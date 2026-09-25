<?php
declare(strict_types=1);

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/');
$root = __DIR__;
$projectRoot = dirname(__DIR__);

if (str_starts_with($uri, '/assets/')) {
    $file = $projectRoot . $uri;
    if (is_file($file)) {
        return serve_file($file);
    }
}
if (str_starts_with($uri, '/uploads/')) {
    $file = $root . $uri;
    if (is_file($file)) {
        return serve_file($file);
    }
}

$path = $root . $uri;
if ($uri !== '/' && is_file($path)) {
    return false;
}
if ($uri !== '/' && is_dir($path)) {
    $index = rtrim($path, '/') . '/index.php';
    if (is_file($index)) {
        require $index;
        return true;
    }
}

if ($uri === '/') {
    require $root . '/index.php';
    return true;
}

http_response_code(404);
echo 'Not found';
return true;

function serve_file(string $file): bool
{
    $mime = [
        'css' => 'text/css',
        'js' => 'text/javascript',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'webp' => 'image/webp',
        'svg' => 'image/svg+xml',
        'woff2' => 'font/woff2',
        'pdf' => 'application/pdf',
    ];
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    header('Content-Type: ' . ($mime[$ext] ?? 'application/octet-stream'));
    readfile($file);
    return true;
}
