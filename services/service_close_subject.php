<?php
//
// SERVICE DE FERMETURE DE SUJET
//
$error=""; // init du message erreur
if ( isset($_GET["Sid"] ) ) { // Test de la présence de l'id du sujet
    $subject_id = $_GET["Sid"]; // Chargement dans une variable de l'id du sujet
    if ( getSubjectById( $subject_id ) ) { // Recherché le sujet par l'id et chargé une variable de SESSION avec les données du sujet
        $_SESSION["subject"]["Sclosed"] = 1; // Changement de la donnée de flag passée a fermeture 
        if ( updateSubjectById( $subject_id ) ) { // Modification du sujet 
            $error = "Sujet fermé !";
        } else {
            $error = "Erreur lors de la fermeture du sujet !";
        }
    } else {
        $error = "Sujet non trouvé avant fermeture du sujet !";    
    }
    $error = "Données absentes du sujet !";
}
header("Location: ?page=showsubjects&error=". $error); // retour vers la liste des sujets
