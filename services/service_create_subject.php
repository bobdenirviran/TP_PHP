<?php
//
// SERVICE DE VALIDATION D'UN NOUVEAU SUJET
//
    // TEST DE LA PRESENCE DANS LE SUJET DE LA DONNEE DE TITRE DE SUJET
    if( isset( $_POST["Slabel"] ) && isset( $_GET["Cid"]) ) { // Si présence d'un libellé de nouveau sujet et Id de catégorie
        $subject_label = $_POST["Slabel"];
        $categorie_id = $_GET["Cid"];
        // RECHERCHE DU SUJET SUR LE TITRE
            $existed = getSubjectByLabel($subject_label); // Recherche d'un titre de sujet déjà existant
        // TEST DE CREATION DE SUJET DEJA EXISTANT SUR LE TITRE DU SUJET
        if( $existed ) {
            $error = urlencode("Le titre de ce sujet existe déjà !");
            header("Location: ?page=showposts&error=".$error); // Recharger la page des posts du sujets trouvé avec le message
            die();
        } else {
            //CHECK TITRE DE SUJET AVEC 1 CARACTERE MINIMUM ET 255 MAXIMUM
            if( strlen( $subject_label ) < 1 || strlen( $subject_label ) > 255 ) {
                $error = urlencode("Nombre incorret de caractères pour le titre du nouveau sujet !");
                header("Location: ?page=showsubjects&error=".$error); // Recharger la page des sujets avec le message
                die();
            } else {
                $error = urlencode("Creation du premier message");
                $subject_label = htmlspecialchars($subject_label);
                header("Location: ?page=createpost&action=1&Cid=" . $categorie_id . "&Slabel=" . $subject_label ); // envoi de la page de creation des posts
                die();
            }
        }
    }  else {
        $error = urlencode("Il manque des données du sujet ou de catégorie !"); // Message d'erreur si absence du titre de sujet ou de l'id de catégorie
    }
    header("Location: ?page=showsubjects&error=". $error);
