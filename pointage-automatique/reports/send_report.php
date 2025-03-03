<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
redirectIfNotLoggedIn();

if (isRH() || isAdmin()) {
    $to = "rh@entreprise.com";
    $subject = "Rapport de pointage hebdomadaire";
    $message = "Veuillez trouver ci-joint le rapport de pointage.";
    $headers = "From: no-reply@entreprise.com";

    // Joindre le fichier généré
    $file = 'rapport_' . date('Y-m-d') . '.csv';
    $content = file_get_contents($file);
    $content = chunk_split(base64_encode($content));
    $uid = md5(uniqid(time()));

    $headers .= "\r\nMIME-Version: 1.0\r\n";
    $headers .= "Content-Type: multipart/mixed; boundary=\"$uid\"\r\n";

    $body = "--$uid\r\n";
    $body .= "Content-Type: text/plain; charset=utf-8\r\n";
    $body .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
    $body .= $message . "\r\n\r\n";
    $body .= "--$uid\r\n";
    $body .= "Content-Type: application/octet-stream; name=\"$file\"\r\n";
    $body .= "Content-Transfer-Encoding: base64\r\n";
    $body .= "Content-Disposition: attachment; filename=\"$file\"\r\n\r\n";
    $body .= $content . "\r\n\r\n";
    $body .= "--$uid--";

    if (mail($to, $subject, $body, $headers)) {
        echo "Email envoyé avec succès.";
    } else {
        echo "Erreur lors de l'envoi de l'email.";
    }
}
?>
