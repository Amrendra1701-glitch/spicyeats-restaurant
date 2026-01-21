<?php
session_start();
include("../db/config.php");

if (!isset($_SESSION['owner'])) {
    header("Location: login.php");
    exit();
}

/* Accept order */
if (isset($_GET['accept'])) {
    $id = intval($_GET['accept']);
    $conn->query("UPDATE orders SET status='Accepted' WHERE id=$id");
}

/* Deny order */
if (isset($_POST['deny'])) {
    $id = intval($_POST['order_id']);
    $reason = trim($_POST['reason']);

    $stmt = $conn->prepare("UPDATE orders SET status='Cancelled', cancel_reason=? WHERE id=?");
    $stmt->bind_param("si", $reason, $id);
    $stmt->execute();
}

/* Only fetch LIVE orders (Pending) */
$orders = $conn->query("SELECT * FROM orders WHERE status='Pending' ORDER BY created_at DESC");

/* Make items readable */
function formatItems($json){
    $items = json_decode($json, true);
    if (!is_array($items)) return "No items";

    $output = "";
    foreach($items as $i){
        $output .= "• " . $i['name'] . " (₹" . $i['price'] . ")<br>";
    }
    return $output;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Owner Dashboard | Spicy Eats</title>
    <link rel="stylesheet" href="../assets/css/theme.css">
    <meta http-equiv="refresh" content="5">
    <style>
        .nav-bar{
            display:flex;
            justify-content:center;
            gap:20px;
            margin:20px 0;
        }
        .nav-bar a{
            text-decoration:none;
        }
        .nav-btn{
            padding:10px 18px;
            border-radius:12px;
            background:rgba(255,255,255,0.08);
            color:var(--gold);
            border:1px solid rgba(255,255,255,0.2);
            cursor:pointer;
            transition:0.3s;
            font-weight:600;
        }
        .nav-btn:hover{
            background:var(--gold);
            color:black;
        }

        .dashboard{ padding:40px; }
        .order-grid{
            display:grid;
            grid-template-columns:repeat(auto-fit,minmax(350px,1fr));
            gap:25px;
        }
        .order-actions{
            display:flex;
            gap:10px;
            margin-top:15px;
        }
        .deny-input{ flex:1; }
        .items-box{
            background:rgba(0,0,0,0.4);
            padding:10px;
            border-radius:10px;
            font-size:13px;
            line-height:1.6;
            margin-top:5px;
        }
    </style>
</head>
<body>

<h1>Spicy Eats Owner Dashboard</h1>
<h2>Live Orders Control Panel</h2>

<!-- Navigation (only secondary panels shown) -->
<div class="nav-bar">
    <a href="history.php"><button class="nav-btn">📜 Order History</button></a>
    <a href="users.php"><button class="nav-btn">🚫 User Management</button></a>
</div>

<div class="dashboard fade-in">
    <div class="order-grid">

    <?php if($orders->num_rows == 0): ?>
        <p style="color:var(--gray);">No pending orders right now.</p>
    <?php endif; ?>

    <?php while($o = $orders->fetch_assoc()): ?>
        <div class="order-card glass">

            <h3>Order #<?php echo $o['id']; ?></h3>

            <p><b>Student:</b> <?php echo $o['student_username']; ?></p>
            <p><b>Name:</b> <?php echo $o['name']; ?></p>
            <p><b>Phone:</b> <?php echo $o['phone']; ?></p>

            <p><b>Items:</b></p>
            <div class="items-box">
                <?php echo formatItems($o['items']); ?>
            </div>

            <p><b>Customization:</b> <?php echo $o['customization']; ?></p>

            <p>
                <b>Status:</b> 
                <span class="status-pending">Pending</span>
            </p>

            <div class="order-actions">
                <a href="?accept=<?php echo $o['id']; ?>">
                    <button class="btn">Accept</button>
                </a>

                <form method="POST" style="display:flex; gap:8px; width:100%;">
                    <input type="hidden" name="order_id" value="<?php echo $o['id']; ?>">
                    <input class="deny-input" type="text" name="reason" placeholder="Cancel reason (optional)">
                    <button class="btn" style="background:linear-gradient(45deg,#ff3b3b,#ff6b6b); color:white;" name="deny">
                        Deny
                    </button>
                </form>
            </div>

        </div>
    <?php endwhile; ?>

    </div>
</div>

</body>
</html>