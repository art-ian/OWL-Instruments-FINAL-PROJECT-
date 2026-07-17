<?php
require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/includes/functions.php';

// Redirect if user is not logged in
if (!isLoggedIn()) {
    redirectTo('login.php');
}

$pageTitle = 'Checkout';
$activePage = '';
require __DIR__ . '/includes/header.php';
?>

<h1>Checkout</h1>

<div class="card">

    <h2>Customer Information</h2>

    <table style="width:100%; margin-bottom:25px;">
        <tr>
            <td><strong>Name:</strong></td>
            <td><?= $_SESSION['fullname'] ?? 'Customer'; ?></td>
        </tr>

        <tr>
            <td><strong>Email:</strong></td>
            <td><?= $_SESSION['email'] ?? ''; ?></td>
        </tr>

        <tr>
            <td><strong>Address:</strong></td>
            <td><?= $_SESSION['address'] ?? ''; ?></td>
        </tr>

        <tr>
            <td><strong>Contact Number:</strong></td>
            <td><?= $_SESSION['contact'] ?? ''; ?></td>
        </tr>
    </table>

    <hr>

    <h2>Order Summary</h2>

    <table style="width:100%; border-collapse:collapse;">
        <thead>
            <tr>
                <th align="left">Product</th>
                <th align="center">Quantity</th>
                <th align="right">Price</th>
                <th align="right">Subtotal</th>
            </tr>
        </thead>

        <tbody>

        <?php
        $cart = $_SESSION['cart'] ?? [];

        $grandTotal = 0;

        foreach($cart as $item){

            $subtotal = $item['price'] * $item['quantity'];
            $grandTotal += $subtotal;
        ?>

        <tr>
            <td><?= $item['name']; ?></td>
            <td align="center"><?= $item['quantity']; ?></td>
            <td align="right">₱<?= number_format($item['price'],2); ?></td>
            <td align="right">₱<?= number_format($subtotal,2); ?></td>
        </tr>

        <?php } ?>

        </tbody>

        <tfoot>

        <tr>
            <th colspan="3" align="right">
                Grand Total
            </th>

            <th align="right">
                ₱<?= number_format($grandTotal,2); ?>
            </th>

        </tr>

        </tfoot>

    </table>

    <br>

    <form action="payment.php" method="post">

    <button class="btn btn-primary">
        Proceed to Payment
    </button>

</form>

<br>

<a href="cart.php" class="btn">
    Back to Cart
</a>

</div>

<?php require __DIR__ . '/includes/footer.php'; ?>