<?php

// TOP OF FILE
if (isset($_POST['preview_mode'])) {
    $product = [
        'name' => $_POST['name'],
        'nepali_name' => $_POST['nepali_name'],
        'price' => $_POST['price'],
        'description' => $_POST['description'],
        'images' => $_POST['main_images'],
        'desc_images' => $_POST['desc_images'],
        'ingredients' => explode(',', $_POST['ingredients']),
        'rituals' => []
    ];
    foreach ($_POST['rituals'] as $idx => $inst) {
        if($inst) $product['rituals'][] = ['step_number' => $idx+1, 'instruction' => $inst];
    }
    // Set dummy variables for reviews
    $averageRating = 5.0; $totalReviews = 0; $distribution = [];
} else {
    // ... your current DATABASE fetch code ...


require '../../includes/db.php';
require '../../includes/products.php';
//navbar
$cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
$is_logged_in = isset($_SESSION['user_id']);

// 2. Include the Nav
include '../../includes/config.php'; 
include '../../includes/navbar.php'; 
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['name'] ?? 'Product') ?> | Luxury Aushadhi</title>

    <!-- SEO & META TAGS -->
    <meta name="description" content="<?= htmlspecialchars(strip_tags(substr($product['description'] ?? 'Discover premium Himalayan herbal products at Aushadhi.', 0, 160))) ?>">
    <meta name="keywords" content="<?= htmlspecialchars($product['name'] ?? '') ?>, Himalayan herbs, Ayurveda, organic, luxury skincare, Nepal">
    <meta name="robots" content="index, follow">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="product">
    <meta property="og:title" content="<?= htmlspecialchars($product['name'] ?? '') ?> | Luxury Aushadhi">
    <meta property="og:description" content="<?= htmlspecialchars(strip_tags(substr($product['description'] ?? 'Discover premium Himalayan herbal products at Aushadhi.', 0, 160))) ?>">
    <meta property="og:image" content="<?= htmlspecialchars($product['images'][0] ?? '') ?>">
    <meta property="og:site_name" content="Aushadhi">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= htmlspecialchars($product['name'] ?? '') ?> | Luxury Aushadhi">
    <meta name="twitter:description" content="<?= htmlspecialchars(strip_tags(substr($product['description'] ?? 'Discover premium Himalayan herbal products.', 0, 160))) ?>">
    <meta name="twitter:image" content="<?= htmlspecialchars($product['images'][0] ?? '') ?>">

    <!-- JSON-LD STRUCTURED DATA FOR GOOGLE RICH SNIPPETS -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org/",
      "@type": "Product",
      "name": "<?= htmlspecialchars($product['name'] ?? '') ?>",
      "image": [
        <?php if (!empty($product['images'])): ?>
            <?php foreach($product['images'] as $index => $img): ?>
              "<?= htmlspecialchars($img) ?>"<?= $index < count($product['images']) - 1 ? ',' : '' ?>
            <?php endforeach; ?>
        <?php endif; ?>
      ],
      "description": "<?= htmlspecialchars(strip_tags($product['description'] ?? '')) ?>",
      "brand": {
        "@type": "Brand",
        "name": "Aushadhi"
      },
      "offers": {
        "@type": "Offer",
        "url": "<?= htmlspecialchars("https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]") ?>",
        "priceCurrency": "NPR",
        "price": "<?= $product['price'] ?? '0' ?>",
        "availability": "https://schema.org/InStock"
      }
      <?php if (!empty($totalReviews) && $totalReviews > 0): ?>
      ,"aggregateRating": {
        "@type": "AggregateRating",
        "ratingValue": "<?= $averageRating ?>",
        "reviewCount": "<?= $totalReviews ?>"
      }
      <?php endif; ?>
    }
    </script>

    <!-- SPEED OPTIMIZATION: Preconnect to critical domains -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdn.tailwindcss.com">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Fixed Google Fonts URL -->
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700;900&family=Inter:wght@300;400;600;700&family=Playfair+Display:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
    
    <!-- External CSS -->
    <link rel="stylesheet" href="/aushadhi-platform/assets/css/st_product.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        cinzel: ['Cinzel', 'serif'],
                        inter: ['Inter', 'sans-serif'],
                        luxury: ['Playfair Display', 'serif']
                    },
                },
            },
        }
    </script>
</head>

