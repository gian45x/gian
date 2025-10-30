
<?php
// migrate.php

$host = 'localhost';
$db   = 'my_portfolio';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    echo "✅ Connected to database successfully.\n";
} catch (PDOException $e) {
    die("❌ Database connection failed: " . $e->getMessage());
}

// Include migration file
$migration = include 'migration.php'; // <--- returns a function
$migration($pdo); // run the migration