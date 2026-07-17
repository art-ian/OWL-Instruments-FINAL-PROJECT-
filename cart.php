<?php
require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/includes/functions.php';
if (!isLoggedIn()) {
    redirectTo('login.php');
}
$pageTitle = 'Cart';
$activePage = 'cart';
require __DIR__ . '/includes/header.php';
?>
<h1>Your Shopping Cart</h1>
<div class="card">
<?php
$cart = $_SESSION['cart'] ?? [];
if (count($cart) > 0) {
    $total = 0;
    foreach ($cart as $item) {
        $subtotal = $item['price'] * $item['quantity'];
        $total += $subtotal;
?>
    <div class="cart-item">
        <div class="cart-left">
            <h3><?= htmlspecialchars($item['name']) ?></h3>
            <p class="cart-price">
                ₱<?= number_format($item['price'], 2) ?>
            </p>
        </div>
        <div class="cart-middle">
            <form action="update_cart.php" method="POST">
                <input
                    type="hidden"
                    name="product_id"
                    value="<?= $item['id'] ?>">
                <input
                    type="hidden"
                    name="action"
                    value="minus">
                <button type="submit" class="qty-btn">
                    −
                </button>
            </form>
            <span class="qty-number">
                <?= $item['quantity'] ?>
            </span>
            <form action="update_cart.php" method="POST">
                <input
                    type="hidden"
                    name="product_id"
                    value="<?= $item['id'] ?>">
                <input
                    type="hidden"
                    name="action"
                    value="plus">
                <button type="submit" class="qty-btn">
                    +
                </button>
            </form>
        </div>
        <div class="cart-right">
            <p class="subtotal">
                Subtotal
                <br>
                <strong>
                    ₱<?= number_format($subtotal, 2) ?>
                </strong>
            </p>
            <form action="remove_cart.php" method="POST">
                <input
                    type="hidden"
                    name="product_id"
                    value="<?= $item['id'] ?>">
                <button type="submit" class="remove-btn">
                    Remove
                </button>
            </form>
        </div>
    </div>
<?php
    }
?>
    <div class="cart-total">
        <h2>Grand Total</h2>
        <h2>
            ₱<?= number_format($total, 2) ?>
        </h2>
    </div>
    <div class="checkout-area">
        <a href="store.php" class="btn">
            Continue Shopping
        </a>
        <a href="checkout.php" class="btn btn-primary">
            Proceed to Checkout
        </a>
    </div>
<?php
} else {
?>
    <p>Your cart is empty.</p>
    <a href="store.php" class="btn btn-primary">
        Continue Shopping
    </a>
<?php
}
?>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>