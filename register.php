<?php
include('database/db-conn.php');

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    

    if ($username && $password && $email) {
        $check = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $check->bind_param("ss", $username, $email);
        $check->execute();
        $check->store_result();
        if ($check->num_rows > 0) {
            $message = "Username or email already exists.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username,$email , $hashed_password);
            if ($stmt->execute()) {
                header("Location: login.php");
                exit();
            } else {
                $message = "Registration failed: " . $conn->error;
            }
        }
        $check->close();
    } else {
        $message = "All fields are required.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register - Finance Tracker</title>
    <link rel="stylesheet" href="finance-tracker/assets/style.css">
</head>
<body>
    <div class="login-container">
        <h1>Register</h1>
        <?php if (!empty($message)): ?>
            <div class="alert"><?php echo $message; ?></div>
        <?php endif; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="finance-tracker/login.php">Login</a></p>
    </div>
</body>
</html>