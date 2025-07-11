<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include ('database/db-conn.php');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Finance Tracker</title>
    <link rel="stylesheet" href="assets/index.css">
</head>
<body>
    <div class="dashboard-header">
        <img src="logoimg.png" alt="Finance Tracker Logo" class="logo">
        <h1 class="heading">Welcome, <?php echo $_SESSION['username']; ?></h1>
        
    </div>
    
    <div class="dashboard">
        <div class="summary-cards">
            <div class="card">
                <h3>Current Balance</h3>
                <p><?php 
                    $stmt = $conn->prepare("SELECT SUM(amount) FROM transactions WHERE user_id = ?");
                    $stmt->bind_param("i", $_SESSION['user_id']);
                    $stmt->execute();
                    echo $stmt->get_result()->fetch_row()[0] ?? 0;
                ?></p>
            </div>
            
        </div>

        
        <div class="recent-transactions">
            <h2>Recent Transactions</h2>
            <table>
                <tr>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Amount</th>
                    <th>Category</th>
                </tr>
                <?php
                $stmt = $conn->prepare("SELECT date, description, amount, category 
                                      FROM transactions 
                                      WHERE user_id = ? 
                                      ORDER BY date DESC");
                $stmt->bind_param("i", $_SESSION['user_id']);
                $stmt->execute();
                $result = $stmt->get_result();
                
                while ($row = $result->fetch_assoc()):
                ?>
                <tr>
                    <td><?php echo $row['date']; ?></td>
                    <td><?php echo $row['description']; ?></td>
                    <td class="<?php echo $row['amount'] >= 0 ? 'income' : 'expense'; ?>">
                        <?php echo number_format($row['amount'], 2); ?>
                    </td>
                    <td><?php echo $row['category']; ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
            <a href="add_transaction.html" class="btn">Add Transaction</a>
 
        </div>


    </div>





</body>
<footer class="footer">
    <p>&copy, All Copy Rights Reserved.  Created By Dipen, Sakshyam, Utsav</p>
</footer>
</html>


