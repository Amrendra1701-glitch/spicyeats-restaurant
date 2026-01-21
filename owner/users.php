<?php
session_start();
include("../db/config.php");

if (!isset($_SESSION['owner'])) {
    header("Location: login.php");
    exit();
}

/* Block user */
if (isset($_GET['block'])) {
    $username = $_GET['block'];
    $conn->query("UPDATE students SET status='Blocked' WHERE username='$username'");
}

/* Unblock user */
if (isset($_GET['unblock'])) {
    $username = $_GET['unblock'];
    $conn->query("UPDATE students SET status='Active' WHERE username='$username'");
}

/* Fetch all students */
$students = $conn->query("SELECT * FROM students ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>User Management | Spicy Eats</title>
    <link rel="stylesheet" href="../assets/css/theme.css">
    <style>
        .users-container{
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
        }

        tr:hover{
            background:rgba(255,255,255,0.05);
        }

        .status-active{
            color:var(--success);
            font-weight:600;
        }

        .status-blocked{
            color:var(--danger);
            font-weight:600;
        }

        .action-btn{
            padding:6px 12px;
            border-radius:8px;
            border:none;
            cursor:pointer;
            font-weight:600;
        }

        .block-btn{
            background:#ff3b3b;
            color:white;
        }

        .unblock-btn{
            background:#1aff88;
            color:black;
        }

        .top-bar{
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin-bottom:20px;
        }
    </style>
</head>
<body>

<h1>User Management</h1>
<h2>Block or Unblock Students</h2>

<div class="users-container glass fade-in">

    <!-- Top bar with back button -->
    <div class="top-bar">
        <a href="dashboard.php">
            <button class="btn">⬅ Back to Live Orders</button>
        </a>
    </div>

    <table>
        <tr>
            <th>Username</th>
            <th>Account Created</th>
            <th>Status</th>
            <th>Action</th>
        </tr>

        <?php if($students->num_rows == 0): ?>
        <tr>
            <td colspan="4" style="text-align:center;color:var(--gray);">
                No users found.
            </td>
        </tr>
        <?php endif; ?>

        <?php while($s = $students->fetch_assoc()): ?>
        <tr>
            <td><?php echo $s['username']; ?></td>
            <td><?php echo $s['created_at']; ?></td>
            <td>
                <?php
                    if ($s['status'] == "Active") {
                        echo "<span class='status-active'>Active</span>";
                    } else {
                        echo "<span class='status-blocked'>Blocked</span>";
                    }
                ?>
            </td>
            <td>
                <?php if($s['status']=="Active"): ?>
                    <a href="?block=<?php echo $s['username']; ?>">
                        <button class="action-btn block-btn">
                            Block
                        </button>
                    </a>
                <?php else: ?>
                    <a href="?unblock=<?php echo $s['username']; ?>">
                        <button class="action-btn unblock-btn">
                            Unblock
                        </button>
                    </a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>

    </table>

</div>

</body>
</html>
