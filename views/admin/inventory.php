<!-- Success/Error Message -->
<?php if (!empty($msg)): ?>
    <div class="mb-8 p-5 rounded-2xl border <?= strpos($msg, 'Error') ? 'bg-red-50 border-red-200 text-red-600' : 'bg-green-50 border-green-200 text-green-600' ?>">
        <?= $msg ?>
    </div>
<?php endif; ?>

<form method="POST" id="main-product-form" class="grid grid-cols-1 lg:grid-cols-3 gap-10">
    <div class="lg:col-span-2 space-y-8">
        <!-- Form Fields -->
        <div class="bg-white p-10 rounded-3xl shadow-sm border border-slate-100">
            <div class="grid grid-cols-2 gap-8 mb-8">
                <div>
                    <label class="text-[10px] uppercase font-black text-slate-400 block mb-2">Product Name</label>
                    <input type="text" name="name" id="name-input" class="w-full border-b-2 py-2 outline-none text-lg" required>
                </div>
                <div>
                    <label class="text-[10px] uppercase font-black text-slate-400 block mb-2">URL Slug</label>
                    <input type="text" name="slug" id="slug-input" class="w-full border-b-2 py-2 outline-none text-lg" required>
                    <p id="slug-error" class="text-[10px] text-red-500 font-bold mt-2 hidden">Slug already exists!</p>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-8 mb-8">
                <div><label class="text-[10px] uppercase font-black text-slate-400 block mb-2">Price</label><input type="number" name="price" class="w-full border-b-2 py-2 outline-none" required></div>
                <div><label class="text-[10px] uppercase font-black text-slate-400 block mb-2">Nepali Name</label><input type="text" name="nepali_name" class="w-full border-b-2 py-2 outline-none"></div>
            </div>

            <div class="mb-8">
                <label class="text-[10px] uppercase font-black text-slate-400 block mb-2">Description</label>
                <textarea name="description" rows="4" class="w-full border-2 rounded-2xl p-4 outline-none"></textarea>
            </div>

            <div>
                <label class="text-[10px] uppercase font-black text-slate-400 block mb-4">Ritual (Enter for steps)</label>
                <div id="ritual-container" class="space-y-3">
                    <div class="flex items-center gap-4"><span class="text-indigo-200 font-bold">01.</span><input type="text" name="rituals[]" class="ritual-input w-full border-b-2 py-2"></div>
                </div>
            </div>
        </div>

        <!-- Media Section -->
        <div class="bg-white p-10 rounded-3xl shadow-sm border border-slate-100">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <div>
                    <label class="text-[10px] uppercase font-black text-slate-400 block mb-4">Main Gallery (Min 1)</label>
                    <div id="main-imgs" class="space-y-4">
                        <div class="url-input-group">
                            <input type="text" name="main_images[]" oninput="handleImg(this)" class="w-full border rounded-xl p-3 text-sm bg-slate-50" placeholder="URL...">
                            <div class="preview-box mt-2 h-20 w-20 rounded-xl border hidden overflow-hidden"></div>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="text-[10px] uppercase font-black text-slate-400 block mb-4">Description Images</label>
                    <div id="desc-imgs" class="space-y-4">
                        <div class="url-input-group">
                            <input type="text" name="desc_images[]" oninput="handleImg(this)" class="w-full border rounded-xl p-3 text-sm bg-slate-50" placeholder="URL...">
                            <div class="preview-box mt-2 h-20 w-20 rounded-xl border hidden overflow-hidden"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar Actions -->
    <div class="space-y-8">
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 sticky top-12">
            <label class="text-[10px] uppercase font-black text-slate-400 block mb-4">Ingredients</label>
            <textarea name="ingredients" class="w-full border-2 rounded-xl p-4 text-sm outline-none mb-6"></textarea>
            
            <button type="button" onclick="openPreview()" class="w-full mb-3 bg-white border-2 border-slate-900 py-4 rounded-2xl font-black uppercase text-xs">Live Preview</button>
            <button type="submit" name="add_product" id="submit-btn" class="w-full bg-slate-900 text-white py-5 rounded-2xl font-black uppercase text-xs hover:bg-indigo-600 transition-all">Add to Inventory</button>
        </div>
    </div>
</form>

<!-- PREVIEW MODAL (Stays in inventory.php) -->
<div id="preview-modal" class="fixed inset-0 z-[100] hidden bg-white">
    <div class="h-16 bg-slate-900 text-white flex justify-between items-center px-8 sticky top-0">
        <span class="text-[10px] font-black uppercase tracking-widest">Storefront Live Preview</span>
        <button type="button" onclick="closePreview()" class="bg-red-500 px-6 py-1 rounded-full text-xs font-bold uppercase">Close [X]</button>
    </div>
    <iframe name="preview-frame" class="w-full h-[calc(100vh-64px)] border-none"></iframe>
</div>

<!-- Hidden Form for Preview -->
<form id="hidden-preview-form" action="../shop/product.php" method="POST" target="preview-frame" class="hidden">
    <input type="hidden" name="preview_mode" value="1">
    <div id="hidden-inputs"></div>
</form>
