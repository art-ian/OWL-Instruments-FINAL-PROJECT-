<?php
require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/includes/functions.php';

// If already logged in, no need to register again
if (isLoggedIn()) {
    redirectTo('index.php');
}

$errors = [];
$success = false;

// Keep submitted values so the form can be re-filled on error
$old = [
    'full_name' => '',
    'email'     => '',
    'address'   => '',
    'contact_number' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $fullName       = trim($_POST['full_name'] ?? '');
    $email          = trim($_POST['email'] ?? '');
    $password       = $_POST['password'] ?? '';
    $confirmPass    = $_POST['confirm_password'] ?? '';
    $address        = trim($_POST['address'] ?? '');
    $contactNumber  = trim($_POST['contact_number'] ?? '');

    $old['full_name']      = $fullName;
    $old['email']          = $email;
    $old['address']        = $address;
    $old['contact_number'] = $contactNumber;

    // ---- Validation ----

    // Full name
    if ($fullName === '') {
        $errors['full_name'] = 'Complete name is required.';
    } elseif (!preg_match("/^[a-zA-Z\s.'-]{2,150}$/", $fullName)) {
        $errors['full_name'] = 'Name may only contain letters and spaces.';
    }

    // Email
    if ($email === '') {
        $errors['email'] = 'Email address is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email address.';
    }

    // Password
    if ($password === '') {
        $errors['password'] = 'Password is required.';
    } elseif (strlen($password) < 8) {
        $errors['password'] = 'Password must be at least 8 characters long.';
    } elseif (!preg_match('/[A-Za-z]/', $password) || !preg_match('/[0-9]/', $password)) {
        $errors['password'] = 'Password must contain at least one letter and one number.';
    }

    // Confirm password
    if ($confirmPass === '') {
        $errors['confirm_password'] = 'Please confirm your password.';
    } elseif ($password !== '' && $password !== $confirmPass) {
        $errors['confirm_password'] = 'Passwords do not match.';
    }

    // Address
    if ($address === '') {
        $errors['address'] = 'Complete address is required.';
    } elseif (strlen($address) < 5) {
        $errors['address'] = 'Please enter a complete address.';
    }

    // Contact number
    if ($contactNumber === '') {
        $errors['contact_number'] = 'Contact number is required.';
    } elseif (!preg_match('/^[0-9+\-\s]{7,20}$/', $contactNumber)) {
        $errors['contact_number'] = 'Please enter a valid contact number.';
    }

    // ---- NOTE FOR THE TEAM ----
    // This is currently a layout/validation-only version.
    // No database save and no real email is sent yet.
    // Once config/database.php + database/schema.sql are added back,
    // this is where we will:
    //   1. Check the DB for a duplicate email
    //   2. password_hash() the password
    //   3. INSERT the new user with a generated verify_token
    //   4. Send the confirmation email
    // ---------------------------

    if (empty($errors)) {

    $db = new PDO(
        "mysql:host=localhost;dbname=inventory_system;charset=utf8mb4",
        "root",
        ""
    );

    // check for duplicate email first
    $checkStmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    $checkStmt->execute([$email]);

    if ($checkStmt->fetch()) {

        $errors['email'] = 'An account with this email already exists.';

    } else {

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $verifyToken = bin2hex(random_bytes(32));

        $stmt = $db->prepare("
            INSERT INTO users
            (fullname, email, password, address, contact, role, verified, verify_token)
            VALUES (?, ?, ?, ?, ?, 'buyer', 0, ?)
        ");

        $stmt->execute([
            $fullName,
            $email,
            $hashedPassword,
            $address,
            $contactNumber,
            $verifyToken
        ]);

        $verifyLink = BASE_URL . "verify.php?token=" . $verifyToken;

        $subject = "Confirm your OWL Instruments account";
        $emailBody = "Hi $fullName,\n\n"
                  . "Thanks for registering at OWL Instruments. "
                  . "Please click the link below to verify your email address:\n\n"
                  . "$verifyLink\n\n"
                  . "If you did not create this account, you can ignore this email.";

        $headers = "From: no-reply@owlinstruments.local";

        @mail($email, $subject, $emailBody, $headers);

        $success = true;

        $old = [
            'full_name' => '',
            'email' => '',
            'address' => '',
            'contact_number' => ''
        ];
    }
}

}
$pageTitle = 'Register';
$activePage = 'register';
require __DIR__ . '/includes/header.php';
?>

<div class="auth-wrap">
  <div class="card auth-card">
    <div class="auth-header">
      <img src="<?= BASE_URL ?>assets/img/logo.png" alt="OWL logo" class="owl-badge">
      <h1>Create your OWL account</h1>
      <p>Register to shop for guitars, keyboards, drums, and more.</p>
    </div>

<?php if ($success): ?>
  <div class="alert alert-success">
    Account created successfully! A confirmation email has been sent to your address.
    Please verify your email before logging in.
  </div>
  <?php if (isset($verifyLink)): ?>
    <div class="alert alert-info" style="font-size:13px;">
      (Dev preview — local mail servers usually don't deliver:
      <a href="<?= htmlspecialchars($verifyLink) ?>">click here to verify</a>)
    </div>
  <?php endif; ?>
<?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>register.php" novalidate>
      <div class="form-grid">

        <div class="field full">
          <label for="full_name">Complete Name</label>
          <input
            type="text"
            id="full_name"
            name="full_name"
            value="<?= clean($old['full_name']) ?>"
            class="<?= isset($errors['full_name']) ? 'error' : '' ?>"
            placeholder="Enter your full name">
          <?php if (isset($errors['full_name'])): ?>
            <span class="error-msg"><?= clean($errors['full_name']) ?></span>
          <?php endif; ?>
        </div>

        <div class="field full">
          <label for="email">Email Address</label>
          <input
            type="email"
            id="email"
            name="email"
            value="<?= clean($old['email']) ?>"
            class="<?= isset($errors['email']) ? 'error' : '' ?>"
            placeholder="Enter your email address">
          <?php if (isset($errors['email'])): ?>
            <span class="error-msg"><?= clean($errors['email']) ?></span>
          <?php endif; ?>
        </div>

        <div class="field">
          <label for="password">Password</label>
          <input
            type="password"
            id="password"
            name="password"
            class="<?= isset($errors['password']) ? 'error' : '' ?>">
          <span class="hint">At least 8 characters, with a letter and a number.</span>
          <?php if (isset($errors['password'])): ?>
            <span class="error-msg"><?= clean($errors['password']) ?></span>
          <?php endif; ?>
        </div>

        <div class="field">
          <label for="confirm_password">Confirm Password</label>
          <input
            type="password"
            id="confirm_password"
            name="confirm_password"
            class="<?= isset($errors['confirm_password']) ? 'error' : '' ?>">
          <span class="hint" id="passwordMatchMsg"></span>
          <?php if (isset($errors['confirm_password'])): ?>
            <span class="error-msg"><?= clean($errors['confirm_password']) ?></span>
          <?php endif; ?>
        </div>

        <div class="field full">
          <label for="address">Complete Address</label>
          <textarea
            id="address"
            name="address"
            rows="2"
            class="<?= isset($errors['address']) ? 'error' : '' ?>"
            placeholder="House No., Street, Barangay, City, Province"
          ><?= clean($old['address']) ?></textarea>
          <?php if (isset($errors['address'])): ?>
            <span class="error-msg"><?= clean($errors['address']) ?></span>
          <?php endif; ?>
        </div>

        <div class="field full">
          <label for="contact_number">Contact Number</label>
          <input
            type="text"
            id="contact_number"
            name="contact_number"
            value="<?= clean($old['contact_number']) ?>"
            class="<?= isset($errors['contact_number']) ? 'error' : '' ?>"
            placeholder="09XXXXXXXXX">
          <?php if (isset($errors['contact_number'])): ?>
            <span class="error-msg"><?= clean($errors['contact_number']) ?></span>
          <?php endif; ?>
        </div>

      </div>

      <button type="submit" class="btn btn-primary btn-block">Create Account</button>
    </form>

    <p class="auth-footer-link">
      Already have an account? <a href="<?= BASE_URL ?>login.php">Log in</a>
    </p>
  </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
