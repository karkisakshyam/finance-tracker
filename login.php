<?php

session_start();
include 'database/db-conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

   

    $sql = "SELECT id, username, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    var_dump($result);



    

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

          


             session_start();
    $_SESSION['is_loggedin'] = true;
    header("Location: dashboard.php");
} else {
    header("Location: login.php");{
    echo "Invalid email or password";
    }
    
    exit();
}
    //        header("Location: dashboard.php");
    //         exit();
    //      } else {
    //          $error = "Invalid email or password";
    //     }
    // } else {
    //     $error = "Invalid email or password";
    //  }
  

}
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Myfinance Tracker</title>
    <link rel="stylesheet" href="./assets/login.css">
</head>

<body>
    <div class="Login-container">
        <img src="logoimg.png" class="logo" alt="">
        <h2>Login To MyFinance</h2>
        <form action="login.php" method="post">
            <div class="form-group">
            <label for="email">email</label>
            <input type="text" name="email" id="email" required><br>
            </div>
            <div class="form-group">
            <label for="password">Password </label>
            <input type="password" name="password" id="password" required><br>
            </div>
            <button type="submit">Login</button>
            <p>Don't have an account? <br><a href="register.html">Register here</a></p>
           
        </form>
    </div>
    
</body>
</html>