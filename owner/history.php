<?php
session_start();
include("../db/config.php");

if (!isset($_SESSION['owner'])) {
    header("Location: login.php");
    exit();
}

$search = "";
$query = "SELECT * FROM orders ORDER BY created_at DESC";

if (isset($_GET['search']) && $_GET['search'] != "") {
    $search = intval($_GET['search']);
    $query = "SELECT * FROM orders WHERE id = $search";
}

$orders = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Order History | Spicy Eats</title>
    <link rel="stylesheet" href="../assets/css/theme.css">
    <style>
        .history-container{ padding:40px; }

        .top-bar{
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin-bottom:20px;
        }

        .search-box{
            display:flex;
            gap:10px;
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
        th{ color:var(--gold); }
        tr:hover{ background:rgba(255,255,255,0.05); }

        .status-pending{ color:#ffaa00; }
        .status-accepted{ color:var(--success); }
        .status-cancelled{ color:var(--danger); }
    </style>
</head>
<body>

<h1>Order History</h1>
<h2>All Orders (Recent → Oldest)</h2>

<div class="history-container glass fade-in">

    <!-- Top Bar -->
    <div class="top-bar">
        <a href="dashboard.php">
            <button class="btn">⬅ Back to Live Orders</button>
        </a>

        <form method="GET" class="search-box">
            <input type="number" name="search" placeholder="Search by Order ID" value="<?php echo $search; ?>">
            <button class="btn">Search</button>
            <a href="history.php">
                <button type="button" class="btn" style="background:#444;color:white;">Reset</button>
            </a>
        </form>
    </div>

    <table>
        <tr>
            <th>Order ID</th>
            <th>Student</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Date & Time</th>
        </tr>

        <?php if($orders->num_rows == 0): ?>
        <tr>
            <td colspan="7" style="text-align:center;color:red;">
                No order found
            </td>
        </tr>
        <?php endif; ?>

        <?php while($o = $orders->fetch_assoc()):
            $amount = 0;
            $items = json_decode($o['items'], true);
            if(is_array($items)){
                foreach($items as $i){
                    $amount += $i['price'];
                }
            }
        ?>
        <tr>
            <td>#<?php echo $o['id']; ?></td>
            <td><?php echo $o['student_username']; ?></td>
            <td><?php echo $o['name']; ?></td>
            <td><?php echo $o['phone']; ?></td>
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

</div>

</body>
</html>
