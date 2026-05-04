<?php
// 1. Initial Logic & Database
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../../includes/db.php';            // Provides $pdo
require_once '../../includes/index_support.php';  // Provides $conn, $featured_result, $all_result
include_once '../../includes/config.php';         // Provides $site_name, etc.

// 2. Navbar Variables
$cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
$is_logged_in = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $site_name ?> | Himalayan Botanical Luxury</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://googleapis.com">
    <link rel="preconnect" href="https://gstatic.com" crossorigin>
    <link
        href="https://googleapis.com/css2?family=Inter:wght@300;400;500&family=Playfair+Display:wght@400;500;600&family=Yatra+One&display=swap"
        rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cloudflare.com">

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { bg: "#faf9f6", dark: "#111111", accent: "#9c845c" },
                    fontFamily: { luxury: ['Playfair Display', 'serif'], nepali: ['Yatra One', 'cursive'], sans: ['Inter', 'sans-serif'] }
                }
            }
        } 
    </script>

    <link rel="stylesheet" href="/aushadhi-platform/assets/css/st_index.css">
</head>

<body>
    <!-- Hero Banner Slider -->
<div class="relative w-full h-[300px] md:h-[450px] overflow-hidden group">
    <!-- Slides Container -->
    <div id="slider" class="flex transition-transform duration-500 ease-out h-full">
        <!-- Slide 1 -->
        <a href="product.php?id=1" class="min-w-full h-full relative">
            <img src="banner1.jpg" class="w-full h-full object-cover" alt="Ayurveda Meets Beauty">
            <div class="absolute inset-0 bg-black/20 flex flex-col justify-center px-12 text-white">
                <h2 class="text-4xl font-bold mb-2">Beautiful You Edit</h2>
                <p class="text-lg opacity-90">Where Ayurveda meets everyday beauty</p>
                <span class="mt-4 inline-block bg-white text-black px-6 py-2 rounded-full font-bold w-fit">UP TO 30% OFF</span>
            </div>
        </a>
        
        <!-- Slide 2 -->
        <a href="product.php?id=2" class="min-w-full h-full relative">
            <img src="banner2.jpg" class="w-full h-full object-cover" alt="Natural Skincare">
            <div class="absolute inset-0 bg-black/20 flex flex-col justify-center px-12 text-white">
                <h2 class="text-4xl font-bold mb-2">Glow Naturally</h2>
                <p class="text-lg opacity-90">Himalayan herbs for a radiant look</p>
                <span class="mt-4 inline-block bg-white text-black px-6 py-2 rounded-full font-bold w-fit">SHOP NOW</span>
            </div>
        </a>
    </div>

    <!-- Navigation Dots -->
    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2">
        <button onclick="goToSlide(0)" class="dot w-3 h-3 rounded-full bg-white/50 hover:bg-white transition-all"></button>
        <button onclick="goToSlide(1)" class="dot w-3 h-3 rounded-full bg-white/50 hover:bg-white transition-all"></button>
    </div>
