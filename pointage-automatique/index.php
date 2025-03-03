<?php
session_start();
require_once 'includes/auth.php';
require_once 'includes/db.php';

// Rediriger si l'utilisateur n'est pas connecté
if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

// Récupérer les informations de l'utilisateur
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT username, role FROM users WHERE id = :user_id");
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Afficher le tableau de bord en fonction du rôle
if (isAdmin()) {
    echo "Bienvenue, Administrateur " . $user['username'];
    echo "<br><a href='admin/dashboard.php'>Tableau de bord Admin</a>";
} elseif (isRH()) {
    echo "Bienvenue, RH " . $user['username'];
    echo "<br><a href='reports/generate_report.php'>Générer un rapport</a>";
} else {
    echo "Bienvenue, Employé " . $user['username'];
    echo "<br><a href='#'>Pointage</a>";
}

echo "<br><a href='logout.php'>Déconnexion</a>";
?>
