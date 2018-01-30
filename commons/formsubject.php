<?php
//
// FORMULAIRE DE SAISIE D'UN SUJET
//
$html_subject .= '<div>';
    $html_subject .= '<form action="?service=create_subject&action=1&Cid=' . $categorie_id . '" method="POST">'; // Renvoie vers la page de service d'evnoi de message avec id catégorie et Titre du nouveau sujet
        $html_subject .= '<label>Titre d'. "'" . 'un nouveau sujet:</label>';
        $html_subject .= '<input type="text" name="Slabel" maxlength="255" placeholder="min. 1 car. max. 255 car.">';
        $html_subject .= '<input type="submit" value="Envoyer le premier message">';
    $html_subject .= '</form>';
$html_subject .= '</div></br>';
?>