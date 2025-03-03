<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
redirectIfNotLoggedIn();

if (isRH() || isAdmin()) {
    $sql = "SELECT u.username, d.name AS department, t.name AS team, a.check_in, a.check_out
            FROM attendance a
            JOIN users u ON a.user_id = u.id
            LEFT JOIN departments d ON u.department_id = d.id
            LEFT JOIN teams t ON u.team_id = t.id
            WHERE a.check_in BETWEEN :start_date AND :end_date";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['start_date' => $_POST['start_date'], 'end_date' => $_POST['end_date']]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Générer un fichier CSV ou PDF (exemple simplifié)
    $filename = 'rapport_' . date('Y-m-d') . '.csv';
    $file = fopen($filename, 'w');
    fputcsv($file, array_keys($results[0]));
    foreach ($results as $row) {
        fputcsv($file, $row);
    }
    fclose($file);

    echo "Rapport généré : <a href='$filename'>Télécharger</a>";
} else {
    echo "Accès refusé.";
}
?>
