<h1>Forum web développeur</h1>
<!-- 
 FORMULAIRE DE CONNEXION
 -->
<h2> CONNEXION </h2>
<form action="?service=login" method="POST">

    <label>Nom d'utilisateur :</label>
    <input type="text" name="username"> <br>
    <label>Mot de passe :</label>
    <input type="password" name="password"> <br>
    <input type="submit" value="Connexion">

</form>
<!-- 
FORMULAIRE D'INSRIPTION
 -->
<h2> INSCRIPTION </h2>
<form action="?service=subscribe" method="POST">

    <label>Nom d'utilisateur :</label>
    <input type="text" name="newusername" placeholder="min. 8 car."> <br>
    <label>Mot de passe :</label>
    <input type="password" name="newpassword" placeholder="min. 8 car."> <br>
    <label>Confirmation du mot de passe :</label>
    <input type="password" name="newconfpswd" placeholder="min. 8 car."> <br>
    <input type="submit" value="Inscription">

</form>
