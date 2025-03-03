<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
redirectIfNotLoggedIn();

if (!isAdmin()) {
    die("Accès refusé.");
}

// Ajouter une équipe
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_team'])) {
    $name = $_POST['name'];
    $department_id = $_POST['department_id'];

    $stmt = $pdo->prepare("INSERT INTO teams (name, department_id) VALUES (:name, :department_id)");
    $stmt->execute(['name' => $name, 'department_id' => $department_id]);
    echo "Équipe ajoutée avec succès.";
}

// Supprimer une équipe
if (isset($_GET['delete_team'])) {
    $team_id = $_GET['delete_team'];
    $stmt = $pdo->prepare("DELETE FROM teams WHERE id = :id");
    $stmt->execute(['id' => $team_id]);
    echo "Équipe supprimée avec succès.";
}

// Récupérer la liste des départements et équipes
$departments = $pdo->query("SELECT * FROM departments")->fetchAll(PDO::FETCH_ASSOC);
$teams = $pdo->query("SELECT t.id, t.name, d.name AS department_name FROM teams t JOIN departments d ON t.department_id = d.id")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gérer les équipes</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <h1>Gérer les équipes</h1>

    <!-- Formulaire pour ajouter une équipe -->
    <form method="POST">
        <h2>Ajouter une équipe</h2>
        <label for="name">Nom de l'équipe :</label>
        <input type="text" name="name" id="name" required>

        <label for="department_id">Département :</label>
        <select name="department_id" id="department_id" required>
            <?php foreach ($departments as $department): ?>
                <option value="<?= $department['id'] ?>"><?= $department['name'] ?></option>
            <?php endforeach; ?>
        </select>

        <button type="submit" name="add_team">Ajouter</button>
    </form>

    <!-- Liste des équipes -->
    <h2>Liste des équipes</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Département</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($teams as $team): ?>
                <tr>
                    <td><?= $team['id'] ?></td>
                    <td><?= $team['name'] ?></td>
                    <td><?= $team['department_name'] ?></td>
                    <td>
                        <a href="?delete_team=<?= $team['id'] ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette équipe ?')">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="dashboard.php">Retour au tableau de bord</a>
</body>
</html>
