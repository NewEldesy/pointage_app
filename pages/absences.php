<?php
session_start();
require '../includes/db.php';
require '../includes/functions.php';

if (!isAdmin() && !isManager()) {
    redirect('../index.php');
}

// Récupérer toutes les absences
$stmt = $conn->query("SELECT absences.*, users.full_name FROM absences JOIN users ON absences.user_id = users.id");
$absences = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Absences</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Gestion des Absences</h2>
        <table>
            <thead>
                <tr>
                    <th>Employé</th>
                    <th>Type</th>
                    <th>Date de Début</th>
                    <th>Date de Fin</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($absences as $absence): ?>
                <tr>
                    <td><?php echo $absence['full_name']; ?></td>
                    <td><?php echo $absence['type']; ?></td>
                    <td><?php echo $absence['start_date']; ?></td>
                    <td><?php echo $absence['end_date']; ?></td>
                    <td><?php echo $absence['status']; ?></td>
                    <td>
                        <a href="edit_absence.php?id=<?php echo $absence['id']; ?>">Modifier</a>
                        <a href="delete_absence.php?id=<?php echo $absence['id']; ?>">Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
