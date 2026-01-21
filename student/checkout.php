<?php
session_start();
include("../db/config.php");

if (!isset($_SESSION['student'])) {
    header("Location: login.php");
    exit();
}

/* Profanity filter */
$badWords = ["cum","fuck","choot","penis","lauda","lund","dick","pussy","sex","ass","boobs","chut","bhosdi"];

function filterBadWords($text, $badWords){
    foreach($badWords as $word){
        $pattern = "/\b" . preg_quote($word, "/") . "\b/i";
        $text = preg_replace($pattern, "***", $text);
    }
    return $text;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name  = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $customization = filterBadWords($_POST['customization'], $badWords);
    $items = $_POST['items'];

    if ($name != "" && $phone != "" && $items != "") {
        $stmt = $conn->prepare(
            "INSERT INTO orders (student_username, name, phone, items, customization, status) 
             VALUES (?, ?, ?, ?, ?, 'Pending')"
        );
        $stmt->bind_param("sssss", $_SESSION['student'], $name, $phone, $items, $customization);
        $stmt->execute();

        echo "<script>
            localStorage.removeItem('cart');
            window.location = 'status.php';
        </script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Checkout | Spicy Eats</title>
    <link rel="stylesheet" href="../assets/css/theme.css">
</head>
<body>

<div class="glass form-box fade-in" style="width:400px;">
    <h1>Checkout</h1>
    <h2>Confirm Your Order</h2>

    <form method="POST">
        <input type="text" name="name" placeholder="Your Full Name" required>
        <input type="text" name="phone" placeholder="Phone Number" required>

        <textarea name="customization" rows="4" 
            placeholder="Extra chutney, less spicy, more spicy, etc..."></textarea>

        <input type="hidden" name="items" id="items">

        <button class="btn" style="width:100%; margin-top:15px;">Place Order</button>
    </form>
</div>

<script>
let cart = JSON.parse(localStorage.getItem("cart")) || [];

if(cart.length === 0){
    alert("Your cart is empty!");
    window.location = "menu.php";
}

document.getElementById("items").value = JSON.stringify(cart);
</script>

</body>
</html>
