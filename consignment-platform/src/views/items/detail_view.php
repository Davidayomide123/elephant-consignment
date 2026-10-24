<!-- src/views/items/detail_view.php -->
<section class="max-w-6xl mx-auto px-6 pt-16 pb-24">
    <a href="/items/browse.php" class="inline-flex items-center gap-2 text-sm text-elephant-500 hover:text-sage-600 mb-8 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Back to Collection
    </a>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
        <!-- Left Side: Images -->
        <div class="space-y-4">
            <!-- Main Image (Explicit Height) -->
            <div class="w-full h-[500px] bg-elephant-200 rounded-[var(--radius-breath)] overflow-hidden border border-elephant-200 shadow-sm relative">
                <?php if (!empty($images)): ?>
                    <img id="main-image" src="<?= e($images[0]['file_path']) ?>" alt="<?= e($item['title']) ?>" class="w-full h-full object-cover absolute inset-0">
                <?php else: ?>
                    <div class="w-full h-full flex items-center justify-center text-elephant-400">
                        <span class="text-sm">No image provided</span>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Thumbnails -->
            <?php if (count($images) > 1): ?>
                <div class="grid grid-cols-4 gap-4">
                    <?php foreach ($images as $index => $img): ?>
                        <button onclick="document.getElementById('main-image').src='<?= e($img['file_path']) ?>'" 
                                class="w-full h-24 bg-elephant-200 rounded-xl overflow-hidden border border-elephant-200 hover:opacity-80 transition-opacity focus:ring-2 focus:ring-sage-500 outline-none relative">
                            <img src="<?= e($img['file_path']) ?>" class="w-full h-full object-cover absolute inset-0">
                        </button>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Right Side: Details -->
        <div class="flex flex-col">
            <div class="mb-2 flex items-center justify-between">
                <span class="badge-breath uppercase tracking-wider text-[10px]"><?= e($item['category']) ?></span>
                <span class="text-sm text-elephant-500">Condition: <strong class="text-elephant-800"><?= e(str_replace('_', ' ', $item['condition'])) ?></strong></span>
            </div>
            
            <h1 class="text-4xl font-display text-elephant-800 mb-4 leading-tight"><?= e($item['title']) ?></h1>
            <p class="text-3xl font-display text-sage-600 mb-8">$<?= number_format($item['asking_price'], 2) ?></p>

            <div class="prose prose-elephant mb-8">
                <p class="text-elephant-600 leading-relaxed whitespace-pre-wrap"><?= e($item['description']) ?></p>
            </div>

            <div class="mt-auto pt-8 border-t border-elephant-200">
                <p class="text-sm text-elephant-500 mb-4">Consigned by <strong><?= e($item['consignor_name']) ?></strong></p>
                <a href="https://your-external-support-link.com" target="_blank" class="btn-breath w-full text-center py-4 text-lg">
                    Inquire to Purchase
                </a>
                <p class="text-xs text-center text-elephant-400 mt-3">You will be redirected to our secure support portal.</p>
            </div>
        </div>
    </div>
</section>