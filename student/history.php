<?php
session_start();
include("../db/config.php");

if (!isset($_SESSION['student'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['student'];

/* Fetch all orders of this student (recent → oldest) */
$orders = $conn->query(
    "SELECT * FROM orders 
     WHERE student_username='$user' 
     ORDER BY created_at DESC"
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>My Orders | Spicy Eats</title>
    <link rel="stylesheet" href="../assets/css/theme.css">
    <style>
        .history-container{
            padding:40px;
        }
        table{
            width:100%;
            border-collapse:collapse;
            margin-top:20px;
        }
        th, td{
            padding:12px;
            border-bottom:1px solid rgba(255,255,255,0.1);
            font-size:13px;
        }
        th{
            color:var(--gold);
            font-weight:600;
        }
        tr:hover{
            background:rgba(255,255,255,0.05);
        }
        .status-pending{ color:#ffaa00; }
        .status-accepted{ color:var(--success); }
        .status-cancelled{ color:var(--danger); }
        .order-id{
            color:var(--gold-soft);
            font-weight:600;
        }
    </style>
</head>
<body>

<h1>My Orders</h1>
<h2>Your Order History (Recent → Oldest)</h2>

<div class="history-container glass fade-in">
    <table>
        <tr>
            <th>Order ID</th>
            <th>Items</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Date & Time</th>
        </tr>

        <?php if($orders->num_rows == 0): ?>
        <tr>
            <td colspan="5" style="text-align:center;color:var(--gray);">
                You have not placed any orders yet.
            </td>
        </tr>
        <?php endif; ?>

        <?php while($o = $orders->fetch_assoc()):
            $amount = 0;
            $items = json_decode($o['items'], true);
            $itemNames = [];

            if(is_array($items)){
                foreach($items as $i){
                    $amount += $i['price'];
                    $itemNames[] = $i['name'];
                }
            }
        ?>
        <tr>
            <td class="order-id">#<?php echo $o['id']; ?></td>
            <td><?php echo implode(", ", $itemNames); ?></td>
            <td>₹<?php echo $amount; ?></td>
            <td>
                <?php
                    if($o['status']=="Pending") echo "<span class='status-pending'>Pending</span>";
                    elseif($o['status']=="Accepted") echo "<span class='status-accepted'>Accepted</span>";
                    else echo "<span class='status-cancelled'>Cancelled</span>";
                ?>
            </td>
            <td><?php echo $o['created_at']; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <a href="menu.php">
        <button class="btn" style="margin-top:20px;">
            ⬅ Back to Menu
        </button>
    </a>
</div>

</body>
</html>
