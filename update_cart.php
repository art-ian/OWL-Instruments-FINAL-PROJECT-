<?php

require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/includes/functions.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $product_id = $_POST["product_id"] ?? 0;
    $action = $_POST["action"] ?? "";

    if(isset($_SESSION["cart"])){

        foreach($_SESSION["cart"] as $key => &$item){

            if($item["id"] == $product_id){

               $host = "localhost";
$dbname = "inventory_system";
$username = "root";
$password = "";

$db = new PDO(
    "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
    $username,
    $password
);

$stmt = $db->prepare("SELECT stock FROM products WHERE id=?");
$stmt->execute([$product_id]);

$product = $stmt->fetch(PDO::FETCH_ASSOC);

if($action == "plus"){

 if($item["quantity"] < $product["stock"]){

    $item["quantity"]++;

    header("Location: cart.php");
    exit;

}else{

    echo "
    <script>
        alert('Out of stock! You cannot add more of this item.');
        window.location='cart.php';
    </script>
    ";
    exit;

}

}

                elseif($action == "minus"){

                    $item["quantity"]--;

                    if($item["quantity"] <= 0){

                        unset($_SESSION["cart"][$key]);

                    }

                }

                break;

            }

        }

        // Re-index the array after removing an item
        $_SESSION["cart"] = array_values($_SESSION["cart"]);

    }

}

header("Location: cart.php");
exit;