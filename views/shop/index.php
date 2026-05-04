<?php
session_start();
require '../../includes/db.php';
// Fetch slides from database
$stmt = $pdo->prepare("SELECT * FROM hero_slides ORDER BY sort_order ASC");
$stmt->execute();
$slides = $stmt->fetchAll();
$slideCount = count($slides);
$promoStmt = $pdo->prepare("SELECT * FROM promo_categories LIMIT 3");
$promoStmt->execute();
$promos = $promoStmt->fetchAll();
$bestStmt = $pdo->prepare("SELECT * FROM products WHERE is_bestseller = 1 ORDER BY created_at DESC");
$bestStmt->execute();
$bestsellers = $bestStmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aushadhi | Home</title>

    <!-- 1. Google Fonts CDN -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- Note: Instrument Sans is used as the public version of Google Sans -->
    <link href="https://googleapis.com" rel="stylesheet">

    <!-- 2. Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- 3. Tailwind Configuration (Integrating your fonts) -->
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        // Standard clean look (Google Sans style)
                        'sans': ['"Instrument Sans"', 'sans-serif'],
                        // Elegant Serif for titles (Bestsellers, etc)
                        'display': ['"Playfair Display"', 'serif'],
                        // Traditional aesthetic for logos
                        'yatra': ['"Yatra One"', 'system-ui'],
                    }
                }
            }
        }
    </script>

    <!-- 4. Custom CSS (Loaded last for highest priority) -->
    <link rel="stylesheet" href="../../assets/css/st_index.css">
</head>


