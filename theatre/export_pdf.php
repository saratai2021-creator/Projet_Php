<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

require_once 'tcpdf/tcpdf.php';

$host = "127.0.0.1";
$port = "3307";
$dbname = "theatre_db";
$user = "root";
$pass = "";

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

 
    $stmt = $pdo->query("SELECT id, nom, prenom, email, role, created_at FROM utilisateurs ORDER BY created_at DESC");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

  
    $pdf = new TCPDF();
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Theatre DB');
    $pdf->SetTitle('Liste des utilisateurs inscrits');
    $pdf->SetHeaderData('', 0, 'Liste des utilisateurs inscrits', '');

    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetMargins(15, 27, 15);
    $pdf->SetHeaderMargin(5);
    $pdf->SetFooterMargin(10);
    $pdf->SetAutoPageBreak(TRUE, 25);
    $pdf->SetFont('dejavusans', '', 10);

    $pdf->AddPage();

    // محتوى الـ PDF - جدول
    $html = '<h2>Liste des utilisateurs inscrits</h2>';
    $html .= '<table border="1" cellpadding="4">';
    $html .= '<tr bgcolor="#cccccc">
                <th>ID</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Role</th>
                <th>Date d\'inscription</th>
              </tr>';

    foreach ($users as $user) {
        $html .= '<tr>
                    <td>'.$user['id'].'</td>
                    <td>'.htmlspecialchars($user['nom']).'</td>
                    <td>'.htmlspecialchars($user['prenom']).'</td>
                    <td>'.htmlspecialchars($user['email']).'</td>
                    <td>'.htmlspecialchars($user['role']).'</td>
                    <td>'.$user['created_at'].'</td>
                  </tr>';
    }

    $html .= '</table>';

    $pdf->writeHTML($html, true, false, true, false, '');


    $pdf->Output('utilisateurs.pdf', 'I'); 
} catch (PDOException $e) {
    die("Erreur base de données : " . $e->getMessage());
}
?>
