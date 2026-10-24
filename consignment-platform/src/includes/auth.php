<?php
// src/includes/auth.php
require_once __DIR__ . '/../config/database.php';

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