<body class="bg-[#FDFCF9]">


    <div class="max-w-7xl mx-auto p-6 md:p-12 lg:p-20 pt-8 lg:pt-12">
        <!-- TOP SECTION: MAIN PRODUCT VIEW -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start mb-20">
            <!-- LEFT: GALLERY -->
            <div class="relative">
                <div class="zoom-area group" id="container">
                    <div id="zoom-lens"></div>
                    <div class="slider-frame" id="slider-frame">
                        <?php if (!empty($product['images'])): ?>
                            <?php foreach ($product['images'] as $i => $img): ?>
                                <!-- SPEED: High fetchpriority for LCP (first image), lazy load others -->
                                <img src="<?= htmlspecialchars($img) ?>" 
                                     class="slide-img <?= $i == 0 ? 'slide-active' : '' ?>"
                                     data-index="<?= $i ?>"
                                     alt="<?= htmlspecialchars($product['name'] ?? '') ?> image <?= $i + 1 ?>"
                                     <?= $i == 0 ? 'fetchpriority="high"' : 'loading="lazy"' ?>>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <button onclick="changeSlide(-1)" aria-label="Previous image"
                        class="absolute left-6 top-1/2 -translate-y-1/2 bg-white/90 p-3 rounded-full shadow-lg z-30 opacity-0 group-hover:opacity-100 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <button onclick="changeSlide(1)" aria-label="Next image"
                        class="absolute right-6 top-1/2 -translate-y-1/2 bg-white/90 p-3 rounded-full shadow-lg z-30 opacity-0 group-hover:opacity-100 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
                <div id="zoom-result"></div>
                <div class="flex justify-center gap-2 mt-8">
                    <?php if (!empty($product['images'])): ?>
                        <?php foreach ($product['images'] as $i => $img): ?>
                            <div class="dot <?= $i == 0 ? 'active' : '' ?>" onclick="goToSlide(<?= $i ?>)" aria-label="Go to image <?= $i + 1 ?>"></div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- RIGHT: QUICK INFO -->
            <div class="pt-4 lg:pt-10">
                <nav class="flex items-center gap-2 text-[10px] uppercase tracking-widest text-gray-400 mb-4 font-bold" aria-label="Breadcrumb">
                    <a href="index.php" class="hover:text-gray-600">Home</a> <span>/</span> 
                    <a href="index.php#products" class="hover:text-gray-600">Body Care</a> <span>/</span> 
                    <span class="text-green-800"><?= htmlspecialchars($product['nepali_name'] ?? '') ?></span>
                </nav>
                
                <!-- NEPALI NAME INTEGRATION -->
                <h2 class="text-green-800 font-cinzel text-xl md:text-2xl mb-2"><?= htmlspecialchars($product['nepali_name'] ?? '') ?></h2>
                
                <h1 class="luxury-font text-5xl md:text-6xl font-bold mb-6 text-gray-900 leading-tight">
                    <?= htmlspecialchars($product['name'] ?? '') ?>
                </h1>

                <div class="flex items-center gap-6 mb-8">
                    <!-- Dynamic Price -->
                    <p class="text-4xl luxury-font italic text-gray-800">
                        Rs. <?= number_format($product['price'] ?? 0, 2) ?>
                    </p>

                    <div class="flex items-center gap-1 text-yellow-500 text-sm">
                        <!-- Dynamic Stars -->
                        <?php
                        $rating = round($averageRating ?? 0);
                        for ($i = 1; $i <= 5; $i++):
                            ?>
                            <span class="<?= $i <= $rating ? 'text-yellow-500' : 'text-gray-300' ?>">★</span>
                        <?php endfor; ?>

                        <!-- Dynamic Review Count -->
                        <span class="text-gray-400 ml-2 font-sans text-xs">
                            (<?= $totalReviews ?? 0 ?> Reviews)
                        </span>
                    </div>
                </div>

                <div class="flex flex-wrap gap-4 mb-10">
                    <button
                        class="flex-1 min-w-[200px] bg-[#2D4030] text-white py-5 rounded-2xl font-bold uppercase tracking-widest text-[11px] hover:bg-black transition-all">Add
                        to Collection</button>
                    <button
                        class="flex-1 min-w-[200px] border border-black py-5 rounded-2xl font-bold uppercase tracking-widest text-[11px] hover:bg-black hover:text-white transition-all">Wishlist</button>
                </div>

                <div class="space-y-6">
                    <h3 class="text-[10px] uppercase tracking-[0.3em] font-black text-gray-400">Core Ingredients</h3>
                    <div class="flex flex-wrap gap-3">
                        <?php if (!empty($product['ingredients'])): ?>
                            <?php foreach ($product['ingredients'] as $ing): ?>
                                <span class="px-5 py-2 bg-white border border-gray-100 rounded-full text-xs text-gray-600 shadow-sm"><?= htmlspecialchars($ing) ?></span>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- BOTTOM SECTION: FULL DESCRIPTION WITH SHOW MORE -->
        <div class="border-t border-gray-100 pt-16">
            <h2 class="font-cinzel uppercase tracking-widest text-3xl mb-8">Description</h2>

            <div id="desc-content" class="relative">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 text-gray-500 leading-relaxed text-lg font-light">
                    <div class="lg:col-span-2">
                        <div class="grid grid-cols-1 gap-6 mt-8 mb-8">
                            <?php if (!empty($product['desc_images'])): ?>
                                <?php foreach ($product['desc_images'] as $img): ?>
                                    <!-- SPEED: Lazy load below-the-fold images -->
                                    <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($product['name'] ?? '') ?> details" class="rounded-xl w-full h-auto object-cover shadow-md" loading="lazy">
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>

                        <div class="font-cinzel text-lg tracking-wide text-gray-800 leading-loose">
                            <?= $product['description'] ?? '' ?>
                        </div>
                    </div>

                    <div class="bg-[#F7F5F0] p-8 rounded-2xl h-fit">
                        <h4 class="font-cinzel text-black font-bold mb-4 uppercase text-xs tracking-widest">Direction of
                            Use</h4>
                        <ul class="space-y-4 text-sm">
                            <?php if (!empty($product['rituals'])): ?>
                                <?php foreach ($product['rituals'] as $ritual): ?>
                                    <li class="flex gap-3">
                                        <strong class="font-cinzel"><?= str_pad($ritual['step_number'], 2, '0', STR_PAD_LEFT) ?>.</strong>
                                        <span class="font-sans"><?= htmlspecialchars($ritual['instruction']) ?></span>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
                <div id="desc-gradient"></div>
            </div>

            <button id="toggle-desc" onclick="toggleDescription()"
                class="mx-auto table mt-6 flex items-center gap-2 text-[11px] font-black uppercase tracking-widest text-black border border-black px-8 py-3 transition-all hover:bg-black hover:text-white"
                style="border-radius: 23px;">
                Show Full Description
            </button>
        </div>

    </div>

    <!-- REVIEWS SECTION -->
    <div class="border-t border-gray-100 pt-16 mb-20">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-12 mb-16">
            <!-- Rating Summary Card -->
            <div class="lg:col-span-1 bg-stone-50 p-8 rounded-3xl border border-stone-100">
                <h2 class="luxury-font text-3xl mb-1">Reviews</h2>
                <div class="flex items-center gap-2 mb-6">
                    <span class="text-5xl font-black text-gray-900"><?= $averageRating ?? '0' ?></span>
                    <div>
                        <div class="text-yellow-500 text-sm">
                            <?php
                            $rating = round($averageRating ?? 0);
                            for ($i = 1; $i <= 5; $i++):
                                ?>
                                <span class="<?= $i <= $rating ? 'text-yellow-500' : 'text-gray-200' ?>">★</span>
                            <?php endfor; ?>
                        </div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">
                            <?= $totalReviews ?? 0 ?> Verified Reviews
                        </p>
                    </div>
                </div>

                <!-- Dynamic Star Progress Bars -->
                <div class="space-y-3">
                    <?php if (!empty($distribution)): ?>
                        <?php foreach ($distribution as $star => $percent): ?>
                            <div class="flex items-center gap-3">
                                <span class="text-[10px] font-bold w-3"><?= $star ?></span>
                                <div class="flex-1 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-yellow-500 transition-all duration-1000"
                                        style="width: <?= $percent ?>%"></div>
                                </div>
                                <span class="text-[10px] text-gray-400 w-6"><?= round($percent) ?>%</span>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Reviews List -->
            <div class="lg:col-span-3 space-y-8">
                <div class="flex justify-between items-center border-b border-gray-100 pb-6">
                    <p class="text-xs font-black uppercase tracking-widest text-gray-400">Reviews from customers</p>
                    <button
                        class="bg-black text-white px-6 py-3 rounded-xl text-[9px] font-bold uppercase tracking-widest hover:bg-green-900 transition-colors">Submit
                        Review</button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <?php if (!empty($product['reviews'])): ?>
                        <?php foreach ($product['reviews'] as $rev): ?>
                            <div class="p-6 border-b border-gray-50">
                                <div class="flex justify-between items-start mb-3">
                                    <div class="text-yellow-500 text-[10px] tracking-widest">
                                        <?= str_repeat('★', $rev['rating'] ?? 5) ?><span
                                            class="text-gray-200"><?= str_repeat('★', 5 - ($rev['rating'] ?? 5)) ?></span>
                                    </div>
                                    <span class="text-[10px] text-gray-400 font-medium uppercase"><?= htmlspecialchars($rev['date'] ?? '') ?></span>
                                </div>
                                <h4 class="font-bold text-gray-900 text-sm mb-2"><?= htmlspecialchars($rev['user'] ?? '') ?> <span
                                        class="ml-2 text-[9px] text-green-600 bg-green-50 px-2 py-0.5 rounded">Verified</span>
                                </h4>
                                <p class="text-gray-500 text-sm leading-relaxed">"<?= htmlspecialchars($rev['comment'] ?? '') ?>"</p>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
        <?php include '../../includes/footer.php'; ?>

    <!-- SPEED: Scripts placed at the end of body -->
    <script src="/aushadhi-platform/assets/js/js_product.js" defer></script>
</body>

</html>
