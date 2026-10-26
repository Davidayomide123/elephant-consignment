<?php
// src/includes/auth.php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/gmail_mailer.php'; // Include the Gmail Mailer

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function registerUser($email, $password, $displayName) {
    $db = getDb();
    
    // Check if email exists
    $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        return "Email is already registered.";
    }

    // Hash password and insert
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $db->prepare("INSERT INTO users (email, password_hash, display_name, role) VALUES (?, ?, ?, 'consignor')");
    
    try {
        $stmt->execute([$email, $hash, $displayName]);
        
        // --- SEND WELCOME EMAIL ---
        $subject = "Welcome to Elephant Logistics, {$displayName}!";
        $body = "
            <div style='font-family: Arial, sans-serif; background-color: #f5f4f1; padding: 40px; color: #3f3c34;'>
                <div style='max-width: 600px; margin: 0 auto; background-color: #eae7e1; padding: 30px; border-radius: 20px; border: 1px solid #d6d1c7;'>
                    <h2 style='color: #6f8569; margin-top: 0;'>Hello {$displayName},</h2>
                    <p>Welcome to the herd! Your account has been created successfully.</p>
                    <p>You can now log in to track your shipments, receive alerts, and manage your consignments.</p>
                    
                    <a href='http://localhost:8000/auth/login.php' style='display: inline-block; background-color: #6f8569; color: white; padding: 12px 24px; text-decoration: none; border-radius: 25px; font-weight: bold; margin-top: 15px;'>Go to Login</a>
                    
                    <p style='font-size: 12px; color: #948d7e; margin-top: 30px;'>Breathe in. Breathe out. Welcome aboard.</p>
                </div>
            </div>
        ";
        
        // Send the email (using the function we created)
        sendGmailEmail($email, $displayName, $subject, $body);
        // --------------------------

        return true;
    } catch (Exception $e) {
        return "Registration failed. Please try again.";
    }
}

function loginUser($email, $password) {
    $db = getDb();
    $stmt = $db->prepare("SELECT id, password_hash, role, display_name FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        // Regenerate session ID for security
        session_regenerate_id(true);
        
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['display_name'] = $user['display_name'];
        return true;
    }
    
    return "Invalid email or password.";
}

function logoutUser() {
    $_SESSION = [];
    session_destroy();
    header("Location: /index.php");
    exit;
}

function requireLogin() {
    if (empty($_SESSION['user_id'])) {
        header("Location: /auth/login.php");
        exit;
    }
}

function requireRole($role) {
    requireLogin();
    if ($_SESSION['role'] !== $role) {
        http_response_code(403);
        die("Access Denied. You do not have permission to view this page.");
    }
}

// Helper to escape output
function e($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}