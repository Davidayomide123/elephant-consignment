<?php
// public/admin/storefront.php
require_once __DIR__ . '/../../src/includes/auth.php';
requireRole('admin');

$db = getDb();
$message = '';
$error = '';

// Handle Delete Item
if (isset($_POST['action']) && $_POST['action'] === 'delete_item') {
    $item_id = (int)$_POST['item_id'];
    
    try {
        // 1. Delete image files from server
        $stmt = $db->prepare("SELECT file_path FROM item_images WHERE item_id = ?");
        $stmt->execute([$item_id]);
        $images = $stmt->fetchAll();
        
        foreach ($images as $img) {
            $filePath = __DIR__ . '/../' . $img['file_path'];
            if (file_exists($filePath)) {
                unlink($filePath); // Delete physical file
            }
        }

        // 2. Delete item folder if empty
        $folderPath = __DIR__ . '/../assets/uploads/items/' . $item_id;
        if (is_dir($folderPath)) {
            @rmdir($folderPath); // Suppress error if folder isn't empty
        }

        // 3. Delete from database (this will cascade and delete item_images records)
        $stmt = $db->prepare("DELETE FROM items WHERE id = ?");
        $stmt->execute([$item_id]);
        
        $message = "Item successfully deleted.";
    } catch (Exception $e) {
        $error = "Error deleting item: " . $e->getMessage();
    }
}

// Handle Item Creation
if (isset($_POST['action']) && $_POST['action'] === 'add_item') {
    $title = trim($_POST['title']);
    $desc = trim($_POST['description']);
    $category = $_POST['category'];
    $condition = $_POST['condition'];
    $price = (float)$_POST['asking_price'];
    $email = filter_var($_POST['consignor_email'], FILTER_SANITIZE_EMAIL);

    $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user) {
        $error = "Consignor email not found in database.";
    } else {
        try {
            $db->beginTransaction();
            $sku = 'CON-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));

            $stmt = $db->prepare("INSERT INTO items (consignor_id, title, description, category, `condition`, asking_price, sku, status, approved_at) VALUES (?, ?, ?, ?, ?, ?, ?, 'approved', NOW())");
            $stmt->execute([$user['id'], $title, $desc, $category, $condition, $price, $sku]);
            $item_id = $db->lastInsertId();

            if (!empty($_FILES['images']['name'][0])) {
                $uploadDir = __DIR__ . '/../assets/uploads/items/' . $item_id . '/';
                
                // Force create the directory and check if it failed
                if (!is_dir($uploadDir)) {
                    if (!mkdir($uploadDir, 0777, true)) {
                        throw new Exception("Failed to create directory: " . $uploadDir . ". Check Windows folder permissions.");
                    }
                }

                $fileCount = count($_FILES['images']['name']);
                if ($fileCount > 4) throw new Exception("Maximum 4 images allowed.");

                for ($i = 0; $i < $fileCount; $i++) {
                    if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                        $tmpName = $_FILES['images']['tmp_name'][$i];
                        $mime = mime_content_type($tmpName);
                        $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];

                        if (!array_key_exists($mime, $allowed)) throw new Exception("Invalid image format.");
                        
                        $ext = $allowed[$mime];
                        $fileName = bin2hex(random_bytes(16)) . '.' . $ext;
                        $filePath = $uploadDir . $fileName;
                        $relPath = '/assets/uploads/items/' . $item_id . '/' . $fileName;

                        // The updated move logic
                        if (move_uploaded_file($tmpName, $filePath)) {
                            $isPrimary = ($i === 0) ? 1 : 0;
                            $imgStmt = $db->prepare("INSERT INTO item_images (item_id, file_path, is_primary, sort_order) VALUES (?, ?, ?, ?)");
                            $imgStmt->execute([$item_id, $relPath, $isPrimary, $i]);
                        } else {
                            throw new Exception("PHP failed to move the uploaded file to: " . $filePath . ". Make sure the folder exists and is writable.");
                        }
                    }
                }
            }

            $db->commit();
            $message = "Item successfully added to storefront!";
        } catch (Exception $e) {
            $db->rollBack();
            $error = "Error: " . $e->getMessage();
        }
    }
}

// Fetch current inventory
$items = $db->query("
    SELECT i.*, (SELECT file_path FROM item_images WHERE item_id = i.id AND is_primary = 1 LIMIT 1) as primary_image
    FROM items i 
    ORDER BY i.submitted_at DESC
")->fetchAll();

$pageTitle = 'Manage Storefront';
require __DIR__ . '/../../src/views/layouts/header.php';
require __DIR__ . '/../../src/views/admin/storefront_view.php';