<body class="font-sans bg-white transition-colors duration-300">
    <?php
    include '../../includes/header.php';
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
    </div>>

    <!-- Hero Slider -->
    <div class="relative w-full overflow-hidden bg-white transition-colors duration-300">
        <div id="slider-container" class="relative w-full h-[350px] md:h-[400px]">
            <div id="slider" class="flex transition-transform duration-700 ease-in-out h-full">
                <?php if ($slideCount > 0): ?>
                    <?php foreach ($slides as $slide): ?>
                        <a href="<?php echo htmlspecialchars($slide['link_url']); ?>"
                            class="slide-item block h-full shrink-0 w-full">
                            <picture class="w-full h-full">
                                <!-- Desktop Image -->
                                <source media="(min-width: 768px)"
                                    srcset="<?php echo htmlspecialchars($slide['image_url_desktop']); ?>">
                                <!-- Mobile Image -->
                                <img src="<?php echo htmlspecialchars($slide['image_url_mobile']); ?>" alt="Banner"
                                    class="w-full h-full object-cover">
                            </picture>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Fallback if database is empty -->
                    <div class="w-full h-full flex items-center justify-center bg-gray-100">
                        <p class="text-gray-400">No banners available</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Navigation Dots -->
        <div class="flex justify-center gap-2 mt-3 mb-6 bg-white transition-colors duration-300">
            <?php for ($i = 0; $i < $slideCount; $i++): ?>
                <button onclick="manualNav(<?php echo $i; ?>)"
                    class="dot w-1.5 h-1.5 rounded-full bg-gray-200 transition-all duration-300">
                </button>
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
    <!-- Summer Self Care Section -->
    <div class="max-w-7xl mx-auto px-4 py-10 bg-white dark:bg-[#0d1117] transition-colors duration-300">
        <div class="flex items-center gap-2 mb-6">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">Summer Self Care Store</h2>
            <span class="text-gray-400">›</span>
        </div>

        <div
            class="flex md:grid md:grid-cols-3 gap-4 md:gap-6 overflow-x-auto md:overflow-x-visible pb-4 md:pb-0 scrollbar-hide snap-x snap-mandatory">
            <?php foreach ($promos as $promo): ?>
                <a href="<?php echo htmlspecialchars($promo['link_url']); ?>"
                    class="min-w-[85%] md:min-w-0 snap-center shrink-0 relative overflow-hidden rounded-2xl border border-gray-50 dark:border-gray-800 shadow-sm transition hover:shadow-md duration-300">
                    <img src="<?php echo htmlspecialchars($promo['bg_image']); ?>" class="w-full h-48 md:h-64 object-cover"
                        alt="<?php echo htmlspecialchars($promo['title']); ?>">
                </a>
            <?php endforeach; ?>

            <?php if (empty($promos)): ?>
                <!-- Placeholder in case DB is empty -->
                <div class="col-span-3 text-center py-10 text-gray-400">No promo categories found.</div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bestsellers Carousel Section -->

    <div class="max-w-7xl mx-auto px-4 py-10 bg-white dark:bg-[#0d1117] transition-colors duration-300">
        <!-- Title Section -->
        <div class="mb-8">
            <h2 class="text-2xl font-display font-bold text-gray-800 dark:text-gray-200">
                Bestsellers
            </h2>
        </div>


        <div id="bestseller-grid"
            class="flex overflow-x-auto gap-4 md:gap-6 scroll-smooth snap-x snap-mandatory scrollbar-hide pb-4">
            <?php foreach ($bestsellers as $product): ?>
                <!-- Card Container with Shadow and rounded corners -->
                <div
                    class="snap-start shrink-0 w-[75%] md:w-[calc(25%-18px)] group relative flex flex-col bg-white dark:bg-[#161b22] rounded-2xl p-3 transition-all border border-gray-100 dark:border-gray-800 shadow-md hover:shadow-xl">

                    <!-- Top Badges -->
                    <div class="absolute top-4 left-4 right-4 z-10 flex justify-between items-start pointer-events-none">
                        <?php if ($product['old_price'] > $product['price']): ?>
                            <span
                                class="bg-green-50 text-green-700 text-[10px] font-bold px-2 py-1 rounded-md border border-green-100">
                                Save Rs. <?php echo ($product['old_price'] - $product['price']); ?>
                            </span>
                        <?php endif; ?>
                        <span
                            class="bg-gray-100 text-gray-700 text-[9px] font-bold px-2 py-1 rounded uppercase tracking-wider">
                            Best Seller
                        </span>
                    </div>

                    <!-- Product Image: object-cover ensures no squeezing, overflow-hidden handles the cutout -->
                    <div class="aspect-square w-full mb-4 overflow-hidden rounded-xl bg-gray-50 dark:bg-gray-800 relative">
                        <img src="<?php echo htmlspecialchars($product['image_path']); ?>"
                            alt="<?php echo htmlspecialchars($product['name']); ?>" class="w-full h-full object-cover">
                        <!-- object-cover is key here -->

                        <!-- Wishlist Icon -->
                        <button
                            class="absolute bottom-3 right-3 p-1.5 bg-white/80 rounded-full shadow-sm text-gray-800 hover:text-red-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"
                                    stroke-width="1.5" />
                            </svg>
                        </button>
                    </div>

                    <!-- Product Info -->
                    <div class="flex-grow flex flex-col px-1">
                        <h3
                            class="text-[15px] font-semibold text-gray-900 dark:text-gray-100 leading-snug mb-1 line-clamp-2">
                            <?php echo htmlspecialchars($product['name']); ?>
                        </h3>

                        <p class="text-[12px] text-green-700 font-medium mb-3">
                            <?php echo htmlspecialchars($product['short_description']); ?>
                        </p>

                        <!-- Rating Row -->
                        <div class="flex items-center gap-1 mb-4">
                            <span class="text-orange-400 text-xs">★</span>
                            <span
                                class="text-xs font-bold text-gray-700 dark:text-gray-300"><?php echo $product['rating']; ?></span>
                            <span class="text-xs text-gray-400">(<?php echo $product['reviews_count']; ?>)</span>
                        </div>

                        <!-- Price and Add Button -->
                        <div class="flex items-center justify-between mt-auto">
                            <!-- Price Container -->
                            <div class="flex items-center gap-1.5">
                                <!-- Actual Price: Green Border & Subtle Green Tint -->
                                <span
                                    class="border border-green-200 bg-green-50/50 dark:bg-green-900/10 dark:border-green-800/50 px-3 py-1.5 rounded-full text-sm font-black text-gray-900 dark:text-gray-100">
                                    ₹<?php echo number_format($product['price']); ?>
                                </span>

                                <?php if ($product['old_price'] > $product['price']): ?>
                                    <!-- Old Price: Grey Border & Line-through -->
                                    <span
                                        class="border border-gray-200 dark:border-gray-700 px-2 py-1.5 rounded-full text-xs text-gray-400 line-through font-light">
                                        <?php echo number_format($product['old_price']); ?>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <!-- Add Button -->
                            <button
                                class="bg-[#2D7A5D] hover:bg-[#235e47] text-white text-xs font-bold px-6 py-2.5 rounded-xl transition-all active:scale-95 shadow-sm">
                                Add
                            </button>
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Bottom Controls (As designed previously) -->
        <div class="relative flex items-center justify-center mt-8">
            <a href="bestseller.php"
                class="text-sm font-bold text-gray-900 dark:text-gray-100 hover:text-gray-600 dark:hover:text-gray-400 underline underline-offset-8 decoration-2 transition-colors">
                View All →
            </a>

            <div class="hidden md:flex gap-3 absolute right-0">
                <button onclick="scrollGrid('bestseller-grid', -1)"
                    class="p-2 border border-gray-200 dark:border-gray-700 rounded-full hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                    <svg class="w-4 h-4 text-gray-800 dark:text-gray-200" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path d="M15 19l-7-7 7-7" stroke-width="2" />
                    </svg>
                </button>
                <button onclick="scrollGrid('bestseller-grid', 1)"
                    class="p-2 border border-gray-200 dark:border-gray-700 rounded-full hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                    <svg class="w-4 h-4 text-gray-800 dark:text-gray-200" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path d="M9 5l7 7-7 7" stroke-width="2" />
                    </svg>
                </button>
            </div>
        </div>
    </div>


    <!-- Recommended Section -->
    <div class="max-w-7xl mx-auto px-4 py-10 bg-white dark:bg-[#0d1117] transition-colors duration-300">

        <!-- Title Section -->
        <div class="mb-8">
            <h2 class="text-2xl font-display font-bold text-gray-800 dark:text-gray-200">
                Recommended for you
            </h2>
        </div>

        <!-- The Grid Container (Unique ID: recommend-grid) -->
        <div id="recommend-grid"
            class="flex overflow-x-auto gap-4 md:gap-6 scroll-smooth snap-x snap-mandatory scrollbar-hide pb-4">
            <?php foreach ($bestsellers as $product): ?>
                <div
                    class="snap-start shrink-0 w-[75%] md:w-[calc(25%-18px)] group relative flex flex-col bg-white dark:bg-[#161b22] rounded-2xl p-3 transition-all border border-gray-100 dark:border-gray-800 shadow-md hover:shadow-xl">

                    <!-- Top Badges -->
                    <div class="absolute top-4 left-4 right-4 z-10 flex justify-between items-start pointer-events-none">
                        <?php if ($product['old_price'] > $product['price']): ?>
                            <span
                                class="bg-green-50 text-green-700 text-[10px] font-bold px-2 py-1 rounded-md border border-green-100">
                                Save Rs. <?php echo ($product['old_price'] - $product['price']); ?>
                            </span>
                        <?php endif; ?>
                        <span
                            class="bg-gray-100 text-gray-700 text-[9px] font-bold px-2 py-1 rounded uppercase tracking-wider">
                            Best Seller
                        </span>
                    </div>

                    <!-- Product Image -->
                    <div class="aspect-square w-full mb-4 overflow-hidden rounded-xl bg-gray-50 dark:bg-gray-800 relative">
                        <img src="<?php echo htmlspecialchars($product['image_path']); ?>"
                            alt="<?php echo htmlspecialchars($product['name']); ?>" class="w-full h-full object-cover">

                        <!-- Wishlist Icon -->
                        <button
                            class="absolute bottom-3 right-3 p-1.5 bg-white/80 rounded-full shadow-sm text-gray-800 hover:text-red-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"
                                    stroke-width="1.5" />
                            </svg>
                        </button>
                    </div>

                    <!-- Product Info -->
                    <div class="flex-grow flex flex-col px-1">
                        <h3
                            class="text-[15px] font-semibold text-gray-900 dark:text-gray-100 leading-snug mb-1 line-clamp-2">
                            <?php echo htmlspecialchars($product['name']); ?>
                        </h3>
                        <p class="text-[12px] text-green-700 font-medium mb-3">
                            <?php echo htmlspecialchars($product['short_description']); ?>
                        </p>

                        <!-- Rating Row -->
                        <div class="flex items-center gap-1 mb-4">
                            <span class="text-orange-400 text-xs">★</span>
                            <span
                                class="text-xs font-bold text-gray-700 dark:text-gray-300"><?php echo $product['rating']; ?></span>
                            <span class="text-xs text-gray-400">(<?php echo $product['reviews_count']; ?>)</span>
                        </div>

                        <!-- Price and Add Button -->
                        <div class="flex items-center justify-between mt-auto">
                            <div class="flex items-center gap-1.5">
                                <span
                                    class="border border-green-200 bg-green-50/50 dark:bg-green-900/10 dark:border-green-800/50 px-3 py-1.5 rounded-full text-sm font-black text-gray-900 dark:text-gray-100">
                                    ₹<?php echo number_format($product['price']); ?>
                                </span>
                                <?php if ($product['old_price'] > $product['price']): ?>
                                    <span
                                        class="border border-gray-200 dark:border-gray-700 px-2 py-1.5 rounded-full text-xs text-gray-400 line-through font-light">
                                        <?php echo number_format($product['old_price']); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <button
                                class="bg-[#2D7A5D] hover:bg-[#235e47] text-white text-xs font-bold px-6 py-2.5 rounded-xl transition-all active:scale-95 shadow-sm">
                                Add
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Bottom Controls -->
        <div class="relative flex items-center justify-center mt-8">
            <a href="bestseller.php"
                class="text-sm font-bold text-gray-900 dark:text-gray-100 hover:text-gray-600 dark:hover:text-gray-400 underline underline-offset-8 decoration-2 transition-colors">
                View All →
            </a>
            <div class="hidden md:flex gap-3 absolute right-0">
                <!-- Updated to trigger recommend-grid -->
                <button onclick="scrollGrid('recommend-grid', -1)"
                    class="p-2 border border-gray-200 dark:border-gray-700 rounded-full hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                    <svg class="w-4 h-4 text-gray-800 dark:text-gray-200" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path d="M15 19l-7-7 7-7" stroke-width="2" />
                    </svg>
                </button>
                <button onclick="scrollGrid('recommend-grid', 1)"
                    class="p-2 border border-gray-200 dark:border-gray-700 rounded-full hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                    <svg class="w-4 h-4 text-gray-800 dark:text-gray-200" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path d="M9 5l7 7-7 7" stroke-width="2" />
                    </svg>
                </button>
            </div>
        </div>
    </div>


    <!-- Business Promotion Video Section -->
