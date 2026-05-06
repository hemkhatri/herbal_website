<!-- header.php -->
<!-- Top Navigation -->
<nav class="sticky top-0 z-50 w-full bg-white border-b border-gray-200 shadow-sm transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- MOBILE: Left (Hamburger Menu) -->
            <div class="flex md:hidden">
                <button onclick="toggleSidebar()"
                    class="text-gray-600 p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </button>
            </div>

            <!-- LOGO -->
            <div class="flex-shrink-0 flex items-center">
                <a href="index.php"
                    class="font-logo text-3xl tracking-tight text-gray-900 dark:text-white transition-all lowercase">
                    aushadhi<span class="text-green-600">.</span>
                </a>
            </div>

            <!-- PC: Categories -->
            <div class="hidden md:flex space-x-6 h-full items-center ml-10">
                <div class="relative group h-full flex items-center">
                    <button
                        class="text-gray-700 hover:text-green-600 font-medium flex items-center gap-1 transition-colors">
                        Medicines
                        <svg class="w-4 h-4 transition-transform group-hover:rotate-180" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div
                        class="absolute top-full left-0 w-48 bg-white border border-gray-100 shadow-xl rounded-b-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                        <a href="#"
                            class="block px-4 py-3 text-sm text-gray-700 hover:bg-green-50 hover:text-green-600">Prescription</a>
                        <a href="#"
                            class="block px-4 py-3 text-sm text-gray-700 hover:bg-green-50 hover:text-green-600">Over-the-Counter</a>
                    </div>
                </div>
                <div class="relative group h-full flex items-center">
                    <button
                        class="text-gray-700 hover:text-green-600 font-medium flex items-center gap-1 transition-colors">
                        Wellness
                        <svg class="w-4 h-4 transition-transform group-hover:rotate-180" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div
                        class="absolute top-full left-0 w-48 bg-white border border-gray-100 shadow-xl rounded-b-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                        <a href="#" class="block px-4 py-3 text-sm text-gray-700 hover:bg-green-50">Supplements</a>
                    </div>
                </div>
            </div>

            <!-- SEARCH TRIGGER (PC) -->
            <div class="hidden md:flex flex-grow max-w-xs mx-6">
                <div onclick="toggleSearch()"
                    class="flex items-center w-full bg-gray-100 rounded-lg px-3 py-2 cursor-pointer text-gray-500 hover:bg-gray-200 transition-all">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <span class="text-sm">Search medicines...</span>
                </div>
            </div>

            <!-- RIGHT ICONS -->
            <div class="flex items-center space-x-1 md:space-x-3">
                <!-- THEME TOGGLE -->
                <button onclick="toggleTheme()"
                    class="p-2 text-gray-600 hover:bg-gray-100 rounded-full transition-colors dark:text-gray-400 dark:hover:bg-gray-800">
                    <svg id="theme-sun" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707m12.728 12.728L12 12z">
                        </path>
                    </svg>
                    <svg id="theme-moon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z">
                        </path>
                    </svg>
                </button>

                <a href="wishlist.php" class="p-2 text-gray-600 hover:text-red-500 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                        </path>
                    </svg>
                </a>

                <a href="cart.php" class="p-2 text-gray-600 hover:text-green-600 relative transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <span
                        class="absolute top-1 right-1 bg-green-600 text-white text-[10px] rounded-full w-4 h-4 flex items-center justify-center">0</span>
                </a>

                <!-- Account Icon Button -->
                <?php if (isset($_SESSION['user_id'])):
                    // Logic for initials
                    $name = $_SESSION['user_name'] ?? 'User';
                    $words = explode(" ", $name);
                    $initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
                    ?>
                    <div class="relative">
                        <!-- TRIGGER: Profile Circle -->
                        <button onclick="toggleUserMenu()" class="flex items-center focus:outline-none">
                            <?php if (!empty($_SESSION['user_photo'])): ?>
                                <img src="<?= htmlspecialchars($_SESSION['user_photo']) ?>"
                                    class="w-9 h-9 rounded-full object-cover border border-gray-200 hover:border-green-500 transition-all">
                            <?php else: ?>
                                <div
                                    class="w-9 h-9 rounded-full bg-green-100 text-green-700 flex items-center justify-center text-sm font-bold border border-green-200 hover:bg-green-200 transition-all">
                                    <?= $initials ?>
                                </div>
                            <?php endif; ?>
                        </button>

                        <!-- DROPDOWN MENU -->
                        <div id="userMenu"
                            class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-100 py-2 z-50">
                            <div class="px-4 py-2 border-b border-gray-50 mb-1">
                                <p class="text-xs text-gray-500 uppercase font-semibold">Account</p>
                                <p class="text-sm font-medium text-gray-800 truncate"><?= htmlspecialchars($name) ?></p>
                            </div>

                            <a href="../user/profile.php"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 transition-colors">My
                                Profile</a>
                            <a href="orders.php"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 transition-colors">My
                                Orders</a>
                            <a href="settings.php"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 transition-colors">Settings</a>

                            <hr class="my-1 border-gray-100">

                            <a href="../auth/logout.php"
                                class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">Logout</a>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Your existing Login button logic -->
                    <!-- LOGIN ICON (NOT LOGGED IN) -->
                    <button onclick="toggleLogin()" class="p-2 text-gray-600 hover:text-green-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </button>
                <?php endif; ?>

                <script>
                    function toggleUserMenu() {
                        const menu = document.getElementById('userMenu');
                        menu.classList.toggle('hidden');
                    }

                    // Close menu if user clicks outside
                    window.addEventListener('click', function (e) {
                        const menu = document.getElementById('userMenu');
                        const button = menu.previousElementSibling;
                        if (!button.contains(e.target) && !menu.contains(e.target)) {
                            menu.classList.add('hidden');
                        }
                    });
                </script>



            </div>
        </div>
    </div>
