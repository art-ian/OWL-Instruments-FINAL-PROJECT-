<?php
require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/includes/functions.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    try {

        $db = new PDO(
            "mysql:host=localhost;dbname=inventory_system;charset=utf8mb4",
            "root",
            ""
        );

        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $db->prepare(
            "SELECT * FROM admins WHERE username = ?"
        );

        $stmt->execute([$username]);

        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if (
            $admin &&
            password_verify($password, $admin['password'])
        ) {

            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $admin['username'];
            $_SESSION['admin_role'] = $admin['role'];

            header("Location: admin.php");
            exit;
        }

        $error = "Invalid username or password.";

    } catch(PDOException $e) {

        $error = "Database connection failed.";

    }
}

$pageTitle = 'Admin Login';
$activePage = '';

require __DIR__ . '/includes/header.php';
?>

<div class="auth-wrap">

    <div class="card auth-card">

        
<div class="auth-header">

    <h1>Admin Login</h1>

    <p>
        Sign in to access the Seller & Inventory Management System.
    </p>

</div>


        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST">

            <div class="form-grid">

                <div class="field full">

                    <label>Username</label>

                    <input
                        type="text"
                        name="username"
                        required>

                </div>

                <div class="field full">

                    <label>Password</label>

                    <input
                        type="password"
                        name="password"
                        required>

                </div>

            </div>

            <button
                type="submit"
                class="btn btn-primary btn-block">

                Login as Admin

            </button>

        </form>

    </div>

</div>

<?php require __DIR__ . '/includes/footer.php'; ?>