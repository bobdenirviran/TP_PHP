<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/css/styles.css">
    </head>
    <body>
        <?php
            //
            // ROLES D'UTILISATION
            //
            define("ROLE", array("Administrateur", "Moderateur", "Membre",""));
            echo "<div class='jumbotron'>";
            echo    "<div style='color:lightseagreen;' align=center>";
            echo        "<h1>";
            echo            "Forum du Web Developper";
            echo        "</h1>";
            echo    "</div>";
            if ($page_file !== "pages/login.php" && isLogged() ) { // Si l'utilisateur est bien connecté
                $role = $_SESSION["user"]["id_role"]-1;
                echo    "<div align=center>";
                echo        "<h2>";
                echo            $_SESSION["user"]["username"];
                echo        "</h2>";
                echo    "</div>";
                echo    "<div align=center>";
                echo            ROLE[$role];
                echo    "</div>";
                echo    "<div align=right>";               
                echo            "<a class='btn btn-lg btn-info' href='?service=deconnexion'>Déconnexion</a>"; // Proposer le lien de déconnexion
                echo    "</div>";
            }
            echo "</div>";
            if( isset($_GET["error"]) ){ // Afficher le message erreur
                $error = urldecode($_GET["error"]);
                echo "<div class='alert alert-warning>'" . $error . "</div>";
            }            
        ?>