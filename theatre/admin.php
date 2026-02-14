<?php
session_start();

// Vérification si l'utilisateur est admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

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
        date_event DATE NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    $pdo->exec($sql);

    // Traiter la modification d'un événement
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['modifier'])) {
        $id = intval($_POST['id']);
        $titre = trim($_POST['titre'] ?? '');
        $desc = trim($_POST['description'] ?? '');
        $date = $_POST['date_event'] ?? '';

        if ($titre && $date) {
            $stmt = $pdo->prepare("UPDATE evenements SET titre = ?, description = ?, date_event = ? WHERE id = ?");
            $stmt->execute([$titre, $desc, $date, $id]);
            header("Location: admin.php");
            exit();
        }
    }

    // Ajouter un événement
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['ajouter'])) {
        $titre = trim($_POST['titre'] ?? '');
        $desc = trim($_POST['description'] ?? '');
        $date = $_POST['date_event'] ?? '';

        if ($titre && $date) {
            $stmt = $pdo->prepare("INSERT INTO evenements (titre, description, date_event) VALUES (?, ?, ?)");
            $stmt->execute([$titre, $desc, $date]);
            header("Location: admin.php");
            exit();
        }
    }

    // Supprimer un événement
    if (isset($_GET['supprimer'])) {
        $id = intval($_GET['supprimer']);
        $stmt = $pdo->prepare("DELETE FROM evenements WHERE id = ?");
        $stmt->execute([$id]);
        header("Location: admin.php");
        exit();
    }

    // Récupérer tous les événements
    $events = $pdo->query("SELECT * FROM evenements ORDER BY date_event ASC")->fetchAll();

    // Récupérer un événement pour modification (si demandé)
    $editEvent = null;
    if (isset($_GET['modifier'])) {
        $id = intval($_GET['modifier']);
        $stmt = $pdo->prepare("SELECT * FROM evenements WHERE id = ?");
        $stmt->execute([$id]);
        $editEvent = $stmt->fetch(PDO::FETCH_ASSOC);
    }

} catch (PDOException $e) {
    die("Erreur base de données : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Admin - Gestion des événements</title>
    <style>
      
  /* Appliquer box-sizing à tous les éléments pour un meilleur contrôle des dimensions */
* {
    box-sizing: border-box;
}

/* Style général du corps de la page : police élégante et ambiance sombre théâtrale */
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background:rgb(0, 0, 0); /* Fond sombre boisé, ambiance théâtre */
    margin: 0;
    padding: 20px;
    color: #f0e5da; /* Couleur beige clair pour le texte, doux sur fond sombre */
}

/* Titres stylisés avec une couleur rouge théâtrale et une ombre */
h2, h3 {
    color: #e63946; /* Rouge rideau de théâtre */
    text-align: center;
    margin-bottom: 20px;
    font-weight: 700;
    text-shadow: 0 1px 0 #3d0000; /* Ombre subtile */
}

/* Conteneur principal avec effet boisé et ombre dorée */
.container {
    max-width: 900px;
    margin: 0 auto;
    background: #2c2421; /* Marron foncé, bois intérieur */
    padding: 40px 50px;
    border-radius: 15px;
    box-shadow: 0 10px 25px rgba(240, 49, 49, 0.95); /* Lueur dorée subtile */
    border: 2px solid rgb(243, 231, 64); /* Bordure bois chaud */
}

/* Espacement entre les formulaires */
form {
    margin-bottom: 40px;
}

/* Étiquettes des champs de formulaire */
label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    font-size: 0.95rem;
    color: #f4d6aa; /* Doré doux */
}

/* Champs de texte, dates, sélection et zone de texte */
input[type="text"],
input[type="date"],
textarea,
select {
    width: 100%;
    padding: 12px 15px;
    margin-bottom: 20px;
    border: 2px solid #a77b4d; /* Bordure brun doré */
    border-radius: 8px;
    font-size: 1rem;
    color: #1e1a19;
    background: #fcefe3; /* Fond clair rappelant les parchemins */
    transition: border-color 0.3s ease;
}

/* Effet de surbrillance des champs actifs */
input[type="text"]:focus,
input[type="date"]:focus,
textarea:focus,
select:focus {
    border-color: #fcbf49; /* Doré lumineux */
    outline: none;
}

/* Zone de texte redimensionnable */
textarea {
    resize: vertical;
    min-height: 80px;
}

/* Bouton principal stylisé en rouge théâtre */
button {
    background-color: #b3001b; /* Rouge rideau */
    color: white;
    border: none;
    padding: 14px 0;
    font-size: 1.2rem;
    font-weight: 700;
    border-radius: 10px;
    cursor: pointer;
    box-shadow: 0 5px 15px rgba(179, 0, 27, 0.4);
    transition: background-color 0.3s ease, transform 0.2s ease;
}

/* Effet hover sur le bouton */
button:hover {
    background-color: #800010;
    transform: scale(1.05);
}

