<?php
session_start();
require '../includes/db.php';
require '../includes/functions.php';

if (!isAdmin() && !isManager()) {
    redirect('../index.php');
}

// Récupérer tous les horaires
$stmt = $conn->query("SELECT schedules.*, users.full_name FROM schedules JOIN users ON schedules.user_id = users.id");
$schedules = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Horaires</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Gestion des Horaires</h2>
        <table>
            <thead>
                <tr>
                    <th>Employé</th>
                    <th>Heure de Début</th>
                    <th>Heure de Fin</th>
                    <th>Type de Travail</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($schedules as $schedule): ?>
                <tr>
                    <td><?php echo $schedule['full_name']; ?></td>
                    <td><?php echo $schedule['start_time']; ?></td>
                    <td><?php echo $schedule['end_time']; ?></td>
                    <td><?php echo $schedule['work_type']; ?></td>
                    <td>
                        <a href="edit_schedule.php?id=<?php echo $schedule['id']; ?>">Modifier</a>
                        <a href="delete_schedule.php?id=<?php echo $schedule['id']; ?>">Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
