<?php
require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/includes/functions.php';
if (empty($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}
$pageTitle = 'Reports';
$activePage = 'reports';
require __DIR__ . '/includes/header.php';
$db = new PDO(
    "mysql:host=localhost;dbname=inventory_system;charset=utf8mb4",
    "root",
    ""
);
?>
<h1>System Reports</h1>
<div style="margin-bottom:20px;">
    <a href="admin.php" class="btn btn-primary">
        Dashboard
    </a>
    <a href="reports.php" class="btn btn-primary">
        Reports
    </a>
    <a href="admin_logout.php" class="btn btn-danger">
        Logout
    </a>
</div>
<div class="card">
    <h2>Inventory Remaining Report</h2>
    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Product Name</th>
            <th>Category</th>
            <th>Price</th>
            <th>Remaining Stock</th>
        </tr>
        <?php
        $products = $db->query(
            "SELECT * FROM products ORDER BY id ASC"
        )->fetchAll(PDO::FETCH_ASSOC);
        foreach ($products as $product):
        ?>
        <tr>
            <td><?= $product['id'] ?></td>
            <td><?= htmlspecialchars($product['name']) ?></td>
            <td><?= htmlspecialchars($product['category']) ?></td>
            <td>₱<?= number_format($product['price'], 2) ?></td>
            <td><?= $product['stock'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
<br>
<div class="card">
    <h2>Audit Log Report</h2>
    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>User</th>
            <th>Activity</th>
            <th>Date</th>
        </tr>
        <?php
        $auditLogs = $db->query(
            "SELECT * FROM audit_logs ORDER BY created_at DESC"
        )->fetchAll(PDO::FETCH_ASSOC);
        foreach ($auditLogs as $log):
        ?>
        <tr>
            <td><?= htmlspecialchars($log['username']) ?></td>
            <td><?= htmlspecialchars($log['activity']) ?></td>
            <td><?= htmlspecialchars($log['created_at']) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>