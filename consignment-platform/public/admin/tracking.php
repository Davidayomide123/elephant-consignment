<?php
// public/admin/tracking.php
require_once __DIR__ . '/../../src/includes/auth.php';
require_once __DIR__ . '/../../src/includes/mailer.php'; // Include the email function
requireRole('admin');

$db = getDb();
$message = '';
$error = '';

// 0. Handle Delete Shipment (Completely wipe it)
if (isset($_POST['action']) && $_POST['action'] === 'delete_shipment') {
    $shipment_id = (int)$_POST['shipment_id'];
    
    try {
        // 1. Find all images attached to the logs of this shipment
        $stmt = $db->prepare("SELECT image_path FROM shipment_logs WHERE shipment_id = ? AND image_path IS NOT NULL");
        $stmt->execute([$shipment_id]);
        $images = $stmt->fetchAll();
        
        // 2. Delete the physical image files
        foreach ($images as $img) {
            $filePath = __DIR__ . '/../' . $img['image_path'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        
        // 3. Delete the empty image folder for this shipment
        $folderPath = __DIR__ . '/../assets/uploads/logs/' . $shipment_id;
        if (is_dir($folderPath)) {
            @rmdir($folderPath); // Suppress error if folder isn't empty
        }

        // 4. Delete the shipment (CASCADE will automatically delete all logs and alerts)
        $stmt = $db->prepare("DELETE FROM shipments WHERE id = ?");
        $stmt->execute([$shipment_id]);
        
        $message = "Consignment completely deleted. The tracking code is now void.";
    } catch (Exception $e) {
        $error = "Error deleting shipment: " . $e->getMessage();
    }
}

// 1. Handle Create Shipment
if (isset($_POST['action']) && $_POST['action'] === 'create_shipment') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $item_name = trim($_POST['item_name']);
    $origin = trim($_POST['origin']);
    $destination = trim($_POST['destination']);
    
    $origin_lat = !empty($_POST['origin_lat']) ? (float)$_POST['origin_lat'] : null;
    $origin_lng = !empty($_POST['origin_lng']) ? (float)$_POST['origin_lng'] : null;
    $dest_lat = !empty($_POST['dest_lat']) ? (float)$_POST['dest_lat'] : null;
    $dest_lng = !empty($_POST['dest_lng']) ? (float)$_POST['dest_lng'] : null;

    $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        $code = 'ELE-' . strtoupper(bin2hex(random_bytes(4)));
        $stmt = $db->prepare("INSERT INTO shipments (tracking_code, user_id, item_name, origin_port, destination_port, origin_lat, origin_lng, destination_lat, destination_lng) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$code, $user['id'], $item_name, $origin, $destination, $origin_lat, $origin_lng, $dest_lat, $dest_lng]);
        
        $shipment_id = $db->lastInsertId();
        $stmt = $db->prepare("INSERT INTO shipment_logs (shipment_id, location, status_message, location_lat, location_lng) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$shipment_id, $origin, "Package received and processed at origin port.", $origin_lat, $origin_lng]);
        
        $message = "Shipment created successfully! Tracking Code: <strong class='font-mono text-lg text-sage-600'>$code</strong>";
    } else {
        $error = "No user found with that email address.";
    }
}

// 2. Handle Add Log / Port Update
if (isset($_POST['action']) && $_POST['action'] === 'add_log') {
    $shipment_id = (int)$_POST['shipment_id'];
    $location = trim($_POST['location']);
    $status_message = trim($_POST['status_message']);
    $is_alert = isset($_POST['is_alert']) ? 1 : 0;
    
    $lat = !empty($_POST['loc_lat']) ? (float)$_POST['loc_lat'] : null;
    $lng = !empty($_POST['loc_lng']) ? (float)$_POST['loc_lng'] : null;
    $image_path = null;

    if ($location && $status_message) {
        try {
            // Handle Image Upload
            if (!empty($_FILES['log_image']['name'])) {
                $uploadDir = __DIR__ . '/../assets/uploads/logs/' . $shipment_id . '/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

                $tmpName = $_FILES['log_image']['tmp_name'];
                $mime = mime_content_type($tmpName);
                $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];

                if (array_key_exists($mime, $allowed)) {
                    $ext = $allowed[$mime];
                    $fileName = bin2hex(random_bytes(16)) . '.' . $ext;
                    $filePath = $uploadDir . $fileName;
                    $image_path = '/assets/uploads/logs/' . $shipment_id . '/' . $fileName;

                    if (!move_uploaded_file($tmpName, $filePath)) {
                        throw new Exception("Failed to save image.");
                    }
                }
            }

            // Insert Log into Database
            $stmt = $db->prepare("INSERT INTO shipment_logs (shipment_id, location, status_message, is_alert, location_lat, location_lng, image_path) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$shipment_id, $location, $status_message, $is_alert, $lat, $lng, $image_path]);
            
            // --- SEND EMAIL IF ALERT IS TRIGGERED ---
            if ($is_alert === 1) {
                $userStmt = $db->prepare("SELECT u.email, u.display_name, s.tracking_code FROM users u JOIN shipments s ON s.user_id = u.id WHERE s.id = ?");
                $userStmt->execute([$shipment_id]);
                $userInfo = $userStmt->fetch();
                
                if ($userInfo) {
                    sendAlertEmail($userInfo['email'], $userInfo['display_name'], $userInfo['tracking_code'], $location, $status_message);
                }
            }
            // ----------------------------------------

            $message = "Port update added successfully.";
        } catch (Exception $e) {
            $error = "Error adding log: " . $e->getMessage();
        }
    } else {
        $error = "Please fill in all fields for the log.";
    }
}

// 3. Handle Dismiss Alert
if (isset($_POST['action']) && $_POST['action'] === 'dismiss_alert') {
    $log_id = (int)$_POST['log_id'];
    
    try {
        $stmt = $db->prepare("UPDATE shipment_logs SET is_alert = 0 WHERE id = ?");
        $stmt->execute([$log_id]);
        $message = "Alert dismissed. The port update remains in the tracking timeline.";
    } catch (Exception $e) {
        $error = "Error dismissing alert: " . $e->getMessage();
    }
}

// Fetch shipments and logs
$shipments = $db->query("SELECT s.*, u.display_name, u.email FROM shipments s JOIN users u ON s.user_id = u.id ORDER BY s.created_at DESC")->fetchAll();
$logs = [];
$logQuery = $db->query("SELECT * FROM shipment_logs ORDER BY created_at DESC");
foreach ($logQuery as $log) {
    $logs[$log['shipment_id']][] = $log;
}

$pageTitle = 'Admin Tracking';
require __DIR__ . '/../../src/views/layouts/header.php';
require __DIR__ . '/../../src/views/admin/tracking_view.php';