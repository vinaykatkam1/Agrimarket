<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgriMarket - Quality Seeds & Fertilizers</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Custom Animations */
        @keyframes gradient {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .gradient-bg {
            background: linear-gradient(-45deg, #10B981, #059669, #047857, #065F46);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
        }

        .hover-card {
            transition: all 0.3s ease;
            transform-style: preserve-3d;
        }

        .hover-card:hover {
            transform: translateY(-5px) rotateX(5deg);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .product-card {
            transition: all 0.3s ease;
        }

        .product-card:hover {
            transform: scale(1.02);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .ripple {
            position: relative;
            overflow: hidden;
        }

        .ripple:after {
            content: "";
            display: block;
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            pointer-events: none;
            background-image: radial-gradient(circle, #fff 10%, transparent 10.01%);
            background-repeat: no-repeat;
            background-position: 50%;
            transform: scale(10, 10);
            opacity: 0;
            transition: transform .5s, opacity 1s;
        }

        .ripple:active:after {
            transform: scale(0, 0);
            opacity: .3;
            transition: 0s;
        }

        .fade-in {
            animation: fadeIn 1s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .testimonial-card {
            transition: all 0.3s ease;
        }

        .testimonial-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .search-container {
            transition: all 0.3s ease;
        }

        .search-container:focus-within {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <nav class="container mx-auto px-6 py-3">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <a href="index.php"
                        class="text-2xl font-bold text-green-600 flex items-center hover:text-green-700 transition-colors">
                        <img alt="AgriMarket Logo" src="logo.avif"
                            class="w-12 h-12 mr-2 rounded-full transform hover:rotate-12 transition-transform">
                        AgriMarket
                    </a>
                </div>
                <div class="flex items-center space-x-8">
                    <a href="index.php" class="text-gray-600 hover:text-green-600 transition-colors">Home</a>
                    <a href="php/products.php" class="text-gray-600 hover:text-green-600 transition-colors">Products</a>
                    <a href="php/suppliers.php"
                        class="text-gray-600 hover:text-green-600 transition-colors">Suppliers</a>
                    <div class="flex items-center space-x-4">
                        <a href="php/cart.php" class="text-gray-600 hover:text-green-600 transition-colors relative">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                            <span id="cart-count"
                                class="absolute -top-2 -right-2 bg-green-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">0</span>
                        </a>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="php/logout.php"
                                class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition-colors ripple">Logout</a>
                        <?php else: ?>
                            <a href="php/login.php"
                                class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition-colors ripple">Login</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="gradient-bg text-white py-20 relative overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="container mx-auto px-6 relative">
            <div class="max-w-3xl fade-in">
                <h1 class="text-5xl font-bold mb-6">Quality Seeds & Fertilizers for Better Harvests</h1>
                <p class="text-xl mb-8">Connect directly with verified suppliers to access premium agricultural inputs
                    at competitive prices.</p>
                <div class="flex space-x-4">
                    <a href="php/products.php"
                        class="bg-white text-green-600 px-6 py-3 rounded-md font-semibold hover:bg-gray-100 transition-colors ripple">Browse
                        Products</a>
                    <a href="php/suppliers.php"
                        class="border-2 border-white text-white px-6 py-3 rounded-md font-semibold hover:bg-green-700 transition-colors ripple">Find
                        Suppliers</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Search Bar -->
    <div class="container mx-auto px-6 -mt-0">
        <div class="bg-white rounded-lg shadow-lg p-4 flex search-container">
            <input type="text" placeholder="Search for seeds, fertilizers, or suppliers..."
                class="flex-1 px-4 py-2 border rounded-l-md focus:outline-none focus:ring-2 focus:ring-green-600 transition-all">
            <button
                class="bg-green-600 text-white px-6 py-2 rounded-r-md hover:bg-green-700 transition-colors ripple">Search</button>
        </div>
    </div>

    <!-- Features -->
    <section class="py-16">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-lg shadow-md text-center hover-card">
                    <div
                        class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 transform hover:scale-110 transition-transform">
                        <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Quality Assurance</h3>
                    <p class="text-gray-600">All products are verified for quality and effectiveness by agricultural
                        experts.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md text-center hover-card">
                    <div
                        class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 transform hover:scale-110 transition-transform">
                        <i class="fas fa-handshake text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Direct Connections</h3>
                    <p class="text-gray-600">Connect directly with suppliers to eliminate middlemen and reduce costs.
                    </p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md text-center hover-card">
                    <div
                        class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 transform hover:scale-110 transition-transform">
                        <i class="fas fa-seedling text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Wide Selection</h3>
                    <p class="text-gray-600">Access a diverse range of seeds, fertilizers, and other agricultural
                        inputs.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl font-bold text-center mb-12">Browse Categories</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <a href="php/products.php?category=seeds"
                    class="bg-white rounded-lg shadow-md overflow-hidden hover-card group">
                    <div class="relative overflow-hidden">
                        <img src="seeds.png" alt="Organic Seeds"
                            class="w-full h-48 object-cover transform group-hover:scale-110 transition-transform duration-300">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    </div>
                    <div class="p-6">
                        <div
                            class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center mb-4 transform group-hover:rotate-12 transition-transform">
                            <i class="fas fa-seedling text-yellow-600"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Seeds</h3>
                        <p class="text-gray-600">High-quality seeds for various crops</p>
                    </div>
                </a>
                <a href="php/products.php?category=fertilizers"
                    class="bg-white rounded-lg shadow-md overflow-hidden hover-card group">
                    <div class="relative overflow-hidden">
                        <img src="th.jpg" alt="Premium Fertilizer"
                            class="w-full h-48 object-cover transform group-hover:scale-110 transition-transform duration-300">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    </div>
                    <div class="p-6">
                        <div
                            class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mb-4 transform group-hover:rotate-12 transition-transform">
                            <i class="fas fa-tractor text-green-600"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Fertilizers</h3>
                        <p class="text-gray-600">Organic and chemical fertilizers</p>
                    </div>
                </a>
                <a href="php/products.php?category=organic"
                    class="bg-white rounded-lg shadow-md overflow-hidden hover-card group">
                    <div class="relative overflow-hidden">
                        <img src="kk.webp" alt="Organic Compost"
                            class="w-full h-48 object-cover transform group-hover:scale-110 transition-transform duration-300">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    </div>
                    <div class="p-6">
                        <div
                            class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mb-4 transform group-hover:rotate-12 transition-transform">
                            <i class="fas fa-leaf text-green-600"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Organic Products</h3>
                        <p class="text-gray-600">100% organic farming inputs</p>
                    </div>
                </a>
                <a href="php/products.php?category=seedlings"
                    class="bg-white rounded-lg shadow-md overflow-hidden hover-card group">
                    <div class="relative overflow-hidden">
                        <img src="seedling.jpg" alt="Seeds and Seedlings"
                            class="w-full h-48 object-cover transform group-hover:scale-110 transition-transform duration-300">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    </div>
                    <div class="p-6">
                        <div
                            class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mb-4 transform group-hover:rotate-12 transition-transform">
                            <i class="fas fa-seedling text-green-600"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Seedlings</h3>
                        <p class="text-gray-600">Ready-to-plant seedlings</p>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="py-16">
        <div class="container mx-auto px-6">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-bold">Featured Products</h2>
                <a href="php/products.php"
                    class="text-green-600 hover:text-green-700 font-semibold flex items-center group">
                    View All Products
                    <i class="fas fa-arrow-right ml-2 transform group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Featured Product 1 -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden product-card">
                    <div class="relative overflow-hidden">
                        <img src="earth.jpg" alt="Dr. Earth Premium Gold Fertilizer"
                            class="w-full h-48 object-cover transform hover:scale-110 transition-transform duration-300">
                        <div class="absolute top-4 right-4">
                            <span class="bg-green-600 text-white text-xs px-2 py-1 rounded-full">Featured</span>
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="text-lg font-semibold mb-2">Dr. Earth Premium Gold</h3>
                        <p class="text-gray-600 mb-2">Organic All Purpose Fertilizer (4-4-4)</p>
                        <div class="flex justify-between items-center">
                            <span class="text-green-600 font-bold">₹2499</span>
                            <button
                                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition-colors ripple">Add
                                to Cart</button>
                        </div>
                    </div>
                </div>
                <!-- Featured Product 2 -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden product-card">
                    <div class="relative overflow-hidden">
                        <img src="kk.jpg" alt="Growise Organic Vegetable Compost"
                            class="w-full h-48 object-cover transform hover:scale-110 transition-transform duration-300">
                        <div class="absolute top-4 right-4">
                            <span class="bg-green-600 text-white text-xs px-2 py-1 rounded-full">Featured</span>
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="text-lg font-semibold mb-2">Growise Organic Compost</h3>
                        <p class="text-gray-600 mb-2">Peat-free vegetable compost</p>
                        <div class="flex justify-between items-center">
                            <span class="text-green-600 font-bold">₹1999</span>
                            <button
                                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition-colors ripple">Add
                                to Cart</button>
                        </div>
                    </div>
                </div>
                <!-- Featured Product 3 -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden product-card">
                    <div class="relative overflow-hidden">
                        <img src="tomato.jpg" alt="Organic Tomato Seeds"
                            class="w-full h-48 object-cover transform hover:scale-110 transition-transform duration-300">
                        <div class="absolute top-4 right-4">
                            <span class="bg-green-600 text-white text-xs px-2 py-1 rounded-full">Featured</span>
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="text-lg font-semibold mb-2">Organic Tomato Seeds</h3>
                        <p class="text-gray-600 mb-2">Chadwick Cherry Heirloom</p>
                        <div class="flex justify-between items-center">
                            <span class="text-green-600 font-bold">₹499</span>
                            <button
                                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition-colors ripple">Add
                                to Cart</button>
                        </div>
                    </div>
                </div>
                <!-- Featured Product 4 -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden product-card">
                    <div class="relative overflow-hidden">
                        <img src="iffco.jpg" alt="NPK Fertilizer"
                            class="w-full h-48 object-cover transform hover:scale-110 transition-transform duration-300">
                        <div class="absolute top-4 right-4">
                            <span class="bg-green-600 text-white text-xs px-2 py-1 rounded-full">Featured</span>
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="text-lg font-semibold mb-2">IFFCO NPK Fertilizer</h3>
                        <p class="text-gray-600 mb-2">10-26-26 Premium Blend</p>
                        <div class="flex justify-between items-center">
                            <span class="text-green-600 font-bold">₹2999</span>
                            <button
                                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition-colors ripple">Add
                                to Cart</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl font-bold text-center mb-12">What Our Customers Say</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-lg shadow-md testimonial-card">
                    <div class="flex items-center mb-4">
                        <img src="customer1.jpg" alt="Customer" class="w-12 h-12 rounded-full mr-4">
                        <div>
                            <h4 class="font-semibold">John Doe</h4>
                            <div class="flex text-yellow-400">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-600">"The quality of seeds I received was exceptional. Great germination rate
                        and excellent customer service!"</p>
                </div>
                <!-- Similar testimonial cards -->
            </div>
        </div>
    </section>

    <!-- Newsletter -->
    <section class="py-16 bg-green-600 text-white">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold mb-4">Subscribe to Our Newsletter</h2>
            <p class="text-xl mb-8">Stay updated with the latest products and offers</p>
            <form class="max-w-md mx-auto flex">
                <input type="email" placeholder="Enter your email"
                    class="flex-1 px-4 py-3 rounded-l-md focus:outline-none text-gray-900">
                <button type="submit"
                    class="bg-white text-green-600 px-6 py-3 rounded-r-md font-semibold hover:bg-gray-100 transition-colors ripple">Subscribe</button>
            </form>
        </div>
    </section>

    <script>
        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Add ripple effect to buttons
        document.querySelectorAll('.ripple').forEach(button => {
            button.addEventListener('click', function (e) {
                let x = e.clientX - e.target.offsetLeft;
                let y = e.clientY - e.target.offsetTop;

                let ripple = document.createElement('span');
                ripple.style.left = `${x}px`;
                ripple.style.top = `${y}px`;

                this.appendChild(ripple);

                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
        });

        // Add loading animation to images
        document.querySelectorAll('img').forEach(img => {
            img.addEventListener('load', function () {
                this.classList.add('loaded');
            });
        });

        // Update cart count
        function updateCartCount() {
            fetch('php/update_cart_count.php')
                .then(response => response.json())
                .then(data => {
                    const cartCount = document.getElementById('cart-count');
                    if (cartCount) {
                        cartCount.textContent = data.count;
                    }
                })
                .catch(error => console.error('Error updating cart count:', error));
        }

        // Update cart count on page load
        updateCartCount();

        // Update cart count every 5 seconds
        setInterval(updateCartCount, 5000);
    </script>
</body>

</html>