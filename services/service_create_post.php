<?php
//
// SERVICE DE VALIDATION D'UN NOUVEAU MESSAGE
//
$error = ""; // init du message erreur
$create_post_allowed = true; // Flag de création de post autorisée en cas de création de sujet ou pas
if ( isset ( $_GET["action"] ) ) {
    $action = $_GET["action"]; // Chargement de la variable action à 0 = Creation de post, 1 = Creation de sujet et de post, 2 = Modif de post, 3 = Suppression de post
}
if ( isset ( $_GET["Slabel"] ) ) {
    $subject_label = $_GET["Slabel"]; // Chargement du titre du sujet en cas de création de sujet
}
if ( isset ( $_GET["Cid"] ) ) {
    $categorie_id = $_GET["Cid"]; // Chargement de l'id de catégorie en cas de création de sujet
}
if ( isset ( $_GET["Sid"] ) ) {
    $subject_id = $_GET["Sid"]; // Chargement de l'id de sujet en cas de création de post seul
}
// TEST DE LA PRESENCE DE LA DONNEE DE TITRE DE SUJET
    if ( isset( $_POST["Ptitle"] ) && isset( $_POST["Ptext"]) ) { // Si présence d'un libellé de nouveau sujet
        $Ptitle = htmlspecialchars($_POST["Ptitle"]); // titre du nouveau message
        $Ptext = htmlspecialchars($_POST["Ptext"]); // texte du nouveau message
        // TEST DE CREATION DE POST AVEC OU SANS CREATION DE SUJET PREALABLE
            if ( $action == "1" ) { // Création du post avec insert du sujet préalable
                $subject = [ //Chargement des données du sujet pour INSERT du sujet
                    "Sid" => null,
                    "Sdate" => date("Y-m-d H:i:s"),
                    "Slabel" => $subject_label,
                    "Sclosed" => 0,
                    "Sid_user" => $_SESSION["user"]["id"],
                    "Sid_categorie" => $categorie_id
                ];
                $inserted_subject = insertSubject( $subject ); // Executer l'insertion du nouveau sujet et passage du résultat
                if ( !$_SESSION["id_subject_inserted"] && !$inserted_subject ) { // Si le sujet n'est pas bien ajouté
                    $create_post_allowed = false; // Flag de création de message passé à interdire car sujet pas créée par l'INSERT
                    $error =  urlencode("Une erreur est survenue lors de l'insertion du sujet !");
                }
            }
        // TEST DE CREATION AUTORISEE DE POST CAR SUJET INSERE OU PRESENT
            if ( $create_post_allowed ) { 
                $error = urlencode("Le sujet a bien été ajouté !");
                if ( $action == "1" ) { // Creation de sujet et de message
                    $subject_id = $_SESSION["id_subject_inserted"]; // Charger la variable de id du sujet
                }
                $postmessage = [ //Chargement des données du post pour INSERT
                    "Pid" => null,
                    "Pdate" => date("Y-m-d H:i:s"),
                    "Ptitle" => $Ptitle,
                    "Ptext" => $Ptext,
                    "Pid_user" => $_SESSION["user"]["id"],
                    "Pid_subject" => $subject_id
                ];
                //CHECK TITRE DE MESSAGE AVEC 1 CARACTERE MINIMUM ET 255 MAXIMUM
                    if ( strlen( $Ptitle ) < 1 || strlen( $Ptitle ) > 255 ) {
                        $error = urlencode("Nombre incorret de caractères pour le titre du nouveau message !");
                    //CHECK TITRE DE MESSAGE AVEC 1 CARACTERE MINIMUM ET 255 MAXIMUM
                        } else if( strlen( $Ptext ) < 1 || strlen( $Ptext ) > 1000 ) {
                            $error = urlencode("Nombre incorret de caractères pour le texte du nouveau message !");
                        } else {
                        // INSERT DU POST VALIDE
                            if ( insertPost( $postmessage ) ) { // si la creation du post a réussi
                                $error =  urlencode("Le post a bien été ajouté !");
                                unset($_SESSION["id_subject_inserted"]); // vider la variable session de l'id du dernier sujet inséré
                            } else {
                                $error =  urlencode("Une erreur est survenue lors de l'insertion du post !");
                                if ($action == 0) { // si création de sujet et de premier post
                                    header("Location: ?page=showsubjects&Cid=" . $categorie_id . "&Sid=" . $subject_id . "&error=". $error); // renvoie vers la page des sujets avec erreur éventuelle
                                    die();
                                }
                            }
                }
            }
    } else {
        $error =  urlencode("Il manque des données !"); // message erreur en cas d'absence de titre ou de texte du post de message
    }
//
// RENVOI DE PAGE EN FONCTION DE L'ACTION
// 
if ( $action == "0") { // En cas de création de sujet et de premier message
    header("Location: ?page=showsubjects&error=". $error); // retour vers la liste des sujets
    die();
} else {
    header("Location: ?page=showposts&Sid=" . $subject_id . "&error=". $error); // retour vers la liste des posts du sujet
}
