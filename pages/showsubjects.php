<?php
//
// PAGE D'AFFICHAGE DES SUJETS TRIES PAR CATEGORIE LES 2 ALPHABETIQUEMENT
//
echo "<h1>Liste des sujets de discussion</h1>";
$subjectslist = getAllSubjectsByCategories();
$categorie_name = ""; // Chargement de la variable de rupture de catégorie
$categorie_id = ""; // Chargement de la variable id de catégorie
//
// BOUCLE D'AFFICHAGE DES SUJETS AVEC RUPTURE SUR LE NOM DE LA CATEGORIE
//
foreach( $subjectslist as $subject_value ) { // pour chaque enregistrement prendre toutes les valeurs de l'enregistrement
    //
    // AFFICHAGE DE LA CATEGORIE
    //
        if ($categorie_name !== $subject_value["Cname"] ) { // Si rupture sur le nom de la catégorie
            if ( $categorie_name !== "" ) { // si ce n'est pas la première catégorie
                include "commons/formsubject.php"; // inclure le formulaire de saisie d'un nouveau sujet'
            }
            echo '<span>' . $subject_value["Cname"] . ' </span>'; // Afficher le libellé de la catégorie 
            $categorie_name = $subject_value["Cname"]; // charger le libellé de la nouvelle catégorie pour comparaison de la ruptur
        }
    //
    // AFFICHAGE DE LA LISTE DES SUJETS DE LA CATEGORIE
    //
        $html_subject = '<div style="border: 1px solid black; margin: 5px;">';
            $html_subject .= '<span> Sujet : '  . $subject_value["Slabel"]    . ' </span>';
            $html_subject .= '<span> Créé par ' . $subject_value["username"]  . ' </span>';
            $html_subject .= '<span> le '       . $subject_value["Sdate"]     . ' </span>';
            $html_subject .= '<span> contient ' . $subject_value["nbposts"]   . ' post(s) </span>';
            $html_subject .= '<a href="?page=showposts&Sid=' . $subject_value["Sid"] . '">Voir les messages</a>';
            if (intval($_SESSION["user"]["id_role"]) < 3) { // Test du role du user Moderateur ou Adminsitrateur pour fermer un sujet
                $html_subject .= '<span> </span>';
                $html_subject .= '<a href="?service=close_subject&Sid=' . $subject_value["Sid"] . '">Clore le sujet</a>'; // lien vers service fermer sujet 
            }
            if (intval($_SESSION["user"]["id_role"]) == 1) { //  Test du role du user Administrateur pour fermer un sujet
                $html_subject .= '<span> </span>';
                $html_subject .= '<a href="?service=delete_subject&Sid=' . $subject_value["Sid"] . '">Supprimer le sujet</a>'; // Lien vers service supprimer sujet
            }
        $html_subject .= '</div>';
        $categorie_id = $subject_value["Cid"]; // Chargement de l'id de catégorie pour creation du nouveau sujet dans cette catégorie
        echo $html_subject; 
}
if ( $categorie_name !== "" ) { // si présence d'une catégorie
    include "commons/formsubject.php"; // inclure le formulaire de saisie d'un nouveau sujet
}
?>