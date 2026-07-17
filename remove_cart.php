<?php

require_once __DIR__ . '/config/session.php';

$product_id = $_POST["product_id"] ?? 0;

if(isset($_SESSION["cart"])){

    foreach($_SESSION["cart"] as $key => $item){

        if($item["id"] == $product_id){

            unset($_SESSION["cart"][$key]);

            break;

        }

    }

    $_SESSION["cart"] = array_values($_SESSION["cart"]);

}

header("Location: cart.php");
exit;