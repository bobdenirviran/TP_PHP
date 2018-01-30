<?php
//
// SERVICE DE CONNEXION DE L'UTILISATEUR
//
$error="";
$connected = false;
if( isset( $_POST["username"] ) && isset( $_POST["password"] ) ){ // Si présence d'un nom utilisateur et d'un mot de passe
    $username = $_POST["username"]; // charger une variable avec la valeur du usernamedu formulaire renvoyé
    $password = sha1( $_POST["password"] . SALT ); // Concaténé au mot de passe la chaine de renforcement du mot de passe et en faire le cryptage avec la fonction SHA1
    $user = getUser( $username, $password ); // retourne le user rechercher et vérifie le mot de passe par la fonction 
    if( $user ){ // Teste si utilisateur trouvé avec la requete (sinon null)
        $_SESSION["user"] = $user; // utilisateur trouvé et nom stocké dans la variable globale de session
        $connected = true; // flag d'utilisateur connecté chargé à VRAI
    }
}
if( $connected ){ // si l'utilsiateur est connecté alors
    header("Location: ?page=showsubjects"); // Charger la homepage
}
else { // utilisateur non identifié et donc non connecté
    session_unset(); // Detruit toutes les variables de SESSION
    $error = urlencode("Identifiant ou mot de passe incorrect"); // chergement du message d'errer de connexion
    header("Location: ?page=login&error=".$error); // Recharger la page login avec le message erreur
}
?>