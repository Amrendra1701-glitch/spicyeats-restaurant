<?php
session_start();
if (!isset($_SESSION['student'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>Menu | Spicy Eats</title>
<link rel="stylesheet" href="../assets/css/theme.css">
<link rel="stylesheet" href="../assets/css/menu.css">
</head>
<body>

<h1 class="menu-title">SPICY EATS RESTAURANT</h1>
<h2 style="text-align:center;color:var(--gray);">Welcome, <?php echo $_SESSION['student']; ?></h2>

<div class="menu-wrapper">
    <div class="menu-grid">

    <?php
    $menu = [
        ["Chicken Biryani",120,"Rich aromatic basmati with tender chicken","https://www.whiskaffair.com/wp-content/uploads/2020/07/Chicken-Biryani-2-3.jpg"],
        ["Veg Biryani",90,"Spiced vegetables with fragrant rice","https://www.whiskaffair.com/wp-content/uploads/2020/08/Veg-Biryani-2-3.jpg"],
        ["Paneer Roll",60,"Grilled paneer with sauces","https://spicecravings.com/wp-content/uploads/2020/12/Paneer-kathi-Roll-Featured-1.jpg"],
        ["Chicken Roll",80,"Juicy chicken wrapped in flatbread","https://uploads-ssl.webflow.com/5c481361c604e53624138c2f/60f2ea67b471327a1d82959b_chicken%20roll_1500%20x%201200.jpg"],
        ["Samosa",20,"Crispy golden snack","https://wallpaperaccess.com/full/2069188.jpg"],
        ["Cold Drink",30,"Chilled soft drink","https://www.bing.com/th/id/OIP.0UkZ0a5M1CHhVvV6HsPxCwHaE_?w=296&h=211&c=8&rs=1&qlt=90&o=6&dpr=1.3&pid=3.1&rm=2"]
    ];

    foreach($menu as $item){
        echo '
        <div class="food-card" style="background-image:url('.$item[3].');">
            <div class="food-price">₹'.$item[1].'</div>
            <div class="food-overlay">
                <div class="food-name">'.$item[0].'</div>
                <div class="food-desc">'.$item[2].'</div>
                <button class="btn food-btn" onclick="addToCart(\''.$item[0].'\','.$item[1].')">
                    Add to Cart
                </button>
            </div>
        </div>
        ';
    }
    ?>

    </div>
</div>

<div class="cart-box">
    <h3>🛒 Your Cart</h3>
    <ul id="cartList" style="list-style:none;padding:0;font-size:13px;"></ul>
    <p id="totalPrice" style="margin-top:10px;font-weight:600;"></p>
    <a href="checkout.php">
        <button class="btn" style="width:100%;margin-top:10px;">Checkout</button>
    </a>
</div>

<script>
let cart = JSON.parse(localStorage.getItem("cart")) || [];

function addToCart(name, price){
    cart.push({name:name, price:price});
    localStorage.setItem("cart", JSON.stringify(cart));
    renderCart();
}

function renderCart(){
    let list = document.getElementById("cartList");
    let total = 0;
    list.innerHTML = "";
    cart.forEach((item,index)=>{
        total += item.price;
        let li = document.createElement("li");
        li.innerHTML = item.name+" - ₹"+item.price+
        " <span style='color:red;cursor:pointer;' onclick='removeItem("+index+")'>✖</span>";
        list.appendChild(li);
    });
    document.getElementById("totalPrice").innerText = "Total: ₹" + total;
}

function removeItem(index){
    cart.splice(index,1);
    localStorage.setItem("cart", JSON.stringify(cart));
    renderCart();
}

renderCart();
</script>

</body>
</html>
