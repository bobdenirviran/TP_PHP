<?php
//
// SERVICE D'INSCRIPTION DE L'UTILISATEUR
//
$error = "";
$subscribed = false; // Chargement par défaut de la variable flag d'inscription à FAUX
// TEST DE LA PRESENCE DANS LE POST DES DONNEES D'INSCRIPTION
if( isset( $_POST["newusername"] ) && isset( $_POST["newpassword"] ) && isset( $_POST["newconfpswd"] ) ) { // Si présence d'un nom utilisateur et d'un mot de passe

    $newusername = htmlspecialchars( $_POST["newusername"] ); // charger une variable avec la valeur du username du formulaire renvoyé
    $newpassword = htmlspecialchars( $_POST["newpassword"] ); 
    $newconfpswd = htmlspecialchars( $_POST["newconfpswd"] );

    //CHECK USERNAME ET PASSWORD AVEC 8 CARACTERES MINIMUM ET 255 CARACTERES MAXIMUM
    $checked = true; // 
    if( strlen( $newusername ) < 8 || strlen( $newusername ) > 255 ){
        $error = urlencode("Nombre incorret de caractères pour le nom d'utilisaeur !");
        $checked = false;
    }
    if( strlen( $newpassword ) < 8 || strlen( $newpassword ) > 255 ){
        $error = urlencode("Nombre incorrect de caractères pour le mot de passe !");
        $checked = false;
    }
    if( $checked ) {
        // TEST MOT DE PASSE = CONFIRMATION DU MOT DE PASSE
        if( $newpassword === $newconfpswd) {

            $newpassword = sha1( $newpassword . SALT ); // Concaténé au mot de passe la chaine de renforcement du mot de passe et en faire le cryptage avec la fonction SHA1   
            $user = getUser( $newusername, $newpassword ); // retourne le user rechercher et vérifie le mot de passe par la fonction

            // TEST NOM D'UTILISATEUR DEJA PRESENT DANS LA BASE
            if( $user ){
                $error = urlencode("Le nom d'utilisateur " . $newusername . " existe déjà !"); // chargement du message d'errer de connexion
            } else { // TEST NOM D'UTILISATEUR ABSENT DE LA BASE

                // INSERTION DE L'UTILISATEUR DANS LA BASE
                $connection = getConnection();
                $sql = "INSERT INTO users VALUES (null, ?, ?, 3 )";
                $statement = mysqli_prepare( $connection, $sql );
                mysqli_stmt_bind_param( $statement, "ss", $newusername, $newpassword );
                mysqli_stmt_execute( $statement );
                $inserted = mysqli_stmt_affected_rows( $statement );
                mysqli_stmt_close( $statement );
                mysqli_close( $connection );
                $inserted = (boolean)($inserted);
                $id = mysqli_insert_id();
                if ( $inserted ) {
                    $user = [
                        "id" => $id,
                        "username" => $newusername,
                        "id_role" => 3
                    ];
                    $_SESSION["user"] = $user; // utilisateur trouvé et nom stocké dans la variable globale de session
                    $subscribed = true; // flag d'utilisateur inscrit
                }
            }
        } else { // MOT DE PASSE DIFFERENT DE LA CONFIRMATION DU MOT DE PASSE

            $subscribed= false;
            $error = urlencode("Les mot de passe sont différents"); // chargement du message d'erreur de connexion si zones incomplètes 
        }
    }
} else { // ABSENCE D'UNE VARIABLE DE POST D'INSCRIPTION

    $subscribed= false;
    $error = urlencode("Les zones nom d'utilisateur mot de passe et confirmation sont obligatoires"); // chargement du message d'erreur de connexion si variables absentes
}
//
// TEST DE L'INSCRIPTION REUSSIE
//
// INSCRIPTION REUSSIE
if( $subscribed ){ // si l'utilisateur est inscrit alors
    header("Location: ?page=showsubjects"); // Charger la homepage
    die();
} else { // ECHEC DE L'INSCRIPTION
    session_unset(); // Detruit toutes les variables de SESSION
    header("Location: ?page=login&error=".$error); // Recharger la page login avec le message erreur
}
?>