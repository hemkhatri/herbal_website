<!-- product.php -->
<?php

// We receive 'neem-soap' from the .htaccess rewrite
$pid = isset($_GET['pid']) ? $_GET['pid'] : '';

if (empty($pid)) {
    header("Location: index.php");
    exit;
}

// Check database for that slug
$stmt = $pdo->prepare("SELECT * FROM products WHERE slug = ?");
$stmt->execute([$pid]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    die("Product not found.");
}

// Set the ID so your other queries (ingredients, reviews) work
$id = $product['id']; 

// --- Fetch Ingredients ---
$stmt = $pdo->prepare("SELECT ingredient_name FROM product_ingredients WHERE product_id = ?");
$stmt->execute([$id]);
$product['ingredients'] = $stmt->fetchAll(PDO::FETCH_COLUMN);

// --- Fetch Images (Main vs Description) ---
$stmt = $pdo->prepare("SELECT image_url, image_type FROM product_images WHERE product_id = ?");
$stmt->execute([$id]);
$all_imgs = $stmt->fetchAll(PDO::FETCH_ASSOC);

$product['images'] = array_column(array_filter($all_imgs, fn($i) => $i['image_type'] == 'main'), 'image_url');
$product['desc_images'] = array_column(array_filter($all_imgs, fn($i) => $i['image_type'] == 'description'), 'image_url');

// --- Fetch Reviews ---
$stmt = $pdo->prepare("SELECT user_name as user, rating, comment, review_date as date FROM product_reviews WHERE product_id = ?");
$stmt->execute([$id]);
$product['reviews'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

// --- Fetch Ritual Steps ---
$stmt = $pdo->prepare("SELECT step_number, instruction FROM product_rituals WHERE product_id = ? ORDER BY step_number ASC");
$stmt->execute([$id]);
$product['rituals'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

// --- Calculate Review Stats ---
$totalReviews = count($product['reviews']);
$sumRatings = array_sum(array_column($product['reviews'], 'rating'));
$averageRating = $totalReviews > 0 ? round($sumRatings / $totalReviews, 1) : 0;

$starCounts = array_count_values(array_column($product['reviews'], 'rating'));
$distribution = [];
for ($i = 5; $i >= 1; $i--) {
    $count = $starCounts[$i] ?? 0;
    $distribution[$i] = $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0;
}
?>

<!-- end of product.php -->