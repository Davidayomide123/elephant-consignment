<?php
// public/items/browse.php
require_once __DIR__ . '/../../src/includes/auth.php';
$db = getDb();

// Fetch all approved items with their primary image
$stmt = $db->query("
    SELECT i.*, 
           (SELECT file_path FROM item_images WHERE item_id = i.id AND is_primary = 1 LIMIT 1) as primary_image,
           u.display_name as consignor_name
    FROM items i
    JOIN users u ON i.consignor_id = u.id
    WHERE i.status = 'approved'
    ORDER BY i.submitted_at DESC
");
$items = $stmt->fetchAll();

$pageTitle = 'Browse Collection';
require __DIR__ . '/../../src/views/layouts/header.php';
require __DIR__ . '/../../src/views/items/browse_view.php';