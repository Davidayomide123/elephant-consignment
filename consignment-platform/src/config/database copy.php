<?php
// src/config/database.php

function getDb(): PDO {
    // Standard local settings
    $host = '127.0.0.1';
    $db   = 'consignment_db';
    $user = 'root';
    $pass = 'YOUR_DB_PASSWORD_HERE';  // CHANGE THIS if you set a root password in MySQL Configurator!
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
        return new PDO($dsn, $user, $pass, $options);
    } catch (\PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
}