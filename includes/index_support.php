<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* 

|--------------------------------------------------------------------------
| DATABASE CONNECTION (MySQLi)
|--------------------------------------------------------------------------
*/
$conn = new mysqli("localhost", "root", "", "aushadhi_platform");

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

/* 

|--------------------------------------------------------------------------
| HERO & SITE SETTINGS
|--------------------------------------------------------------------------
*/
$hero_title = "The Soul of Herbs";
$hero_subtitle = "हिमाली शुद्धता";
$hero_description = "Rare Himalayan botanicals handcrafted in Nepal for modern luxury wellness.";

/* 

|--------------------------------------------------------------------------
| FEATURED PRODUCTS QUERY
|--------------------------------------------------------------------------
*/
$sql_featured = "
    SELECT p.*, 
    (SELECT image_url FROM product_images WHERE product_id = p.id AND image_type = 'main' LIMIT 1) as image_url,
    (SELECT COUNT(*) FROM product_reviews WHERE product_id = p.id) as review_count,
    (SELECT AVG(rating) FROM product_reviews WHERE product_id = p.id) as avg_rating
    FROM products p 
    WHERE p.is_featured = 1 
    ORDER BY p.id DESC
";
$featured_result = $conn->query($sql_featured);

/* 

|--------------------------------------------------------------------------
| ALL PRODUCTS QUERY (APOTHECARY)
|--------------------------------------------------------------------------
*/
$sql_all = "
    SELECT p.*, 
    (SELECT image_url FROM product_images WHERE product_id = p.id AND image_type = 'main' LIMIT 1) as image_url,
    (SELECT COUNT(*) FROM product_reviews WHERE product_id = p.id) as review_count,
    (SELECT AVG(rating) FROM product_reviews WHERE product_id = p.id) as avg_rating
    FROM products p 
    ORDER BY p.id DESC
";
$all_result = $conn->query($sql_all);

/* 

|--------------------------------------------------------------------------
| STAR RENDER FUNCTION
|--------------------------------------------------------------------------
*/
function renderStars($rating) {
    $rating = round($rating ?: 0);
    $html = '<div class="flex gap-1 text-[#9c845c] text-sm">';
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= $rating) {
            $html .= '★';
        } else {
            $html .= '<span class="opacity-20">★</span>';
        }
    }
    $html .= '</div>';
    return $html;
}
?>
