<?php

require_once __DIR__ . '/config/session.php';

if (empty($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

$host = 'localhost';
$dbname = 'inventory_system';
$username = 'root';
$password = '';

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

$message = "";

// List of categories used across the store (must match store.php filter buttons)
$categoryOptions = ['Guitar', 'Piano', 'Keyboard', 'Drums', 'Violin', 'Wind Instruments'];

// form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // for adding admin users
    if (isset($_POST['action']) && $_POST['action'] === 'add_admin') {
        $username = trim($_POST['username']);
        $role = $_POST['role'];
        $adminPassword = $_POST['admin_password'] ?? '';

        if (!empty($username) && !empty($adminPassword)) {
            $hashedAdminPassword = password_hash($adminPassword, PASSWORD_DEFAULT);
            // avoids duplicates
            $stmt = $db->prepare("INSERT IGNORE INTO admins (username, role, password) VALUES (?, ?, ?)");
            $stmt->execute([$username, $role, $hashedAdminPassword]);
            $message = "Admin user processed successfully!";
        }
    }

    // for adding and editing products
    if (isset($_POST['action']) && $_POST['action'] === 'save_product') {
        $id = $_POST['product_id'] ?? '';
        $name = trim($_POST['name']);
        $category = $_POST['category'] ?? '';
        $price = floatval($_POST['price']);
        $stock = intval($_POST['stock']);
        $image = trim($_POST['image'] ?? '');
        $status = $_POST['status'] ?? 'active';

        if (!empty($name) && !empty($category)) {
            if (empty($id)) {
                // no id = new product
                $stmt = $db->prepare("INSERT INTO products (name, category, price, stock, image, status) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$name, $category, $price, $stock, $image, $status]);
                $message = "Product added successfully!";
            } else {
                // if there is an id = edit product
                $stmt = $db->prepare("UPDATE products SET name = ?, category = ?, price = ?, stock = ?, image = ?, status = ? WHERE id = ?");
                $stmt->execute([$name, $category, $price, $stock, $image, $status, $id]);
                $message = "Product updated successfully!";
            }
        } else {
            $message = "Product name and category are required.";
        }
    }

    // price/stock changes, for quick updates 
    if (isset($_POST['action']) && $_POST['action'] === 'quick_update') {
        $id = $_POST['product_id'];
        $price = floatval($_POST['price']);
        $stock = intval($_POST['stock']);
        
        $stmt = $db->prepare("UPDATE products SET price = ?, stock = ? WHERE id = ?");
        $stmt->execute([$price, $stock, $id]);
        $message = "Quick update completed!";
    }

    // for the toggles
    if (isset($_POST['action']) && $_POST['action'] === 'toggle_status') {
        $id = $_POST['product_id'];
        $current_status = $_POST['current_status'];
        $new_status = ($current_status === 'active') ? 'disabled' : 'active';
        
        $stmt = $db->prepare("UPDATE products SET status = ? WHERE id = ?");
        $stmt->execute([$new_status, $id]);
        $message = "Product status updated!";
    }

    // for deleting products
    if (isset($_POST['action']) && $_POST['action'] === 'delete_product') {
        $id = $_POST['product_id'];

        $stmt = $db->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $message = "Product deleted successfully!";
    }
}

// fetching the data
$admins = $db->query("SELECT * FROM admins ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
$products = $db->query("SELECT * FROM products ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

// checks if the product is being edited
$edit_product = null;
if (isset($_GET['edit'])) {
    $stmt = $db->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $edit_product = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller & Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <header>
        <h1>Seller Part: Admin & Inventory Management</h1>
		
<div style="margin:20px 0;">
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
        <?php if (!empty($message)): ?>
            <div style="background: #dbeafe; color: #1e40af; padding: 10px; border-radius: 4px; margin-top: 15px;">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>
    </header>

    <div class="grid">
        
        <div class="sidebar">
            
            <div class="card">
                <h2>Add Admin User</h2>
                <form action="admin.php" method="POST">
                    <input type="hidden" name="action" value="add_admin">
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" required placeholder="e.g., alex_admin">
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="admin_password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Role</label>
                        <select name="role" class="form-control">
                            <option value="Admin">Admin</option>
                            <option value="Inventory Manager">Inventory Manager</option>
                            <option value="Super Admin">Super Admin</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Admin</button>
                </form>
            </div>

            <div class="card">
                <h2><?= $edit_product ? 'Edit Product Details' : 'Add Product to Inventory' ?></h2>
                <form action="admin.php" method="POST">
                    <input type="hidden" name="action" value="save_product">
                    <input type="hidden" name="product_id" value="<?= $edit_product['id'] ?? '' ?>">
                    
                    <div class="form-group">
                        <label>Product Name</label>
                        <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($edit_product['name'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label>Category</label>
                        <select name="category" class="form-control" required>
                            <option value="">-- Select Category --</option>
                            <?php foreach ($categoryOptions as $cat): ?>
                                <option value="<?= htmlspecialchars($cat) ?>"
                                    <?= (isset($edit_product['category']) && $edit_product['category'] === $cat) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Price (₱)</label>
                        <input type="number" step="0.01" name="price" class="form-control" required value="<?= $edit_product['price'] ?? '0.00' ?>">
                    </div>
                    <div class="form-group">
                        <label>Stock Quantity</label>
                        <input type="number" name="stock" class="form-control" required value="<?= $edit_product['stock'] ?? '0' ?>">
                    </div>

                    <div class="form-group">
                        <label>Image Filename</label>
                        <input type="text" name="image" class="form-control" placeholder="e.g., violin.jpg" value="<?= htmlspecialchars($edit_product['image'] ?? '') ?>">
                        <small style="color:#6b7280;">
                            File must already exist inside <code>assets/img/products/</code>
                        </small>
                    </div>

                    <?php if ($edit_product): ?>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="active" <?= $edit_product['status'] == 'active' ? 'selected' : '' ?>>Active</option>
                            <option value="disabled" <?= $edit_product['status'] == 'disabled' ? 'selected' : '' ?>>Disabled</option>
                        </select>
                    </div>
                    <?php endif; ?>
                    
                    <button type="submit" class="btn btn-primary"><?= $edit_product ? 'Update Product' : 'Add Product' ?></button>
                    <?php if ($edit_product): ?>
                        <a href="admin.php" class="btn" style="background:#e5e7eb; color:#333; margin-left:10px;">Cancel</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <div class="main-content">
            
            <div class="card">
                <h2>Admin Users System</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($admins as $user): ?>
                        <tr>
                            <td><?= $user['id'] ?></td>
                            <td><strong><?= htmlspecialchars($user['username']) ?></strong></td>
                            <td><?= htmlspecialchars($user['role']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="card">
                <h2>Product & Inventory Management</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Quick Update (Price / Stock)</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $prod): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($prod['name']) ?></strong></td>
                            <td><?= htmlspecialchars($prod['category'] ?? '') ?></td>
                            <td>
                                <form action="admin.php" method="POST" class="inline-form">
                                    <input type="hidden" name="action" value="quick_update">
                                    <input type="hidden" name="product_id" value="<?= $prod['id'] ?>">
                                    ₱ <input type="number" step="0.01" name="price" value="<?= $prod['price'] ?>" style="width:70px; padding:4px;">
                                    | Stock: <input type="number" name="stock" value="<?= $prod['stock'] ?>" style="width:55px; padding:4px;">
                                    <button type="submit" class="btn btn-primary btn-sm">✔</button>
                                </form>
                            </td>
                            <td>
                                <span class="status-badge status-<?= $prod['status'] ?>">
                                    <?= ucfirst($prod['status']) ?>
                                </span>
                            </td>
                            <td>
                                <a href="admin.php?edit=<?= $prod['id'] ?>" class="btn btn-sm" style="background:#f3f4f6; color:#1f2937;">Edit</a>
                                
                                <form action="admin.php" method="POST" class="inline-form">
                                    <input type="hidden" name="action" value="toggle_status">
                                    <input type="hidden" name="product_id" value="<?= $prod['id'] ?>">
                                    <input type="hidden" name="current_status" value="<?= $prod['status'] ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <?= $prod['status'] === 'active' ? 'Disable' : 'Enable' ?>
                                    </button>
                                </form>

                                <form action="admin.php" method="POST" class="inline-form" onsubmit="return confirm('Are you sure you want to permanently delete this product?');">
                                    <input type="hidden" name="action" value="delete_product">
                                    <input type="hidden" name="product_id" value="<?= $prod['id'] ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

</body>
</html>