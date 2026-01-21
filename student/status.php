<?php
session_start();
include("../db/config.php");

if (!isset($_SESSION['student'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['student'];

/* Fetch latest order */
$order = $conn->query(
    "SELECT * FROM orders 
     WHERE student_username='$user' 
     ORDER BY created_at DESC 
     LIMIT 1"
)->fetch_assoc();

/* Calculate total amount from cart items */
$totalAmount = 0;
$items = json_decode($order['items'], true);
if (is_array($items)) {
    foreach ($items as $i) {
        $totalAmount += $i['price'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Order Status | Spicy Eats</title>
    <link rel="stylesheet" href="../assets/css/theme.css">
    <meta http-equiv="refresh" content="5">
    <style>
        .status-box{
            width:450px;
            margin:120px auto;
            padding:35px;
            text-align:center;
        }

        .pulse{
            animation:pulse 1.5s infinite;
        }

        @keyframes pulse{
            0%{transform:scale(1); box-shadow:0 0 0 rgba(255,0,0,0.6);}
            50%{transform:scale(1.02); box-shadow:0 0 25px rgba(255,0,0,0.6);}
            100%{transform:scale(1); box-shadow:0 0 0 rgba(255,0,0,0.6);}
        }

        .amount-box{
            margin-top:15px;
            padding:12px;
            border-radius:12px;
            background:rgba(0,0,0,0.5);
            font-size:18px;
            color:var(--gold-soft);
        }

        .instruction{
            margin-top:12px;
            font-size:14px;
            color:var(--gray);
        }
    </style>
</head>
<body>

<div class="glass status-box fade-in <?php if($order['status']=="Cancelled") echo 'pulse'; ?>">

    <h1>Order Status</h1>

    <?php if($order['status']=="Pending"): ?>
        <h2 class="status-pending">⏳ Waiting for Approval</h2>
        <p class="instruction">
            Your order has been sent to the restaurant.<br>
            Please wait for the owner’s permission before coming.
        </p>

    <?php elseif($order['status']=="Accepted"): ?>
        <h2 class="status-accepted">✅ Order Accepted</h2>

        <div class="amount-box">
            Amount to Pay: ₹<?php echo $totalAmount; ?>
        </div>

        <p class="instruction">
            Please reach <b>Gate 2, GLA University</b><br>
            within <b>10 minutes</b>.
        </p>

        <p class="instruction">
            Pay the above amount at delivery (Cash / UPI).
        </p>

    <?php elseif($order['status']=="Cancelled"): ?>
        <h2 class="status-cancelled">❌ Order Cancelled</h2>
        <p style="color:var(--danger); margin-top:10px;">
            <?php echo $order['cancel_reason'] != "" ? $order['cancel_reason'] : "No reason provided."; ?>
        </p>
    <?php endif; ?>

    <a href="menu.php">
        <button class="btn" style="width:100%; margin-top:20px;">
            ⬅ Back to Menu
        </button>
    </a>

    <p style="margin-top:15px; font-size:12px; color:var(--gray);">
        This page refreshes automatically every 5 seconds.
    </p>
</div>

<script>
<?php if($order['status']=="Cancelled"): ?>
    if (navigator.vibrate) {
        navigator.vibrate([400,200,400,200,800]);
    }

    let alarm = new Audio("https://www.soundjay.com/buttons/sounds/beep-07.mp3");
    alarm.play();
<?php endif; ?>
</script>

</body>
</html>
