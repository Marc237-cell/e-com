<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

if (!isAdmin()) {
    header('Location: login.php');
    exit;
}

$action = $_GET['action'] ?? 'list';
$message = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] === 'add') {
        $productData = [
            'name' => $_POST['name'],
            'description' => $_POST['description'],
            'price' => floatval($_POST['price']),
            'image_url' => $_POST['image_url'],
            'stock_quantity' => intval($_POST['stock_quantity']),
            'category' => $_POST['category']
        ];
        
        if (addProduct($productData)) {
            $message = "Product added successfully!";
        } else {
            $message = "Error adding product.";
        }
    } elseif ($_POST['action'] === 'edit') {
        $productId = intval($_POST['product_id']);
        $productData = [
            'name' => $_POST['name'],
            'description' => $_POST['description'],
            'price' => floatval($_POST['price']),
            'image_url' => $_POST['image_url'],
            'stock_quantity' => intval($_POST['stock_quantity']),
            'category' => $_POST['category']
        ];
        
        if (updateProduct($productId, $productData)) {
            $message = "Product updated successfully!";
        } else {
            $message = "Error updating product.";
        }
    }
}

// Handle delete action
if (isset($_GET['delete'])) {
    $productId = intval($_GET['delete']);
    if (deleteProduct($productId)) {
        $message = "Product deleted successfully!";
    } else {
        $message = "Error deleting product.";
    }
}

$products = getProducts();
$editingProduct = null;

if ($action === 'edit' && isset($_GET['id'])) {
    $editingProduct = getProductById(intval($_GET['id']));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products - TechGadgets Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo">Tech<span>Gadgets</span> Admin</div>
                <nav>
                    <ul>
                        <li><a href="index.php">Dashboard</a></li>
                        <li><a href="products.php" class="active">Products</a></li>
                        <li><a href="../index.php">View Site</a></li>
                        <li><a href="logout.php">Logout</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main>
        <div class="container">
            <h2 class="section-title">Manage Products</h2>
            
            <?php if ($message): ?>
                <div class="success-message"><?php echo $message; ?></div>
            <?php endif; ?>
            
            <?php if ($action === 'add' || $action === 'edit'): ?>
                <!-- Add/Edit Product Form -->
                <div class="form-container">
                    <h3><?php echo $action === 'add' ? 'Add New Product' : 'Edit Product'; ?></h3>
                    
                    <form method="POST">
                        <input type="hidden" name="action" value="<?php echo $action; ?>">
                        <?php if ($action === 'edit'): ?>
                            <input type="hidden" name="product_id" value="<?php echo $editingProduct['id']; ?>">
                        <?php endif; ?>
                        
                        <div class="form-group">
                            <label for="name">Product Name *</label>
                            <input type="text" id="name" name="name" class="form-control" 
                                   value="<?php echo $editingProduct['name'] ?? ''; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Description *</label>
                            <textarea id="description" name="description" class="form-control" rows="4" required><?php echo $editingProduct['description'] ?? ''; ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="price">Price *</label>
                            <input type="number" id="price" name="price" step="0.01" min="0" class="form-control" 
                                   value="<?php echo $editingProduct['price'] ?? ''; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="image_url">Image URL *</label>
                            <input type="url" id="image_url" name="image_url" class="form-control" 
                                   value="<?php echo $editingProduct['image_url'] ?? ''; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="stock_quantity">Stock Quantity *</label>
                            <input type="number" id="stock_quantity" name="stock_quantity" min="0" class="form-control" 
                                   value="<?php echo $editingProduct['stock_quantity'] ?? '0'; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="category">Category *</label>
                            <input type="text" id="category" name="category" class="form-control" 
                                   value="<?php echo $editingProduct['category'] ?? ''; ?>" required>
                        </div>
                        
                        <button type="submit" class="btn btn-primary"><?php echo $action === 'add' ? 'Add Product' : 'Update Product'; ?></button>
                        <a href="products.php" class="btn">Cancel</a>
                    </form>
                </div>
            <?php else: ?>
                <!-- Product List -->
                <div class="admin-actions">
                    <a href="products.php?action=add" class="btn btn-primary">Add New Product</a>
                </div>
                
                <table class="products-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Category</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?php echo $product['id']; ?></td>
                            <td>
                                <img src="<?php echo $product['image_url']; ?>" alt="<?php echo $product['name']; ?>" style="width: 50px; height: 50px; object-fit: cover;">
                            </td>
                            <td><?php echo $product['name']; ?></td>
                            <td>XAF<?php echo number_format($product['price'], 2); ?></td>
                            <td><?php echo $product['stock_quantity']; ?></td>
                            <td><?php echo $product['category']; ?></td>
                            <td>
                                <a href="products.php?action=edit&id=<?php echo $product['id']; ?>" class="btn edit-btn">Edit</a>
                                <a href="products.php?delete=<?php echo $product['id']; ?>" class="btn delete-btn" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>