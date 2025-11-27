<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';


if (!isAdmin()) {
    header('Location: login.php');
    exit;
}

$products = getProducts();

$ordersCount = getOrdersCount();
$revenueTotal = getRevenueTotal();
$pendingOrders = array_slice(getAllOrders(5), 0, 5);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - TechGadgets</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo">Tech<span>Gadgets</span> Admin</div>
                <nav>
                    <ul>
                        <li><a href="index.php" class="active">Dashboard</a></li>
                        <li><a href="products.php">Products</a></li>
                        <li><a href="../index.php">View Site</a></li>
                        <li><a href="logout.php">Logout</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main>
        <div class="container">
            <h2 class="section-title">Admin Dashboard</h2>
            
            <div class="admin-stats">
                <div class="stat-card">
                    <h3>Total Products</h3>
                    <p><?php echo count($products); ?></p>
                </div>
                <div class="stat-card">
                    <h3>Low Stock Items</h3>
                    <p>0</p>
                </div>
            </div>
            
            <div class="admin-actions">
                <a href="products.php?action=add" class="btn btn-primary">Add New Product</a>
                <a href="products.php" class="btn">Manage Products</a>
            </div>
        </div>


<div class="admin-stats">
    <div class="stat-card">
        <h3>Total Products</h3>
        <p><?php echo count($products); ?></p>
    </div>
    <div class="stat-card">
        <h3>Total Orders</h3>
        <p><?php echo $ordersCount; ?></p>
    </div>
     
</div>

<!-- Add Recent Orders section -->
<div class="recent-orders" style="margin-top: 40px;">
    <h3>Recent Orders</h3>
    <?php if (empty($pendingOrders)): ?>
        <p>No recent orders.</p>
    <?php else: ?>
        <table class="products-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pendingOrders as $order): ?>
                <tr>
                    <td>#<?php echo $order['id']; ?></td>
                    <td><?php echo htmlspecialchars($order['username']); ?></td>
                    <td><?php echo number_format($order['total_amount'], 2); ?></td>
                    <td>
                        <span class="order-status status-<?php echo $order['status']; ?>">
                            <?php echo ucfirst($order['status']); ?>
                        </span>
                    </td>
                    <td><?php echo date('M j, Y', strtotime($order['created_at'])); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div style="text-align: center; margin-top: 20px;">
            <a href="orders.php" class="btn">View All Orders</a>
        </div>
    <?php endif; ?>
                </div>

    </main>
</body>
</html>


 