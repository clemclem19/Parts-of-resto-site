<?php include('serveur.php'); ?>
<html>
<header>
<link rel="stylesheet" type="text/css" href="stylenewmember.css">

</header>
<body>
<br>
<br>
<br>
<br>
	<form method="post" action="newmember.php">
		<?php include('errors.php'); ?>
		<div class="input-group"> 
			<label>Nom de compte:</label>
			<input required style="width: 90%;" type="text" id="user" name="user" value="<?php echo $username; ?>"/>
		</div>
		<div class="input-group">
			<label>Email:</label>
			<input required style="width: 90%;" type="email" id="email" name="email" value="<?php echo $email; ?>"/>
		</div>
		<div class="input-group"> 
			<label>Mot de passe:</label>
			<input required style="width: 90%;" type="password" id="pass" name="pass_1"/>
		</div>
		<div class="input-group">
			<label>Confirmer mot de passe:</label>
			<input required style="width: 90%;" type="password" id="pass" name="pass_2"/>
		</div>
		<div class="input-group">
			<button type="submit" name="createaccount" class="btn">Créer compte</button>
		</div>
		<p>
			Déjà membre? <a href="accueil.php">Se connecter</a> 
		</p>
	</form>
</body>
<html/>