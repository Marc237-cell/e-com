<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

if (!isAdmin()) {
    header('Location: login.php');
    exit;
}

$orders = getAllOrders();
$ordersCount = getOrdersCount();
$revenueTotal = getRevenueTotal();

// Handle status updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $orderId = intval($_POST['order_id']);
    $status = $_POST['status'];
    
    if (updateOrderStatus($orderId, $status)) {
        $message = "Order status updated successfully!";
    } else {
        $error = "Error updating order status.";
    }
    
    // Refresh orders
    $orders = getAllOrders();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Management - TechGadgets Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .order-status {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: bold;
        }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-processing { background: #cce7ff; color: #004085; }
        .status-completed { background: #d4edda; color: #155724; }
        .status-cancelled { background: #f8d7da; color: #721c24; }
        .order-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .stat-card h3 {
            color: var(--secondary);
            margin-bottom: 10px;
            font-size: 1rem;
        }
        .stat-card .number {
            font-size: 2rem;
            font-weight: bold;
            color: var(--primary);
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
                        <li><a href="orders.php" class="active">Orders</a></li>
                        <li><a href="../index.php">View Site</a></li>
                        <li><a href="logout.php">Logout</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main>
        <div class="container">
            <h2 class="section-title">Order Management</h2>
            
            <?php if (isset($message)): ?>
                <div class="success-message"><?php echo $message; ?></div>
            <?php endif; ?>
            
            <?php if (isset($error)): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <!-- Order Statistics -->
            <div class="order-stats">
                <div class="stat-card">
                    <h3>Total Orders</h3>
                    <div class="number"><?php echo $ordersCount; ?></div>
                </div>
                <div class="stat-card">
                    <h3>Total Revenue</h3>
                    <div class="number"><?php echo number_format($revenueTotal, 2); ?></div>
                </div>
                <div class="stat-card">
                    <h3>Pending Orders</h3>
                    <div class="number">
                        <?php 
                        $pendingCount = array_reduce($orders, function($carry, $order) {
                            return $carry + ($order['status'] === 'pending' ? 1 : 0);
                        }, 0);
                        echo $pendingCount;
                        ?>
                    </div>
                </div>
            </div>
            
            <!-- Orders Table -->
            <table class="products-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($orders)): ?>
                        <tr>
                            <td colspan="6" style="text-align: center;">No orders found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($orders as $order): ?>
                        <tr>
                            <td>#<?php echo $order['id']; ?></td>
                            <td>
                                <?php echo htmlspecialchars($order['username']); ?><br>
                                <small><?php echo htmlspecialchars($order['email']); ?></small>
                            </td>
                            <td><?php echo number_format($order['total_amount'], 2); ?></td>
                            <td>
                                <span class="order-status status-<?php echo $order['status']; ?>">
                                    <?php echo ucfirst($order['status']); ?>
                                </span>
                            </td>
                            <td><?php echo date('M j, Y g:i A', strtotime($order['created_at'])); ?></td>
                            <td>
                                <a href="order_details.php?id=<?php echo $order['id']; ?>" class="btn edit-btn">View</a>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                    <select name="status" onchange="this.form.submit()" style="padding: 4px;">
                                        <option value="pending" <?php echo $order['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="processing" <?php echo $order['status'] === 'processing' ? 'selected' : ''; ?>>Processing</option>
                                        <option value="completed" <?php echo $order['status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                                        <option value="cancelled" <?php echo $order['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                    </select>
                                    <input type="hidden" name="update_status" value="1">
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>