<?php
session_start();
include("../db/config.php");

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM students WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['student'] = $username;
        header("Location: menu.php");
        exit();
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Student Login | Spicy Eats</title>
    <link rel="stylesheet" href="../assets/css/theme.css">
</head>
<body>

<div class="glass form-box fade-in">
    <h1>Spicy Eats</h1>
    <h2>Student Login</h2>

    <?php if($error!=""): ?>
        <p style="color:var(--danger); text-align:center; margin-top:10px;">
            <?php echo $error; ?>
        </p>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>

        <button class="btn" style="width:100%; margin-top:15px;">Login</button>
    </form>

    <p style="text-align:center; margin-top:15px; color:var(--gray);">
        New here?
        <a href="register.php" style="color:var(--gold); text-decoration:none;">Create Account</a>
    </p>
</div>

</body>
</html>
