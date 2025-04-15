<?php
session_start();
require_once '../includes/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Fetch cart items with product details
$stmt = $pdo->prepare("
    SELECT c.*, p.name, p.price, p.image_url, (p.price * c.quantity) as total_price 
    FROM cart c 
    JOIN products p ON c.product_id = p.id 
    WHERE c.user_id = ?
");
$stmt->execute([$_SESSION['user_id']]);
$cart_items = $stmt->fetchAll();

// Calculate totals
$subtotal = 0;
foreach ($cart_items as $item) {
    $subtotal += $item['total_price'];
}

// Available promo codes (in real app, these would be in database)
$promo_codes = [
    'FIRST20' => ['discount' => 20, 'type' => 'percentage'],
    'SAVE500' => ['discount' => 500, 'type' => 'fixed'],
    'FARMER10' => ['discount' => 10, 'type' => 'percentage']
];

// Get user details
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Farmer Marketplace</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row gap-8">
            <!-- Left Column - Order Summary -->
            <div class="md:w-2/3">
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-2xl font-bold mb-4">Order Summary</h2>
                    <?php foreach ($cart_items as $item): ?>
                        <div class="flex items-center border-b py-4">
                            <img src="kk.webp" <?php echo htmlspecialchars($item['image_url']); ?>"
                                alt="<?php echo htmlspecialchars($item['name']); ?>" class="w-20 h-20 object-cover rounded">
                            <div class="ml-4 flex-grow">
                                <h3 class="font-semibold"><?php echo htmlspecialchars($item['name']); ?></h3>
                                <p class="text-gray-600">Quantity: <?php echo $item['quantity']; ?></p>
                                <p class="text-green-600">₹<?php echo number_format($item['price'], 2); ?> each</p>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold">₹<?php echo number_format($item['total_price'], 2); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Shipping Address -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-2xl font-bold mb-4">Shipping Address</h2>
                    <form id="addressForm" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-700">Full Name</label>
                            <input type="text" id="fullName"
                                value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>"
                                class="w-full p-2 border rounded" required>
                        </div>
                        <div>
                            <label class="block text-gray-700">Phone</label>
                            <input type="tel" id="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>"
                                class="w-full p-2 border rounded" required>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-gray-700">Address</label>
                            <textarea id="address" class="w-full p-2 border rounded" rows="3" required></textarea>
                        </div>
                        <div>
                            <label class="block text-gray-700">City</label>
                            <input type="text" id="city" class="w-full p-2 border rounded" required>
                        </div>
                        <div>
                            <label class="block text-gray-700">PIN Code</label>
                            <input type="text" id="pincode" class="w-full p-2 border rounded" required>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Column - Payment Summary -->
            <div class="md:w-1/3">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
                    <h2 class="text-2xl font-bold mb-4">Payment Summary</h2>

                    <!-- Promo Code -->
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Promo Code</label>
                        <div class="flex space-x-2">
                            <input type="text" id="promoCode" class="flex-grow p-2 border rounded"
                                placeholder="Enter promo code">
                            <button onclick="applyPromoCode()"
                                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                                Apply
                            </button>
                        </div>
                        <p id="promoMessage" class="text-sm mt-1"></p>
                    </div>

                    <!-- Price Details -->
                    <div class="border-t pt-4 space-y-2">
                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span>₹<?php echo number_format($subtotal, 2); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Shipping</span>
                            <span class="text-green-600">FREE</span>
                        </div>
                        <div class="flex justify-between" id="discountRow" style="display: none;">
                            <span>Discount</span>
                            <span class="text-green-600" id="discountAmount">-₹0.00</span>
                        </div>
                        <div class="flex justify-between font-bold text-lg border-t pt-2">
                            <span>Total</span>
                            <span id="finalTotal">₹<?php echo number_format($subtotal, 2); ?></span>
                        </div>
                    </div>

                    <!-- Payment Options -->
                    <div class="mt-6">
                        <h3 class="font-semibold mb-2">Payment Method</h3>
                        <div class="space-y-2">
                            <label
                                class="flex items-center space-x-2 p-3 border rounded hover:bg-gray-50 cursor-pointer">
                                <input type="radio" name="payment" value="online" checked>
                                <span>Online Payment (Get 5% Extra Discount)</span>
                            </label>
                            <label
                                class="flex items-center space-x-2 p-3 border rounded hover:bg-gray-50 cursor-pointer">
                                <input type="radio" name="payment" value="cod">
                                <span>Cash on Delivery</span>
                            </label>
                        </div>
                    </div>

                    <!-- Place Order Button -->
                    <button onclick="placeOrder()"
                        class="w-full bg-green-600 text-white py-3 rounded-lg font-bold mt-6 hover:bg-green-700">
                        Place Order
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentDiscount = 0;
        const subtotal = <?php echo $subtotal; ?>;
        const promoCodes = <?php echo json_encode($promo_codes); ?>;

        function applyPromoCode() {
            const promoInput = document.getElementById('promoCode');
            const promoMessage = document.getElementById('promoMessage');
            const code = promoInput.value.trim().toUpperCase();

            if (promoCodes[code]) {
                const promo = promoCodes[code];
                if (promo.type === 'percentage') {
                    currentDiscount = (subtotal * promo.discount) / 100;
                    promoMessage.textContent = `${promo.discount}% discount applied!`;
                } else {
                    currentDiscount = promo.discount;
                    promoMessage.textContent = `₹${promo.discount} discount applied!`;
                }
                promoMessage.className = 'text-sm mt-1 text-green-600';
                updateTotals();
            } else {
                promoMessage.textContent = 'Invalid promo code';
                promoMessage.className = 'text-sm mt-1 text-red-600';
                currentDiscount = 0;
                updateTotals();
            }
        }

        function updateTotals() {
            const discountRow = document.getElementById('discountRow');
            const discountAmount = document.getElementById('discountAmount');
            const finalTotal = document.getElementById('finalTotal');
            const paymentMethod = document.querySelector('input[name="payment"]:checked').value;

            let totalDiscount = currentDiscount;

            // Add 5% discount for online payment
            if (paymentMethod === 'online') {
                totalDiscount += (subtotal * 0.05);
            }

            if (totalDiscount > 0) {
                discountRow.style.display = 'flex';
                discountAmount.textContent = `-₹${totalDiscount.toFixed(2)}`;
            } else {
                discountRow.style.display = 'none';
            }

            const final = subtotal - totalDiscount;
            finalTotal.textContent = `₹${final.toFixed(2)}`;
        }

        // Update totals when payment method changes
        document.querySelectorAll('input[name="payment"]').forEach(radio => {
            radio.addEventListener('change', updateTotals);
        });

        function placeOrder() {
            // Validate form
            const form = document.getElementById('addressForm');
            if (!form.checkValidity()) {
                alert('Please fill in all required fields');
                return;
            }

            // Collect order data
            const orderData = {
                fullName: document.getElementById('fullName').value,
                phone: document.getElementById('phone').value,
                address: document.getElementById('address').value,
                city: document.getElementById('city').value,
                pincode: document.getElementById('pincode').value,
                paymentMethod: document.querySelector('input[name="payment"]:checked').value,
                promoCode: document.getElementById('promoCode').value,
                discount: currentDiscount
            };

            // Here you would typically send this data to the server
            // For now, we'll just show a success message
            alert('Order placed successfully! Thank you for shopping with us.');
            window.location.href = 'order_confirmation.php';
        }
    </script>
</body>

</html>