<?php
$activePage = $activePage ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= isset($pageTitle) ? clean($pageTitle) . ' - ' . SITE_NAME : SITE_NAME ?></title>
<link rel="icon" href="<?= BASE_URL ?>assets/img/logo.png" type="image/png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/cart.css">
<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/store.css">
</head>
<body>
<header class="site-header">
  <div class="container navbar">
    <a href="<?= BASE_URL ?>index.php" class="brand">
      <img src="<?= BASE_URL ?>assets/img/logo.png" alt="OWL logo">
      <div class="brand-text">
        <span class="group-name">OWL</span>
        <span class="site-name">Musical Instruments</span>
      </div>
    </a>
    <button class="mobile-toggle" id="navToggle" aria-label="Toggle navigation">&#9776;</button>
    <nav class="nav-links" id="navLinks">
      <a href="<?= BASE_URL ?>index.php" class="<?= $activePage === 'home' ? 'active' : '' ?>">Home</a>
      <a href="<?= BASE_URL ?>store.php" class="<?= $activePage === 'store' ? 'active' : '' ?>">Store</a>
      <a href="<?= BASE_URL ?>cart.php" class="<?= $activePage === 'cart' ? 'active' : '' ?>">Cart</a>
      <a href="<?= BASE_URL ?>about.php" class="<?= $activePage === 'about' ? 'active' : '' ?>">About</a>
      <?php if (isLoggedIn()): ?>
        <?php if (currentRole() === 'admin'): ?>
          <a href="<?= BASE_URL ?>admin/dashboard.php" class="<?= $activePage === 'admin' ? 'active' : '' ?>">Admin</a>
        <?php endif; ?>
        <a href="<?= BASE_URL ?>logout.php" class="btn-outline">Logout</a>
      <?php else: ?>
        <a href="<?= BASE_URL ?>login.php" class="<?= $activePage === 'login' ? 'active' : '' ?>">Login</a>
        <a href="<?= BASE_URL ?>register.php" class="btn-outline <?= $activePage === 'register' ? 'active' : '' ?>">Register</a>
      <?php endif; ?>
    </nav>
  </div>
</header>
<main>
  <div class="container">
