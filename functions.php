<?php
//
// OUVERTURE DE LA SESSION
//
    session_start();
//
// DEFINITION DES CONSTANTES
//
    // CONSTANTE DE RENFORCEMENT DU MOT DE PASSE
    define("SALT", "QWONQULqF0");
    // CONSTANTE DE LA BASE DE DONNES
    define("DB_HOST", "localhost");
    define("DB_NAME", "forum");
    define("DB_USER", "root");
    define("DB_PASS", "root");
    // ROLES D'UTILISATION
    define("ADMIN", 1);
    define("MODERATEUR", 2);
    define("USER", 3);
    // CONSTANTE DU NOMBRE DE POSTS PAR PAGE
    define("POSTS_BY_PAGE", 2);
    // TAILLE DES ICONES BOOTSTRAP
    define("ICONE_SIZE", "20px");
//
// CONNEXION A LA BASE DE DONNEES
//
function getConnection() {

    $connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if( $errors = mysqli_connect_error($connection) ){
        $errors = utf8_encode($errors);
        header("Location: ?page=login&error=" . $errors); 
        die();
    }
    return $connection;
}
//
// TEST SI MEMBRE LOGUE
//
function isLogged( $as_role = USER ) {

    return ( 
        isset( $_SESSION["user"] ) 
        && $_SESSION["user"]["id_role"] <= $as_role 
    );
}
//
// CONNECTION REQUISE POUR UN ROLE
//
function connectionRequired( $as_role = USER ) { // forcer le role en utlisateur

    if( !isset( $_SESSION["user"] ) ){ // si pas absence d'un user connecté de session
        header("Location: ?page=login"); // renvoie vers la page de login
        die(); // tuer la tache
    }
    else if( !isLogged( $as_role ) ){ // sinon pas logué 
        $error = urlencode("Vous n'avez pas les autorisations nécessaires !"); // chargé le message d'erreur
        header("Location: ?page=login&error=".$error); // revoyé la page login avec erreur d'autorisation
        die(); // tuer la tache
    }
}
//
// PRESENCE DES AUTORISATIONS OU DROITS PAR RAPPORT AU ROLE 
//
function isGranted( $id_role, $id_grant ) {

    $connection = getConnection(); 
    $sql = "SELECT COUNT(*)
            FROM link_role_grant
            WHERE link_role_grant.id_role = ? 
            AND link_role_grant.id_grant = ?";
    $statement = mysqli_prepare( $connection, $sql );
    mysqli_stmt_bind_param( $statement, "ii", $id_role, $id_grant );
    mysqli_stmt_execute( $statement );
    mysqli_stmt_bind_result( $statement, $result );
    mysqli_stmt_fetch( $statement );
    return (boolean)$result; //cast converting
}
//
// RECUPERATION DES DONNES username et password DES UTILISATEURS
//
function getUsers() {
    // L: Pierre    P:starwars ADMIN
    // L: Paul      P:football MODERATOR
    // L: Bernard   P:bernard  USER
    // L: David     P:david    USER
    // L: Anthonny  P:anthonny USER
    $connection = getConnection(); // Connexion à la base de données
    $sql = "SELECT username, password FROM users";
    $results = mysqli_query($connection, $sql);
    mysqli_close( $connection );
    $users = [];
    while( $row = mysqli_fetch_assoc($results) ){
        $users[] = $row;
    }
    return $users;
}
//
// RECUPERATION DES DONNES username et password D'UN UTILISATEUR
//
function getUser( $username, $password ) {

    $connection = getConnection(); // Connexion à la base de données
    $sql = "SELECT * FROM users WHERE username=? AND password=?"; // Select des valeurs de la base de données en variables cachées
    $statement = mysqli_prepare( $connection, $sql ); // Prépare une requête avec des ? en inconnues
    mysqli_stmt_bind_param( $statement, "ss", $username, $password ); // Remplace les ? par les variables (+ sécurité)
    mysqli_stmt_execute( $statement ); // Exécution de la requête
    mysqli_stmt_bind_result($statement, $b_id, $b_username, $b_password, $b_id_role); // On associe des variables aux colonnes récupérées
    mysqli_stmt_fetch($statement); // On prend le premier enregistrement ( les variables associées précédemment vont être mises à jour )
    $user = null;
    if( $b_id ){
        $user = [
            "id" => $b_id,
            "username" => $b_username,
            "password" => $b_password,
            "id_role" => $b_id_role
        ];
    }
    mysqli_stmt_close( $statement ); // Fermeture de la préparation de la requête
    mysqli_close( $connection ); // Fermeture de la connexion à la base de données
    return $user;
}
//
// RECHERCHE D'UN SUJET PAR LE TITRE
//
function getSubjectByLabel( $subject_label ) { 

    $connection = getConnection(); // Connexion à la base de données
    $sql = "SELECT COUNT(*) FROM subjects WHERE subjects.Slabel=?"; // Select des valeurs de la base de données en variables cachées
    $statement = mysqli_prepare( $connection, $sql ); // Preparation de la requete
    mysqli_stmt_bind_param( $statement, "s", $subject_label ); // Passage des parametres cachés
    mysqli_stmt_execute( $statement ); // Exécution de la requete 
    mysqli_stmt_bind_result( $statement, $existed ); // Récupération du résultat et stockage dans une variable 
    $existed = (boolean)($existed > 0); // Conversion castind de la variable d'existence
    // mysqli_stmt_fetch( $statement ); // Récupération de résultat requete
    mysqli_stmt_close( $statement ); // Fermeture de la préparation de la requête
    mysqli_close( $connection ); // Fermeture de la connexion à la base de données
    return $existed;
}

