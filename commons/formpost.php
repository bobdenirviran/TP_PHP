<?php
//
// FORMULAIRE DE SAISIE D'UN POST
//
$error = ""; // Mise à vide du message d'erreur
$html_formpost = ""; // Mise à vide du formulaire et chargement global de la variable
// $error = urlencode("Action impossible a définir !"); 
    //
    // ENVOI DE MESSAGE SEUL
    //
    if ( $action == "0" && isset( $_GET["Sid"] ) ) { // Test de chargement de l'id categorie en création de message avec ou sans sujet
        $subject_id = $_GET["Sid"]; // Chargement de la variable d'id de sujet
        // Afichage Envoi d'un nouveau message seul
        $html_formpost .= "<h4>Donnez un titre et saisissez votre message</h4>";
        $html_formpost .= '<form action="?service=create_post&action=0&Sid=' . $subject_id . '" method="POST">'; // renvoie vers le service de creation de message
        include "postformcreate.php";  // Saisie des champs Titre et Texte
        $html_formpost .= '<input type="submit" value="Envoyer le message">'; // Bouton envoyer en creation de message avec ou sans sujet
    } else {
        $error = urlencode("Absence de données en envoi de message !"); 
    }
    //
    // ENVOI DE PREMIER MESSAGE AVEC CREATION DE SUJET PREALABLE
    //
    if ( $action == "1" && isset( $_GET["Cid"] ) && isset( $_GET["Slabel"] ) ) { // Test de chargement du labellé de sujet en création de sujet avec message
        $categorie_id = $_GET["Cid"]; // Chargement de la variable d'id de categorie
        $subject_label = $_GET["Slabel"]; // Chargement de la variable d'id de sujet
        // Affichage Envoi d'un message après la création d'un sujet
        $html_formpost .= "<h4>Saisissez le premier message de ce sujet</h4>";
        $html_formpost .= '<form action="?service=create_post&action=1&Cid=' . $categorie_id . '&Slabel=' . $subject_label . '" method="POST">'; // renvoie vers le service de creation de message
        include "postformcreate.php"; // Saisie des champs Titre et Texte
        $html_formpost .= '<input type="submit" value="Envoyer le message">'; // Bouton envoyer en creation de message avec ou sans sujet
    } else {
        $error = urlencode("Absence de données en création de sujet sur le premier message !"); 
    }
    //
    // MODIFICATION DE MESSAGE
    //
    if ( $action == "2" && isset( $_GET["Uid"] ) && isset( $_GET["Sid"] ) && isset( $_GET["Pid"] ) ) { // Test de chargement de l'id post en modif et suppression
        $user_id = $_SESSION["user"]["id"]; // Chargement de l'id user pour vérification de l'auteur du post en modification et suppression du message
        $post_id = $_GET["Pid"]; // Chargement de la variable d'id de post 
        $subject_id = $_GET["Sid"]; // Chargement de la variable d'id de sujet
        $_SESSION["Ptitle"] = htmlspecialchars($postrows[$key]["Ptitle"]); // conservation du champs titre du message (max 255 car.) en session pour éviter de le passer dans l'url ou le POST
        $_SESSION["Ptext"] = htmlspecialchars($postrows[$key]["Ptext"]); // conservation du champs texte du message (max 1000 car.) en session pour éviter de le passer dans l'url ou le POST
        // Affichage Modification d'un message
        $html_formpost .= "<h4>Saisissez les modifications du message</h4>";
        $html_formpost .= '<form action="?service=update_post&action=2&Uid=' . $user_id . '&Pid=' . $post_id . '&Sid=' . $subject_id . '" method="POST">'; // renvoie vers le service de update de message
        include "postformodsup.php"; // Saisie des champs Titre et Texte avec affichage préalable des values
        $html_formpost .= '<input type="submit" value="Confirmez les modifications du message">'; // bouton confirmation en cas de modification
    } else {
        $error = urlencode("Absence de données en modification de message !"); 
    }
    //
    // SUPPRESSION DE MESSAGE
    //
    if ( $action == "3" && isset( $_GET["Uid"] ) && isset( $_GET["Sid"] ) && isset( $_GET["Pid"] ) ) { // Test de l'action en suppression et présence du nombre de posts par sujet
        $user_id = $_SESSION["user"]["id"]; // Chargement de l'id user pour vérification de l'auteur du post en modification et suppression du message
        $subject_id = $_GET["Sid"]; // Chargment de l'id du sujet pour vérification de la non suppression du premier message du post
        $post_id = $_GET["Pid"]; // Chargement de la variable d'id de post 
        $nbposts = $_SESSION["subject"]["nbposts"]; // Chargement du nombre de posts restant du sujet issu de la requete getSubjectById lancé de showpost.php pour tester la suppression du sujet en cas de dernier post détruit
        // Affichage Suppression d'un message
        $html_formpost = "<h4>Confirmez la suppression de ce message</h4>";
        $html_formpost .= '<form action="?service=delete_post&action=3&Uid=' . $user_id . '&Pid=' . $post_id . '&Sid=' . $subject_id . '&nbposts=' . $nbposts . '" method="POST">'; // renvoie vers le service de delete de message
        include "postformodsup.php"; // Saisie des champs Titre et Texte avec affichage préalable des values
        $html_formpost .= '<input type="submit" value="Confirmez la suppression de ce message">'; // bouton confimation de suppression
    } else {
        $error = urlencode("Absence de données en suppression de message !");
    }
    //
    // AFFICHAGE DU FORMULAIRE DE MESSAGE
    //
    if ( $html_formpost == "" ) {
        // Retour vers page d'affichage des sujets
       header("Location: ?page=showsubjects&error=". $error); // renvoie vers la page des sujets avec erreur éventuelle
    } else {
        $html_formpost .= '</form>'; // affichage formulaire
        echo $html_formpost;    
    }
