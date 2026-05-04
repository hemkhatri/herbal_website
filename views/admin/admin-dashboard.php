<?php
session_start();
require '../../includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.php");
    exit;
}

$msg = "";
if (isset($_SESSION['msg'])) {
    $msg = $_SESSION['msg'];
    unset($_SESSION['msg']);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])) {
    try {
        $pdo->beginTransaction();
        $slug = strtolower(str_replace(' ', '-', $_POST['slug']));

        $stmt = $pdo->prepare("INSERT INTO products (name, slug, nepali_name, price, description, is_featured) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$_POST['name'], $slug, $_POST['nepali_name'], $_POST['price'], $_POST['description'], isset($_POST['is_featured']) ? 1 : 0]);
        $product_id = $pdo->lastInsertId();

        $img_stmt = $pdo->prepare("INSERT INTO product_images (product_id, image_url, image_type) VALUES (?, ?, ?)");
        foreach (($_POST['main_images'] ?? []) as $url)
            if (!empty(trim($url)))
                $img_stmt->execute([$product_id, trim($url), 'main']);
        foreach (($_POST['desc_images'] ?? []) as $url)
            if (!empty(trim($url)))
                $img_stmt->execute([$product_id, trim($url), 'description']);

        if (!empty($_POST['ingredients'])) {
            $ing_stmt = $pdo->prepare("INSERT INTO product_ingredients (product_id, ingredient_name) VALUES (?, ?)");
            foreach (explode(',', $_POST['ingredients']) as $ing)
                $ing_stmt->execute([$product_id, trim($ing)]);
        }

        if (!empty($_POST['rituals'])) {
            $rit_stmt = $pdo->prepare("INSERT INTO product_rituals (product_id, step_number, instruction) VALUES (?, ?, ?)");
            foreach ($_POST['rituals'] as $idx => $step)
                if (!empty(trim($step)))
                    $rit_stmt->execute([$product_id, $idx + 1, trim($step)]);
        }

        $pdo->commit();
        $_SESSION['msg'] = "Success: Product added to vault.";
        header("Location: admin-dashboard.php");
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['msg'] = "Error: " . ($e->getCode() == 23000 ? "Slug already exists!" : $e->getMessage());
        header("Location: admin-dashboard.php");
        exit;
    }
}
$view = $_GET['view'] ?? 'inventory';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Aushadhi Admin | Inventory</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cloudflare.com">
    <style>
        .ritual-input:focus {
            border-bottom-color: #4f46e5;
            outline: none;
        }

        .preview-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>
</head>

<body class="bg-[#F9FAFB] flex min-h-screen text-slate-800">

    <aside class="w-72 bg-slate-900 text-white p-8 sticky top-0 h-screen flex flex-col">
        <h2 class="text-3xl font-bold mb-10 tracking-tighter">Aushadhi.</h2>

        <nav class="space-y-2 flex-1">
            <!-- Inventory Link -->
            <a href="?view=inventory"
                class="flex items-center gap-3 p-3 rounded-xl transition-all <?= $view == 'inventory' ? 'bg-indigo-600' : 'hover:bg-slate-800 text-slate-400' ?>">
                <i class="fa-solid fa-box-open w-5"></i> Inventory
            </a>

            <!-- Order Tracking Link -->
            <a href="?view=orders"
                class="flex items-center gap-3 p-3 rounded-xl transition-all <?= $view == 'orders' ? 'bg-indigo-600' : 'hover:bg-slate-800 text-slate-400' ?>">
                <i class="fa-solid fa-truck-fast w-5"></i> Order Tracking
            </a>

            <!-- Insights Link -->
            <a href="?view=insights"
                class="flex items-center gap-3 p-3 rounded-xl transition-all <?= $view == 'insights' ? 'bg-indigo-600' : 'hover:bg-slate-800 text-slate-400' ?>">
                <i class="fa-solid fa-chart-line w-5"></i> Insights
            </a>
        </nav>

        <a href="../auth/logout.php" class="p-3 text-red-400 hover:bg-slate-800 rounded-xl transition-all">
            <i class="fa-solid fa-power-off mr-2"></i> Logout
        </a>
    </aside>


    <main class="flex-1 p-12 overflow-y-auto">
        <?php if ($view == 'inventory'): ?>
            <!-- MOVE ALL YOUR EXISTING INVENTORY FORM & TABLE HTML HERE -->
            <h1 class="text-4xl font-bold mb-10">Inventory Management</h1>
            <?php include "inventory.php" ?>



        <?php elseif ($view == 'orders'): ?>
            <!-- ORDER TRACKING VIEW -->
            <h1 class="text-4xl font-bold mb-10">Order Tracking</h1>
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
                <p class="text-slate-500">Real-time status of customer deliveries...</p>
            </div>

        <?php elseif ($view == 'insights'): ?>
            <!-- INSIGHTS VIEW -->
            <h1 class="text-4xl font-bold mb-10">Business Insights</h1>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
                    <h4 class="text-xs font-bold text-slate-400 uppercase">Total Sales</h4>
                    <p class="text-3xl font-bold mt-2">Rs. 124,500</p>
                </div>
                <!-- More insight cards... -->
            </div>
        <?php endif; ?>
    </main>




    <script>
        const nameIn = document.getElementById('name-input'), slugIn = document.getElementById('slug-input'), btn = document.getElementById('submit-btn');

        // 1. Slug Check
        nameIn.addEventListener('input', () => {
            slugIn.value = nameIn.value.toLowerCase().trim().replace(/[\s_-]+/g, '-').replace(/[^\w-]/g, '');
            checkSlug(slugIn.value);
        });
        function checkSlug(v) {
            fetch(`check-slug.php?slug=${v}`).then(r => r.text()).then(st => {
                const isDup = st === 'exists';
                slugIn.classList.toggle('text-red-600', isDup);
                document.getElementById('slug-error').classList.toggle('hidden', !isDup);
                btn.disabled = isDup; btn.style.opacity = isDup ? '0.5' : '1';
            });
        }

        // 2. Rituals
        document.getElementById('ritual-container').addEventListener('keydown', e => {
            if (e.key === 'Enter' && e.target.tagName === 'INPUT') {
                e.preventDefault();
                const div = document.createElement('div');
                div.className = "flex items-center gap-4";
                div.innerHTML = `<span class="text-indigo-200 font-bold">${String(document.querySelectorAll('.ritual-input').length + 1).padStart(2, '0')}.</span>
                                 <input type="text" name="rituals[]" class="ritual-input w-full border-b-2 py-2">`;
                document.getElementById('ritual-container').appendChild(div);
                div.querySelector('input').focus();
            }
        });

        // 3. Images
        function handleImg(input) {
            const container = input.closest('.space-y-4'), preview = input.nextElementSibling;
            if (input.value.trim()) {
                preview.innerHTML = `<img src="${input.value}">`; preview.classList.remove('hidden');
                if (input === container.lastElementChild.querySelector('input')) {
                    const div = document.createElement('div'); div.className = "url-input-group mt-3";
                    div.innerHTML = `<input type="text" name="${input.name}" oninput="handleImg(this)" class="w-full border rounded-xl p-3 text-sm bg-slate-50">
                                     <div class="preview-box mt-2 h-20 w-20 rounded-xl border hidden overflow-hidden"></div>`;
                    container.appendChild(div);
                }
            }
        }

        // 4. Validation & Preview Logic
        function openPreview() {
            const hiddenInputs = document.getElementById('hidden-inputs');
            hiddenInputs.innerHTML = '';
            new FormData(document.getElementById('main-product-form')).forEach((val, key) => {
                const input = document.createElement('input'); input.type = 'hidden';
                input.name = key; input.value = val; hiddenInputs.appendChild(input);
            });
            document.getElementById('preview-modal').classList.remove('hidden');
            document.getElementById('hidden-preview-form').submit();
        }
        function closePreview() { document.getElementById('preview-modal').classList.add('hidden'); }

        document.getElementById('main-product-form').onsubmit = (e) => {
            if (!document.querySelector('input[name="main_images[]"]').value) {
                alert("You must add at least one Main Image."); e.preventDefault();
            }
        };
    </script>
</body>

</html>