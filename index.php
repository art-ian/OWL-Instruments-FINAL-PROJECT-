<?php
require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Home';
$activePage = 'home';
require __DIR__ . '/includes/header.php';
?>

<section class="hero">
  <h1>Find Your Sound at OWL</h1>
  <p>Guitars, keyboards, drums, and more &mdash; hand-picked instruments for
     beginners and pros alike. Browse the store and add your favorites to
     the cart.</p>
  <a href="<?= BASE_URL ?>store.php" class="btn btn-primary">Browse the Store</a>
</section>

<div class="card" style="margin-top:40px;">
    <h2>Why Choose OWL Musical Instruments?</h2>

<p>
    OWL Musical Instruments offers quality guitars, keyboards,
    pianos, drums, violins, and wind instruments for beginners
    and experienced musicians alike.
</p>


<ul>
    <li>High-quality musical instruments</li>
    <li>Affordable prices</li>
    <li>Secure online ordering</li>
    <li>Wide range of categories</li>
</ul>

</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
