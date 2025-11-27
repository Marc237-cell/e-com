<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$products = getProducts();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechGadgets - Online Store</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo"><img src="images/logo.png" alt="TechGadgets Logo" class="logo"></div>
                <nav>
                    <ul>
                        <li><a href="index.php" class="active">Home</a></li>
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
       <!-- Video Hero Section -->
<section class="hero-video">
    <div class="video-slideshow">
        <!-- Video Slide 1 -->
        <div class="video-slide active" data-video="assets/videos/tech-showcase-1.mp4">
            <video loop muted playsinline>
                <source src="assets/videos/v1.mov" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
        
        <!-- Video Slide 2 -->
        <div class="video-slide" data-video="assets/videos/v2.mov">
            <video loop muted playsinline>
                <source src="assets/videos/v2.mov" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
        
        <!-- Video Slide 3 -->
        <div class="video-slide" data-video="assets/videos/v3.mov">
            <video loop muted playsinline>
                <source src="assets/videos/v3.mov" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>

        <!-- Video Slide 4 -->
        <div class="video-slide" data-video="assets/videos/v4.mov">
            <video loop muted playsinline>
                <source src="assets/videos/v4.mov" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
    </div>
    
    <div class="video-overlay"></div>
    
    <div class="container">
        <div class="hero-content">
            <h1>Welcome to TechGadgets</h1>
            <p>Discover the latest in technology with our premium selection of gadgets and electronics. Innovation meets style.</p>
            <div class="hero-buttons">
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <a href="register.php" class="btn">Create Account to Shop</a>
                <?php else: ?>
                    <a href="#" class="btn">Shop Now</a>
                <?php endif; ?>
                <a href="product.php" class="btn btn-primary" style="margin-left: 15px;">Explore Products</a>
            </div>
        </div>
    </div>
    
    <!-- Video Controls -->
    <div class="video-controls">
        <button class="video-prev" aria-label="Previous video">‹</button>
        
        <div class="video-indicators">
            <span class="video-indicator active" data-slide="0"></span>
            <span class="video-indicator" data-slide="1"></span>
            <span class="video-indicator" data-slide="2"></span>
        </div>
        
        <button class="video-next" aria-label="Next video">›</button>
        
      
    </div>
</section>



        
        <div class="container">
            <h2 class="section-title">Featured Products</h2>
            <div class="products-grid">
                <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <img src="<?php echo $product['image_url']; ?>" alt="<?php echo $product['name']; ?>" class="product-image">
                    <div class="product-info">
                        <h3 class="product-title"><?php echo $product['name']; ?></h3>
                        <div class="product-price">XAF<?php echo number_format($product['price'], 2); ?></div>
                        <p class="product-description"><?php echo substr($product['description'], 0, 100); ?>...</p>
                        <a href="product.php?id=<?php echo $product['id']; ?>" class="btn">View Details</a>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <form method="POST" action="add_to_cart.php" style="margin-top: 10px;">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <input type="number" name="quantity" value="1" min="1" style="width: 60px; margin-right: 10px;">
                                <button type="submit" class="btn btn-primary">Add to Cart</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>

    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>TechGadgets</h3>
                    <p>Your trusted source for the latest technology and gadgets at competitive prices.</p>
                </div>
                
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="login.php">Login</a></li>
                        <li><a href="register.php">Register</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
</body>
<script src="assets/js/script.js"></script>
</html>