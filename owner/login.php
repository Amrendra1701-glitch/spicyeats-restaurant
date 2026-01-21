<?php
session_start();

/* Fixed owner credentials */
$OWNER_USERNAME = "spiceytreats01";
$OWNER_PASSWORD = "Pass@spiceyrestaurant";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if ($username === $OWNER_USERNAME && $password === $OWNER_PASSWORD) {
        $_SESSION['owner'] = $username;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid owner credentials.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Owner Login | Spicy Eats</title>
    <link rel="stylesheet" href="../assets/css/theme.css">
</head>
<body>

<div class="glass form-box fade-in">
    <h1>Spicy Eats</h1>
    <h2>Owner Login</h2>

    <?php if($error != ""): ?>
        <p style="color:var(--danger); text-align:center; margin-top:10px;">
            <?php echo $error; ?>
        </p>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Owner Username" required>
        <input type="password" name="password" placeholder="Owner Password" required>

        <button class="btn" style="width:100%; margin-top:15px;">Login</button>
    </form>

    <p style="text-align:center; margin-top:15px; color:var(--gray); font-size:13px;">
        Authorized access only
    </p>
</div>

</body>
</html>
