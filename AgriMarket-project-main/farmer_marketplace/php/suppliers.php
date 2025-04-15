<?php
session_start();
require_once '../includes/db_connect.php';

// Get all suppliers with their contact information
$stmt = $pdo->query("
    SELECT s.*, sc.contact_person, sc.email, sc.phone, sc.address, sc.city, sc.state, sc.country
    FROM suppliers s
    LEFT JOIN supplier_contact sc ON s.id = sc.supplier_id AND sc.is_primary = 1
    ORDER BY s.name ASC
");
$suppliers = $stmt->fetchAll();

// Get supplier categories
$stmt = $pdo->query("SELECT DISTINCT category FROM suppliers");
$categories = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Get business types for filter
$business_types = ['manufacturer', 'distributor', 'retailer', 'wholesaler'];

// Get featured suppliers
$stmt = $pdo->query("
    SELECT s.*, sc.contact_person, sc.email, sc.phone
    FROM suppliers s
    LEFT JOIN supplier_contact sc ON s.id = sc.supplier_id AND sc.is_primary = 1
    WHERE s.rating >= 4.5
    ORDER BY s.reviews_count DESC
    LIMIT 3
");
$featured_suppliers = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suppliers - AgriMarket</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <nav class="container mx-auto px-6 py-3">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <a href="../index.php" class="text-2xl font-bold text-green-600 flex items-center">
                        <svg class="w-8 h-8 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 110-12 6 6 0 010 12z" />
                        </svg>
                        AgriMarket
                    </a>
                </div>
                <div class="flex items-center space-x-8">
                    <a href="../index.php" class="text-gray-600 hover:text-green-600">Home</a>
                    <a href="products.php" class="text-gray-600 hover:text-green-600">Products</a>
                    <a href="suppliers.php" class="text-gray-600 hover:text-green-600">Suppliers</a>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="logout.php" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">Logout</a>
                    <?php else: ?>
                        <a href="login.php" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">Login</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </header>

    <main class="container mx-auto px-6 py-8">
        <!-- Featured Suppliers Section -->
        <?php if ($featured_suppliers): ?>
            <div class="mb-12">
                <h2 class="text-3xl font-bold mb-6">Featured Suppliers</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <?php foreach ($featured_suppliers as $supplier): ?>
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                            <div class="relative">
                                <img src="../<?php echo htmlspecialchars($supplier['logo_url']); ?>" 
                                     alt="<?php echo htmlspecialchars($supplier['name']); ?>"
                                     class="w-full h-48 object-cover">
                                <div class="absolute top-4 right-4 bg-green-600 text-white px-3 py-1 rounded-full text-sm">
                                    Featured
                                </div>
                            </div>
                            <div class="p-6">
                                <h3 class="text-xl font-semibold mb-2"><?php echo htmlspecialchars($supplier['name']); ?></h3>
                                <p class="text-gray-600 mb-4"><?php echo htmlspecialchars($supplier['description']); ?></p>
                                <div class="flex items-center space-x-4 mb-4">
                                    <div class="flex text-yellow-400">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <?php if ($i <= $supplier['rating']): ?>
                                                <i class="fas fa-star"></i>
                                            <?php else: ?>
                                                <i class="far fa-star"></i>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                    </div>
                                    <span class="text-gray-600"><?php echo number_format($supplier['rating'], 1); ?> (<?php echo $supplier['reviews_count']; ?> reviews)</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <a href="supplier_details.php?id=<?php echo $supplier['id']; ?>" 
                                       class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                                        View Details
                                    </a>
                                    <button onclick="contactSupplier(<?php echo $supplier['id']; ?>)"
                                            class="border border-green-600 text-green-600 px-4 py-2 rounded hover:bg-green-50">
                                        Contact
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- All Suppliers Section -->
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold">Our Verified Suppliers</h1>
            <div class="flex space-x-4">
                <select id="categoryFilter" class="border rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo htmlspecialchars($category); ?>">
                            <?php echo htmlspecialchars(ucfirst($category)); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <select id="businessTypeFilter" class="border rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">All Business Types</option>
                    <?php foreach ($business_types as $type): ?>
                        <option value="<?php echo htmlspecialchars($type); ?>">
                            <?php echo htmlspecialchars(ucfirst($type)); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <select id="sortOrder" class="border rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="name_asc">Name (A-Z)</option>
                    <option value="name_desc">Name (Z-A)</option>
                    <option value="rating_desc">Highest Rated</option>
                    <option value="established_desc">Most Established</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($suppliers as $supplier): ?>
                <div class="supplier-card bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow" 
                     data-category="<?php echo htmlspecialchars($supplier['category']); ?>"
                     data-business-type="<?php echo htmlspecialchars($supplier['business_type']); ?>">
                    <div class="flex items-start space-x-4">
                        <img src="../<?php echo htmlspecialchars($supplier['logo_url']); ?>"
                            alt="<?php echo htmlspecialchars($supplier['name']); ?>"
                            class="w-24 h-24 object-cover rounded-lg">
                        <div class="flex-grow">
                            <h3 class="text-xl font-semibold mb-2"><?php echo htmlspecialchars($supplier['name']); ?></h3>
                            <p class="text-gray-600 mb-2"><?php echo htmlspecialchars($supplier['description']); ?></p>
                            
                            <!-- Business Details -->
                            <div class="text-sm text-gray-600 mb-2">
                                <p><i class="fas fa-building mr-2"></i> <?php echo htmlspecialchars(ucfirst($supplier['business_type'])); ?></p>
                                <p><i class="fas fa-map-marker-alt mr-2"></i> <?php echo htmlspecialchars($supplier['location']); ?></p>
                                <p><i class="fas fa-calendar-alt mr-2"></i> Established: <?php echo htmlspecialchars($supplier['established_year']); ?></p>
                            </div>

                            <!-- Rating and Reviews -->
                            <div class="flex items-center space-x-4 mb-2">
                                <div class="flex items-center">
                                    <div class="flex text-yellow-400">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <?php if ($i <= $supplier['rating']): ?>
                                                <i class="fas fa-star"></i>
                                            <?php elseif ($i - 0.5 <= $supplier['rating']): ?>
                                                <i class="fas fa-star-half-alt"></i>
                                            <?php else: ?>
                                                <i class="far fa-star"></i>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                    </div>
                                    <span class="ml-2 text-gray-600"><?php echo number_format($supplier['rating'], 1); ?>
                                        (<?php echo $supplier['reviews_count']; ?> reviews)</span>
                                </div>
                            </div>

                            <!-- Contact Information -->
                            <div class="text-sm text-gray-600 mb-2">
                                <p><i class="fas fa-user mr-2"></i> <?php echo htmlspecialchars($supplier['contact_person']); ?></p>
                                <p><i class="fas fa-phone mr-2"></i> <?php echo htmlspecialchars($supplier['phone']); ?></p>
                                <p><i class="fas fa-envelope mr-2"></i> <?php echo htmlspecialchars($supplier['email']); ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col space-y-2 mt-4">
                        <a href="supplier_details.php?id=<?php echo $supplier['id']; ?>"
                            class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 text-center">
                            View Details
                        </a>
                        <button onclick="contactSupplier(<?php echo $supplier['id']; ?>)"
                            class="border border-green-600 text-green-600 px-4 py-2 rounded hover:bg-green-50">
                            Contact
                        </button>
                        <button onclick="requestQuote(<?php echo $supplier['id']; ?>)"
                            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Request Quote
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <!-- Contact Modal -->
    <div id="contactModal" class="fixed inset-0 bg-black bg-opacity-50 hidden">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white rounded-lg p-8 max-w-md w-full">
                <h3 class="text-xl font-semibold mb-4">Contact Supplier</h3>
                <form id="contactForm" class="space-y-4">
                    <input type="hidden" id="supplierId" name="supplier_id">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Your Name</label>
                        <input type="text" name="name" required 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Your Email</label>
                        <input type="email" name="email" required 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Subject</label>
                        <input type="text" name="subject" required 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Message</label>
                        <textarea name="message" required rows="4" 
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500"></textarea>
                    </div>
                    <div class="flex justify-end space-x-4">
                        <button type="button" onclick="closeContactModal()" 
                                class="px-4 py-2 border rounded-md hover:bg-gray-50">Cancel</button>
                        <button type="submit" 
                                class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Send</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Quote Request Modal -->
    <div id="quoteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white rounded-lg p-8 max-w-md w-full">
                <h3 class="text-xl font-semibold mb-4">Request Quote</h3>
                <form id="quoteForm" class="space-y-4">
                    <input type="hidden" id="quoteSupplierId" name="supplier_id">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Your Name</label>
                        <input type="text" name="name" required 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Your Email</label>
                        <input type="email" name="email" required 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Product Details</label>
                        <textarea name="product_details" required rows="4" 
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500"
                                  placeholder="Please provide details about the products you're interested in, including quantities and specifications"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Additional Requirements</label>
                        <textarea name="requirements" rows="4" 
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500"
                                  placeholder="Any specific requirements or questions"></textarea>
                    </div>
                    <div class="flex justify-end space-x-4">
                        <button type="button" onclick="closeQuoteModal()" 
                                class="px-4 py-2 border rounded-md hover:bg-gray-50">Cancel</button>
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Filter and sort functionality
        const categoryFilter = document.getElementById('categoryFilter');
        const businessTypeFilter = document.getElementById('businessTypeFilter');
        const sortOrder = document.getElementById('sortOrder');
        const supplierCards = document.querySelectorAll('.supplier-card');

        function filterAndSortSuppliers() {
            const selectedCategory = categoryFilter.value;
            const selectedBusinessType = businessTypeFilter.value;
            const selectedSort = sortOrder.value;

            // Convert NodeList to Array for sorting
            const cardsArray = Array.from(supplierCards);

            // Filter cards
            cardsArray.forEach(card => {
                const categoryMatch = !selectedCategory || card.dataset.category === selectedCategory;
                const businessTypeMatch = !selectedBusinessType || card.dataset.businessType === selectedBusinessType;
                
                if (categoryMatch && businessTypeMatch) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });

            // Sort visible cards
            const visibleCards = cardsArray.filter(card => card.style.display !== 'none');
            const sortedCards = visibleCards.sort((a, b) => {
                const nameA = a.querySelector('h3').textContent;
                const nameB = b.querySelector('h3').textContent;
                const ratingA = parseFloat(a.querySelector('.text-gray-600').textContent);
                const ratingB = parseFloat(b.querySelector('.text-gray-600').textContent);
                const establishedA = parseInt(a.querySelector('.fa-calendar-alt').nextSibling.textContent.split(':')[1]);
                const establishedB = parseInt(b.querySelector('.fa-calendar-alt').nextSibling.textContent.split(':')[1]);

                switch (selectedSort) {
                    case 'name_asc':
                        return nameA.localeCompare(nameB);
                    case 'name_desc':
                        return nameB.localeCompare(nameA);
                    case 'rating_desc':
                        return ratingB - ratingA;
                    case 'established_desc':
                        return establishedB - establishedA;
                    default:
                        return 0;
                }
            });

            // Reorder cards in the DOM
            const container = document.querySelector('.grid');
            sortedCards.forEach(card => container.appendChild(card));
        }

        categoryFilter.addEventListener('change', filterAndSortSuppliers);
        businessTypeFilter.addEventListener('change', filterAndSortSuppliers);
        sortOrder.addEventListener('change', filterAndSortSuppliers);

        // Contact Modal Functions
        function contactSupplier(supplierId) {
            document.getElementById('supplierId').value = supplierId;
            document.getElementById('contactModal').classList.remove('hidden');
        }

        function closeContactModal() {
            document.getElementById('contactModal').classList.add('hidden');
        }

        // Quote Modal Functions
        function requestQuote(supplierId) {
            document.getElementById('quoteSupplierId').value = supplierId;
            document.getElementById('quoteModal').classList.remove('hidden');
        }

        function closeQuoteModal() {
            document.getElementById('quoteModal').classList.add('hidden');
        }

        // Handle contact form submission
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            // In a real application, this would send the data to the server
            alert('Message sent successfully!');
            closeContactModal();
        });

        // Handle quote form submission
        document.getElementById('quoteForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            // In a real application, this would send the data to the server
            alert('Quote request submitted successfully!');
            closeQuoteModal();
        });

        // Add loading animation for images
        document.querySelectorAll('img').forEach(img => {
            img.addEventListener('load', function() {
                this.classList.add('loaded');
            });
        });
    </script>

    <style>
        /* Add loading animation for images */
        img {
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }
        img.loaded {
            opacity: 1;
        }

        /* Add hover effects for cards */
        .hover-card {
            transition: transform 0.3s ease-in-out;
        }
        .hover-card:hover {
            transform: translateY(-5px);
        }

        /* Add custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Add focus styles for form elements */
        input:focus, textarea:focus, select:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2);
        }
    </style>
</body>

</html>