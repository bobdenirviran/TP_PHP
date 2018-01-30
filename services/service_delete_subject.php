<?php
//
// SUPPRESSION D'UN SUJET PAR UN ADMINISTRATEUR
//
$error = ""; // init du message erreur
if( isset($_GET["Sid"]) ){ // Test de la présence de l'id du sujet
    $subject_id = $_GET["Sid"]; // Chargement de l'id subject 
    if( deleteSubjectById( $subject_id ) ){ // Si sujet supprimé la fonction deleteSubjectById renvoie TRUE
        $error = urlencode("Suppression du sujet réussie !");
    } else {
        $error = urlencode("Erreur lors de la supression !");
    }
}
header("Location: ?page=showsubjects&error=". $error); // retour vers la liste des sujets
