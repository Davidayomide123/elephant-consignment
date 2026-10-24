<!-- src/views/items/browse_view.php -->
<section class="max-w-6xl mx-auto px-6 pt-16 pb-24">
    <div class="text-center mb-12">
        <h1 class="text-4xl mb-3 font-display">The Collection</h1>
        <p class="text-elephant-500 max-w-xl mx-auto">Curated pieces given another breath. Every item is reviewed and approved by our team.</p>
    </div>

    <?php if (empty($items)): ?>
        <div class="card-breath p-12 text-center max-w-lg mx-auto">
            <svg class="w-12 h-12 mx-auto text-elephant-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            <h2 class="text-2xl text-elephant-800 mb-2">Nothing here yet.</h2>
            <p class="text-elephant-500">Our shelves are currently empty. Check back soon for new pieces.</p>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <?php foreach ($items as $item): ?>
                <a href="/items/detail.php?id=<?= $item['id'] ?>" class="group block card-breath overflow-hidden hover:shadow-lg transition-all duration-500">
                    
                    <!-- Image Container (Explicit height so it doesn't collapse) -->
                    <div class="w-full h-64 bg-elephant-200 relative overflow-hidden">
                        <?php if (!empty($item['primary_image'])): ?>
                            <!-- Added ./ to ensure it links correctly -->
                            <img src="<?= e($item['primary_image']) ?>" alt="<?= e($item['title']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out" onerror="this.src='/assets/uploads/items/placeholder.jpg'">
                        <?php else: ?>
                            <div class="w-full h-full flex flex-col items-center justify-center text-elephant-400">
                                <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span class="text-xs">No Image</span>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Condition Badge -->
                        <div class="absolute top-3 left-3 bg-elephant-50/90 backdrop-blur-sm px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider text-elephant-700">
                            <?= e(str_replace('_', ' ', $item['condition'])) ?>
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="p-5">
                        <p class="text-xs text-sage-600 font-medium mb-1 uppercase tracking-wider"><?= e($item['category']) ?></p>
                        <h3 class="font-medium text-elephant-800 truncate mb-2"><?= e($item['title']) ?></h3>
                        <div class="flex items-center justify-between">
                            <p class="font-display text-lg text-elephant-900">$<?= number_format($item['asking_price'], 2) ?></p>
                            <p class="text-xs text-elephant-400">by <?= e($item['consignor_name']) ?></p>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>