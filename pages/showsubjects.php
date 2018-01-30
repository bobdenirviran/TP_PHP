<?php
//
// PAGE D'AFFICHAGE DES SUJETS TRIES PAR CATEGORIE LES 2 ALPHABETIQUEMENT
//
echo "<h1>Liste des sujets de discussion</h1>";
$subjectslist = getAllSubjectsByCategories();
$categorie_name = ""; // Chargement de la variable de rupture de catégorie
$categorie_id = ""; // Chargement de la variable id de catégorie
$html_subject = "";
//
// BOUCLE D'AFFICHAGE DES SUJETS AVEC RUPTURE SUR LE NOM DE LA CATEGORIE
//
foreach( $subjectslist as $subject_value ) { // pour chaque enregistrement prendre toutes les valeurs de l'enregistrement
    //
    // AFFICHAGE DE LA CATEGORIE
    //
    if ($categorie_name !== $subject_value["Cname"] ) { // Si rupture sur le nom de la catégorie et si ce n'est pas la première catégorie
        if ( $categorie_name !== "" ) {
            $html_subject .= '</table></p>';
            include "commons/formsubject.php"; // inclure le formulaire de saisie d'un nouveau sujet'
        }
        $html_subject .= '<p>Catégorie : ' . $subject_value["Cname"] . ' </p>'; // Afficher le libellé de la catégorie
        $html_subject .= '<p><table>';
        $html_subject .= '<tr>';
            $html_subject .= '<th>Sujet</th>';
            $html_subject .= '<th>Auteur</th>';
            $html_subject .= '<th>Date</th>';
            $html_subject .= '<th>Nb posts</th>';
            $html_subject .= '<th>Actions</th>';
        $html_subject .= '</tr>';
        $categorie_name = $subject_value["Cname"]; // charger le libellé de la nouvelle catégorie pour comparaison de la ruptur
    }
    //
    // AFFICHAGE DE LA LISTE DES SUJETS DE LA CATEGORIE
    //
    $html_subject .= '<tr>';
        $html_subject .= '<td>'.$subject_value["Slabel"].'</td>';
        $html_subject .= '<td>'.$subject_value["username"].'</td>';
        $html_subject .= '<td>'.$subject_value["Sdate"].'</td>';
        $html_subject .= '<td>'.$subject_value["nbposts"].'</td>';
        $html_subject .= '<td>';
        $html_subject .= '<a href="?page=showposts&Sid=' . $subject_value["Sid"] . '">Voir les messages</a>';
        if ( intval($_SESSION["user"]["id_role"]) < 3 && $subject_value["Sclosed"] == "0" ) { // Test du role du user Moderateur ou Adminsitrateur pour fermer un sujet et sujet pas encore clos
            $html_subject .= '<span> </span>';
            $html_subject .= '<a href="?service=close_subject&Sid=' . $subject_value["Sid"] . '">Clore le sujet</a>'; // lien vers service fermer sujet 
        }
        if ( intval($_SESSION["user"]["id_role"]) < 3 && $subject_value["Sclosed"] == "1" ) { // Test du role du user Moderateur ou Adminsitrateur pour fermer un sujet et sujet pas encore clos
            $html_subject .= '<span> </span>';
            $html_subject .= '<a href="?service=open_subject&Sid=' . $subject_value["Sid"] . '">Réouvrir le sujet</a>'; // lien vers service fermer sujet 
        }
        if ( intval($_SESSION["user"]["id_role"]) == 1 ) { //  Test du role du user Administrateur pour fermer un sujet
            $html_subject .= '<span> </span>';
            $html_subject .= '<a href="?service=delete_subject&Sid=' . $subject_value["Sid"] . '">Supprimer le sujet</a>'; // Lien vers service supprimer sujet
        }
        $html_subject .= '</td>';
    $html_subject .= '</tr>';
    $categorie_id = $subject_value["Cid"]; // Chargement de l'id de catégorie pour creation du nouveau sujet dans cette catégorie
}
if ( $categorie_name !== "" ) { // si présence d'une catégorie
    $html_subject .= '</table></p>';
    include "commons/formsubject.php"; // inclure le formulaire de saisie d'un nouveau sujet
}
echo $html_subject; 
?>