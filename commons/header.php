<html>
    <head>
        <meta charset="UTF-8">
        <!-- <link rel=" -->
        <link rel="stylesheet" href="assets/css/styles.css">
    </head>
    <body>
        <?php
            //
            // ROLES D'UTILISATION
            //
            define("ROLE", array("Administrateur", "Moderateur", "Membre"));
            if( isLogged() ) { // Si l'utilisateur est bien connecté
                $role = $_SESSION["user"]["id_role"]-1;
                echo "<h2>Connecté : " . ROLE[$role] . " - " . $_SESSION["user"]["username"] . "</h2>";
                echo "<a href='?service=deconnexion'>Déconnexion</a>"; // Proposé le lien de déconnexion
            }
            if( isset($_GET["error"]) ){ // Afficher le message erreur
                $error = urldecode($_GET["error"]);
                echo "<div class='error'>" . $error . "</div>";
            }            
        ?>