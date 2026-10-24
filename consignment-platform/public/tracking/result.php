<?php
// public/tracking/result.php
require_once __DIR__ . '/../../src/includes/auth.php';

$trackingCode = trim($_GET['code'] ?? '');
$shipment = null;
$logs = [];
$error = '';

if (empty($trackingCode)) {
    $error = "Please enter a tracking code.";
} else {
    $db = getDb();
    $stmt = $db->prepare("SELECT * FROM shipments WHERE tracking_code = ?");
    $stmt->execute([$trackingCode]);
    $shipment = $stmt->fetch();

    if ($shipment) {
        $logStmt = $db->prepare("SELECT * FROM shipment_logs WHERE shipment_id = ? ORDER BY created_at DESC");
        $logStmt->execute([$shipment['id']]);
        $logs = $logStmt->fetchAll();
    } else {
        $error = "No shipment found with that tracking code.";
    }
}

$pageTitle = 'Track Package';
require __DIR__ . '/../../src/views/layouts/header.php';

if ($error) {
    echo '<div class="max-w-xl mx-auto mt-24 p-6 card-breath text-center">';
    echo '<h1 class="text-2xl text-red-600 mb-2">Not Found</h1>';
    echo '<p class="text-elephant-500 mb-6">' . e($error) . '</p>';
    echo '<a href="/" class="btn-breath inline-block">Try Again</a>';
    echo '</div>';
} else {
    require __DIR__ . '/../../src/views/tracking/result.php';
}

require __DIR__ . '/../../src/views/layouts/footer.php';
?>