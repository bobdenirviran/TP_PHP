<?php
// FORMULAIRE DE MODIFICATION DES MESSAGES
$html_formpost .= '<label>Titre :</label>';
$html_formpost .= '<input type="text" name="Ptitle" value="' . $postrows[$key]['Ptitle'] . '" maxlength="255" placeholder="min. 1 car. max. 255 car.">';
$html_formpost .= '<label>Texte :</label>';
$html_formpost .= '<input type="text" name="Ptext" value="' . $postrows[$key]['Ptext'] . '" maxlength="255" placeholder="min. 1 car. max. 1000 car.">';
?>