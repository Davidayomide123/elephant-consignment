<!-- src/views/admin/storefront_view.php -->
<section class="max-w-5xl mx-auto px-6 pt-16 pb-12">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-4xl font-display text-elephant-800">Manage Storefront</h1>
        <a href="/dashboard/admin.php" class="btn-ghost">Back to Dashboard</a>
    </div>

    <?php if ($message): ?>
        <div class="mb-6 p-4 rounded-xl bg-sage-300/30 text-sage-600 border border-sage-300 font-medium"><?= $message ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="mb-6 p-4 rounded-xl bg-red-50 text-red-700 border border-red-100"><?= e($error) ?></div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Upload Form -->
        <div class="card-breath p-6">
            <h2 class="text-xl mb-4 border-b border-elephant-200 pb-2 text-elephant-700">List a New Item</h2>
            <form method="POST" enctype="multipart/form-data" class="space-y-4">
                <input type="hidden" name="action" value="add_item">
                
                <div>
                    <label class="block text-sm mb-1 text-elephant-600">Item Title</label>
                    <input type="text" name="title" required class="input-breath">
                </div>
                
                <div>
                    <label class="block text-sm mb-1 text-elephant-600">Description</label>
                    <textarea name="description" required rows="3" class="input-breath"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm mb-1 text-elephant-600">Category</label>
                        <select name="category" required class="input-breath">
                            <option value="Clothing">Clothing</option>
                            <option value="Accessories">Accessories</option>
                            <option value="Home Goods">Home Goods</option>
                            <option value="Electronics">Electronics</option>
                            <option value="Art">Art</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm mb-1 text-elephant-600">Condition</label>
                        <select name="condition" required class="input-breath">
                            <option value="new">New</option>
                            <option value="like_new">Like New</option>
                            <option value="good">Good</option>
                            <option value="fair">Fair</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm mb-1 text-elephant-600">Price ($)</label>
                        <input type="number" step="0.01" name="asking_price" required class="input-breath">
                    </div>
                    <div>
                        <label class="block text-sm mb-1 text-elephant-600">Consignor Email</label>
                        <input type="email" name="consignor_email" required class="input-breath" placeholder="user@example.com">
                    </div>
                </div>

                <div>
                    <label class="block text-sm mb-1 text-elephant-600">Images (Max 4)</label>
                    <input type="file" name="images[]" id="imageInput" multiple accept="image/*" class="block w-full text-sm text-elephant-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-sage-100 file:text-sage-700 hover:file:bg-sage-200">
                    <div id="preview" class="grid grid-cols-4 gap-2 mt-2"></div>
                </div>

                <button type="submit" class="btn-breath w-full mt-4">Publish Item</button>
            </form>
        </div>

        <!-- Current Inventory -->
        <div>
            <h2 class="text-xl mb-4 border-b border-elephant-200 pb-2 text-elephant-700">Current Inventory</h2>
            <div class="space-y-4 max-h-[600px] overflow-y-auto pr-2">
                <?php if(empty($items)): ?>
                    <p class="text-elephant-500 text-sm">No items in storefront.</p>
                <?php endif; ?>
                <?php foreach($items as $item): ?>
                    <div class="flex items-center justify-between gap-4 p-3 bg-elephant-50 border border-elephant-200 rounded-xl">
                        <div class="flex items-center gap-4">
                           <div class="w-16 h-16 bg-elephant-200 rounded-lg overflow-hidden flex-shrink-0 relative">
    <?php if($item['primary_image']): ?>
        <img src="<?= e($item['primary_image']) ?>" class="w-full h-full object-cover absolute inset-0">
    <?php endif; ?>
</div>
                            <div class="flex flex-col">
                                <p class="font-medium text-elephant-800 text-sm leading-tight"><?= e($item['title']) ?></p>
                                <p class="text-xs text-elephant-500 mt-1">$<?= number_format($item['asking_price'], 2) ?></p>
                            </div>
                        </div>
                        
                        <!-- DELETE BUTTON -->
                        <form method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this item? This will also delete all its images.');">
                            <input type="hidden" name="action" value="delete_item">
                            <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                            <button type="submit" class="p-2 text-red-400 hover:text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors" title="Delete Item">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>
</section>

<!-- Image Preview Script -->
<script>
document.getElementById('imageInput').addEventListener('change', function(event) {
    const preview = document.getElementById('preview');
    preview.innerHTML = '';
    const files = Array.from(event.target.files).slice(0, 4);
    
    files.forEach(file => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'w-full h-16 object-cover rounded-md border border-elephant-200';
            preview.appendChild(img);
        }
        reader.readAsDataURL(file);
    });
});
</script>