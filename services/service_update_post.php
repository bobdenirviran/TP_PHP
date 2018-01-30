<?php
// 
// SERVICE DE MODIFICATION DE MESSAGE
//
// TEST DE PRESENCE DES DONNES
$error = ""; // init du message erreur
if ((isset( $_GET["action"])) && (isset($_GET["Uid"])) && (isset($_GET["Sid"])) && (isset($_GET["Pid"]))) { // Test de présence des données action, Sujet Id, Post Id dans l'url
    $action = $_GET["action"]; // Chargement de la variable action de modification
    $post_autor = $_GET["Uid"]; // Chargement auteur du message tiré de la function getPageOfPostsForOneSubject appelé dans showpost.php
    $subject_id = $_GET["Sid"]; // Chargmement de l'id sujet pour requete de recherche du premier message
    $post_id = $_GET["Pid"]; // Chargement de l'id du post à supprimer
    $_SESSION["Ptitle"] = htmlspecialchars($postrows[$key]["Ptitle"]); // conservation du champs titre du message (max 255 car.) en session pour éviter de le passer dans l'url ou le POST
    $_SESSION["Ptext"] = htmlspecialchars($postrows[$key]["Ptext"]); // conservation du champs texte du message (max 1000 car.) en session pour éviter de le passer dans l'url ou le POST
    var_dump( "action:", $action, "sujet-id:", $subject_id, "post_id:", $post_id, "post_autor:", $post_autor, "$SESSION id user:", $_SESSION["user"]["id"] );
    // TEST DES DROITS DE MODIFICATION UTILISATEUR
    if ( $action == "2" ) { // Test de l'action sélectionnée en modification
        if ( $_SESSION["user"]["id"] == $post_autor ) { // Si user auteur autoriser la modification du message
            $post_fields = [
            "Pid" => $post_id, // Chargement de l'id de message à partir de la page formpost.php
            "Pdate" => date("Y-m-d H:i:s"), // met la date du systeme dans le champ
            "Ptitle" => $_SESSION["Ptitle"], // Chargement du nouvau titre de message à partir de formpost.php
            "Ptext" => $_SESSION["Ptext"], // Chargement du nouveau texte de message à partir de formpost.php
            "Pid_subject" => $subject_id, // Chargement de l'id du sujet à partir de la page formpost.php
            "Pid_user" => $post_autor // Chargement de l'id user de l'auteur
            ];
            if ( updatePostById( $post_id, $post_fields ) ) { // Message modifié si la fonction deletePostById renvoie TRUE
                $error = "Modification réussie !"; 
            } else { // Erreur enregistrement du message
                $error = "Erreur lors de la modification du message !";
            }
        } else { // User non auteur du message seul donc pas autorisé à modifier son le message
            $error = "La modification du message n'est autorisée que pour son auteur !";
        }
    } else { // Si action n'est pas modification
        $error = "L'action que vous tentez de réaliser n'est pas une modification";
    }
} else { // En cas d'absence de données dans l'url
    $error = "Il manque des données de message !"; // Message erreur sur la présence des données dans l'url
}
header("Location: ?page=Showpost&error=".$error); // Lancement de la page de la liste des posts du sujets pour vérifier la modification
