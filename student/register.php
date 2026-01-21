<?php
include("../db/config.php");

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm  = trim($_POST['confirm']);

    if ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        $hashed = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $conn->prepare("INSERT INTO students (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hashed);

        if ($stmt->execute()) {
            $success = "Account created successfully. You can now login.";
        } else {
            $error = "Username already exists. Choose another one.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Student Register | Spicy Eats</title>
    <link rel="stylesheet" href="../assets/css/theme.css">
</head>
<body>

<div class="glass form-box fade-in">
    <h1>Spicy Eats</h1>
    <h2>Create Account</h2>

    <?php if($error!=""): ?>
        <p style="color:var(--danger); text-align:center; margin-top:10px;">
            <?php echo $error; ?>
        </p>
    <?php endif; ?>

    <?php if($success!=""): ?>
        <p style="color:var(--success); text-align:center; margin-top:10px;">
            <?php echo $success; ?>
        </p>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Choose Username" required>
        <input type="password" name="password" placeholder="Choose Password" required>
        <input type="password" name="confirm" placeholder="Confirm Password" required>

        <button class="btn" style="width:100%; margin-top:15px;">Create Account</button>
    </form>

    <p style="text-align:center; margin-top:15px; color:var(--gray);">
        Already have an account?
        <a href="login.php" style="color:var(--gold); text-decoration:none;">Login</a>
    </p>
</div>

</body>
</html>
