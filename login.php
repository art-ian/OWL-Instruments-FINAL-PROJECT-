<?php
require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/includes/functions.php';

if(isLoggedIn()){
    redirectTo('index.php');
}

$errors = [];
$old = [
    'email' => ''
];

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $old['email'] = $email;

    if($email == ''){
        $errors['email'] = "Email is required.";
    }

    if($password == ''){
        $errors['password'] = "Password is required.";
    }

    if(empty($errors)){

        $host = "localhost";
        $dbname = "inventory_system";
        $username = "root";
        $passwordDB = "";

        try{

            $db = new PDO(
                "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
                $username,
                $passwordDB
            );

            $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

            $stmt = $db->prepare("SELECT * FROM users WHERE email=?");
            $stmt->execute([$email]);

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if($user && password_verify($password,$user['password'])){

                if((int)$user['verified'] !== 1){

                    $errors['login'] = "Please verify your email before logging in. Check your inbox for the confirmation link.";

                } else {

                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['fullname'] = $user['fullname'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['address'] = $user['address'];
                    $_SESSION['contact'] = $user['contact'];
                    $_SESSION['role'] = $user['role'];

                    $logStmt = $db->prepare(
                        "INSERT INTO audit_logs (username, activity)
                        VALUES (?, ?)"
                    );

                    $logStmt->execute([
                        $user['fullname'],
                        'Logged In'
                    ]);

                    redirectTo('index.php');

                }

            }else{

                $errors['login'] = "Invalid email or password.";

            }

        }catch(PDOException $e){

            $errors['login'] = "Database connection failed.";

        }

    }

}

$pageTitle = 'Login';
$activePage = 'login';
require __DIR__ . '/includes/header.php';
?>
<div class="auth-wrap">

<div class="card auth-card">

<div class="auth-header">

<img
src="<?= BASE_URL ?>assets/img/logo.png"
alt="OWL logo"
class="owl-badge">

<h1>Welcome Back!</h1>

<p>Log in to continue shopping.</p>

</div>

<?php if(isset($errors['login'])): ?>

<div class="alert alert-danger">

<?= clean($errors['login']) ?>

</div>

<?php endif; ?>

<form method="POST" action="<?= BASE_URL ?>login.php">

<div class="form-grid">

<div class="field full">

<label for="email">

Email Address

</label>

<input
type="email"
id="email"
name="email"
value="<?= clean($old['email']) ?>"
class="<?= isset($errors['email']) ? 'error' : '' ?>"
placeholder="you@example.com">

<?php if(isset($errors['email'])): ?>

<span class="error-msg">

<?= clean($errors['email']) ?>

</span>

<?php endif; ?>

</div>

<div class="field full">

<label for="password">

Password

</label>

<input
type="password"
id="password"
name="password"
class="<?= isset($errors['password']) ? 'error' : '' ?>">

<?php if(isset($errors['password'])): ?>

<span class="error-msg">

<?= clean($errors['password']) ?>

</span>

<?php endif; ?>

</div>

</div>

<button
type="submit"
class="btn btn-primary btn-block">
    Log In
</button>

</form>

<p class="auth-footer-link">
    Don't have an account?
    <a href="register.php">Create one</a>
</p>

<hr style="margin:20px 0;">

<p class="auth-footer-link">
    Are you an Administrator?
   <a href="admin_login.php">Admin Login</a>
</p>

</div>

<?php require __DIR__ . '/includes/footer.php'; ?>