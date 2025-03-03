<?php
session_start();
require 'includes/db.php';
require 'includes/functions.php';

if (!isset($_SESSION['user_id'])) {
    redirect('login.php');
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Récupérer les informations de l'utilisateur
$stmt = $conn->prepare("SELECT * FROM users WHERE id = :user_id");
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de Bord</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="dashboard-container">
        <h2>Bienvenue, <?php echo $user['full_name']; ?></h2>
        <nav>
            <ul>
                <li><a href="pages/employees.php">Gestion des Employés</a></li>
                <li><a href="pages/schedules.php">Gestion des Horaires</a></li>
                <li><a href="pages/absences.php">Gestion des Absences</a></li>
                <li><a href="pages/reports.php">Rapports</a></li>
                <li><a href="logout.php">Déconnexion</a></li>
            </ul>
        </nav>
    </div>
</body>
</html>