/* Carte d’un événement avec bordure rouge et fond sombre */
.event {
    background: #3b2e2a;
    border-left: 5px solid #e63946;
    padding: 20px;
    margin-bottom: 25px;
    border-radius: 12px;
    position: relative;
    color: #f0e5da;
   
    
}

/* Titre d’un événement */
.event strong {
    font-size: 1.3rem;
    color: #fcbf49; /* Doré théâtral */
}

/* Date de l’événement en italique */
.event-date {
    font-style: italic;
    color: #f4d6aa;
    margin-bottom: 10px;
}

/* Texte de description d’un événement */
.event p {
    margin: 10px 0;
    line-height: 1.5;
}

/* Liens d'action (modifier, supprimer) positionnés en haut à droite */
.actions {
     margin-top: 15px; 
    text-align: right;
    display: flex;
    flex-wrap: wrap;   
    justify-content: flex-end; 
    gap: 10px
}

/* Style général des liens d'action */
.actions a {
     flex-shrink: 0;
    margin-left: 12px;
    color: #fcbf49;
    font-weight: 600;
    text-decoration: none;
    border: 2px solid #fcbf49;
    padding: 7px 14px;
    border-radius: 10px;
    transition: background-color 0.3s ease, color 0.3s ease;
}

/* Lien de suppression en rouge doux */
.actions a.delete-link {
    color:rgb(236, 20, 9);
    border-color:rgba(185, 9, 0, 0.85);
}

/* Effet hover sur les liens d’action */
.actions a:hover {
    background-color: #fcbf49;
    color: #1e1a19;
}

/* Hover spécifique pour le lien de suppression */
.actions a.delete-link:hover {
    background-color: #ff6961;
    color: white;
}

/* Section de déconnexion au bas de la page */
.logout {
    text-align: center;
    margin-top: 40px;
}

/* Lien de déconnexion */
.logout a {
    color:rgb(240, 18, 18);
    font-weight: 600;
    text-decoration: none;
    transition: color 0.3s ease;
}

/* Effet hover sur le lien de déconnexion */
.logout a:hover {
    color: #ffd166;
    text-decoration: underline;
}

/* Responsive : adaptation aux petits écrans */
@media (max-width: 600px) {
    body {
        padding: 10px;
    }
    .container {
        padding: 30px 20px;
    }
    .actions {
        position: static;
        margin-top: 10px;
        text-align: right;
    }
    .actions a {
        margin-left: 0;
        margin-right: 10px;
    }
}



    </style>
</head>
<body>
    <div class="container">
        <h2>Bienvenue <?= htmlspecialchars($_SESSION['nom']) ?> (Admin)</h2>

        <?php if ($editEvent): ?>
            <h3>Modifier l'événement</h3>
            <form method="post" action="">
                <input type="hidden" name="id" value="<?= $editEvent['id'] ?>">
                <label for="titre">Titre :</label>
                <input type="text" id="titre" name="titre" required value="<?= htmlspecialchars($editEvent['titre']) ?>">

                <label for="date_event">Date :</label>
                <input type="date" id="date_event" name="date_event" required value="<?= htmlspecialchars($editEvent['date_event']) ?>">

                <label for="description">Description :</label>
                <textarea id="description" name="description"><?= htmlspecialchars($editEvent['description']) ?></textarea>

                <button type="submit" name="modifier">Enregistrer</button>
                <a href="admin.php" style="margin-left: 15px; color: #555; font-weight: 600; text-decoration: none;">Annuler</a>
            </form>
        <?php else: ?>
            <h3>Ajouter un événement</h3>
            <form method="post" action="">
                <label for="titre">Titre :</label>
                <input type="text" id="titre" name="titre" required>

                <label for="date_event">Date :</label>
                <input type="date" id="date_event" name="date_event" required>

                <label for="description">Description :</label>
                <textarea id="description" name="description"></textarea>

                <button type="submit" name="ajouter">Ajouter</button>
            </form>
        <?php endif; ?>

        <h3>Liste des événements</h3>
        <?php if (count($events) === 0): ?>
            <p style="text-align:center; color: #555;">Aucun événement pour le moment.</p>
        <?php else: ?>
            <?php foreach ($events as $event): ?>
                <div class="event">
                    <strong><?= htmlspecialchars($event["titre"]) ?></strong>
                    <div class="event-date"><?= htmlspecialchars($event["date_event"]) ?></div>
                    <p><?= nl2br(htmlspecialchars($event["description"])) ?></p>
                    <div class="actions">
                        <a href="?modifier=<?= $event["id"] ?>">Modifier</a>
                        <a href="?supprimer=<?= $event["id"] ?>" class="delete-link" onclick="return confirm('Supprimer cet événement ?')">Supprimer</a>
                         <a href="export_pdf.php" target="_blank" class="btn btn-success">Télécharger la liste PDF</a> 
                         <a href="export_excel.php" class="btn btn-success">Exporter la liste en Excel</a>

                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <div class="logout">
            <a href="logout.php">Déconnexion</a>
        </div>
    </div>
</body>
</html>
