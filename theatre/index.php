<?php 

$host = "127.0.0.1";
$port = "3307";
$dbname = "theatre_db";
$user = "root";
$pass = "";

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    
    $events = $pdo->query("SELECT * FROM evenements ORDER BY date_event ASC")->fetchAll();

} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plateforme des Piece de Theatre</title>
    <link rel="stylesheet" href="bootstrap-5.3.3-dist/css/bootstrap.min.css">
<!-- <link rel="stylesheet" href="index.css"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
  /* ========== BASE STYLES ========== */
body {
  background: linear-gradient(135deg, #0d0d0d, #1a1a1a);
  font-family: 'Poppins', sans-serif;
  color: #ffffff;
  margin: 0;
  padding: 0;
  overflow-x: hidden;
}

/* ========== NAVBAR ========== */
nav {
  background: rgba(255, 0, 0, 0.1);
  border: 1px solid rgba(248, 205, 11, 0.9);
  backdrop-filter: blur(10px);
  margin: 30px auto;
  width: 100%;
  border-radius: 15px;
  box-shadow: 0 8px 25px rgba(255, 0, 0, 0.2);
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px 40px;
}

.navbar-brand {
  font-size: 30px;
  font-weight: bold;
  color: #ff0000;
  text-shadow: 0 0 10px rgba(255, 0, 0, 0.6);
  transition: 0.3s;
}

.navbar-brand:hover {
  color: #ffffff;
}

.nav-link {
  font-size: 18px;
  color: #ffffff;
  margin-left: 25px;
  transition: 0.3s ease;
  position: relative;
}

.nav-link::after {
  content: "";
  position: absolute;
  width: 0%;
  height: 2px;
  bottom: -5px;
  left: 0;
  background: red;
  transition: width 0.3s ease;
}

.nav-link:hover {
  color: red;
}

.nav-link:hover::after {
  width: 100%;
}

/* ========== HERO SECTION ========== */
.hero {
  background:
    linear-gradient(to bottom, rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)),
    url('img4.jpg') center/cover no-repeat;
  min-height: 500px;
  padding: 120px 40px;
  text-align: center;
  color:rgb(228, 66, 66);
  backdrop-filter: blur(3px);
  border-radius: 30px;
  margin: 30px 5%;
  box-shadow: 0 0 20px rgb(243, 225, 59);
}

.hero h1 {
  font-size: 50px;
  font-weight: bold;
  margin-bottom: 20px;
  text-shadow: 0 0 15px red;
}

.hero p {
  font-size: 20px;
  font-style: italic;
  color: #dddddd;
}

/* ========== ARROW ========== */
.scroll-down-arrow {
  font-size: 3rem;
  color: red;
  animation: bounce 2s infinite;
  margin-top: 30px;
}

@keyframes bounce {

  0%,
  20%,
  50%,
  80%,
  100% {
    transform: translateY(0);
  }

  40% {
    transform: translateY(12px);
  }

  60% {
    transform: translateY(6px);
  }
}

/* ========== SECTION 2 ========== */
#s2 {
  background: rgba(255, 0, 0, 0.05);
  backdrop-filter: blur(10px);
  margin: 60px 5%;
  padding: 40px;
  border-radius: 30px;
  border:1px solid rgba(248, 205, 11, 0.9);
  box-shadow: 0 10px 30px rgba(206, 33, 33, 0.94);
  color: #ffffff;
  text-align: center;
  margin:auto;
 
}

#s2 h2 {
  font-size: 32px;
  color: #ff0000;
  margin-bottom: 20px;
}

