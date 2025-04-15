<?php
session_start();
require_once '../includes/db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login to remove items from cart']);
    exit;
}

// Check if cart_id was sent
if (!isset($_POST['cart_id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$cart_id = $_POST['cart_id'];
$user_id = $_SESSION['user_id'];

try {
    // Remove item from cart
    $stmt = $pdo->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
    $stmt->execute([$cart_id, $user_id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Item removed from cart']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Item not found in cart']);
    }

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error removing item from cart']);
}
?>