<?php
require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/includes/functions.php';

$token = $_GET['token'] ?? '';
$verified = false;
$errorMsg = '';

if ($token === '') {
    $errorMsg = "Invalid verification link.";
} else {

    $db = new PDO(
        "mysql:host=localhost;dbname=inventory_system;charset=utf8mb4",
        "root",
        ""
    );

    $stmt = $db->prepare("SELECT id, verified FROM users WHERE verify_token = ?");
    $stmt->execute([$token]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        $errorMsg = "This verification link is invalid or has already been used.";
    } elseif ($user['verified'] == 1) {
        $verified = true;
        $errorMsg = "This account is already verified. You can log in.";
    } else {
        $update = $db->prepare("UPDATE users SET verified = 1, verify_token = NULL WHERE id = ?");
        $update->execute([$user['id']]);
        $verified = true;
    }
}

$pageTitle = 'Verify Account';
$activePage = '';
require __DIR__ . '/includes/header.php';
?>

<div class="auth-wrap">
  <div class="card auth-card">
    <div class="auth-header">
      <h1><?= $verified ? 'Email Verified!' : 'Verification Failed' ?></h1>
    </div>

    <?php if ($verified): ?>
      <div class="alert alert-success">
        Your account is now active. You may log in.
      </div>
      <a href="login.php" class="btn btn-primary btn-block">Go to Login</a>
    <?php else: ?>
      <div class="alert alert-danger">
        <?= htmlspecialchars($errorMsg) ?>
      </div>
      <a href="register.php" class="btn btn-block">Back to Register</a>
    <?php endif; ?>
  </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>