<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/constants.php';

function isLoggedIn(): bool {
    return isset($_SESSION['user_id']);
}

function currentRole(): ?string {
    return $_SESSION['role'] ?? null;
}