</nav>

<!-- LOGIN POPUP OVERLAY -->
<div id="login-overlay"
    class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">

    <div id="login-card"
        class="relative w-full max-w-md bg-white rounded-[25px] shadow-2xl overflow-hidden transform transition-all duration-300 scale-95 opacity-0">

        <!-- Close Button -->
        <button onclick="toggleLogin()"
            class="absolute top-4 right-4 z-20 p-2 bg-gray-100 rounded-full hover:bg-gray-200">
            ✕
        </button>

        <!-- Dynamic Content -->
        <div id="login-content" class="p-6">
            <!-- Loaded via JS -->
        </div>

    </div>
</div>

<!-- FULL-PAGE SEARCH OVERLAY -->
<div id="search-overlay"
    class="fixed inset-0 z-[100] bg-white transform translate-x-full transition-transform duration-300 ease-in-out">
    <div class="p-4 flex items-center border-b shadow-sm">
        <button onclick="toggleSearch()" class="p-2 mr-2 text-gray-600 hover:bg-gray-100 rounded-full">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>
        <input type="text" id="search-input" placeholder="Search for products..."
            class="w-full py-2 text-lg focus:outline-none bg-transparent" autofocus>
    </div>
    <div class="p-6 text-center text-gray-400">
        <p>Type to search medicines and products...</p>
    </div>
</div>

<!-- SIDEBAR OVERLAY -->
<div id="sidebar-overlay" onclick="toggleSidebar()" class="fixed inset-0 z-[60] bg-black/50 hidden transition-opacity">
</div>

<!-- SIDEBAR CONTENT -->
<div id="sidebar"
    class="fixed top-0 left-0 z-[70] h-full w-80 bg-white transform -translate-x-full transition-transform duration-300 ease-in-out shadow-2xl overflow-y-auto">
    <div class="p-5 bg-green-600 flex justify-between items-center text-white">
        <span class="font-bold text-lg">Menu</span>
        <button onclick="toggleSidebar()" class="hover:rotate-90 transition-transform">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
    <div class="py-4">
        <div class="px-4 py-2 text-sm font-semibold text-gray-400 uppercase tracking-wider">Categories</div>
        <a href="#"
            class="block px-6 py-3 text-gray-700 hover:bg-green-50 border-b border-gray-50 transition-colors">Medicines</a>
        <a href="#"
            class="block px-6 py-3 text-gray-700 hover:bg-green-50 border-b border-gray-50 transition-colors">Wellness</a>
        <a href="#"
            class="block px-6 py-3 text-gray-700 hover:bg-green-50 border-b border-gray-50 transition-colors">Personal
            Care</a>
    </div>
</div>

