<!-- 
    PAGE DU LOGIN
 -->
<!-- 
 FORMULAIRE DE CONNEXION
 -->
<form action="?service=login" method="POST">

    <label>Nom d'utilisateur :</label>
    <input class="form-control" type="text" name="username" placeholder="Username"> <br>
    <label>Mot de passe :</label>
    <input class="form-control" type="password" name="password" placeholder="Password"> <br>
    <input style="display:block; margin:auto;" class="btn btn-lg btn-info" type="submit" value="Connexion">

</form>
<!-- 
FORMULAIRE D'INSRIPTION
 -->
<form action="?service=subscribe" method="POST">

    <label>Nom d'utilisateur :</label>
    <input class="form-control" type="text" name="newusername" placeholder="min. 8 car."> <br>
    <label>Mot de passe :</label>
    <input class="form-control" type="password" name="newpassword" placeholder="min. 8 car."> <br>
    <label>Confirmation du mot de passe :</label>
    <input class="form-control" type="password" name="newconfpswd" placeholder="min. 8 car."> <br>
    <input style="display:block; margin:auto;" class="btn btn-lg btn-info" type="submit" value="Inscription">

</form>
<?php
    echo    "</div>";
?>