<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'visiteur') {
    header("Location: login.php");
    exit();
}

$host = "127.0.0.1";
$port = "3307";
$dbname = "theatre_db";
$user = "root";
$pass = "";

$message = "";

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    $sql = "CREATE TABLE IF NOT EXISTS inscriptions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        event_id INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
        FOREIGN KEY (event_id) REFERENCES evenements(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    $pdo->exec($sql);

    
    $events = $pdo->query("SELECT * FROM evenements ORDER BY date_event ASC")->fetchAll();

 
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['inscrire'])) {
        $userId = $_SESSION['user_id'];
        $eventId = intval($_POST['event_id']);

        
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM inscriptions WHERE user_id = ? AND event_id = ?");
        $stmt->execute([$userId, $eventId]);
        $alreadyRegistered = $stmt->fetchColumn();

        if ($alreadyRegistered) {
            $message = "tTu es déjà inscrit à cet événement.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO inscriptions (user_id, event_id) VALUES (?, ?)");
            $stmt->execute([$userId, $eventId]);
            $message = "Tu t'es inscrit avec succès.";
        }
    }

    $stmt = $pdo->prepare("
        SELECT e.* FROM evenements e
        JOIN inscriptions i ON e.id = i.event_id
        WHERE i.user_id = ?
        ORDER BY e.date_event ASC
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $registeredEvents = $stmt->fetchAll();

} catch (PDOException $e) {
    die("Erreur base de données : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Visiteur - Liste des événements</title>
<style>
   * {
    margin: 0; 
    padding: 0; 
    box-sizing: border-box;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

body {
    background: radial-gradient(circle at top, #1a0000, #000000);
    display: flex;
    justify-content: center;
    align-items: flex-start;
    min-height: 100vh;
    color: #ffd700;
    padding: 40px 0;
}

.container {
    background: rgba(30, 0, 0, 0.95);
    padding: 40px 50px;
    border-radius: 20px;
    box-shadow: 0 0 25px rgba(255, 0, 80, 0.3), 0 0 60px rgba(255, 0, 0, 0.1);
    width: 700px;
    max-width: 95%;
    border: 1.5px solid rgb(250, 247, 59);
}

h2 {
    margin-bottom: 15px;
    font-weight: 700;
    font-size: 2rem;
    text-align: center;
    color: #ff4d4d;
    text-shadow: 0 0 10px #ff0033;
}

h3 {
    margin-bottom: 25px;
    text-align: center;
    color: #ffd700;
    text-shadow: 0 0 8px #ffcc00;
}

.event {
    background-color: rgba(60, 0, 0, 0.6);
    border-left: 5px solid rgb(255, 223, 44);
    padding: 18px 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    box-shadow: 0 0 15px rgba(255, 0, 80, 0.2);
}

.event strong {
    color: #ff4d4d;
    font-size: 1.2rem;
    text-shadow: 0 0 5px #ff0040;
}

.event p {
    margin-top: 8px;
    color: #ffd9b3;
    line-height: 1.6;
}

a.link {
    display: block;
    text-align: center;
    margin-top: 25px;
    font-weight: 600;
    color: #ffd700;
    text-decoration: underline;
    transition: color 0.3s ease, text-shadow 0.3s ease;
}

a.link:hover {
    color: #ff4d4d;
    text-shadow: 0 0 10px #ff0033;
}

form button {
    background-color: #ff4d4d;
    border: none;
    padding: 8px 15px;
    border-radius: 6px;
    color: white;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.3s ease;
    margin-top: 10px;
}

form button:hover {
    background-color: #ff1a1a;
}

.message {
    text-align: center;
    margin-bottom: 20px;
    font-weight: 700;
    color: lightgreen;
    text-shadow: 0 0 5px #00ff00;
}
</style>
</head>
<body>
    <div class="container">
        <h2>Bienvenue <?= htmlspecialchars($_SESSION["nom"]) ?> (Visiteur)</h2>

        <?php if (!empty($message)): ?>
            <p class="message"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <h3>Liste des événements disponibles</h3>
        <?php foreach ($events as $event): ?>
            <div class="event">
                <strong><?= htmlspecialchars($event["titre"]) ?></strong> - <?= htmlspecialchars($event["date_event"]) ?>
                <p><?= nl2br(htmlspecialchars($event["description"])) ?></p>
                <form method="post" action="">
                    <input type="hidden" name="event_id" value="<?= $event['id'] ?>">
                    <button type="submit" name="inscrire">S'inscrire</button>
                </form>
            </div>
        <?php endforeach; ?>

        <h3>Vos événements inscrits</h3>
        <?php if ($registeredEvents): ?>
            <?php foreach ($registeredEvents as $event): ?>
                <div class="event">
                    <strong><?= htmlspecialchars($event["titre"]) ?></strong> - <?= htmlspecialchars($event["date_event"]) ?>
                    <p><?= nl2br(htmlspecialchars($event["description"])) ?></p>
                    <p><em>Tu es inscrit avec nous !</em></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align:center;">Vous n'êtes inscrit à aucun événement pour le moment.</p>
        <?php endif; ?>

        <a href="logout.php" class="link">Déconnexion</a>
    </div>
</body>
</html>
