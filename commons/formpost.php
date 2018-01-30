<?php
//
// FORMULAIRE DE SAISIE D'UN POST
//
if ( isset( $_GET["action"] ) ) { // Test de la présence de la données dans les données reçues
    $action = $_GET["action"]; // Chargement en variable de la donnée action
} else {
    $action = "0"; // Action par défaut creation de post
}
if ( ( $action == "0" || $action == "1" ) && isset( $_GET["Cid"] ) ) { // Test de chargement de l'id categorie en création de message avec ous sans sujet
    $categorie_id = $_GET["Cid"]; // Chargement de la variable d'id de categorie
}
if ( $action == "0" && isset( $_GET["Sid"] ) ) { // Test de chargement de l'id sujet en création de message seul
    $subject_id = $_GET["Sid"]; // Chargement de la variable d'id de sujet 
}
if ( $action == "1" && isset( $_GET["Slabel"] ) ) { // Test de chargement du labellé de sujet en création de sujet avec message
    $subject_label = $_GET["Slabel"]; // Chargement de la variable d'id de sujet 
}
if ( ( $action == "1" || $action == "2" || $action == "3" ) && ( isset( $_GET["Pid"] ) ) ) { // Test de chargement de l'id post en création modif et suppression
    $post_id = $_GET["Pid"]; // Chargement de la variable d'id de post 
}
if ( $action == "2" ) { // Test de l'action en modif
    $_SESSION["Ptitle"] = htmlspecialchars($postrows[$key]["Ptitle"]); // conservation du champs titre du message (max 255 car.) en session pour éviter de le passer dans l'url ou le POST
    $_SESSION["Ptext"] = htmlspecialchars($postrows[$key]["Ptext"]); // conservation du champs texte du message (max 1000 car.) en session pour éviter de le passer dans l'url ou le POST
}
if ( $action == "3" ) && ( isset( $_GET["nbposts"] ) ) && ( isset( $_GET["Sid"] ) ) { // Test de l'action en suppression et présence du nombre de posts par sujet
    $_SESSION["nbposts"] = $nbposts; // Chargement en session du nombre de post issu de getSubjectById lancé de showpost.php pour tester la suppression du sujet en cas de dernier post détruit
    $subject_id = $_GET["Sid"]; // Chargment de l'id du sujet pour vérification de la non suppression du premier message du post
} else {
    $nbposts = null; 
}
switch( $action ) {
    case "0": // Envoi d'un nouveau message seul
        $html_formpost = "<h4>Donnez un titre et saisissez votre message</h4>";
        $html_formpost .= '<form action="?service=create_post&action=0&Cid=' . $categorie_id . '&Sid=' . $subject_id . '" method="POST">'; // renvoie vers le service de creation de message
        break;
    case "1": // Envoi d'un message après la création d'un sujet
        $html_formpost = "<h4>Saisissez le premier message de ce sujet</h4>";
        $html_formpost .= '<form action="?service=create_post&action=1&Cid=' . $categorie_id . '&Slabel=' . $subject_label . '" method="POST">'; // renvoie vers le service de creation de message
        break;
    case "2": // Modification d'un message
        $html_formpost = "<h4>Saisissez les modifications du message</h4>";
        $html_formpost .= '<form action="?service=update_post&action=2&Pid=' . $post_id . '" method="POST">'; // renvoie vers le service de update de message
        break;
    case "3": // Suppression d'un message
        $html_formpost = "<h4>Confirmez la suppression de ce message</h4>";
        $html_formpost .= '<form action="?service=delete_post&action=3&Pid=' . $post_id . '&Sid=' . $subject_id . '&nbposts=' . $nbposts . '" method="POST">'; // renvoie vers le service de delete de message
        break;
}
switch( $action ){
    case "0": // affichage des inputs en mode création de message seul
    case "1": // affichage des inputs en mode création de sujet premier avec message
        $html_formpost .= '<label>Titre :</label>';
        $html_formpost .= '<input type="text" name="Ptitle" maxlength="255" placeholder="min. 1 car. max. 255 car.">';
        $html_formpost .= '<label>Texte :</label>';
        $html_formpost .= '<input type="text" name="Ptext" maxlength="255" placeholder="min. 1 car. max. 1000 car.">';
        break;
    case "2": // affichage des inputs en mode modification de message avec réaffichage des valeurs
    case "3": // affichage des inputs en mode suppression de message avec réaffichage des valeurs
        $html_formpost .= '<label>Titre :</label>';
        $html_formpost .= '<input type="text" name="Ptitle" value="' . $postrows[$key]['Ptitle'] . '" maxlength="255" placeholder="min. 1 car. max. 255 car.">';
        $html_formpost .= '<label>Texte :</label>';
        $html_formpost .= '<input type="text" name="Ptext" value="' . $postrows[$key]['Ptext'] . '" maxlength="255" placeholder="min. 1 car. max. 1000 car.">';
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////        
//////////////////////////////////////////// passage de ptext et Ptitle en session ///////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        break;
}
switch( $action ){
    case "0": // envoi de message avec creation de sujet
    case "1": // envoi de message seul
        $html_formpost .= '<input type="submit" value="Envoyer le message">'; // Bouton envoyer en creation de message avec ou sans sujet
        break;
    case "2": // modification de post
        $html_formpost .= '<input type="submit" value="Confirmez les modifications du message">'; // bouton confirmation en cas de modification de sujet
        break;
    case "3": // suppression de post
        $html_formpost .= '<input type="submit" value="Confirmez la suppression de ce message">'; // bouton confimation de suppression
        break;
}
$html_formpost .= '</form>'; // affichage formulaire
echo $html_formpost;
?>