<?php
//
// FORMULAIRE DE SAISIE D'UN SUJET
//
echo '<form action="?service=create_subject&action=1&Cid=' . $categorie_id . '" method="POST">'; // Renvoie vers la page de service d'evnoi de message avec id catégorie et Titre du nouveau sujet
    echo '<label>Titre d'. "'" . 'un nouveau sujet:</label>';
    echo '<input type="text" name="Slabel" maxlength="255" placeholder="min. 1 car. max. 255 car.">';
    echo '<input type="submit" value="Envoyer le premier message">';
echo '</form>';
?>