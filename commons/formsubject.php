<?php
//
// FORMULAIRE DE SAISIE D'UN SUJET
//
$html_subject .= "<h4 style='color:lightseagreen;' align=center>Créez un nouveau sujet</h4>";
$html_subject .= '<div>';
    $html_subject .= '<form action="?service=create_subject&action=1&Cid=' . $categorie_id . '" method="POST">'; // Renvoie vers la page de service d'evnoi de message avec id catégorie et Titre du nouveau sujet
        $html_subject .= '<label style="display:inline-block; margin-left: auto; margin-right: auto;">Titre d'. "'" . 'un nouveau sujet:</label>';
        $html_subject .= '<input class="form-control" style="height:50px;" size="255" maxlength="255" type="text" name="Slabel" maxlength="255" placeholder="min. 1 car. max. 255 car."></br>';
        $html_subject .= '<input class="btn btn-lg btn-info" style="display:block; margin:auto;" type="submit" value="Envoyer le premier message">';
    $html_subject .= '</form>';
$html_subject .= '</div>';
?>