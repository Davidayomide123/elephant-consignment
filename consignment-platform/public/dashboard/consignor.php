<?php
// public/dashboard/consignor.php
require_once __DIR__ . '/../../src/includes/auth.php';
requireRole('consignor');
$db = getDb();

// Fetch user's shipments and any triggered alerts
$stmt = $db->prepare("SELECT * FROM shipments WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$myShipments = $stmt->fetchAll();

$activeAlerts = [];
foreach ($myShipments as $s) {
    // Only show the alert if it's the MOST RECENT update for this shipment
    $alertStmt = $db->prepare("SELECT * FROM shipment_logs WHERE shipment_id = ? ORDER BY created_at DESC LIMIT 1");
    $alertStmt->execute([$s['id']]);
    $latestLog = $alertStmt->fetch();
    
    if ($latestLog && $latestLog['is_alert'] == 1) {
        $activeAlerts[] = ['shipment' => $s, 'alert' => $latestLog];
    }
}

$pageTitle = 'Consignor Dashboard';
require __DIR__ . '/../../src/views/layouts/header.php';
?>
<section class="max-w-4xl mx-auto px-6 pt-16 pb-12">
    <h1 class="text-4xl mb-2">Welcome, <?= e($_SESSION['display_name']) ?>.</h1>
    <p class="text-elephant-500 mb-10">Manage your shipments and track your goods.</p>
    
    <!-- Alerts Section -->
    <?php if (!empty($activeAlerts)): ?>
        <div class="mb-12 space-y-4">
            <?php foreach ($activeAlerts as $item): ?>
                <div class="bg-red-50 border-l-4 border-red-500 p-6 rounded-r-xl shadow-sm">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-1">
                            <svg class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-lg font-medium text-red-800">Action Required at <?= e($item['alert']['location']) ?></h3>
                            <p class="mt-1 text-sm text-red-700"><?= e($item['alert']['status_message']) ?></p>
                            <div class="mt-4">
                                <!-- Your External Support Link -->
                                <a href="https://your-external-support-link.com" target="_blank" class="btn-breath bg-red-600 hover:bg-red-700 text-sm px-5 py-2">
                                    Contact Support Now
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Tracking Search Box -->
    <div class="card-breath p-8 md:p-12 text-center mb-12">
        <h2 class="text-2xl font-display mb-2 text-elephant-800">Track your package.</h2>
        <p class="text-sm text-elephant-500 mb-6">Enter the tracking code provided to you by our support team.</p>
        
        <form action="/tracking/result.php" method="GET" class="flex flex-col md:flex-row gap-3 max-w-lg mx-auto">
            <input type="text" name="code" required placeholder="e.g. ELE-A1B2C3D4" class="input-breath flex-1 text-center md:text-left uppercase" style="text-transform: uppercase;">
            <button type="submit" class="btn-breath whitespace-nowrap">Track Package</button>
        </form>
    </div>

</section>
<?php require __DIR__ . '/../../src/views/layouts/footer.php'; ?>