<div class="max-w-7xl mx-auto px-4 py-6">
    <!-- Manual control: h-[140px] for mobile | md:h-[220px] for PC -->
    <div class="relative w-full h-[140px] md:h-[220px] overflow-hidden rounded-[20px] shadow-md border border-gray-100 dark:border-gray-800"> 
        
        <!-- Video Element -->
        <video autoplay muted loop playsinline class="w-full h-full object-cover"> 
            <source src="../../storage/videos/family.mp4" type="video/mp4"> 
            Your browser does not support the video tag. 
        </video>

        <!-- Overlay Content -->
        <div class="absolute inset-0 bg-black/30 flex flex-col justify-center px-6 md:px-12"> 
            <span class="text-white/90 text-[10px] md:text-xs uppercase tracking-[0.2em] mb-1">Our Story</span> 
            <h2 class="text-white text-xl md:text-3xl font-display font-bold leading-tight">
                Purely Himalayan.
            </h2> 
        </div> 
    </div>
</div>

    <div class="max-w-7xl mx-auto px-4 py-10 bg-white dark:bg-[#0d1117] transition-colors duration-300">

        <!-- Title Section -->
        <div class="mb-8">
            <h2 class="text-2xl font-display font-bold text-gray-800 dark:text-gray-200">
                Recommended for you
            </h2>
        </div>

        <!-- The Grid Container (Unique ID: recommend-grid) -->
        <div id="recommend-grid"
            class="flex overflow-x-auto gap-4 md:gap-6 scroll-smooth snap-x snap-mandatory scrollbar-hide pb-4">
            <?php foreach ($bestsellers as $product): ?>
                <div
                    class="snap-start shrink-0 w-[75%] md:w-[calc(25%-18px)] group relative flex flex-col bg-white dark:bg-[#161b22] rounded-2xl p-3 transition-all border border-gray-100 dark:border-gray-800 shadow-md hover:shadow-xl">

                    <!-- Top Badges -->
                    <div class="absolute top-4 left-4 right-4 z-10 flex justify-between items-start pointer-events-none">
                        <?php if ($product['old_price'] > $product['price']): ?>
                            <span
                                class="bg-green-50 text-green-700 text-[10px] font-bold px-2 py-1 rounded-md border border-green-100">
                                Save Rs. <?php echo ($product['old_price'] - $product['price']); ?>
                            </span>
                        <?php endif; ?>
                        <span
                            class="bg-gray-100 text-gray-700 text-[9px] font-bold px-2 py-1 rounded uppercase tracking-wider">
                            Best Seller
                        </span>
                    </div>

                    <!-- Product Image -->
                    <div class="aspect-square w-full mb-4 overflow-hidden rounded-xl bg-gray-50 dark:bg-gray-800 relative">
                        <img src="<?php echo htmlspecialchars($product['image_path']); ?>"
                            alt="<?php echo htmlspecialchars($product['name']); ?>" class="w-full h-full object-cover">

                        <!-- Wishlist Icon -->
                        <button
                            class="absolute bottom-3 right-3 p-1.5 bg-white/80 rounded-full shadow-sm text-gray-800 hover:text-red-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"
                                    stroke-width="1.5" />
                            </svg>
                        </button>
                    </div>

                    <!-- Product Info -->
                    <div class="flex-grow flex flex-col px-1">
                        <h3
                            class="text-[15px] font-semibold text-gray-900 dark:text-gray-100 leading-snug mb-1 line-clamp-2">
                            <?php echo htmlspecialchars($product['name']); ?>
                        </h3>
                        <p class="text-[12px] text-green-700 font-medium mb-3">
                            <?php echo htmlspecialchars($product['short_description']); ?>
                        </p>

                        <!-- Rating Row -->
                        <div class="flex items-center gap-1 mb-4">
                            <span class="text-orange-400 text-xs">★</span>
                            <span
                                class="text-xs font-bold text-gray-700 dark:text-gray-300"><?php echo $product['rating']; ?></span>
                            <span class="text-xs text-gray-400">(<?php echo $product['reviews_count']; ?>)</span>
                        </div>

                        <!-- Price and Add Button -->
                        <div class="flex items-center justify-between mt-auto">
                            <div class="flex items-center gap-1.5">
                                <span
                                    class="border border-green-200 bg-green-50/50 dark:bg-green-900/10 dark:border-green-800/50 px-3 py-1.5 rounded-full text-sm font-black text-gray-900 dark:text-gray-100">
                                    ₹<?php echo number_format($product['price']); ?>
                                </span>
                                <?php if ($product['old_price'] > $product['price']): ?>
                                    <span
                                        class="border border-gray-200 dark:border-gray-700 px-2 py-1.5 rounded-full text-xs text-gray-400 line-through font-light">
                                        <?php echo number_format($product['old_price']); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <button
                                class="bg-[#2D7A5D] hover:bg-[#235e47] text-white text-xs font-bold px-6 py-2.5 rounded-xl transition-all active:scale-95 shadow-sm">
                                Add
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Bottom Controls -->
        <div class="relative flex items-center justify-center mt-8">
            <a href="bestseller.php"
                class="text-sm font-bold text-gray-900 dark:text-gray-100 hover:text-gray-600 dark:hover:text-gray-400 underline underline-offset-8 decoration-2 transition-colors">
                View All →
            </a>
            <div class="hidden md:flex gap-3 absolute right-0">
                <!-- Updated to trigger recommend-grid -->
                <button onclick="scrollGrid('recommend-grid', -1)"
                    class="p-2 border border-gray-200 dark:border-gray-700 rounded-full hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                    <svg class="w-4 h-4 text-gray-800 dark:text-gray-200" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path d="M15 19l-7-7 7-7" stroke-width="2" />
                    </svg>
                </button>
                <button onclick="scrollGrid('recommend-grid', 1)"
                    class="p-2 border border-gray-200 dark:border-gray-700 rounded-full hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                    <svg class="w-4 h-4 text-gray-800 dark:text-gray-200" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path d="M9 5l7 7-7 7" stroke-width="2" />
                    </svg>
                </button>
            </div>
        </div>
    </div>



    <?php include '../../includes/footer.php'; ?>
    <?php include '../../includes/mobile_nav.php'; ?>
    <script src="../../assets/js/js_index.js"></script>
</body>

</html>