<?php
//
// ROUTEUR PROCEDURAL
//
    //
    // OUVERTURE DES FONCTIONS ET CHARGEMENT DES FONCTIONS GLOBALES
    //
    require "functions.php"; 
    //
    // ROUTAGE DES SERVICES SUR LES PAGES HTML GENEREES
    //
    if( isset( $_GET["service"] ) ){
        $service = $_GET["service"];
        switch( $service ){
            case "login": // Login d'un utilisateur existant
                include "services/service_login.php";
                break;
            case "subscribe": // inscription d'un nouveau membre
                include "services/service_subscribe.php";
                break;
            case "deconnexion":
                connectionRequired();
                include "services/service_deconnexion.php";
                break;
            case "create_subject": // un utilisateur créer un sujet
                connectionRequired();
                include "services/service_create_subject.php";
                break;
            case "delete_subject": // L'admin supprime un sujet et tous les post s'y référent
                connectionRequired();
                include "services/service_delete_subject.php";
                break;
            case "close_subject": // L'admin ou le modérateur ferme un sujet accessible en lecture seulement mais plus en modif ni suppression
                connectionRequired();
                include "services/service_close_subject.php";
                break;
            case "create_post": // un utilisateur créer un post dans un sujet
                connectionRequired(); 
                include "services/service_create_post.php";
                break;
            case "update_post": // un utilisateur modifie ses posts
                connectionRequired(); 
                include "services/service_update_post.php";
                break;        
            case "delete_posts": // un moderateur ou un admin supprime un ou plusieurs posts de n'importe quel sujet
                connectionRequired(); 
                include "services/service_delete_posts.php"; // tester si l'utilsiateur est un moderateur ou un user pour tester l'accès à tous les enregistrements ou seulement aux siens
                break;
            default :
                header("Location: ?page=login");
        }
        die();
    }
//
// ROUTAGE DES PAGES EN FONCTION DE LA DONNEE ?page
//
    $page ="showsubjects"; // page par défaut
    $page_file = "";

    if( isset( $_GET["page"] ) ){
        $page = $_GET["page"];
    }
    switch( $page ){
        case "login":
            $page_file = "pages/login.php";
            break;
        case "showsubjects":
            connectionRequired();
            $page_file = "pages/showsubjects.php";
            break;
        case "showposts":
            connectionRequired();
            $page_file = "pages/showposts.php";
            break;
        case "createpost":
            connectionRequired();
            $page_file = "pages/createpost.php";
            break;
        default:
            $page_file = "pages/404.php";
    }
//
// GENERATION DES PAGES HTML EN PHP
//
    include "commons/header.php";
    include $page_file;
    include "commons/footer.php";

?>