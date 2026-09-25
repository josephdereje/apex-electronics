<?php
declare(strict_types=1);

const APEX_NAME = 'APEX IMPORT AND EXPORT COMPANY LIMITED';
const APEX_WHATSAPP = 'https://wa.me/8613640666344';
const ADMIN_USER = 'admin';
const ASSET_BASE = '/assets';
const UPLOAD_URL = '/uploads/products';
const CATALOG_URL = '/assets/docs/APEX-Company-Catalog.pdf';

function apex_admin_password(): string
{
    return getenv('APEX_ADMIN_PASSWORD') ?: 'ApexAdmin2026!';
}

function h(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function asset(string $path): string
{
    return ASSET_BASE . '/' . ltrim($path, '/');
}

function current_year(): int
{
    return (int) date('Y');
}

function redirect(string $path): void
{
    header('Location: ' . $path);
    exit;
}
