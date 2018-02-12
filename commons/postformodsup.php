<?php
// FORMULAIRE DE MODIFICATION DES MESSAGES
$html_formpost .= '<label>Titre :</label>';
$html_formpost .= '<textarea class="form-control" rows="1" cols="255" type="text" name="Ptitle" maxlength="255" placeholder="min. 1 car. max. 255 car.">' . $postrows[$key]['Ptitle'] . '</textarea>';
$html_formpost .= '</br>';
$html_formpost .= '<label>Message :</label>';
$html_formpost .= '<textarea class="form-control" rows="4" cols="255" type="text" name="Ptext" maxlength="1000" placeholder="min. 1 car. max. 1000 car.">' . $postrows[$key]['Ptext'] . '</textarea>';
?>