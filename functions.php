<?php
function clean(string $value): string {
    return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
}

function redirectTo(string $path): void {
    header('Location: ' . BASE_URL . $path);
    exit;
}

function generateToken(): string {
    return bin2hex(random_bytes(32));
}