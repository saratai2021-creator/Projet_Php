<?php
session_start();

$host = "127.0.0.1";
$port = "3307";
$dbname = "theatre_db";
$user = "root";
$pass = "";

$erreur = "";

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $erreur = "tous les champs obligatoire";
        } else {
           
            $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
              
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['nom'] = $user['nom'];
                $_SESSION['role'] = $user['role'];

               
                if ($user['role'] === 'admin') {
                    header("Location: admin.php");
                    exit();
                } else {
                    header("Location: visiteur.php");
                    exit();
                }
            } else {
                $erreur = "Email ou mot de passe incorrecte !";
            }
        }
    }
} catch (PDOException $e) {
    $erreur = "Erreur " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Page de connexion</title>
    <style>
        /* Reset & basics */
        * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

body {
    background: linear-gradient(to bottom, #0c0c0c, #1a0000); /* black to dark red */
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    color: #ffd700; /* gold */
}

.container {
    background: rgba(50, 0, 0, 0.95); /* deep stage red with opacity */
    padding: 40px 50px;
    border-radius: 20px;
    box-shadow: 0 15px 30px rgba(255, 0, 0, 0.4); /* glowing red */
    width: 360px;
    text-align: center;
    border: 2px solid #ffd700; /* gold border */
}

h2 {
    margin-bottom: 25px;
    font-weight: 800;
    font-size: 2.1rem;
    color: #ff4d4d; /* bright red */
    text-shadow: 0 0 10px #ff0000;
}

form {
    display: flex;
    flex-direction: column;
    gap: 18px;
    margin-bottom: 15px;
}

label {
    font-weight: 600;
    font-size: 0.9rem;
    text-align: left;
    color: #ffd700; /* gold */
}

input, select {
    padding: 12px 15px;
    border: 2px solid #8b0000; /* dark red */
    border-radius: 8px;
    font-size: 1rem;
    background-color: #1a0000;
    color: white;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

input:focus, select:focus {
    border-color: #ffd700;
    outline: none;
    box-shadow: 0 0 8px #ff0000;
}

button {
    margin-top: 10px;
    padding: 14px 0;
    background: #b30000; /* blood red */
    border: none;
    border-radius: 10px;
    color: white;
    font-weight: 700;
    font-size: 1.2rem;
    cursor: pointer;
    box-shadow: 0 6px 20px rgba(255, 0, 0, 0.5);
    transition: background-color 0.3s ease, transform 0.2s ease;
}

button:hover {
    background-color: #ff0000;
    transform: scale(1.05);
}

a.link {
    display: inline-block;
    margin-top: 12px;
    color: #ffd700;
    font-weight: 600;
    text-decoration: underline;
    cursor: pointer;
    transition: color 0.3s ease;
}

a.link:hover {
    color: #ff4d4d;
}

/* Error message style */
.error-msg {
    background-color: #3d0000; /* dark red */
    border: 1px solid #ff4d4d;
    color: #ffb3b3;
    padding: 10px 15px;
    border-radius: 8px;
    margin-bottom: 18px;
    font-weight: 600;
    font-size: 0.95rem;
}
 
    </style>
</head>
<body>
    <div class="container">
        <h2>Connexion</h2>

        <?php if (!empty($erreur)): ?>
            <p class="error-msg"><?= htmlspecialchars($erreur) ?></p>
        <?php endif; ?>

        <form method="post" action="">
            <label for="email">Email :</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required>

            <!-- <label for="role">Rôle :</label>
            <select name="role" id="role" required>
                <option value="visiteur" selected>Visiteur</option>
                <option value="admin">Admin</option>
            </select> -->

            <button type="submit">Se connecter</button>
        </form>

        <a href="inscription.php" class="link">Pas encore de compte ? Créer un compte</a>
    </div>

<script>
    // Just for fun, button scale effect on click
    const btn = document.querySelector('button[type="submit"]');
    btn.addEventListener('mousedown', () => {
        btn.style.transform = 'scale(0.95)';
    });
    btn.addEventListener('mouseup', () => {
        btn.style.transform = 'scale(1)';
    });
    btn.addEventListener('mouseout', () => {
        btn.style.transform = 'scale(1)';
    });
</script>
</body>
</html> 