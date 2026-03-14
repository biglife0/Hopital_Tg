<?php
// Configuration de la base de données
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'hospital_db');

// Connexion à la base de données
try {
    $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->exec("SET NAMES utf8mb4");
} catch(PDOException $e) {
    die("Erreur de connexion: " . $e->getMessage());
}

// Démarrer la session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Fonction de sécurisation
define('SITE_URL', 'http://localhost/hospital_project/');

function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

function show_message($message, $type = 'success') {
    return '<div class="alert alert-'.$type.' alert-dismissible fade show" role="alert">
            '.$message.'
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>';
}
?>
