<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['order_id'])) {
    header('Location: index.php');
    exit;
}

$orderId = intval($_GET['order_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Successful - TechGadgets</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo">Tech<span>Gadgets</span></div>
                <nav>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <li><a href="logout.php">Logout (<?php echo $_SESSION['username']; ?>)</a></li>
                        <?php else: ?>
                            <li><a href="login.php">Login</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main>
        <div class="container">
            <div class="success-container">
                <div class="success-icon">✓</div>
                <h2>Order Placed Successfully!</h2>
                <p>Thank you for your purchase. Your order has been received and is being processed.</p>
                <div class="order-details">
                    <p><strong>Order ID:</strong> #<?php echo $orderId; ?></p>
                    <p><strong>Order Date:</strong> <?php echo date('F j, Y'); ?></p>
                </div>
                <div class="success-actions">
                    <a href="index.php" class="btn btn-primary">Continue Shopping</a>
                    <a href="order_history.php" class="btn">View Order History</a>
                </div>
            </div>
        </div>
    </main>
</body>
</html>