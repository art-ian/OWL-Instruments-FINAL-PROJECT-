<?php
require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Store';
$activePage = 'store';
require __DIR__ . '/includes/header.php';

$host = "localhost";
$dbname = "inventory_system";
$username = "root";
$password = "";

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed.");
}

// Category Filter
$category = "";

if (isset($_GET['category'])) {
    $category = $_GET['category'];
}

if ($category == "" || $category == "All") {

    $stmt = $db->prepare("SELECT * FROM products WHERE status='active' ORDER BY id DESC");
    $stmt->execute();

} else {

    $stmt = $db->prepare("SELECT * FROM products WHERE status='active' AND category=? ORDER BY id DESC");
    $stmt->execute([$category]);

}

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<section class="store-page">



    <div class="category-buttons">

        <a href="store.php?category=All" class="btn">All</a>

        <a href="store.php?category=Guitar" class="btn">Guitar</a>

        <a href="store.php?category=Piano" class="btn">Piano</a>

        <a href="store.php?category=Keyboard" class="btn">Keyboard</a>

        <a href="store.php?category=Drums" class="btn">Drums</a>

        <a href="store.php?category=Violin" class="btn">Violin</a>

        <a href="store.php?category=Wind Instruments" class="btn">Wind Instruments</a>

    </div>



    <div class="products-grid">

<?php if(count($products) > 0): ?>

<?php foreach($products as $product): ?>

<div class="product-card">

    <div class="product-image">

        <?php if(!empty($product['image'])): ?>

            <img src="assets/img/products/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">

        <?php else: ?>

            <img src="assets/images/products/no-image.png" alt="No Image">

        <?php endif; ?>

    </div>

    <div class="product-details">

        <h3><?= htmlspecialchars($product['name']) ?></h3>

        <p class="category">
            <?= htmlspecialchars($product['category']) ?>
        </p>

        <p class="price">
            ₱<?= number_format($product['price'],2) ?>
        </p>

        <p class="stock">
            Stock: <?= $product['stock'] ?>
        </p>

        <?php if($product['stock'] > 0): ?>

        <form action="add_to_cart.php" method="POST">

            <input
                type="hidden"
                name="product_id"
                value="<?= $product['id'] ?>"
            >

            <input
                type="hidden"
                name="quantity"
                value="1"
            >

            <button type="submit" class="btn">
                Add to Cart
            </button>

        </form>

        <?php else: ?>

            <button class="btn" disabled>
                Out of Stock
            </button>

        <?php endif; ?>

    </div>

</div>

<?php endforeach; ?>

<?php else: ?>

<div class="no-products">

    <h3>No products found.</h3>

    <p>Please check another category.</p>

</div>

<?php endif; ?>

</div>

</section>

<?php
require __DIR__ . '/includes/footer.php';
?>