/* ========== CARDS ========== */
.custom-card {
  background: linear-gradient(135deg, #1a1a1a, #0d0d0d);
  backdrop-filter: blur(10px);
  color: white;
  position: relative;
  min-height: 350px;
  border-radius: 20px;
  overflow: hidden;
  margin: 20px;
  transition: transform 0.4s ease, box-shadow 0.4s ease;
  box-shadow: 0 0 15px rgba(255, 0, 0, 0.1);
  border: 1px solid rgba(238, 235, 52, 0.86);
}

.custom-card:hover {
  transform: scale(1.07);
  box-shadow: 0 0 30px rgba(224, 15, 15, 0.82);
}

.custom-card-content {
  position: relative;
  z-index: 2;
  padding: 25px;
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: center;
  text-align: center;
  font-weight: bold;
  font-style: italic;
}
</style>
</head>
<body>
   
        <nav class="navbar navbar-expand-lg">
    <div class="container">
      <a class="navbar-brand" href="#"><svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-bluesky" viewBox="0 0 16 16">
  <path d="M3.468 1.948C5.303 3.325 7.276 6.118 8 7.616c.725-1.498 2.698-4.29 4.532-5.668C13.855.955 16 .186 16 2.632c0 .489-.28 4.105-.444 4.692-.572 2.04-2.653 2.561-4.504 2.246 3.236.551 4.06 2.375 2.281 4.2-3.376 3.464-4.852-.87-5.23-1.98-.07-.204-.103-.3-.103-.218 0-.081-.033.014-.102.218-.379 1.11-1.855 5.444-5.231 1.98-1.778-1.825-.955-3.65 2.28-4.2-1.85.315-3.932-.205-4.503-2.246C.28 6.737 0 3.12 0 2.632 0 .186 2.145.955 3.468 1.948"/>
</svg>
           ThéâtreLive</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
       
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="#">Accueil</a></li>
          <li class="nav-item"><a class="nav-link" href="#">Événements</a></li>
          <li class="nav-item"><a class="nav-link" href="#">Connexion</a></li>
        </ul>
      </div>
    </div>
  </nav>
    <section class="hero">
    <h1>Bienvenue sur notre plateforme</h1>
    <p>Découvrez, suivez et participez aux plus belles pièces de théâtre en ville !</p>
    <div class="d-flex justify-content-center gap-2">
            <a href="login.php" class="btn btn-outline-light">Connexion</a>
            <a href="inscription.php" class="btn btn-light">Inscription</a>
          </div>
   <div class="scroll-down-arrow mt-4">
    <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-arrow-down-circle-fill" viewBox="0 0 16 16">
  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v5.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293z"/>
</svg>
  </div>
  </section>
  <section class="container my-5" id="s2">
    <h2 class="text-center mb-4">À propos du théâtre</h2>
    <p class="text-center">
  Le théâtre est un art vivant, une passion, un voyage à travers les émotions.  
  C’est un espace où les histoires prennent vie, où chaque représentation offre une expérience unique qui nous connecte à notre humanité profonde.  
  Sur notre plateforme, vous trouverez des pièces inédites, classiques ou modernes, soigneusement sélectionnées pour éveiller votre curiosité et toucher votre cœur.  
  Que vous soyez amateur de drame, de comédie ou d’expériences théâtrales innovantes, notre sélection vous invite à découvrir la richesse et la diversité du monde théâtral.  
  Rejoignez-nous pour partager des moments inoubliables, nourrir votre esprit et enrichir votre sensibilité artistique.
</p>
<p class="text-center mt-4">
  Le théâtre n’est pas seulement un divertissement, c’est un miroir de la société.  
  À travers les personnages, les dialogues et les scènes, il reflète nos joies, nos peurs, nos conflits et nos espoirs.  
  Notre objectif est de rendre cet art accessible à tous, en proposant des spectacles de qualité et en valorisant les talents locaux et émergents.  
  Ensemble, faisons revivre la magie du théâtre dans chaque cœur et chaque ville.
</p>
<p class="text-center mt-4">
  Chaque représentation théâtrale est une rencontre unique entre les artistes et le public.  
  C’est un moment vivant, éphémère, où l’émotion circule librement et où chaque spectateur devient acteur de l’histoire.  
  Le théâtre nous rassemble, nous fait réfléchir, et parfois même nous transforme.  
  Sur cette plateforme, nous croyons que chaque pièce peut éveiller les consciences et toucher les âmes.
</p>

  </section>

  <div class="container my-5" id="d2">
  <div class="row">

    <!-- Card 1 -->
    <!-- <div class="col-md-4 mb-4">
      <div class="custom-card" style="background-image: url('img1.jpg');">
        <div class="custom-card-content">
          <h5 class="card-title">Masrahiyat Al Amal</h5>
          <p class="card-text">Une pièce qui raconte l'espoir d'une génération en quête de lumière.</p>
          <p class="text-light">Date : 10 Juin 2025</p>
          
        </div>
      </div>
    </div> -->

    
<?php foreach ($events as $event): ?>
  <div class="col-md-4 mb-4">
    <div class="custom-card" style="background-image: url('img2.jpg');">
      <div class="custom-card-content">
        <h5 class="card-title"><?= htmlspecialchars($event['titre']) ?></h5>
        <p class="card-text"><?= htmlspecialchars($event['description']) ?></p>
        <p class="text-light">Date : <?= htmlspecialchars($event['date_event']) ?></p>
        <div class="d-flex justify-content-center gap-2">
          <a href="login.php" class="btn btn-outline-light">Connexion</a>
          <a href="inscription.php" class="btn btn-light">Inscription</a>
        </div>
      </div>
    </div>
  </div>
<?php endforeach; ?>

   
   

  </div>
</div>
<footer class="bg-dark text-white pt-4 pb-3 mt-5">
  <div class="container text-center">
    <h4 class="mb-3">ThéâtreLive</h4>
    <p class="mb-4">
      Rejoignez-nous pour découvrir des spectacles inspirants et vivre l'émotion du théâtre en direct.
    </p>

    <!-- Social Media Icons -->
    <div class="mb-4">
      <a href="https://facebook.com" target="_blank" class="text-white me-3 fs-4"><i class="fab fa-facebook"></i></a>
      <a href="https://instagram.com" target="_blank" class="text-white me-3 fs-4"><i class="fab fa-instagram"></i></a>
      <a href="https://wa.me/212600000000" target="_blank" class="text-white fs-4"><i class="fab fa-whatsapp"></i></a>
    </div>

    <!-- Copy -->
    <p class="mb-0">&copy; 2025 ThéâtreLive. Tous droits réservés.</p>
  </div>
</footer>

</body>
</html>