</div>


    <!-- Include Navbar -->
    <?php include '../../includes/navbar.php'; ?>

    <!-- Success Toast (Moved from support file to here) -->
    <?php if (isset($_GET['registration']) && $_GET['registration'] === 'success'): ?>
        <div id="success-toast" class="fixed top-24 left-1/2 -translate-x-1/2 z-50 animate-bounce">
            <div
                class="bg-[#2D4030] text-white px-8 py-4 rounded-2xl shadow-2xl border border-stone-700 flex items-center gap-4">
                <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-check text-sm"></i>
                </div>
                <div>
                    <p class="text-[10px] uppercase tracking-[0.2em] font-bold opacity-70">Welcome</p>
                    <p class="luxury-font text-sm">Account Verified Successfully</p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- HERO -->
    <section class="hero-bg h-screen flex items-center px-8 md:px-20 text-white relative overflow-hidden"
        style="background-image: url('https://unsplash.com'); background-size: cover; background-position: center;">
        <div class="absolute inset-0 bg-black/40"></div>
        <div class="max-w-4xl relative z-10">
            <p class="font-nepali text-3xl text-green-200 mb-5 reveal"> <?= $hero_subtitle ?> </p>
            <h2 class="font-luxury text-6xl md:text-8xl leading-[0.9] mb-8 reveal" style="transition-delay: 100ms;">
                <?= $hero_title ?> </h2>
            <p class="max-w-xl text-gray-200 text-lg mb-8 reveal" style="transition-delay: 200ms;">
                <?= $hero_description ?> </p>
            <div class="reveal" style="transition-delay: 300ms;">
                <a href="#products"
                    class="border-b border-white pb-2 uppercase tracking-[0.3em] text-sm hover:text-accent hover:border-accent transition-colors">
                    Explore Collection </a>
            </div>
        </div>
    </section>

    <!-- ABOUT -->
    <section id="about" class="py-32 px-6 bg-bg">
        <div class="max-w-6xl mx-auto grid md:grid-cols-2 gap-16 items-center">
            <div class="reveal">
                <h2 class="font-luxury text-5xl leading-tight mb-6 italic text-dark"> Crafted from Nepal's botanical
                    heritage. </h2>
            </div>
            <div class="reveal" style="transition-delay: 200ms;">
                <p class="text-gray-600 leading-relaxed text-lg"> Aushadhi sources rare herbs from Himalayan regions of
                    Nepal and transforms them into premium wellness goods. Every product is handcrafted in small batches
                    with intentional design and timeless rituals. </p>
            </div>
        </div>
    </section>

    <!-- FEATURED SECTION -->
    <section id="featured" class="py-24 bg-white px-6">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-20 reveal">
                <p class="font-nepali text-3xl text-accent">विशेष चयन</p>
                <h2 class="font-luxury text-5xl mt-4 text-dark">Curated Collection</h2>
            </div>
            <div class="grid md:grid-cols-2 gap-10">
                <?php if ($featured_result && $featured_result->num_rows > 0): ?>
                    <?php $delay = 0;
                    while ($row = $featured_result->fetch_assoc()): ?>
                        <div class="product-card block reveal group relative" style="transition-delay: <?= $delay ?>ms;">
                            <a href="product/<?= $row['slug']; ?>" class="block group">
                                <div
                                    class="aspect-[4/5] overflow-hidden mb-6 bg-[#f5f3ee] flex items-center justify-center relative transition-all duration-300">
                                    <img src="<?= $row['image_url'] ?? 'placeholder.jpg' ?>"
                                        class="w-full h-full object-contain transition-transform duration-500 ease-out group-hover:scale-110"
                                        alt="<?= htmlspecialchars($row['name']) ?>">
                                    <div
                                        class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors duration-300">
                                    </div>
                                    <div
                                        class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 pointer-events-none group-hover:pointer-events-auto z-20">
                                        <button
                                            onclick="event.preventDefault(); event.stopPropagation(); addToCart(<?= $row['id'] ?>)"
                                            class="bg-white text-dark text-[10px] uppercase tracking-[0.2em] font-bold px-8 py-4 shadow-2xl transform translate-y-4 group-hover:translate-y-0 transition-all duration-300 hover:bg-dark hover:text-white pointer-events-auto">
                                            + Add to Cart </button>
                                    </div>
                                </div>
                                <h3 class="font-luxury text-3xl mb-2 text-dark"><?= htmlspecialchars($row['name']) ?></h3>
                                <p class="text-gray-500 italic mb-3"><?= htmlspecialchars($row['nepali_name'] ?? '') ?></p>
                                <p class="uppercase tracking-[0.2em] text-sm text-dark font-medium">Rs.
                                    <?= number_format($row['price']) ?> </p>
                            </a>
                        </div>
                        <?php $delay += 200; endwhile; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- ALL PRODUCTS (APOTHECARY) -->
    <section id="products" class="py-32 px-6 bg-bg">
        <div class="max-w-7xl mx-auto">
            <div class="mb-20 reveal">
                <h2 class="font-luxury text-6xl text-dark">Apothecary</h2>
            </div>
            <?php
            $products = [];
            if ($all_result) {
                while ($row = $all_result->fetch_assoc()) {
                    $products[] = $row;
                }
            }
            $has_many = count($products) > 6;
            ?>
            <div id="product-wrapper" class="relative transition-all duration-700 ease-in-out overflow-hidden"
                style="<?= $has_many ? 'max-height: 1100px;' : '' ?>">
                <div class="grid md:grid-cols-3 gap-14">
                    <?php if (!empty($products)): ?>
                        <?php $delay = 0;
                        foreach ($products as $row): ?>
                            <div class="product-card block reveal group relative" style="transition-delay: <?= $delay ?>ms;">
                                <a href="product/<?= $row['slug']; ?>" class="block group">
                                    <div
                                        class="aspect-[3/4] overflow-hidden bg-[#f5f3ee] mb-6 flex items-center justify-center relative transition-all duration-300">
                                        <img src="<?= $row['image_url'] ?? 'placeholder.jpg' ?>"
                                            class="w-full h-full object-contain transition-transform duration-500 ease-out group-hover:scale-110"
                                            alt="<?= htmlspecialchars($row['name']) ?>">
                                        <div
                                            class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors duration-300">
                                        </div>
                                        <div
                                            class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 pointer-events-none group-hover:pointer-events-auto z-20">
                                            <button
                                                onclick="event.preventDefault(); event.stopPropagation(); addToCart(<?= $row['id'] ?>)"
                                                class="bg-white text-dark text-[10px] uppercase tracking-[0.2em] font-bold px-6 py-3 shadow-2xl transform translate-y-4 group-hover:translate-y-0 transition-all duration-300 hover:bg-dark hover:text-white pointer-events-auto">
                                                + Add to Cart </button>
                                        </div>
                                    </div>
                                    <div class="flex flex-col gap-1">
                                        <h3 class="font-luxury text-2xl text-dark"><?= htmlspecialchars($row['name']) ?></h3>
                                        <div class="flex items-center gap-2">
                                            <div class="flex text-dark text-xs"><?= renderStars($row['avg_rating'] ?? 0) ?>
                                            </div>
                                            <span
                                                class="text-[10px] uppercase tracking-[0.2em] text-gray-400 font-medium">(<?= $row['review_count'] ?? 0 ?>)</span>
                                        </div>
                                        <p class="uppercase tracking-[0.2em] text-sm text-dark font-medium mt-1">Rs.
                                            <?= number_format($row['price']) ?> </p>
                                    </div>
                                </a>
                            </div>
                            <?php $delay += 100; endforeach; ?>
                    <?php endif; ?>
                </div>
                <?php if ($has_many): ?>
                    <div id="fade-overlay"
                        class="absolute bottom-0 left-0 w-full h-64 bg-gradient-to-t from-bg via-bg/80 to-transparent z-10 flex items-end justify-center pb-10">
                        <button onclick="showAllProducts()"
                            class="bg-white border border-dark px-10 py-4 uppercase tracking-[0.2em] text-sm hover:bg-dark hover:text-white transition-all duration-300 shadow-lg">See
                            More</button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <?php include '../../includes/footer.php'; ?>

    <!-- EXTERNAL JS -->
    <script src="/aushadhi-platform/assets/js/js_product.js" defer></script>
    <script>
        let currentSlide = 0;
const slider = document.getElementById('slider');
const dots = document.querySelectorAll('.dot');
const totalSlides = dots.length;

function updateSlider() {
    slider.style.transform = `translateX(-${currentSlide * 100}%)`;
    // Update dots
    dots.forEach((dot, index) => {
        dot.style.background = index === currentSlide ? 'white' : 'rgba(255,255,255,0.5)';
        dot.style.width = index === currentSlide ? '24px' : '12px'; // Optional effect
    });
}

function nextSlide() {
    currentSlide = (currentSlide + 1) % totalSlides;
    updateSlider();
}

function goToSlide(index) {
    currentSlide = index;
    updateSlider();
}

// Initial dot state
updateSlider();

// Auto-slide every 5 seconds
setInterval(nextSlide, 5000);

    </script>

    <style>
        /* Clean UI override */
        .product-card:hover {
            transform: none !important;
        }
    </style>
</body>

</html>