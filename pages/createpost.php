<?php
echo "<h1 style='color:lightseagreen;' align=center>Envoi d'un message</h1>";
$error=""; // init message erreur
if ( isset( $_GET["action"] ) && isset( $_GET["Cid"] ) && isset( $_GET["Slabel"] ) ) { // Test de présence de la donnée action 0 = Création de message seul 1 = Création de sujet avec message
    $action = $_GET["action"];
    $categorie_id = $_GET["Cid"]; // Chargement de la variable d'id de categorie
    $subject_label = $_GET["Slabel"]; // Chargement de la variable d'id de sujet
    switch( $action ) { // 0 = Creation de message seul , 1 = Creation de sujet et de message
        case "0": // Cas d'un envoi de message seul
            include "commons/formpost.php"; // insertion du formulaire d'envoi d'un post
            break;
        case "1": // Cas d'un envoi de message avec création de sujet
            // AFFICHAGE DU SUJET ET DU MEMBRE CONNECTE
            $html_subject = '<div>';
                $html_subject .= '<span> Sujet : '  . $subject_label . ' </span>'; // Affichage du nouveau libellé
                $html_subject .=        '<span> </span>';
                $html_subject .=        '<img width="' . ICONE_SIZE . '" src="assets/svg/si-glyph-folder-search.svg"/>';
                $html_subject .=        '<span> </span>';
                $html_subject .= '<a href="?page=showsubjects">Choisir un autre sujet</a></br>'; // Lien vers la liste des sujets pour en choisir un autre
                $html_subject .= '</div>'; 
                $html_subject .= '</br>';
            echo $html_subject; // Affichage de l entete du nouveau sujet
            // AFFICHAGE DU FORMULAIRE DE SAISIE DU POST
            include "commons/formpost.php"; // insertion du formulaire d'envoi d'un post
            break;
    }
} else { 
    $error = urlencode("Les données du sujet sont absentes"); // Chargement du message de titre de sujet absent
    header("Location: ?page=showsubjects&error=".$error); // Recharger la page des sujets avec le message
}
?>