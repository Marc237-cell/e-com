<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

if (!isAdmin()) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: orders.php');
    exit;
}

$orderId = intval($_GET['id']);
$order = getOrderById($orderId);
$orderItems = getOrderItems($orderId);

if (!$order) {
    header('Location: orders.php');
    exit;
}

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $status = $_POST['status'];
    
    if (updateOrderStatus($orderId, $status)) {
        $message = "Order status updated successfully!";
        $order = getOrderById($orderId); // Refresh order data
    } else {
        $error = "Error updating order status.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details - TechGadgets Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .order-detail-card {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        .order-info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }
        .order-items {
            margin-top: 30px;
        }
        .order-item {
            display: flex;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }
        .order-item-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            margin-right: 15px;
            border-radius: 4px;
        }
        .order-item-details {
            flex: 1;
        }
        .order-summary {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }
        @media (max-width: 768px) {
            .order-info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo">Tech<span>Gadgets</span> Admin</div>
                <nav>
                    <ul>
                        <li><a href="index.php">Dashboard</a></li>
                        <li><a href="products.php">Products</a></li>
                        <li><a href="orders.php">Orders</a></li>
                        <li><a href="currency.php">Currency</a></li>
                        <li><a href="../index.php">View Site</a></li>
                        <li><a href="logout.php">Logout</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main>
        <div class="container">
            <div class="order-detail-card">
                <div class="order-header">
                    <div>
                        <h2>Order #<?php echo $order['id']; ?></h2>
                        <p>Placed on <?php echo date('F j, Y \a\t g:i A', strtotime($order['created_at'])); ?></p>
                    </div>
                    <div>
                        <form method="POST" class="status-form">
                            <label for="status">Order Status:</label>
                            <select name="status" id="status" onchange="this.form.submit()" style="margin-left: 10px; padding: 8px;">
                                <option value="pending" <?php echo $order['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="processing" <?php echo $order['status'] === 'processing' ? 'selected' : ''; ?>>Processing</option>
                                <option value="completed" <?php echo $order['status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                                <option value="cancelled" <?php echo $order['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                            </select>
                            <input type="hidden" name="update_status" value="1">
                        </form>
                    </div>
                </div>
                
                <?php if (isset($message)): ?>
                    <div class="success-message"><?php echo $message; ?></div>
                <?php endif; ?>
                
                <?php if (isset($error)): ?>
                    <div class="error-message"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <div class="order-info-grid">
                    <div>
                        <h3>Customer Information</h3>
                        <p><strong>Name:</strong> <?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?></p>
                        <p><strong>Username:</strong> <?php echo htmlspecialchars($order['username']); ?></p>
                    </div>
                    
                    <div>
                        <h3>Shipping Address</h3>
                        <p><?php echo nl2br(htmlspecialchars($order['shipping_address'])); ?></p>
                    </div>
                </div>
                
                <div class="order-items">
                    <h3>Order Items</h3>
                    <?php foreach ($orderItems as $item): ?>
                    <div class="order-item">
                        <img src="<?php echo $item['image_url']; ?>" alt="<?php echo $item['name']; ?>" class="order-item-image">
                        <div class="order-item-details">
                            <h4><?php echo $item['name']; ?></h4>
                            <p>Quantity: <?php echo $item['quantity']; ?></p>
                        </div>
                        <div class="order-item-price">
                            <?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="order-summary">
                    <div style="display: flex; justify-content: space-between; font-size: 1.2rem; font-weight: bold;">
                        <span>Total Amount:</span>
                        <span><?php echo number_format($order['total_amount'], 2); ?></span>
                    </div>
                </div>
            </div>
            
            <a href="orders.php" class="btn">← Back to Orders</a>
        </div>
    </main>
</body>
</html>