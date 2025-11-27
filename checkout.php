<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$cart = getCartItems();
if (empty($cart)) {
    header('Location: cart.php');
    exit;
}

$user = getUserById($_SESSION['user_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $shippingAddress = $_POST['shipping_address'];
    $orderId = createOrder($_SESSION['user_id'], $cart, $shippingAddress);
    
    if ($orderId) {
        header('Location: order_success.php?order_id=' . $orderId);
        exit;
    } else {
        $error = "There was an error processing your order. Please try again.";
    }
}

$cartItems = [];
foreach ($cart as $productId => $quantity) {
    $product = getProductById($productId);
    if ($product) {
        $cartItems[] = [
            'product' => $product,
            'quantity' => $quantity
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - TechGadgets</title>
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
                        <li><a href="cart.php">Cart</a></li>
                        <li><a href="checkout.php" class="active">Checkout</a></li>
                        <li><a href="logout.php">Logout (<?php echo $_SESSION['username']; ?>)</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main>
        <div class="container">
            <h2 class="section-title">Checkout</h2>
            
            <?php if (isset($error)): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <div class="checkout-container">
                <div class="checkout-form">
                    <h3>Shipping Information</h3>
                    <form method="POST">
                        <div class="form-group">
                            <label for="full_name">Full Name</label>
                            <input type="text" id="full_name" name="full_name" class="form-control" 
                                   value="<?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" class="form-control" 
                                   value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="tel" id="phone" name="phone" class="form-control" 
                                   value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="shipping_address">Shipping Address *</label>
                            <textarea id="shipping_address" name="shipping_address" class="form-control" rows="4" required><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                        </div>
                        
                        <h3>Order Summary</h3>
                        <div class="order-summary">
                            <?php foreach ($cartItems as $item): ?>
                                <div class="order-item">
                                    <span><?php echo $item['product']['name']; ?> (x<?php echo $item['quantity']; ?>)</span>
                                    <span>XAF<?php echo number_format($item['product']['price'] * $item['quantity'], 2); ?></span>
                                </div>
                            <?php endforeach; ?>
                            
                            <div class="order-total">
                                <span>Subtotal: XAF<?php echo number_format(getCartTotal(), 2); ?></span>
                                <span>Shipping: XAF5.00</span>
                                <span class="total">Total: XAF<?php echo number_format(getCartTotal() + 5, 2); ?></span>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 20px;">Place Order</button>
                    </form>
                </div>
            </div>
        </div>
    </main>
</body>
</html>