<?php
session_start();
require_once '../includes/db_connect.php';

// Get filter parameters
$category = isset($_GET['category']) ? $_GET['category'] : '';
$min_price = isset($_GET['min_price']) ? (float) $_GET['min_price'] : 0;
$max_price = isset($_GET['max_price']) ? (float) $_GET['max_price'] : 1000000;
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'name_asc';

// Build query
$query = "SELECT * FROM products WHERE price >= ? AND price <= ?";
$params = [$min_price, $max_price];

if (!empty($category)) {
    $query .= " AND category = ?";
    $params[] = $category;
}

// Add sorting
switch ($sort) {
    case 'price_asc':
        $query .= " ORDER BY price ASC";
        break;
    case 'price_desc':
        $query .= " ORDER BY price DESC";
        break;
    case 'name_desc':
        $query .= " ORDER BY name DESC";
        break;
    default:
        $query .= " ORDER BY name ASC";
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$products = $stmt->fetchAll();

// Get all categories for filter
$stmt = $pdo->query("SELECT DISTINCT category FROM products");
$categories = $stmt->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - Farmer's Marketplace</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <!-- Header -->
    <header class="bg-green-600 text-white shadow-lg">
        <nav class="container mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center">
                <a href="../index.php" class="text-2xl font-bold">Farmer's Marketplace</a>
            </div>
            <div class="flex items-center space-x-4">
                <a href="../index.php" class="hover:text-green-200">Home</a>
                <a href="products.php" class="hover:text-green-200">Products</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="logout.php" class="hover:text-green-200">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="hover:text-green-200">Login</a>
                    <a href="register.php" class="hover:text-green-200">Register</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <main class="container mx-auto px-6 py-8">
        <!-- Filters -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-8">
            <h2 class="text-xl font-bold mb-4">Filters</h2>
            <form method="GET" action="products.php" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="category">
                        Category
                    </label>
                    <select name="category" id="category" class="w-full border rounded px-3 py-2">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo htmlspecialchars($cat); ?>" <?php echo $category === $cat ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars(ucfirst($cat)); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="min_price">
                        Min Price
                    </label>
                    <input type="number" name="min_price" id="min_price" value="<?php echo $min_price; ?>"
                        class="w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="max_price">
                        Max Price
                    </label>
                    <input type="number" name="max_price" id="max_price" value="<?php echo $max_price; ?>"
                        class="w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="sort">
                        Sort By
                    </label>
                    <select name="sort" id="sort" class="w-full border rounded px-3 py-2">
                        <option value="name_asc" <?php echo $sort === 'name_asc' ? 'selected' : ''; ?>>Name (A-Z)</option>
                        <option value="name_desc" <?php echo $sort === 'name_desc' ? 'selected' : ''; ?>>Name (Z-A)
                        </option>
                        <option value="price_asc" <?php echo $sort === 'price_asc' ? 'selected' : ''; ?>>Price (Low to
                            High)</option>
                        <option value="price_desc" <?php echo $sort === 'price_desc' ? 'selected' : ''; ?>>Price (High to
                            Low)</option>
                    </select>
                </div>
                <div class="md:col-span-4">
                    <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        Apply Filters
                    </button>
                </div>
            </form>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php foreach ($products as $product): ?>
                <div class="product-card bg-white rounded-lg shadow-md p-4 hover:shadow-lg transition-shadow">
                    <img src="../<?php echo htmlspecialchars($product['image_url']); ?>"
                        alt="<?php echo htmlspecialchars($product['name']); ?>"
                        class="w-full h-48 object-cover rounded-md mb-4">
                    <h3 class="text-xl font-semibold mb-2"><?php echo htmlspecialchars($product['name']); ?></h3>
                    <p class="text-gray-600 mb-2"><?php echo htmlspecialchars($product['description']); ?></p>
                    <div class="flex justify-between items-center">
                        <span
                            class="text-2xl font-bold text-green-600">₹<?php echo number_format($product['price'], 2); ?></span>
                        <div class="flex items-center space-x-2">
                            <input type="number" min="1" max="<?php echo $product['stock']; ?>" value="1"
                                class="w-16 p-1 border rounded" id="quantity-<?php echo $product['id']; ?>">
                            <button onclick="addToCart(<?php echo $product['id']; ?>)"
                                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                                Add to Cart
                            </button>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 mt-2">Stock: <?php echo $product['stock']; ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <script>
        function addToCart(productId) {
            const quantity = document.getElementById(`quantity-${productId}`).value;

            fetch('add_to_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `product_id=${productId}&quantity=${quantity}`
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Product added to cart successfully!');
                    } else {
                        alert(data.message || 'Error adding product to cart');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error adding product to cart');
                });
        }
    </script>
</body>

</html>