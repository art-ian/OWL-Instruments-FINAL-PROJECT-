<?php
require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/includes/functions.php';

$host = "localhost";
$dbname = "inventory_system";
$username = "root";
$password = "";

try{
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4",$username,$password);
    $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
    die("Connection failed.");
}

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $product_id = $_POST["product_id"];

    $stmt = $db->prepare("SELECT * FROM products WHERE id=?");
    $stmt->execute([$product_id]);

    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if($product){

        if(!isset($_SESSION["cart"])){
            $_SESSION["cart"] = [];
        }

        $found = false;

        foreach($_SESSION["cart"] as &$item){

            if($item["id"] == $product["id"]){

                $item["quantity"]++;

                $found = true;

                break;

            }

        }

        if(!$found){

            $_SESSION["cart"][] = [

                "id"=>$product["id"],
                "name"=>$product["name"],
                "price"=>$product["price"],
                "quantity"=>1

            ];

        }

    }

}


header("Location: cart.php");
exit;