//
// RECHERCHE DE TOUS LES SUJETS 
//
function getAllSubjectsByCategories() {

    $connection = getConnection(); // Connexion à la base de données
    $sql = "SELECT categories.Cid, categories.Cname, subjects.Sid, subjects.Slabel, subjects.Sdate, subjects.Sclosed, subjects.Sid_categorie, subjects.Sid_user, username, 
            COUNT(posts.Pid) as nbposts
            FROM categories
            JOIN subjects
            ON subjects.Sid_categorie = categories.Cid
            JOIN users
            ON users.id = Sid_user
            JOIN posts 
            ON posts.Pid_subject = subjects.Sid
            GROUP BY subjects.Sid
            ORDER BY categories.Cname, subjects.Slabel";
    $results = mysqli_query($connection, $sql); // Exécution de la requete et chargement du résultat dans une variable
    mysqli_close( $connection ); // Fermeture de la connexion à la base de données
    while( $row = mysqli_fetch_assoc($results) ){ // tant que l'on peut lire un enregistrements du résultat que l'on charge dans la variable d'enregistrement
        $subjectslist[] = $row; // Ajout de l'enregistrement à la liste des sujets
    }
    // debug($subjectslist);
    // Tout en string
    // array 
    // 0 => 
    //   array
    //     'Cid'
    //     'Cname'
    //     'Sid'
    //     'Slabel'
    //     'Sdate '
    //     'Sclosed'
    //     'Sid_categorie'
    //     'Sid_user'
    //     'username' 
    //     'nbposts'      
    return $subjectslist;
}
//
// RECHERCHE DU SUJET ET COMPTAGE DE SES POSTS
// 
function getSubjectById( $subject_id ) {

    $connection = getConnection(); // Connection à la base de données
    // Requete SQL sélection des champs du sujet et username de son créateur avec comptages des posts
    // Jointure interne des sujets sur l'id du sujet du post
    // Jointure interne des users sur l'id du créateur du sujet
    // Lorsque l'id du sujet du post correspond au sujet sélectionné
    $sql = "SELECT subjects.*, users.username, categories.Cname,
            COUNT(posts.Pid) as nbposts
            FROM posts
            JOIN subjects ON subjects.Sid=posts.Pid_subject
            JOIN users ON users.id=Sid_user
            JOIN categories ON categories.Cid=subjects.Sid_categorie
            WHERE posts.Pid_subject=?";
    $statement = mysqli_prepare( $connection, $sql ); // Preparation de la requete
    mysqli_stmt_bind_param( $statement, "i", $subject_id ); // Passage des parametres cachés
    mysqli_stmt_execute( $statement ); // Exécution de la requete
    mysqli_stmt_bind_result( $statement, $b_Sid, $b_Sdate, $b_Slabel, $b_Sclosed, $b_Sid_categorie, $b_Sid_user, $b_username, $b_Cname, $b_nbposts ); // On associe des variables aux colonnes récupérées
    mysqli_stmt_fetch($statement); // On prend le premier enregistrement ( les variables associées précédemment vont être mises à jour )
    $subject = null;
    if( $b_Sid ){
        $subject = [
            "Sid" => $b_Sid,
            "Sdate" => $b_Sdate,
            "Slabel" => $b_Slabel,
            "Sclosed" => $b_Sclosed,
            "Sid_categorie" => $b_Sid_categorie,
            "Sid_user" => $b_Sid_user,
            "username" => $b_username,
            "Cname" => $b_Cname,
            "nbposts" => $b_nbposts
        ];
    }
    $_SESSION["subject"] = $subject;// garder les champs de sujets en session
    mysqli_stmt_close( $statement ); // Fermeture de la préparation de la requête
    mysqli_close( $connection ); // Fermeture de la connexion à la base de données
    return $subject;
}
//
// INSERT D'UN SUJET
//
function insertSubject( $subject ) {
        
    $connection = getConnection(); // Connexion à la base de données
    $sql = "INSERT INTO subjects VALUES (null, ?, ?, ?, ?, ?)"; //insert des valeurs de la base de données en variables cachées
    $statement = mysqli_prepare( $connection, $sql ); // Preparation de la requete
    mysqli_stmt_bind_param( // Passage des parametres cachés
        $statement, 
        "ssiii", 
        $subject["Sdate"],
        $subject["Slabel"],
        $subject["Sclosed"],
        $subject["Sid_categorie"],
        $subject["Sid_user"]
    );
    mysqli_stmt_execute( $statement ); // Exécution de la requete 
    $inserted = mysqli_stmt_affected_rows( $statement ); // Résultat de l'insert chargé dans une variable nb enregistrement trouvé = 1
    $inserted = (boolean)($inserted > 0); // Conversion du renvoi de résultat d'insert à true si bien inseré et false si erreur insert
    $_SESSION["id_subject_inserted"] = mysqli_insert_id( $connection ); // Enregistrement dans un tableau de session du sujet enregistré
    mysqli_stmt_close( $statement ); // Fermeture de la préparation de la requête
    mysqli_close( $connection ); // Fermeture de la connexion à la base de données
    $inserted = (boolean)($inserted > 0);
    return $inserted;
}
//
// SUPPRESSION D'UN SUJET
// 
function deleteSubjectById( $subject_id ) {

    $connection = getConnection(); // Connection à la base de données
    // Requete SQL suppression du message
    // Quand l'id du sujet correspond à l'id fournit en parametre
    $sql = "DELETE FROM subjects WHERE subjects.Sid=? LIMIT 1";
    $statement = mysqli_prepare( $connection, $sql ); // Preparation de la requete
    mysqli_stmt_bind_param( $statement, "i", $subject_id ); // Passage des parametres cachés
    mysqli_stmt_execute( $statement ); // Exécution de la requete 
    $deleted = mysqli_stmt_affected_rows($statement); // On prend le premier enregistrement ( les variables associées précédemment vont être mises à jour )
    $deleted = (boolean)($deleted>0); // Cast converting en booléen du flag de retour de suppression
    mysqli_stmt_close( $statement ); // Fermeture de la préparation de la requête
    mysqli_close( $connection ); // Fermeture de la connexion à la base de données
    return $deleted;
}
//
// CLORE UN SUJET PAR MODIFICATION
//
function updateSubjectById( $subject_id ) {
    $connection = getConnection(); // Connexion à la base de données
    $sql = "UPDATE subjects 
            SET Sdate=?, Slabel=?, Sclosed=?, Sid_categorie=?, Sid_user=?
            WHERE subjects.Sid=?"; //modification des valeurs de la base de données en variables cachées
    $statement = mysqli_prepare( $connection, $sql ); // Preparation de la requete
    mysqli_stmt_bind_param( // Passage des parametres cachés
        $statement, 
        "ssiiii", 
        $_SESSION["subject"]["Sdate"],
        $_SESSION["subject"]["Slabel"],
        $_SESSION["subject"]["Sclosed"],
        $_SESSION["subject"]["Sid_categorie"],
        $_SESSION["subject"]["Sid_user"],
        $_SESSION["subject"]["Sid"]
    );
    mysqli_stmt_execute( $statement ); // Exécution de la requete
    $modified = mysqli_stmt_affected_rows( $statement ); // Résultat de l'insert chargé dans une variable 1 = Ok
    $modified = (boolean)($modified > 0); // Conversion du renvoi de résultat d'insert à true si bien inseré et false si erreur insert
    mysqli_stmt_close( $statement ); // Fermeture de la préparation de la requête
    mysqli_close( $connection ); // Fermeture de la connexion à la base de données
    return $modified;
}
//
// INSERT D'UN MESSAGE
//
function insertPost( $postmessage ) {
        
    $connection = getConnection(); // Connexion à la base de données
    $sql = "INSERT INTO posts VALUES (null, ?, ?, ?, ?, ?)"; //insert des valeurs de la base de données en variables cachées
    $statement = mysqli_prepare( $connection, $sql ); // Preparation de la requete
    mysqli_stmt_bind_param( // Passage des parametres cachés
        $statement, 
        "sssii", 
        $postmessage["Pdate"],
        $postmessage["Ptitle"],
        $postmessage["Ptext"],
        $postmessage["Pid_subject"],
        $postmessage["Pid_user"]
    );
    mysqli_stmt_execute( $statement ); // Exécution de la requete
    $inserted = mysqli_stmt_affected_rows( $statement ); // Résultat de l'insert chargé dans une variable 1 = Ok
    $inserted = (boolean)($inserted > 0); // Conversion du renvoi de résultat d'insert à true si bien inseré et false si erreur insert
    mysqli_stmt_close( $statement ); // Fermeture de la préparation de la requête
    mysqli_close( $connection ); // Fermeture de la connexion à la base de données
    return $inserted;
}
//
// MODIFICATION D'UN MESSAGE
//
function updatePostById( $post_id, $post_fields ) {
        
    $connection = getConnection(); // Connexion à la base de données
    $sql = "UPDATE posts 
            SET Pdate=?, Ptitle=?, Ptext=?, Pid_subject=?, Pid_user=?
            WHERE posts.Pid=?"; //modification des valeurs de la base de données en variables cachées
    $statement = mysqli_prepare( $connection, $sql ); // Preparation de la requete
    mysqli_stmt_bind_param( // Passage des parametres cachés
        $statement, 
        "sssiii", 
        $post_fields["Pdate"],
        $post_fields["Ptitle"],
        $post_fields["Ptext"],
        $post_fields["Pid_subject"],
        $post_fields["Pid_user"],
        $post_fields["Pid"]
    );
    mysqli_stmt_execute( $statement ); // Exécution de la requete
    $modified = mysqli_stmt_affected_rows( $statement ); // Résultat de l'insert chargé dans une variable 1 = Ok
    $modified = (boolean)($modified > 0); // Conversion du renvoi de résultat d'insert à true si bien inseré et false si erreur insert
    mysqli_stmt_close( $statement ); // Fermeture de la préparation de la requête
    mysqli_close( $connection ); // Fermeture de la connexion à la base de données
    return $modified;
}
//
// SUPPRESSION D'UN MESSAGE
// 
function deletePostById( $post_id ) {

    $connection = getConnection(); // Connection à la base de données
    // Requete SQL suppression du post
    // Quand l'id du message correspond à l'id fournit en parametre
    $sql = "DELETE FROM posts WHERE posts.Pid=? LIMIT 1";
    $statement = mysqli_prepare( $connection, $sql ); // Preparation de la requete
    mysqli_stmt_bind_param( $statement, "i", $post_id ); // Passage des parametres cachés
    mysqli_stmt_execute( $statement ); // Exécution de la requete 
    $deleted = mysqli_stmt_affected_rows($statement); // On prend le premier enregistrement ( les variables associées précédemment vont être mises à jour )
    $deleted = (boolean)($deleted>0); // Cast converting en booléen du flag de retour de suppression
    mysqli_stmt_close( $statement ); // Fermeture de la préparation de la requête
    mysqli_close( $connection ); // Fermeture de la connexion à la base de données
    return $deleted;
}
//
// RECHERCHE DES MESSAGES D'UN SUJET 
//
function getPageOfPostsForOneSubject( $subject_id, $index_page = 0 ) {

    $start_post = $index_page * POSTS_BY_PAGE; // Variable de post de début de page
    $nb_posts = POSTS_BY_PAGE; // Variable de nombre de posts par page
    $connection = getConnection(); // Connextion à la base de données
    // Requete SQL sélection des champs du post et username de son créateur 
    // Jointure interne des users sur l'id du créateur du post
    // Lorsque l'id du sujet du post correspond au sujet sélectionné
    // Comprenant un début à partir de l'enregistrement du début de page et un nombre d'enregistrement par page
    $sql = "SELECT Pid, Pdate, Ptitle, Ptext, Pid_subject, Pid_user, username
            FROM posts
            JOIN users ON users.id = posts.Pid_user
            WHERE Pid_subject = ?
            LIMIT ?,?"; 
    $statement = mysqli_prepare( $connection, $sql ); // Preparation de la requete
    mysqli_stmt_bind_param( $statement, "iii", $subject_id, $start_post, $nb_posts ); // Passage des parametres cachés
    mysqli_stmt_execute( $statement ); // Exécution de la requete 
    mysqli_stmt_bind_result( $statement, $Pid, $Pdate, $Ptitle, $Ptext, $Pid_subject, $Pid_user, $username ); // Liaison du résultat dans les variables de sorties
    $postrows = null; //  Données des posts mises à vide
    while( mysqli_stmt_fetch( $statement ) ) { // Pour chaque enregistrement de post
        $postrows[] = [ // ajouter au tableau associatif
            "Pid" => intval($Pid),
            "Pdate" => $Pdate,
            "Ptitle" => utf8_encode($Ptitle),
            "Ptext" => utf8_encode($Ptext),
            "Pid_subject" => intval($Pid_subject),
            "Pid_user" => intval($Pid_user),
            "username" => utf8_encode($username)
        ];
    }
    $_SESSION["first_post"] = $postrows["0"]["Pid"]; // Chargement en session de l'id du premier post du sujet pour l'interdiction en suppression
    // Fermeture de la connexion à la base de données
    mysqli_stmt_close( $statement );
    mysqli_close( $connection );
    // debug($postrows);
    // array (size=2)
    // 0 => 
    // array (size=7)
    // 'Pid' => int 1
    // 'Pdate' => string '2018-01-26 20:41:19' (length=19)
    // 'Ptitle' => string 'titre 1' (length=7)
    // 'Ptext' => string 'ceci est le texte titre 1 SUJET 1' (length=33)
    // 'Pid_subject' => int 1
    // 'Pid_user' => int 1
    // 'username' => string 'Pierre' (length=6)
    return $postrows;
}
//
// DEBBUG
//
function debug( $arg, $printr = false ) {
    if( $printr ){
        echo "<pre>";
        print_r($arg);
        echo "</pre>";
    }
    else {
        var_dump( $arg );
    }
    die();
}
// Pour mémoire en cas d'errer requete avec statement// debug(mysqli_stmt_error($statement))