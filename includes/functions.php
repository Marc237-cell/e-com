<?php
require_once 'config.php';

function getProducts($limit = null) {
    global $pdo;
    
    $sql = "SELECT * FROM products WHERE stock_quantity > 0";
    if ($limit) {
        $sql .= " LIMIT " . intval($limit);
    }
    
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll();
}

function getProductById($id) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function addProduct($productData) {
    global $pdo;
    
    $stmt = $pdo->prepare("INSERT INTO products (name, description, price, image_url, stock_quantity, category) VALUES (?, ?, ?, ?, ?, ?)");
    return $stmt->execute([
        $productData['name'],
        $productData['description'],
        $productData['price'],
        $productData['image_url'],
        $productData['stock_quantity'],
        $productData['category']
    ]);
}

function updateProduct($id, $productData) {
    global $pdo;
    
    $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, image_url = ?, stock_quantity = ?, category = ? WHERE id = ?");
    return $stmt->execute([
        $productData['name'],
        $productData['description'],
        $productData['price'],
        $productData['image_url'],
        $productData['stock_quantity'],
        $productData['category'],
        $id
    ]);
}

function deleteProduct($id) {
    global $pdo;
    
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    return $stmt->execute([$id]);
}

function getCartItems() {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    return $_SESSION['cart'];
}

function addToCart($productId, $quantity = 1) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId] += $quantity;
    } else {
        $_SESSION['cart'][$productId] = $quantity;
    }
}

function removeFromCart($productId) {
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
}

function updateCartQuantity($productId, $quantity) {
    if (isset($_SESSION['cart'][$productId])) {
        if ($quantity <= 0) {
            unset($_SESSION['cart'][$productId]);
        } else {
            $_SESSION['cart'][$productId] = $quantity;
        }
    }
}

function getCartTotal() {
    $total = 0;
    $cart = getCartItems();
    
    foreach ($cart as $productId => $quantity) {
        $product = getProductById($productId);
        if ($product) {
            $total += $product['price'] * $quantity;
        }
    }
    
    return $total;
}

function createOrder($userId, $cart, $shippingAddress) {
    global $pdo;
    
    try {
        $pdo->beginTransaction();
        
        $total = getCartTotal();
        
        // Create order
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_amount, shipping_address) VALUES (?, ?, ?)");
        $stmt->execute([$userId, $total, $shippingAddress]);
        $orderId = $pdo->lastInsertId();
        
        // Create order items
        $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        
        foreach ($cart as $productId => $quantity) {
            $product = getProductById($productId);
            if ($product) {
                $stmt->execute([$orderId, $productId, $quantity, $product['price']]);
                
                // Update stock
                $updateStmt = $pdo->prepare("UPDATE products SET stock_quantity = stock_quantity - ? WHERE id = ?");
                $updateStmt->execute([$quantity, $productId]);
            }
        }
        
        $pdo->commit();
        $_SESSION['cart'] = []; // Clear cart
        return $orderId;
    } catch (Exception $e) {
        $pdo->rollBack();
        return false;
    }
}



function getAllOrders($limit = null) {
    global $pdo;
    
    $sql = "SELECT o.*, u.username, u.email 
            FROM orders o 
            LEFT JOIN users u ON o.user_id = u.id 
            ORDER BY o.created_at DESC";
    
    if ($limit) {
        $sql .= " LIMIT " . intval($limit);
    }
    
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll();
}

function getOrderById($orderId) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT o.*, u.username, u.email, u.first_name, u.last_name 
                          FROM orders o 
                          LEFT JOIN users u ON o.user_id = u.id 
                          WHERE o.id = ?");
    $stmt->execute([$orderId]);
    return $stmt->fetch();
}

function getOrderItems($orderId) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT oi.*, p.name, p.image_url 
                          FROM order_items oi 
                          LEFT JOIN products p ON oi.product_id = p.id 
                          WHERE oi.order_id = ?");
    $stmt->execute([$orderId]);
    return $stmt->fetchAll();
}

function updateOrderStatus($orderId, $status) {
    global $pdo;
    
    $validStatuses = ['pending', 'processing', 'completed', 'cancelled'];
    if (!in_array($status, $validStatuses)) {
        return false;
    }
    
    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    return $stmt->execute([$status, $orderId]);
}

function getUserOrders($userId) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$userId]);
    return $stmt->fetchAll();
}

function getOrdersCount() {
    global $pdo;
    
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM orders");
    return $stmt->fetch()['count'];
}

function getRevenueTotal() {
    global $pdo;
    
    $stmt = $pdo->query("SELECT SUM(total_amount) as total FROM orders WHERE status = 'completed'");
    return $stmt->fetch()['total'] ?? 0;
}
?>