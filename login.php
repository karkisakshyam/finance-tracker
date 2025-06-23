<?php
session_start();
include 'database/db_config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];
}

$sql =" SELECT ID, username, password FROM users WHERE email= .?";
$stmt= $conn->prepare($sql);
$stmt ->bind_param('s', 'email');
$stmt -> execute ();
$result = $stmt -> get_result();

if ( $result ->nums_row == 1) {
    $user = $result ->fetch_assoc();{
     if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: dashboard.php");
            exit();


}
}
 $error = "Invalid email or password";
}







?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Finance Tracker</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="login-container">
        <h1>Finance Tracker</h1>
        <?php if (isset($error)): ?>
            <div class="alert"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="register.html">Register</a></p>
    </div>
</body>
</html>