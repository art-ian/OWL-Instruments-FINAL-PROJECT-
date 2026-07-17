<?php
require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'About';
$activePage = 'about';
require __DIR__ . '/includes/header.php';
?>


<h1>About Owl Instruments</h1>

<div class="card">
    <h2>Company Information</h2>

    <p>
        Owl Instruments is an online musical instrument store created as a final project.
        The website allows customers to browse musical instruments, add products to their cart,
        and proceed through checkout and payment.
    </p>

    <h2>Group Members</h2>

    <ul>
        <li>(KHALLID BARRI)          Member 1 - Login and Registration Module.</li>
        <li>(SHAYNE AGATHA JOVENIR)  Member 2 - Admin and Inventory Management.</li>
        <li>(CHRISTINE ANDREA BOJA)  Member 3 - Store, Cart, Checkout and Payment System.</li>
        <li>(ARTAINIAN C. ABULENCIA) Member 4 – Reports Module, About Page Development, System Integration, Code Optimization, and System Testing.</li>
    </ul>
</div>


<?php require __DIR__ . '/includes/footer.php'; ?>
