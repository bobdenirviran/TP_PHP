<?php 
// 
// SERVICE DE SUPPRESSION DE POST
//
// TEST DE PRESENCE DES DONNEES
$error=""; // init du message erreur
$subject_id=""; // init variable hors bloc
if ((isset( $_GET["action"])) && (isset($_GET["Uid"])) && (isset($_GET["Sid"])) && (isset($_GET["Pid"])) && (isset($_GET["nbposts"]))) { // Test de présence des données action, Sujet Id, Post Id dans l'url
    $action = $_GET["action"]; // Chargement de la variable action de suppression
    $post_autor = $_GET["Uid"]; // Chargement auteur du message tiré de la function getPageOfPostsForOneSubject appelé dans showpost.php
    // TEST DES DROITS DE SUPPRESSION UTILISATEUR
    if ( ( $_SESSION["user"]["id_role"] < 3 ) || ( $_SESSION["user"]["id"] == $post_autor ) ) { // Si user auteur ou si user est un modérateur = 2 ou un admin = 1 autoriser la suppression du post
        if ( $action == "3" ) { // Test de l'action de la suppression
            $post_id = $_GET["Pid"]; // Chargement de l'id du post à supprimer
            $subject_id = $_GET["Sid"]; // Chargmement de l'id sujet pour requete de recherche du premier message
            $nbposts = $_GET["nbposts"]; // Chargement du nombre de post issue de getSubjectById lancé depuis showpost.php
            // RECHERCHE DU PREMIER MESSAGE DU SUJET
            $found_first_post = getPageOfPostsForOneSubject( $subject_id, $index_page = 0 ); // Chargement du premier message dans l'ordre chornolgique
            if ( $post_id == $_SESSION["first_post"] ) { // Test pour savoir si l'on cherche à supprimer le premier message
                $error = urlencode("La suppression du premier message d'un sujet n'est pas autorisée"); 
            } else { // Suppression autorisée d'un message qui n'est pas le premier
                if ( $nbposts > 1 ) {  // Test de présence de messages autre que le dernier message 
                    if ( deletePostById( $post_id ) ){ // Si la fonction deletePostById renvoie TRUE
                        $error = urlencode("Suppression réussie !");
                    }
                    else {
                        $error = urlencode("Erreur lors de la supression du message !");
                    }
                } else {
                    $error = urlencode("Tentative de suppression du dernier message d'un sujet non autorisée"); // Message de suppression sur le premier message
                }
            }
        } else { // Si action est bien une suppression
            $error = urlencode("L'action que vous tentez de réaliser n'est pas une suppression");
        }
    } else {
        $error = urlencode("Vous n'avez pas les droits nécessaires !");
    }
} else { // En cas d'absence de données dans l'url
    $error = urlencode("Il manque des données de message !"); // Message erreur sur la présence des données dans l'url
}
header("Location: ?page=showposts&Sid=" . $subject_id ."&error=".$error); // Lancement de la page de la liste des posts par sujets pour vérif de la suppression
