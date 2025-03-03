<?php
session_start();
require '../includes/db.php';
require '../includes/functions.php';

if (!isAdmin()) {
    redirect('../index.php');
}

// Récupérer tous les employés
$stmt = $conn->query("SELECT * FROM users");
$employees = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Employés</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Gestion des Employés</h2>
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Rôle</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($employees as $employee): ?>
                <tr>
                    <td><?php echo $employee['full_name']; ?></td>
                    <td><?php echo $employee['role']; ?></td>
                    <td><?php echo $employee['email']; ?></td>
                    <td>
                        <a href="edit_employee.php?id=<?php echo $employee['id']; ?>">Modifier</a>
                        <a href="delete_employee.php?id=<?php echo $employee['id']; ?>">Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
