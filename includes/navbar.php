<!-- NAVBAR -->
<nav class="fixed w-full z-50 bg-white/70 backdrop-blur-xl border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-6 py-5 flex justify-between items-center">

        <div>
            <h1 class="font-luxury text-3xl lowercase">
                <a href="/aushadhi-platform/views/shop" class="hover:opacity-80 transition-opacity">
                    <?= strtolower($site_name) ?>.
                </a>
            </h1>
        </div>


        <div class="hidden md:flex items-center gap-10 text-sm uppercase tracking-[0.25em]">
            <a href="#featured" class="hover:text-accent transition-colors">Curated</a>
            <a href="#products" class="hover:text-accent transition-colors">Shop</a>
            <a href="#about" class="hover:text-accent transition-colors">Story</a>


            <div class="flex items-center gap-6">
                <!-- Cart Container (Visible to Everyone) -->
                <div class="relative group">
                    <a href="cart.php"
                        class="hover:text-accent transition-colors font-bold flex items-center gap-2 py-2">
                        Cart (<span id="cart-count-nav"><?= $cart_count; ?></span>)
                    </a>

                    <!-- Dropdown Menu -->
                    <div
                        class="absolute right-0 mt-2 w-80 bg-white border border-stone-100 rounded-2xl shadow-2xl opacity-0 invisible pointer-events-none group-hover:opacity-100 group-hover:visible group-hover:pointer-events-auto transition-all duration-300 z-50 overflow-hidden">
                        <div class="p-4 border-b border-stone-50 bg-stone-50/50">
                            <p class="text-[10px] uppercase tracking-widest text-gray-400 font-bold">Your Selection
                            </p>
                        </div>

                        <div class="max-h-[400px] overflow-hidden relative bg-white">
                            <?php if (!empty($_SESSION['cart']) && isset($conn)):
                                $cart_ids = array_keys($_SESSION['cart']);
                                $preview_ids = array_slice(array_reverse($cart_ids), 0, 3);
                                $ids_string = implode(',', array_map('intval', $preview_ids));

                                $sql_preview = "
                        SELECT p.name, p.price, pi.image_url 
                        FROM products p 
                        LEFT JOIN product_images pi ON p.id = pi.product_id 
                        WHERE p.id IN ($ids_string) 
                        AND (pi.image_type = 'main' OR pi.image_type IS NULL)
                        GROUP BY p.id
                    ";
                                $preview_result = $conn->query($sql_preview);

                                if ($preview_result && $preview_result->num_rows > 0):
                                    while ($item = $preview_result->fetch_assoc()): ?>
                                        <div
                                            class="flex items-center gap-4 p-4 border-b border-stone-50 last:border-0 hover:bg-stone-50 transition-colors">
                                            <div
                                                class="w-14 h-14 bg-stone-100 rounded-xl overflow-hidden flex-shrink-0 border border-stone-50">
                                                <img src="<?= htmlspecialchars($item['image_url'] ?? 'placeholder.jpg') ?>"
                                                    class="w-full h-full object-cover">
                                            </div>
                                            <div class="flex-1 min-w-0 text-left">
                                                <p class="text-xs font-semibold text-gray-800 truncate">
                                                    <?= htmlspecialchars($item['name']) ?>
                                                </p>
                                                <p class="text-[10px] text-[#2D4030] font-bold mt-1 uppercase tracking-wider">
                                                    Rs. <?= number_format($item['price']) ?></p>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>

                                    <div class="p-4 bg-stone-50/30 flex justify-center border-t border-stone-50">
                                        <a href="cart.php"
                                            class="bg-[#2D4030] text-white text-[9px] uppercase tracking-[0.2em] px-8 py-3 rounded-full hover:bg-black transition-all shadow-md">
                                            View Full Cart
                                        </a>
                                    </div>
                                <?php else: ?>
                                    <div class="p-10 text-center text-gray-400 text-xs italic">Selected items unavailable.
                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <div class="p-12 text-center text-gray-400 text-xs italic">
                                    <i class="fa-solid fa-basket-shopping block text-3xl mb-3 opacity-10"></i>
                                    Your apothecary is empty.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Login/Account Toggle -->
                <?php if (!$is_logged_in): ?>
                    <a href="../auth/login.php" class="hover:text-accent transition-colors font-bold">Login</a>
                <?php else: ?>
                    <a href="../auth/logout.php" class="hover:text-accent transition-colors font-bold">Logout</a>

                    <!-- Profile Dropdown Container -->
                    <div class="relative group">
                        <!-- The Circle (Trigger) -->
                        <button
                            class="flex items-center justify-center w-10 h-10 rounded-full bg-[#2D4030] text-white font-bold tracking-tighter border-2 border-transparent group-hover:border-[#2D4030] transition-all text-xs">
                            <?= $user_initials ?>
                        </button>

                        <!-- Dropdown Menu (Shows on Hover) -->
                        <div
                            class="absolute right-0 mt-2 w-48 bg-white border border-stone-100 rounded-2xl shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50 overflow-hidden">
                            <div class="px-4 py-3 border-b border-stone-50 bg-stone-50/50">
                                <p class="text-[9px] uppercase tracking-widest text-gray-400 font-bold">Account</p>
                                <p class="text-xs font-medium text-gray-800 truncate">
                                    <?= htmlspecialchars($_SESSION['user_name']) ?>
                                </p>
                            </div>

                            <div class="py-2">
                                <a href="../user/profile.php"
                                    class="flex items-center gap-3 px-4 py-2 text-xs text-gray-600 hover:bg-stone-50 hover:text-[#2D4030] transition-colors">
                                    <i class="fa-regular fa-user w-4"></i> View Profile
                                </a>
                                <a href="orders.php"
                                    class="flex items-center gap-3 px-4 py-2 text-xs text-gray-600 hover:bg-stone-50 hover:text-[#2D4030] transition-colors">
                                    <i class="fa-solid fa-box w-4"></i> My Orders
                                </a>
                            </div>

                            <div class="border-t border-stone-50 py-2 bg-red-50/10">
                                <a href="../auth/logout.php"
                                    class="flex items-center gap-3 px-4 py-2 text-xs text-red-500 hover:bg-red-50 transition-colors">
                                    <i class="fa-solid fa-arrow-right-from-bracket w-4"></i> Logout
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </div>
</nav>