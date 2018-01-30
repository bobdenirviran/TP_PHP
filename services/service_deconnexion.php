<?php
    session_unset(); // Vidage des variables de session
    session_destroy(); // suppression de la session
    header ('Location: index.php');
?>