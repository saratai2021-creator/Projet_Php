<?php
session_start();

$host = "127.0.0.1";
$port = "3307";
$dbname = "theatre_db";
$user = "root";
$pass = "";

$erreur = "";

try {
    // Connexion initiale pour créer la base
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=mysql", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("CREATE DATABASE IF NOT EXISTS $dbname CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");

    // Connexion à la base spécifique
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Création de la table utilisateurs
    $sql = "CREATE TABLE IF NOT EXISTS utilisateurs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nom VARCHAR(100) NOT NULL,
        prenom VARCHAR(100) NOT NULL,
        email VARCHAR(150) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role ENUM('admin', 'visiteur') DEFAULT 'visiteur',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    $pdo->exec($sql);

    // Création d'un admin par défaut si aucun admin
    $stmt = $pdo->query("SELECT COUNT(*) FROM utilisateurs WHERE role = 'admin'");
    if ($stmt->fetchColumn() == 0) {
        $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, prenom, email, password, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute(["Admin", "Principal", "admin@admin.com", password_hash("admin123", PASSWORD_DEFAULT), "admin"]);
    }

    // Traitement du formulaire
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit'])) {
        $nom = trim($_POST['nom'] ?? '');
        $prenom = trim($_POST['prenom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? 'visiteur';  // <-- récupère le role choisi

        if (empty($nom) || empty($prenom) || empty($email) || empty($password)) {
            $erreur = "Toutes les champs sont obligatoires.";
        } else {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM utilisateurs WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetchColumn() > 0) {
                $erreur = "Email est déjà utilisé.";
            } else {
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, prenom, email, password, role) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$nom, $prenom, $email, $passwordHash, $role]);

                $_SESSION['user_id'] = $pdo->lastInsertId();
                $_SESSION['nom'] = $nom;
                $_SESSION['role'] = $role;

                // Redirection selon le role
                if ($role === 'admin') {
                    header("Location: admin.php");
                    exit();
                } else {
                    header("Location: visiteur.php");
                    exit();
                }
            }
        }
    }
} catch (PDOException $e) {
    $erreur = "Erreur de connexion : " . $e->getMessage();
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Page inscription</title>
    <!-- <link rel="stylesheet" href="style1.css" /> -->
     <style>
        /* Reset basics */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

body {
    background-color: #0c0c0c; /* deep black */
    color: #ffd700; /* golden text */
    background-image: linear-gradient(to bottom, #0c0c0c, #1a0000); 
}

.container {
    background: rgba(50, 0, 0, 0.9); /* dark red semi-transparent */
    max-width: 400px;
    margin: 60px auto;
    padding: 40px 30px;
    border-radius: 20px;
    box-shadow: 0 10px 25px rgba(255, 0, 0, 0.3); /* red glow */
    text-align: center;
    border: 2px solid #ffd700; /* golden border */
}

h2 {
    color: #ff4d4d; /* bright red */
    font-weight: 800;
    font-size: 2.3rem;
    margin-bottom: 30px;
    text-shadow: 0 0 10px #ff0000;
}

.form-label {
    display: block;
    text-align: left;
    margin-bottom: 8px;
    font-weight: 600;
    color: #ffd700; /* gold */
    font-size: 1rem;
}

.form-control {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #8b0000; /* dark red */
    border-radius: 8px;
    font-size: 1rem;
    background-color: #1a0000;
    color: white;
    margin-bottom: 20px;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

.form-control:focus {
    border-color: #ffd700; /* gold */
    outline: none;
    box-shadow: 0 0 8px #ff0000;
}

.btn-primary {
    background-color: #b30000; /* blood red */
    border: none;
    border-radius: 12px;
    color: white;
    font-weight: 700;
    font-size: 1.2rem;
    padding: 14px 0;
    cursor: pointer;
    box-shadow: 0 6px 20px rgba(255, 0, 0, 0.5);
    transition: background-color 0.3s ease, transform 0.2s ease;
    width: 100%;
}

.btn-primary:hover {
    background-color: #ff0000;
    transform: scale(1.05);
}

.link {
    display: inline-block;
    margin-top: 20px;
    color: #ffd700;
    font-weight: 600;
    text-decoration: underline;
    cursor: pointer;
    transition: color 0.3s ease;
}

.link:hover {
    color: #ff4d4d;
}

     </style>
</head>
<body>
    <div class="container my-5">
        <h2 class="mb-4 text-center">Créer un compte</h2>

        <?php if (!empty($erreur)) : ?>
            <p style="color: red; text-align: center; font-weight: bold;">
                <?= htmlspecialchars($erreur) ?>
            </p>
        <?php endif; ?>

        <form action="" method="post" class="mx-auto" id="frm">
            <div>
                <label for="nom" class="form-label">Nom :</label>
                <input type="text" class="form-control" id="nom" name="nom" required />
            </div>
            <div>
                <label for="prenom" class="form-label">Prénom :</label>
                <input type="text" class="form-control" id="prenom" name="prenom" required />
            </div>
            <div>
                <label for="email" class="form-label">Email :</label>
                <input type="email" class="form-control" id="email" name="email" required />
            </div>
            <div>
                <label for="password" class="form-label">Mot de passe :</label>
                <input type="password" class="form-control" id="password" name="password" required />
            </div>
            <div>
                <label for="role" class="form-label">Choisissez votre rôle :</label>
                <select name="role" id="role" class="form-control" required>
                    <option value="visiteur" selected>Visiteur</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <button type="submit" name="submit" class="btn btn-primary" id="btn">S'inscrire</button> 
           
        </form>
         <a href="login.php" class="link">Vous avez déjà un compte ? Se connecter</a>
    </div>
</body>
</html>
