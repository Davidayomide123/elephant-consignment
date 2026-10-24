<?php
// public/admin/users.php
require_once __DIR__ . '/../../src/includes/auth.php';
requireRole('admin');

$db = getDb();
$message = '';

// Handle Quick Shipment Creation from the User List
if (isset($_POST['action']) && $_POST['action'] === 'quick_create_shipment') {
    $user_id = (int)$_POST['user_id'];
    $email = $_POST['user_email'];
    $item_name = trim($_POST['item_name']);
    $origin = trim($_POST['origin']);
    $destination = trim($_POST['destination']);

    $code = 'ELE-' . strtoupper(bin2hex(random_bytes(4)));
    
    // Insert the shipment
    $stmt = $db->prepare("INSERT INTO shipments (tracking_code, user_id, item_name, origin_port, destination_port) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$code, $user_id, $item_name, $origin, $destination]);
    
    // Add the initial log
    $shipment_id = $db->lastInsertId();
    $stmt = $db->prepare("INSERT INTO shipment_logs (shipment_id, location, status_message) VALUES (?, ?, ?)");
    $stmt->execute([$shipment_id, $origin, "Package received and processed at origin port."]);
    
    $message = "Shipment created for <strong>$email</strong>! Tracking Code: <strong class='font-mono text-lg text-sage-600'>$code</strong>";
}

// Fetch all consignors
$users = $db->query("SELECT id, display_name, email, created_at FROM users WHERE role = 'consignor' ORDER BY created_at DESC")->fetchAll();

$pageTitle = 'User Directory';
require __DIR__ . '/../../src/views/layouts/header.php';
require __DIR__ . '/../../src/views/admin/users_view.php';