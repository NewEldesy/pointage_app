<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
redirectIfNotLoggedIn();

if (!isAdmin()) {
    die("Accès refusé.");
}

// Récupérer des statistiques
$total_departments = $pdo->query("SELECT COUNT(*) FROM departments")->fetchColumn();
$total_teams = $pdo->query("SELECT COUNT(*) FROM teams")->fetchColumn();
$total_users = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$total_attendance = $pdo->query("SELECT COUNT(*) FROM attendance")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord Admin</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h1>Tableau de bord Admin</h1>

    <!-- Statistiques -->
    <div class="stats">
        <div>
            <h2>Statistiques</h2>
            <p>Départements : <?= $total_departments ?></p>
            <p>Équipes : <?= $total_teams ?></p>
            <p>Utilisateurs : <?= $total_users ?></p>
            <p>Pointages : <?= $total_attendance ?></p>
        </div>
    </div>

    <!-- Graphique des pointages -->
    <div>
        <h2>Pointages par jour</h2>
        <canvas id="attendanceChart"></canvas>
    </div>

    <!-- Liens vers les fonctionnalités -->
    <h2>Actions</h2>
    <ul>
        <li><a href="manage_departments.php">Gérer les départements</a></li>
        <li><a href="manage_teams.php">Gérer les équipes</a></li>
        <li><a href="../reports/generate_report.php">Générer un rapport</a></li>
        <li><a href="../charts/attendance_chart.php">Voir les graphiques</a></li>
    </ul>

    <script>
        // Données pour le graphique
        const data = {
            labels: <?php
                $sql = "SELECT DATE(check_in) AS date, COUNT(*) AS count FROM attendance GROUP BY DATE(check_in)";
                $stmt = $pdo->query($sql);
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode(array_column($data, 'date'));
            ?>,
            datasets: [{
                label: 'Pointages par jour',
                data: <?php echo json_encode(array_column($data, 'count')); ?>,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        };

        // Configuration du graphique
        const config = {
            type: 'bar',
            data: data,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        };

        // Afficher le graphique
        const ctx = document.getElementById('attendanceChart').getContext('2d');
        new Chart(ctx, config);
    </script>
</body>
</html>
