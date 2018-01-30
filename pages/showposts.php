<?php
//
// AFFICHAGE DE LA LISTE DES POSTS D'UN SUJET
//
//
// CHARGEMENT DU NUEMRO DE PAGE
//
$subject_id = "";
$index_page=0; // Chargement en global du numéro de page
if( isset( $_GET["index_page"] ) ) { // Test de la présence de la données dans les données reçues
    $index_page = $_GET["index_page"]; // Chargement en variable de la donnée numéro de page
} else {
    $index_page = 0; // Chargement de la première page par défaut
}
$numpage = $index_page + 1;
echo "<h1>Liste des posts - Page " . $numpage . "</h1>";
//
// CHARGEMENT DE L'ACTION CHOISIE OU PAR DEFAUT
//
// 0 = Creation de post, 1 = Creation de sujet et de post, 2 = Modif de post, 3 = Suppression de post
$action = "0";
if( isset( $_GET["action"] ) ) { // Test de la présence de la données dans les données reçues
    $action = ($_GET["action"]); // Chargement en variable de la donnée action
} else {
    $action = "0"; // Chargement de l'action' par défaut
}
//
// CHARGEMENT DE L'ID POST A TRAITER
//
if( isset( $_GET["Pid"] ) ){ // Test de la présence de la données dans les données reçues
    $post_id = $_GET["Pid"]; // Chargement en variable de la donnée action
}
//
// TEST DE PRESENCE DES DONNEES DE LA PAGE DES POSTS
//
if( isset( $_GET["Sid"] ) ) { // test de présence de l'id du sujet sélectionné
    //
    // RECHERCHE DU SUJET SELECTIONNE ET COMPTAGE DES POSTS
    //
    $subject_id = $_GET["Sid"]; 
    $subject = getSubjectById( $subject_id ); // fonction de recherche du sujet sélectionné avec comptage des posts
    $_SESSION["subject"] = $subject;
    //
    // AFFICHAGE DU SUJET EXISTANT
    //
    if ( $_SESSION["subject"]["Sid"] !== null ) { // si sujet trouvé dans la base
        $_SESSION["subject"]["nbposts"] = intval($_SESSION["subject"]["nbposts"]); // conversion du nombre de posts en numérique entier
        $_SESSION["subject"]["Sid"] = intval($_SESSION["subject"]["Sid"]); // conversion de l'id de sujet en numérique entier
        $_SESSION["subject"]["Sclosed"] = intval($_SESSION["subject"]["Sclosed"]); // Conversion du flag de sujet fermé
        $_SESSION["subject"]["Sid_categorie"] = intval($_SESSION["subject"]["Sid_categorie"]); // Conversion de la catégoriede sujet
        $categorie_id = $_SESSION["subject"]["Sid_categorie"];
        $_SESSION["subject"]["Sid_user"] = intval($_SESSION["subject"]["Sid_user"]); // conversion de l'id de créateur du sujet en numérique entier
        $html_subject  = '<div>Catégorie : ' . $_SESSION["subject"]["Cname"] ; // Afficher la catégorie
        $html_subject .=    '<span> Sujet : '  . $subject["Slabel"]    . ' </span>'; // Titre du sujet
        $html_subject .=    '<span> Créé par ' . $subject["username"]  . ' </span>'; // Nom de l'auteur du sujet
        $html_subject .=    '<span> le '       . $subject["Sdate"]     . ' </span>'; // Date de création du sujet
        $html_subject .=    '<span> contient ' . $subject["nbposts"]   . ' post(s) </span>'; // Nombre de posts du sujet
        $html_subject .=    '<a href="?page=showsubjects">Choisir un autre sujet</a>'; // Lien vers la liste des sujets pour en choisir un autre
        if ( $action == "2" || $action == "3" ) {
            $html_subject .= '<span> </span>';
            $html_subject .= '<a href="?page=showposts&Sid=' . $subject_id . '">Envoyer un nouveau message</a>'; // Lien vers la création de message
        }
        $html_subject .= '</div>';
        echo $html_subject;
        //
        // RECHERCHE ET CHARGEMENT DE LA PAGE DES POSTS DU SUJET
        //
        $postrows = getPageOfPostsForOneSubject( $subject_id, $index_page ); // fonction de recherche d'une page de posts du sujet sélectionné
        if( count($postrows) ) { // Test de présence de post pour le sujet
            //
            // AFFICHAGE DE LA PAGE DE CHAQUE POST DU SUJET
            //
            foreach( $postrows as $key => $row ) { // Afficher le post pour chaque enregistrement prendre toutes les valeurs de l'enregistrement
                $html_post = '<div style="border: 1px solid black; margin: 5px;">';
                $html_post .=   '<span> Message : ' . $postrows[$key]["Ptitle"]   . ' </span>';
                $html_post .=   '<span> Créé par '  . $postrows[$key]["username"] . ' </span>';
                $html_post .=   '<span> le '        . $postrows[$key]["Pdate"]    . ' </span>';
                $html_post .=   '<span> a écrit : ' . $postrows[$key]["Ptext"]    . ' post(s)</span>';
                //   
                // AFFICHER LE LIEN DE LA MODIFICATION POUR CHAQUE MESSSAGE SI USER AUTEUR
                //
                if( intval( $postrows[$key]["Pid_user"] ) == $_SESSION["user"]["id"] ) { // Test du membre connecté est l'auteur du message
                    $_SESSION["Ptitle"] = htmlspecialchars($postrows[$key]["Ptitle"]); // conservation du champs titre du message (max 255 car.) en session pour éviter de le passer dans l'url ou le POST
                    $_SESSION["Ptext"] = htmlspecialchars($postrows[$key]["Ptext"]); // conservation du champs texte du message (max 1000 car.) en session pour éviter de le passer dans l'url ou le POST
                    $html_post .=   '<span> </span>';
                    $html_post .=   '<a href="?page=showposts&action=2&Uid=' . $postrows[$key]["Pid_user"] .'&Pid=' . $postrows[$key]["Pid"]  . '&Sid=' . $postrows[$key]["Pid_subject"] . '">Modifier</a>';
                }
                //   
                // AFFICHER LE LIEN DE LA SUPPRESSION POUR CHAQUE MESSAGE SI ADMIN OU MODO OU USER AUTEUR
                //
                if( intval( $postrows[$key]["Pid_user"] ) == $_SESSION["user"]["id"] || $_SESSION["user"]["id_role"] < 3 ) { // Test du membre connecté est l'auteur du message Ou si membre connecté est un administrateur ou un modérateur
                    $html_post .=   '<span> </span>';
                    $html_post .=   '<a href="?page=showposts&action=3&Uid=' . $postrows[$key]["Pid_user"] . '&Pid=' . $postrows[$key]["Pid"]  . '&Sid=' . $postrows[$key]["Pid_subject"] . '">Supprimer</a>';
                }
                $html_post .= '</div>';
                echo $html_post;
            }
            //
            // CALCUL DU NOMBRE DE PAGES ET AFFICHAGE DE LA LISTE HORIZONTALE DES PAGES DE POST
            //
            if( isset( $_GET["index_page"] ) ) {
                $index_page = intval($index_page);
            }
            $nb_pages = ceil( $_SESSION["subject"]["nbposts"] / POSTS_BY_PAGE ); // Calcul du nombre de pages par rapport au nombre de posts du sujet
            if ( $nb_pages > 1 ) { // afficher les pages uniquement si leur nombre est supérieur à 1
                $html_pages = '<ul style="list-style-type: none;">';
                $html_pages .= '<span>Page(s) </span>';                                  
                for( $numpage=0; $numpage < $nb_pages; $numpage++ ){ // Pour toutes les pages
                        $html_pages .= '<li Style = "display: inline-block; ">'; // Liste avec numero de page horizontalement
                        if ( $numpage+1 == $index_page ) {
                            $html_pages .=  '<a href="?page=showposts&Sid=' . $postrows[$key]["Pid_subject"] . '&index_page=' . $numpage .'" >' ; // affichage du lien vers la page
                        } else {
                            $html_pages .=  '<a href="?page=showposts&Sid=' . $postrows[$key]["Pid_subject"] . '&index_page=' . $numpage .'" >' ; // affichage du lien vers la page
                        }
                        $html_pages .=      ($numpage + 1); // affichage du numéro de la page
                        $html_pages .=      '</a>';
                        $html_pages .=      '<span>.</span>';
                        $html_pages .= '</li>';
                }
                $html_pages .= '</ul>';
                echo $html_pages; // Affichage de la liste horizontale des numéros de pages
            }
        } else { // Aucun post trouvé pour le sujet avec afficha de message
            $error = urlencode("Aucun message trouvé pour ce sujet");
        }
    //
    // AFFICHER FORMULAIRE POUR ENVOYER UN NOUVEAU MESSAGE
    //
    include "commons/formpost.php";
    }
} else { // EN CAS D'ABSENCE DE DONNEES GET RENVOI VERS LA PAGE DE CHOIX DU SUJET 
    $error = urlencode("Il manque des données du sujet !");
    header("Location: ?page=showsubjects&Cid=" . $categorie_id . "&Sid=" . $subject_id . "&error=". $error); // renvoie vers la page des sujets avec erreur éventuelle
}