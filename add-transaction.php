<?php

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include ('database/db-conn.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
    $type = $_POST['type'] ?? '';
    $amount = $_POST['amount'] ?? '';
    $category = $_POST['category'] ?? '';
    $description = $_POST['description'] ?? '';
    $date = $_POST['date'] ?? '';


    if (!is_numeric($amount)) {
        header("Location: add_transaction.html?error=invalid_amount");
        exit();
    }
    $amount = $type == 'expense' ? -abs($amount) : abs($amount);

    
    $d = DateTime::createFromFormat('Y-m-d', $date);
    if (!$d || $d->format('Y-m-d') !== $date) {
        header("Location: add_transaction.html?error=invalid_date");
        exit();
    }


    $category = substr(trim($conn->real_escape_string($category)), 0, 50);
    $description = substr(trim($conn->real_escape_string($description)), 0, 255);

    $stmt = $conn->prepare("INSERT INTO transactions (user_id, amount, category, description, date) 
                           VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("idsss", $_SESSION['user_id'], $amount, $category, $description, $date);


    if ($stmt->execute()) {
        header("Location: dashboard.php?&success=1");
    } else {
        header("Location: add_transaction.html?error=" . urlencode($stmt->error));
    }
    ?>