<script>
    function applyTheme(theme) {
        const html = document.documentElement;
        const sunIcon = document.getElementById('theme-sun');
        const moonIcon = document.getElementById('theme-moon');
        if (theme === 'dark') {
            html.classList.add('dark');
            if (sunIcon) sunIcon.classList.remove('hidden');
            if (moonIcon) moonIcon.classList.add('hidden');
        } else {
            html.classList.remove('dark');
            if (sunIcon) sunIcon.classList.add('hidden');
            if (moonIcon) moonIcon.classList.remove('hidden');
        }
    }

    function toggleTheme() {
        const isDark = document.documentElement.classList.contains('dark');
        const newTheme = isDark ? 'light' : 'dark';
        localStorage.setItem('theme', newTheme);
        applyTheme(newTheme);
    }

    (function () {
        const savedTheme = localStorage.getItem('theme') || 'light';
        applyTheme(savedTheme);
    })();

    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const isOpen = sidebar.classList.contains('translate-x-0');
        sidebar.classList.toggle('translate-x-0', !isOpen);
        sidebar.classList.toggle('-translate-x-full', isOpen);
        overlay.classList.toggle('hidden', isOpen);
    }

    function toggleSearch() {
        const overlay = document.getElementById('search-overlay');
        const input = document.getElementById('search-input');
        const isOpen = overlay.classList.contains('translate-x-0');
        overlay.classList.toggle('translate-x-0', !isOpen);
        overlay.classList.toggle('translate-x-full', isOpen);
        if (!isOpen) setTimeout(() => input.focus(), 300);
    }
    // Inside your header.php toggle script
    async function loadLoginForm() {
        const container = document.getElementById('login-content');
        const res = await fetch('/aushadhi-platform/views/auth/login_form.php');
        const html = await res.text();

        // FIX: Using createContextualFragment forces the <script> tags to run
        const fragment = document.createRange().createContextualFragment(html);
        container.innerHTML = "";
        container.appendChild(fragment);

        console.log("Form Loaded and Scripts Activated");
    }




    function toggleLogin() {
        const overlay = document.getElementById('login-overlay');
        const card = document.getElementById('login-card');
        const isHidden = overlay.classList.contains('hidden');

        if (isHidden) {
            overlay.classList.remove('hidden');

            setTimeout(() => {
                card.classList.remove('scale-95', 'opacity-0');
                card.classList.add('scale-100', 'opacity-100');
            }, 10);

            loadLoginForm(); // 🔥 load dynamically

        } else {
            card.classList.remove('scale-100', 'opacity-100');
            card.classList.add('scale-95', 'opacity-0');

            setTimeout(() => {
                overlay.classList.add('hidden');
                document.getElementById('login-content').innerHTML = ""; // clear
            }, 300);
        }
    }

    // Close when clicking outside
    window.addEventListener('click', function (e) {
        const overlay = document.getElementById('login-overlay');
        if (e.target === overlay) {
            toggleLogin();
        }
    });



    async function handleEmailCheck(e) {
        e.preventDefault(); // Stop the page from refreshing

        const form = e.target;
        const formData = new FormData(form);

        try {
            const response = await fetch('/aushadhi-platform/views/auth/login_form.php', {
                method: 'POST',
                body: formData
            });

            const redirectUrl = await response.text();

            // Clean up the URL (removes any accidental spaces or hidden characters)
            const cleanUrl = redirectUrl.trim();

            if (cleanUrl) {
                // This will take you to login.php or signup.php
                window.location.href = cleanUrl;
            } else {
                alert("Server returned an empty response.");
            }
        } catch (error) {
            console.error("Error:", error);
            alert("Something went wrong.Please try again.");
        }
    }

    // This function MUST be global so Google's library can find it
    window.handleCredentialResponse = function (response) {
        console.log("Google Token Received, sending to verify...");

        const formData = new FormData();
        formData.append('credential', response.credential);

        fetch('/aushadhi-platform/views/auth/google_verify.php', {
            method: 'POST',
            body: formData
        })
            .then(async res => {
                const text = await res.text();
                try {
                    return JSON.parse(text);
                } catch (e) {
                    console.error("Server sent non-JSON response:", text);
                    throw new Error("Invalid Server Response");
                }
            })
            .then(data => {
                if (data.success) {
                    // Success! Redirect to home or account page
                    window.location.href = "/aushadhi-platform/index.php";
                } else {
                    alert("Login Failed: " + data.message);
                }
            })
            .catch(err => {
                console.error("Verification Error:", err);
                alert("Verification Error. Please check console.");
            });
    }


</script>