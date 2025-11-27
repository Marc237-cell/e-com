<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$cart = getCartItems();
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

// Handle quantity updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_quantity'])) {
        $productId = intval($_POST['product_id']);
        $quantity = intval($_POST['quantity']);
        updateCartQuantity($productId, $quantity);
        header('Location: cart.php');
        exit;
    } elseif (isset($_POST['remove_item'])) {
        $productId = intval($_POST['product_id']);
        removeFromCart($productId);
        header('Location: cart.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - TechGadgets</title>
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
                        <li><a href="cart.php" class="active">Cart</a></li>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <li><a href="checkout.php">Checkout</a></li>
                            <li><a href="logout.php">Logout (<?php echo $_SESSION['username']; ?>)</a></li>
                        <?php else: ?>
                            <li><a href="login.php">Login</a></li>
                            <li><a href="register.php">Register</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main>
        <div class="container">
            <h2 class="section-title">Your Shopping Cart</h2>
            
            <?php if (empty($cartItems)): ?>
                <div class="empty-cart-message">
                    <p>Your cart is empty. <a href="index.php">Continue shopping</a>.</p>
                </div>
            <?php else: ?>
                <div class="cart-container">
                    <?php foreach ($cartItems as $item): ?>
                        <div class="cart-item">
                            <img src="<?php echo $item['product']['image_url']; ?>" alt="<?php echo $item['product']['name']; ?>" class="cart-item-image">
                            <div class="cart-item-details">
                                <h3 class="cart-item-title"><?php echo $item['product']['name']; ?></h3>
                                <div class="cart-item-price">XAF<?php echo number_format($item['product']['price'], 2); ?></div>
                            </div>
                            <div class="cart-item-actions">
                                <form method="POST" class="quantity-form">
                                    <input type="hidden" name="product_id" value="<?php echo $item['product']['id']; ?>">
                                    <div class="quantity-selector">
                                        <button type="button" class="decrease-qty">-</button>
                                        <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" max="<?php echo $item['product']['stock_quantity']; ?>" class="quantity-input">
                                        <button type="button" class="increase-qty">+</button>
                                    </div>
                                    <button type="submit" name="update_quantity" class="btn">Update</button>
                                </form>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="product_id" value="<?php echo $item['product']['id']; ?>">
                                    <button type="submit" name="remove_item" class="btn btn-danger">Remove</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="cart-summary">
                    <div class="summary-row">
                        <span>Subtotal:</span>
                        <span>XAF<?php echo number_format(getCartTotal(), 2); ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Shipping:</span>
                        <span>XAF5.00</span>
                    </div>
                    <div class="summary-row summary-total">
                        <span>Total:</span>
                        <span>XAF<?php echo number_format(getCartTotal() + 5, 2); ?></span>
                    </div>
                    
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="checkout.php" class="btn btn-primary" style="width: 100%; margin-top: 20px;">Proceed to Checkout</a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-primary" style="width: 100%; margin-top: 20px;">Login to Checkout</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <script>
        // Quantity controls for cart items
        document.querySelectorAll('.increase-qty').forEach(button => {
            button.addEventListener('click', function() {
                const input = this.parentElement.querySelector('.quantity-input');
                const max = parseInt(input.getAttribute('max'));
                const current = parseInt(input.value);
                if (current < max) {
                    input.value = current + 1;
                }
            });
        });
        
        document.querySelectorAll('.decrease-qty').forEach(button => {
            button.addEventListener('click', function() {
                const input = this.parentElement.querySelector('.quantity-input');
                const current = parseInt(input.value);
                if (current > 1) {
                    input.value = current - 1;
                }
            });
        });
    </script>
</body>
</html>