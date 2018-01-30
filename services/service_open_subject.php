<?php
//
// SERVICE DE REOUVERTURE DE SUJET
//
$error=""; // init du message erreur
if ( isset($_GET["Sid"] ) ) { // Test de la présence de l'id du sujet
    $subject_id = $_GET["Sid"]; // Chargement dans une variable de l'id du sujet
    if ( getSubjectById( $subject_id ) ) { // Recherché le sujet par l'id et chargé une variable de SESSION avec les données du sujet
        $_SESSION["subject"]["Sclosed"] = 0; // Changement de la donnée de flag passée a fermeture 
        if ( updateSubjectById( $subject_id ) ) { // Modification du sujet 
            $error = urlencode("Sujet réouvert !");
        } else {
            $error = urlencode("Erreur lors de la réouverture du sujet !");
        }
    } else {
        $error = urlencode("Sujet non trouvé avant ouverture du sujet !");    
    }
} else {
    $error = urlencode("Données absentes du sujet !");
}
header("Location: ?page=showsubjects&error=". $error); // retour vers la liste des sujets