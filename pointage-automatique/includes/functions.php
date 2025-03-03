<?php
require_once 'db.php'; // Inclure la connexion à la base de données

/**
 * Vérifie si un utilisateur a déjà pointé aujourd'hui.
 * @param int $user_id - L'ID de l'utilisateur.
 * @return bool - True si l'utilisateur a déjà pointé, sinon false.
 */
function hasUserCheckedInToday($user_id) {
    global $pdo;
    $sql = "SELECT COUNT(*) FROM attendance WHERE user_id = :user_id AND DATE(check_in) = CURDATE()";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['user_id' => $user_id]);
    return $stmt->fetchColumn() > 0;
}

/**
 * Vérifie si un utilisateur a déjà pointé pour sortir aujourd'hui.
 * @param int $user_id - L'ID de l'utilisateur.
 * @return bool - True si l'utilisateur a déjà pointé pour sortir, sinon false.
 */
function hasUserCheckedOutToday($user_id) {
    global $pdo;
    $sql = "SELECT COUNT(*) FROM attendance WHERE user_id = :user_id AND DATE(check_in) = CURDATE() AND check_out IS NOT NULL";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['user_id' => $user_id]);
    return $stmt->fetchColumn() > 0;
}

/**
 * Enregistre le pointage d'entrée d'un utilisateur.
 * @param int $user_id - L'ID de l'utilisateur.
 * @return bool - True si le pointage est enregistré, sinon false.
 */
function checkInUser($user_id) {
    global $pdo;
    if (hasUserCheckedInToday($user_id)) {
        return false; // L'utilisateur a déjà pointé aujourd'hui
    }
    $sql = "INSERT INTO attendance (user_id, check_in) VALUES (:user_id, NOW())";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute(['user_id' => $user_id]);
}

/**
 * Enregistre le pointage de sortie d'un utilisateur.
 * @param int $user_id - L'ID de l'utilisateur.
 * @return bool - True si le pointage est enregistré, sinon false.
 */
function checkOutUser($user_id) {
    global $pdo;
    if (!hasUserCheckedInToday($user_id) || hasUserCheckedOutToday($user_id)) {
        return false; // L'utilisateur n'a pas pointé ou a déjà pointé pour sortir
    }
    $sql = "UPDATE attendance SET check_out = NOW() WHERE user_id = :user_id AND DATE(check_in) = CURDATE()";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute(['user_id' => $user_id]);
}

/**
 * Récupère les données de pointage d'un utilisateur.
 * @param int $user_id - L'ID de l'utilisateur.
 * @return array - Les données de pointage.
 */
function getUserAttendance($user_id) {
    global $pdo;
    $sql = "SELECT check_in, check_out FROM attendance WHERE user_id = :user_id ORDER BY check_in DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['user_id' => $user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Récupère la liste des départements.
 * @return array - La liste des départements.
 */
function getDepartments() {
    global $pdo;
    $sql = "SELECT * FROM departments";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Récupère la liste des équipes d'un département.
 * @param int $department_id - L'ID du département.
 * @return array - La liste des équipes.
 */
function getTeamsByDepartment($department_id) {
    global $pdo;
    $sql = "SELECT * FROM teams WHERE department_id = :department_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['department_id' => $department_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Valide une adresse email.
 * @param string $email - L'adresse email à valider.
 * @return bool - True si l'email est valide, sinon false.
 */
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Génère un mot de passe sécurisé.
 * @param int $length - La longueur du mot de passe.
 * @return string - Le mot de passe généré.
 */
function generateSecurePassword($length = 12) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
    return substr(str_shuffle($chars), 0, $length);
}

/**
 * Envoie un email.
 * @param string $to - L'adresse email du destinataire.
 * @param string $subject - Le sujet de l'email.
 * @param string $message - Le contenu de l'email.
 * @param string $headers - Les en-têtes de l'email.
 * @return bool - True si l'email est envoyé, sinon false.
 */
function sendEmail($to, $subject, $message, $headers = '') {
    if (empty($headers)) {
        $headers = "From: no-reply@entreprise.com\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    }
    return mail($to, $subject, $message, $headers);
}

/**
 * Redirige l'utilisateur vers une page.
 * @param string $url - L'URL de destination.
 */
function redirect($url) {
    header("Location: $url");
    exit();
}

/**
 * Nettoie les entrées utilisateur pour éviter les injections SQL et XSS.
 * @param string $data - La donnée à nettoyer.
 * @return string - La donnée nettoyée.
 */
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}
?>
