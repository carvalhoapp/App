<?php

declare(strict_types=1);

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}

function baseUrl(string $path = ''): string
{
    $config = require __DIR__ . '/../config.php';
    $base = rtrim($config['app']['base_url'] ?? '', '/');
    return $base . '/' . ltrim($path, '/');
}

function whatsappLink(string $phone, string $message): string
{
    $cleanPhone = preg_replace('/\D+/', '', $phone) ?? '';
    return 'https://wa.me/' . $cleanPhone . '?text=' . rawurlencode($message);
}

function ensureUploadPath(): string
{
    $path = __DIR__ . '/../uploads';
    if (!is_dir($path)) {
        mkdir($path, 0775, true);
    }
    return $path;
}

function saveBase64Image(string $base64): ?string
{
    if (!preg_match('/^data:image\/(png|jpeg|jpg);base64,/', $base64, $matches)) {
        return null;
    }

    $ext = $matches[1] === 'jpeg' ? 'jpg' : $matches[1];
    $data = substr($base64, strpos($base64, ',') + 1);
    $decoded = base64_decode($data, true);

    if ($decoded === false) {
        return null;
    }

    $filename = uniqid('foto_', true) . '.' . $ext;
    $target = ensureUploadPath() . '/' . $filename;

    if (file_put_contents($target, $decoded) === false) {
        return null;
    }

    return 'uploads/' . $filename;
}

function formatCurrency(float $value): string
{
    return 'R$ ' . number_format($value, 2, ',', '.');
}
