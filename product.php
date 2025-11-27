<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$productId = intval($_GET['id']);
$product = getProductById($productId);

if (!$product) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $quantity = intval($_POST['quantity']);
    addToCart($productId, $quantity);
    header('Location: cart.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $product['name']; ?> - TechGadgets</title>
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
            <div class="product-details">
                <div class="product-image-large">
                    <img src="<?php echo $product['image_url']; ?>" alt="<?php echo $product['name']; ?>">
                </div>
                <div class="product-info-detail">
                    <h1><?php echo $product['name']; ?></h1>
                    <div class="product-price-detail">XAF<?php echo number_format($product['price'], 2); ?></div>
                    <div class="stock-info">
                        <?php if ($product['stock_quantity'] > 0): ?>
                            <span class="in-stock">In Stock (<?php echo $product['stock_quantity']; ?> available)</span>
                        <?php else: ?>
                            <span class="out-of-stock">Out of Stock</span>
                        <?php endif; ?>
                    </div>
                    <p class="product-description"><?php echo $product['description']; ?></p>
                    
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <form method="POST">
                            <div class="quantity-selector">
                                <label for="quantity">Quantity:</label>
                                <button type="button" id="decrease-qty">-</button>
                                <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?php echo $product['stock_quantity']; ?>">
                                <button type="button" id="increase-qty">+</button>
                            </div>
                            
                            <?php if ($product['stock_quantity'] > 0): ?>
                                <button type="submit" class="btn">Add to Cart</button>
                            <?php else: ?>
                                <button type="button" class="btn" disabled>Out of Stock</button>
                            <?php endif; ?>
                        </form>
                    <?php else: ?>
                        <p>Please <a href="login.php">login</a> to add items to your cart.</p>
                    <?php endif; ?>
                    
                    <a href="index.php" class="btn btn-primary">Back to Products</a>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.getElementById('increase-qty').addEventListener('click', function() {
            const quantityInput = document.getElementById('quantity');
            const max = parseInt(quantityInput.getAttribute('max'));
            const current = parseInt(quantityInput.value);
            if (current < max) {
                quantityInput.value = current + 1;
            }
        });
        
        document.getElementById('decrease-qty').addEventListener('click', function() {
            const quantityInput = document.getElementById('quantity');
            const current = parseInt(quantityInput.value);
            if (current > 1) {
                quantityInput.value = current - 1;
            }
        });
    </script>
</body>
</html>