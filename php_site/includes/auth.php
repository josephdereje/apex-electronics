<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

function start_session(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
}

function require_admin(): void
{
    start_session();
    if (empty($_SESSION['apex_admin'])) {
        redirect('/admin/login.php');
    }
}

function attempt_login(string $username, string $password): bool
{
    start_session();
    if (hash_equals(ADMIN_USER, $username) && hash_equals(apex_admin_password(), $password)) {
        $_SESSION['apex_admin'] = true;
        return true;
    }
    return false;
}

function admin_logout(): void
{
    start_session();
    $_SESSION = [];
    session_destroy();
    redirect('/admin/login.php');
}
