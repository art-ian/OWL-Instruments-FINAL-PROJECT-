<?php

require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/includes/functions.php';

if(!isLoggedIn()){
    redirectTo('login.php');
}

$pageTitle = "Payment";
$activePage = "";
require __DIR__ . '/includes/header.php';

$cart = $_SESSION['cart'] ?? [];

if(empty($cart)){
    redirectTo('cart.php');
}

$total = 0;

foreach($cart as $item){
    $total += $item['price'] * $item['quantity'];
}

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $paymentMethod = $_POST["payment_method"] ?? "";

    $db = new PDO(
        "mysql:host=localhost;dbname=inventory_system;charset=utf8mb4",
        "root",
        ""
    );

    foreach($cart as $item){

        $stmt = $db->prepare(
            "UPDATE products
             SET stock = stock - ?
             WHERE name = ?"
        );

        $stmt->execute([
            $item['quantity'],
            $item['name']
        ]);
    }

    $_SESSION["payment_method"] = $paymentMethod;

    $_SESSION["order_number"] = "OWL-" . rand(100000,999999);

    $_SESSION["cart"] = [];

    $success = true;

}
?>

<div class="payment-wrapper">

<?php if(isset($success)): ?>

<div class="payment-success">

<div class="success-icon">

✔

</div>

<<?php if($_SESSION["payment_method"] == "Cash on Delivery"): ?>

<h1>Order Confirmed!</h1>

<p>
Your order has been received and will be paid upon delivery.
</p>

<?php else: ?>

<h1>Payment Successful!</h1>

<p>
Your payment has been received and your order is now being processed.
</p>

<?php endif; ?>

<p>

Thank you,

<strong><?= htmlspecialchars($_SESSION['fullname']) ?></strong>

</p>

<p>

Your order has been received and is now being prepared.

</p>

<p>

Order Number

</p>

<h2>

<?= $_SESSION["order_number"] ?>

</h2>

<a
href="store.php"
class="btn btn-primary">

Continue Shopping

</a>

</div>

<?php else: ?>

<div class="payment-card">

<h1>

Payment

</h1>

<p class="payment-subtitle">

Choose your preferred payment method.

</p>

<div class="payment-total">

<h3>

Order Total

</h3>

<h2>

₱<?= number_format($total,2) ?>

</h2>

</div>

<form method="POST">

<div class="payment-methods">

<label class="method-card">

<input
type="radio"
name="payment_method"
value="Cash on Delivery"
required>

<div>

<h3>

Cash on Delivery

</h3>

<p>

Pay when your order arrives.

</p>

</div>

</label>

<label class="method-card">

<input
type="radio"
name="payment_method"
value="GCash">

<div>

<h3>

GCash

</h3>

<p>

Secure payment using GCash.

</p>

</div>

</label>

<label class="method-card">

<input
type="radio"
name="payment_method"
value="Credit Card">

<div>

<h3>

Credit / Debit Card

</h3>

<p>

Visa, Mastercard & more.

</p>

</div>

</label>

</div>

</label>

</div>

<!-- Cash on Delivery -->

<div
id="codSection"
class="payment-section">

<div class="section-header">

<h2>Cash on Delivery</h2>

</div>

<p>

Your order will be shipped immediately.

Payment will be collected upon delivery.

</p>

</div>

<!-- GCash -->

<div
id="gcashSection"
class="payment-section">

<div class="section-header">

<h2>📱 GCash</h2>

</div>

<div class="payment-form">

<div class="input-group">

<label>

GCash Number

</label>

<input
type="text"
name="gcash_number"
placeholder="09XXXXXXXXX">

</div>

<div class="input-group">

<label>

Reference Number

</label>

<input
type="text"
name="reference_number"
placeholder="Enter your payment reference">

</div>

</div>

</div>

<!-- Credit Card -->

<div
id="cardSection"
class="payment-section">

<div class="section-header">

<h2>Credit / Debit Card</h2>

</div>

<div class="payment-form">

<div class="input-group">

<label>

Cardholder Name

</label>

<input
type="text"
name="card_name"
placeholder="Juan Dela Cruz">

</div>

<div class="input-group">

<label>

Card Number

</label>

<input
type="text"
maxlength="19"
name="card_number"
placeholder="1234 5678 9012 3456">

</div>

<div class="card-grid">

<div class="input-group">

<label>

Expiry Date

</label>

<input
type="text"
name="expiry"
placeholder="MM/YY">

</div>

<div class="input-group">

<label>

CVV

</label>

<input
type="password"
maxlength="3"
name="cvv"
placeholder="123">

</div>

</div>

</div>

</div>

<button
type="submit"
class="btn btn-primary payment-button">

Confirm Payment

</button>

</form>

</div>

<?php endif; ?>

</div>

<script>

const methods=document.querySelectorAll('input[name="payment_method"]');

const cod=document.getElementById("codSection");
const gcash=document.getElementById("gcashSection");
const card=document.getElementById("cardSection");

cod.style.display="none";
gcash.style.display="none";
card.style.display="none";

methods.forEach(method=>{

method.addEventListener("change",()=>{

cod.style.display="none";
gcash.style.display="none";
card.style.display="none";

if(method.value==="Cash on Delivery"){

cod.style.display="block";

}

if(method.value==="GCash"){

gcash.style.display="block";

}

if(method.value==="Credit Card"){

card.style.display="block";

}

});

});

</script>

<?php require __DIR__ . '/includes/footer.php'; ?>