<<?php
session_start();

$host = "127.0.0.1";
$port = "3307";
$dbname = "theatre_db";
$user = "root";
$pass = "";

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->query("SELECT id, nom, prenom, email, role, created_at FROM utilisateurs ORDER BY id ASC");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Headers pour forcer le téléchargement du fichier CSV
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=liste_utilisateurs.csv');

    $output = fopen('php://output', 'w');

    // En-tête CSV
    fputcsv($output, ['ID', 'Nom', 'Prénom', 'Email', 'Role', 'Date inscription']);

    // Parcourir et écrire chaque ligne
    foreach ($users as $user) {
        fputcsv($output, $user);
    }

    fclose($output);
    exit();

} catch (PDOException $e) {
    echo "Erreur base de données : " . $e->getMessage();
}
