<?php
echo "<h1>Envoi d'un message</h1>";
if ( isset( $_GET["action"] ) ) { // Test de présence de la donnée action 0 = Création de message seul 1 = Création de sujet avec message
    $action = $_GET["action"];
    if ( isset( $_GET["Cid"] ) ) {
        $categorie_id = $_GET["Cid"]; // Chargement de la variable d'id de categorie
    }
    if ( isset( $_GET["Slabel"] ) ) {
        $subject_label = $_GET["Slabel"]; // Chargement de la variable d'id de sujet
    }
    switch( $action ) { // 0 = Creation de message, 1 = Creation de sujet et de post
        case "0": // Cas d'un envoi de message seul
            include "commons/formpost.php"; // insertion du formulaire d'envoi d'un post
        case "1": // Cas d'un envoi de message avec création de sujet
            // AFFICHAGE DU SUJET ET DU MEMBRE CONNECTE
                if( isset( $_GET["Slabel"] ) && isset ( $_GET["Cid"]) ) { // Test de présence du libellé du nouveau sujet et de l'id de catégorie
                    $subject_label = $_GET["Slabel"]; // Chargement de la donnée de titre du sujet
                    $categorie_id = $_GET["Cid"]; // Chargement de la donnée de l'id catégorie
                    $html_subject = '<div style="border: 1px solid black; margin: 5px;">';
                        $html_subject .= '<span> Sujet : '  . $subject_label . ' </span>'; // Affichage du nouveau libellé
                        $html_subject .= '<a href="?page=showsubjects">Choisir un autre sujet</a>'; // Lien vers la liste des sujets pour en choisir un autre
                    $html_subject .= '</div>'; 
                    echo $html_subject; // Affichage de l'entete du nouveau sujet
                    // AFFICHAGE DU FORMULAIRE DE SAISIE DU POST
                        include "commons/formpost.php"; // insertion du formulaire d'envoi d'un post
                } else {
                    $error = urlencode("Les données du sujet sont absentes"); // Chargement du message de titre de sujet absent
                    header("Location: ?page=showsubjects&error=".$error); // Recharger la page des sujets avec le message
                }
            break;
    }
}
?>