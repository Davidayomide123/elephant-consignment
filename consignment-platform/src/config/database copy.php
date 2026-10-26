<?php
// src/config/database.php

function getDb(): PDO {
    // InfinityFree settings
    $host = 'sql203.infinityfree.com';
    $db   = 'if0_43277377_elephant_db'; // <--- CHANGE TO YOUR FULL DB NAME
    $user = 'if0_43277377';             // <--- YOUR USERNAME
    $pass = 'Bzfv2G65SabrWB8s'; // <--- YOUR INFINITYFREE PASSWORD
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