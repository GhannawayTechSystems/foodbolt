<?php

declare(strict_types=1);

/**
 * Minimal dependency-free utilities.
 */

function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function redirect(string $path): never
{
    header('Location: ' . $path);
    exit;
}

function csrf_token(): string
{
    if (!isset($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['csrf'];
}

function csrf_ensure(): void
{
    csrf_token();
}

function csrf_field(): string
{
    return '<input type="hidden" name="_csrf" value="' . e(csrf_token()) . '">';
}

function csrf_verify(): void
{
    $session = $_SESSION['csrf'] ?? '';
    $token = $_POST['_csrf'] ?? '';
    if ($session === '' || !is_string($token) || !hash_equals($session, $token)) {
        http_response_code(419);
        exit('Invalid CSRF token. Please go back and try again.');
    }
}

function flash(string $message, string $type = 'info'): void
{
    $_SESSION['flash'][] = ['message' => $message, 'type' => $type];
}

function flash_get(): array
{
    $flash = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $flash;
}

function money(float $amount, string $symbol = '$'): string
{
    return $symbol . number_format($amount, 2);
}

function url(string $path = ''): string
{
    // Works whether the app is at the domain root or in a subfolder.
    $base = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/\\');
    return $base . '/' . ltrim($path, '/');
}
