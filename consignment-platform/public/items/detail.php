<?php
// public/items/detail.php
require_once __DIR__ . '/../../src/includes/auth.php';
$db = getDb();

$item_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch the item
$stmt = $db->prepare("
    SELECT i.*, u.display_name as consignor_name 
    FROM items i 
    JOIN users u ON i.consignor_id = u.id 
    WHERE i.id = ? AND i.status = 'approved'
");
$stmt->execute([$item_id]);
$item = $stmt->fetch();

if (!$item) {
    // Item not found or not approved
    header("Location: /items/browse.php");
    exit;
}

// Fetch all images for this item
$imgStmt = $db->prepare("SELECT * FROM item_images WHERE item_id = ? ORDER BY is_primary DESC, sort_order ASC");
$imgStmt->execute([$item_id]);
$images = $imgStmt->fetchAll();

$pageTitle = $item['title'];
require __DIR__ . '/../../src/views/layouts/header.php';
require __DIR__ . '/../../src/views/items/detail_view.php';