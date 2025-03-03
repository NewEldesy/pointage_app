<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
redirectIfNotLoggedIn();

if (!isAdmin()) {
    die("Accès refusé.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $stmt = $pdo->prepare("INSERT INTO departments (name) VALUES (:name)");
    $stmt->execute(['name' => $name]);
    echo "Département ajouté.";
}
?>

<form method="POST">
    <input type="text" name="name" placeholder="Nom du département" required>
    <button type="submit">Ajouter</button>
</form>
