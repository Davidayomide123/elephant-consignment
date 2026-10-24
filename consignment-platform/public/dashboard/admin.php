<?php
require_once __DIR__ . '/../../src/includes/auth.php';
requireRole('admin');
$pageTitle = 'Admin Dashboard';
require __DIR__ . '/../../src/views/layouts/header.php';
?>
<section class="max-w-6xl mx-auto px-6 pt-16 pb-12">
    <h1 class="text-4xl mb-4 font-display text-elephant-800">Admin Control Room</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-8">
        
        <!-- Logistics Link -->
        <a href="/admin/tracking.php" class="card-breath p-8 hover:bg-elephant-50 transition-colors block">
            <h2 class="text-xl mb-2 text-sage-600 font-display">Manage Logistics</h2>
            <p class="text-elephant-500 text-sm">Create tracking codes, add ports, and trigger user alerts.</p>
        </a>

        <!-- Storefront Link -->
        <a href="/admin/storefront.php" class="card-breath p-8 hover:bg-elephant-50 transition-colors block">
            <h2 class="text-xl mb-2 text-sage-600 font-display">Manage Storefront</h2>
            <p class="text-elephant-500 text-sm">Upload new items for sale, set prices, and manage the collection.</p>
        </a>

        <!-- User Directory Link -->
        <a href="/admin/users.php" class="card-breath p-8 hover:bg-elephant-50 transition-colors block">
            <h2 class="text-xl mb-2 text-sage-600 font-display">User Directory</h2>
            <p class="text-elephant-500 text-sm">View all registered users and easily create shipments for them.</p>
        </a>

    </div>
</section>
<?php require __DIR__ . '/../../src/views/layouts/footer.php'; ?>