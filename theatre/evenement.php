<?php
$host = "127.0.0.1";
$port = "3307";
$dbname = "theatre_db";
$user = "root";
$pass = "";

try {
    
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    
    $sql = "CREATE TABLE IF NOT EXISTS evenements (
        id INT AUTO_INCREMENT PRIMARY KEY,
        titre VARCHAR(255) NOT NULL,
        description TEXT,
        date_event DATE NOT NULL,
        lieu VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

  
    $pdo->exec($sql);

    echo "Table 'evenements' créée ou déjà existante.";

} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Liste des événements</title>
</head>
<body>
    <h2>Événements à venir</h2>
    <?php if (isset($message)): ?>
        <p style="color:green;"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>
    <ul>
        <?php foreach ($evenements as $event): ?>
            <li>
                <strong><?= htmlspecialchars($event['titre']) ?></strong> - <?= htmlspecialchars($event['date_event']) ?>
                <form method="post" style="display:inline;">
                    <input type="hidden" name="evenement_id" value="<?= $event['id'] ?>" />
                    <button type="submit">S'inscrire</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
