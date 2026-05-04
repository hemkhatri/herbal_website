<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aushadhi | Home</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // REQUIRED: Enables manual dark mode toggling with Tailwind CDN
        tailwind.config = {
            darkMode: 'class'
        }
    </script>
    <link rel="stylesheet" href="assets/css/st_index.css">
</head>

<body class="font-sans bg-white transition-colors duration-300">
    <?php
    include 'includes/header.php';

    $bestsellers = [
        ["id" => 1, "name" => "Naynam Day-long Kohl Kajal", "desc" => "11 Hours Stay | Black", "price" => 205, "old_price" => 225, "rating" => 4.8, "reviews" => 183, "save" => 20, "img" => "kajal.jpg"],
        ["id" => 2, "name" => "Aloe Vera Skin Gel", "desc" => "Pure & Organic | 200ml", "price" => 150, "old_price" => 180, "rating" => 4.5, "reviews" => 95, "save" => 30, "img" => "aloe.jpg"],
        ["id" => 3, "name" => "Ayurvedic Hair Oil", "desc" => "Root Strength Formula", "price" => 340, "old_price" => 400, "rating" => 4.9, "reviews" => 210, "save" => 60, "img" => "oil.jpg"],
        ["id" => 4, "name" => "Vitamin C Face Wash", "desc" => "Glow & Brightening", "price" => 199, "old_price" => 250, "rating" => 4.7, "reviews" => 120, "save" => 51, "img" => "wash.jpg"],
        ["id" => 5, "name" => "Sunscreen SPF 50", "desc" => "Matte Finish | No White Cast", "price" => 450, "old_price" => 499, "rating" => 4.6, "reviews" => 340, "save" => 49, "img" => "sun.jpg"],
        ["id" => 6, "name" => "Herbal Green Tea", "desc" => "Detox & Immunity", "price" => 280, "old_price" => 320, "rating" => 4.4, "reviews" => 67, "save" => 40, "img" => "tea.jpg"],
        ["id" => 7, "name" => "Moisturizing Cream", "desc" => "Deep Hydration | 24h", "price" => 120, "old_price" => 150, "rating" => 4.8, "reviews" => 88, "save" => 30, "img" => "cream.jpg"]
    ];
    ?>

    <!-- Mobile Search Bar Trigger -->
    <div class="block md:hidden px-4 py-3 bg-white transition-colors duration-300">
        <div onclick="toggleSearch()"
            class="flex items-center bg-gray-50 rounded-xl px-4 py-2.5 text-gray-400 border border-gray-100 cursor-pointer">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <span class="text-sm">Search medicines...</span>
        </div>
    </div>

    <!-- Hero Slider -->
    <div class="relative w-full overflow-hidden bg-white transition-colors duration-300">
        <div id="slider-container" class="relative w-full h-[350px] md:h-[400px]">
            <div id="slider" class="flex transition-transform duration-700 ease-in-out h-full">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <a href="product.php?id=<?php echo $i; ?>" class="slide-item block h-full">
                        <picture class="w-full h-full">
                            <source media="(min-width: 768px)"
                                srcset="https://images.unsplash.com/photo-1769089220494-686431afcee9">
                            <img src="https://plus.unsplash.com/premium_photo-1776726086605-4f7c3664fb50"
                                alt="Slide <?php echo $i; ?>" class="slide-img">
                        </picture>
                    </a>
                <?php endfor; ?>
            </div>
        </div>
        <div class="flex justify-center gap-2 mt-3 mb-6 bg-white transition-colors duration-300">
            <?php for ($i = 0; $i < 5; $i++): ?>
                <button onclick="manualNav(<?php echo $i; ?>)"
                    class="dot w-1.5 h-1.5 rounded-full bg-gray-200 transition-all duration-300"></button>
            <?php endfor; ?>
        </div>
    </div>

    <!-- Announcement Ticker -->
    <div
        class="w-full bg-white text-black py-3 mt-2 overflow-hidden whitespace-nowrap select-none border-y border-gray-100 transition-colors duration-300">
        <div class="inline-block animate-marquee pause-on-hover">
            <span class="px-4 text-sm font-medium">✨ Get 20% Off on your first order! Use code: FIRST20</span>
            <span class="px-4 text-sm font-medium">🚚 Free delivery on orders above Rs. 500!</span>
            <span class="px-4 text-sm font-medium">💊 Authentic Medicines delivered to your doorstep.</span>
            <span class="px-4 text-sm font-medium">✨ Get 20% Off on your first order! Use code: FIRST20</span>
            <span class="px-4 text-sm font-medium">🚚 Free delivery on orders above Rs. 500!</span>
            <span class="px-4 text-sm font-medium">💊 Authentic Medicines delivered to your doorstep.</span>
        </div>
    </div>

    <!-- Summer Self Care Section -->
    <div class="max-w-7xl mx-auto px-4 py-10 bg-white transition-colors duration-300">
        <div class="flex items-center gap-2 mb-6">
            <h2 class="text-xl font-bold text-gray-800">Summer Self Care Store</h2>
            <span class="text-gray-400">›</span>
        </div>
        <div
            class="flex md:grid md:grid-cols-3 gap-4 md:gap-6 overflow-x-auto md:overflow-x-visible pb-4 md:pb-0 scrollbar-hide snap-x snap-mandatory">
            <a href="category.php?id=bestsellers"
                class="min-w-[85%] md:min-w-0 snap-center shrink-0 relative overflow-hidden rounded-2xl border border-gray-50 shadow-sm transition hover:shadow-md duration-300">
                <img src="bestseller_bg.jpg" class="w-full h-48 md:h-64 object-cover" alt="Bestsellers">
            </a>
            <a href="category.php?id=discounts"
                class="min-w-[85%] md:min-w-0 snap-center shrink-0 relative overflow-hidden rounded-2xl border border-gray-50 shadow-sm transition hover:shadow-md duration-300">
                <img src="discounts_bg.jpg" class="w-full h-48 md:h-64 object-cover" alt="Discounts">
            </a>
            <a href="category.php?id=luxury"
                class="min-w-[85%] md:min-w-0 snap-center shrink-0 relative overflow-hidden rounded-2xl border border-gray-50 shadow-sm transition hover:shadow-md duration-300">
                <img src="luxury_bg.jpg" class="w-full h-48 md:h-64 object-cover" alt="Luxury Products">
            </a>
        </div>
    </div>

    <!-- Bestsellers Carousel Section -->
    <div class="max-w-7xl mx-auto px-4 py-10 bg-white dark:bg-[#0d1117] transition-colors duration-300">
    <div class="flex items-center justify-between mb-8">
        <a href="bestseller.php" class="group flex items-center gap-1">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200 group-hover:text-green-700 transition-colors">Bestsellers</h2>
            <span class="text-gray-400 font-light text-2xl group-hover:text-green-700 transition-colors">›</span>
        </a>
        <div class="hidden md:flex gap-2">
            <button onclick="scrollGrid('bestseller-grid', -1)" class="p-2 border border-gray-200 dark:border-gray-700 rounded-full hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7" stroke-width="2" /></svg>
            </button>
            <button onclick="scrollGrid('bestseller-grid', 1)" class="p-2 border border-gray-200 dark:border-gray-700 rounded-full hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2" /></svg>
            </button>
        </div>
    </div>

    <div id="bestseller-grid" class="flex overflow-x-auto gap-4 md:gap-6 scroll-smooth snap-x snap-mandatory scrollbar-hide pb-4">
        <?php foreach ($bestsellers as $product): ?>
        <div class="snap-start shrink-0 w-[75%] md:w-[calc(25%-18px)] group relative flex flex-col bg-white dark:bg-[#0d1117] rounded-xl p-4 transition-all border border-transparent hover:border-gray-100 dark:hover:border-gray-800 hover:shadow-sm">
            
            <!-- Product Image Container -->
            <div class="aspect-square w-full mb-4 overflow-hidden rounded-lg bg-gray-50/30 dark:bg-gray-800/30 flex items-center justify-center relative">
                <img src="<?php echo $product['img']; ?>" alt="<?php echo $product['name']; ?>" class="w-[85%] h-[85%] object-contain transition-transform group-hover:scale-105">
                
                <!-- Wishlist Heart: Now inside image at Bottom-Right -->
                <button class="absolute bottom-2 right-2 z-10 text-gray-400 hover:text-red-500 transition-colors bg-white/80 dark:bg-black/20 p-1.5 rounded-full backdrop-blur-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" stroke-width="1.5" />
                    </svg>
                </button>
            </div>

            <!-- Product Info -->
            <div class="flex-grow flex flex-col relative">
                <h3 class="text-[15px] font-medium text-gray-900 dark:text-gray-100 leading-tight mb-1">
                    <?php echo $product['name']; ?></h3>
                <p class="text-[12px] text-green-700 dark:text-green-500 font-medium mb-6">
                    <?php echo $product['desc']; ?></p>

                <!-- Price & Add -->
                <div class="mt-auto">
                    <div class="mb-2">
                        <span class="bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 text-[10px] px-2 py-0.5 rounded border border-green-100 dark:border-green-800/30 font-medium">Limited Time Deal</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="border border-gray-200 dark:border-gray-700 rounded-full px-4 py-1">
                                <span class="text-sm font-bold text-gray-900 dark:text-gray-100">Rs. <?php echo $product['price']; ?></span>
                            </div>
                            <span class="text-xs text-gray-400 line-through font-light">Rs. <?php echo $product['old_price']; ?></span>
                        </div>
                        <button class="bg-[#2D7A5D] hover:bg-[#235e47] text-white text-xs font-bold px-5 py-2 rounded-full transition-colors">Add</button>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>

        <!-- Magnetic "View All" Card -->
        <?php if (count($bestsellers) > 6): ?>
        <a href="bestseller.php" class="snap-start shrink-0 w-[45%] md:w-[15%] flex flex-col items-center justify-center border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-xl p-4 hover:border-green-500 hover:bg-green-50 dark:hover:bg-gray-800/50 transition-all group bg-white dark:bg-transparent">
            <div class="w-10 h-10 rounded-full bg-green-50 dark:bg-green-900/20 flex items-center justify-center mb-2 group-hover:bg-[#2D7A5D] transition-colors">
                <svg class="w-6 h-6 text-[#2D7A5D] group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
            </div>
            <span class="text-sm font-bold text-gray-700 dark:text-gray-300 group-hover:text-green-700 text-center">View All</span>
        </a>
        <?php endif; ?>
    </div>
</div>






    <?php include '../../includes/mobile_nav.php'; ?>
    <script src="assets/js/js_index.js"></script>
</body>

</html>