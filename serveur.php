<?php
	session_start();
	$username="";
	$email="";
	$password1="";
	$password2="";
	$errors = array();
	$errors2 = array();
	
	$mysqli = new mysqli("localhost","root","","livraison");
	if ($mysqli -> connect_errno) {
		echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
		exit();
	} 
	if(isset($_POST['createaccount'])){
		$username = $mysqli -> real_escape_string($_POST['user']);
		$email = $mysqli -> real_escape_string($_POST['email']);
		$password1 = $mysqli -> real_escape_string($_POST['pass_1']);
		$password2 = $mysqli -> real_escape_string($_POST['pass_2']);
		
		if(empty($username)){
			array_push($errors, "Nom d'utilisateur requis");
		}
		
		if(empty($password1)){
			array_push($errors, "Password requis");
		}
		if($password2 != $password1){
			array_push($errors, "Les deux mots de passe ne sont pas pareil");
		}
		if(empty($email)){
			array_push($errors, "Email requis");
		}
		$result = $mysqli -> query("select * from account where Username= '$username'");
		$row = mysqli_fetch_array($result);
		if($row['Username'] == $username){
			array_push($errors, "Nom de compte existe déjà");
		}
		if(count($errors) == 0) {
			$password= md5($password1);
			$sql = "INSERT INTO account (Username, Password,Email) VALUES ('$username', '$password', '$email')";
			$mysqli -> query($sql);
			$_SESSION['username'] = $username;
			$_SESSION['success'] = "Vous etes connectés";
			header('location: restaurantpage.php');
			
			
		}
		
		
		
	}
	if(isset($_POST['login'])){
		
		$username = $mysqli -> real_escape_string($_POST['user']);
		$password = $mysqli -> real_escape_string($_POST['pass']);
		
		if(empty($username)){
			array_push($errors, "Nom d'utilisateur requis");
		}
		
		if(empty($password)){
			array_push($errors, "Password requis");
		}
		
		if(count($errors) == 0){
			$password = md5($password);
			$result = $mysqli -> query("select * from account where Username= '$username' and Password='$password'");
			if(mysqli_num_rows($result) == 1){
				$_SESSION['username'] = $username;
				$_SESSION['success'] = "Vous etes connectés";
				header('location: restaurantpage.php');
			}else{
				array_push($errors, "Le compte/password est incorrect");
				
			}
			
			
			
		}
	}
	if(isset($_POST['insertnewname'])){
		$restoname = $mysqli -> real_escape_string($_POST['newname']);
		$sql = "INSERT INTO restaurant (RestaurantName) VALUES ('$restoname')";
		$mysqli -> query($sql);
		$searchid = $mysqli -> query("SELECT * FROM `restaurant` where RestaurantName='$restoname' ORDER BY Restaurantid DESC LIMIT 1");
		$row = mysqli_fetch_array($searchid);
		$restoid=$row["Restaurantid"];
		$username =$_SESSION['username'];
		$sql1 = "UPDATE account set Restaurantid = $restoid where (Username= '$username')";
		$mysqli -> query($sql1);
		$sql2 = "UPDATE restaurant set Carteid = $restoid where (Restaurantid= '$restoid')";
		$mysqli -> query($sql2);
		$_SESSION['restoname'] = $restoname;
		$_SESSION['restoid'] = $restoid;
		$sqlcarte = "INSERT INTO carte (Carteid) VALUES ('$restoid')";
		$mysqli -> query($sqlcarte);
		header('location: restaurantpage.php');
			
			
	}
	if(isset($_GET['logout'])){
		session_destroy();
		unset($_SESSION['username']);
		unset($_SESSION['restoid']);
		unset($_SESSION['restoname']);
		unset($_SESSION['Carteid']);
		header('location: accueil.php');
	}
	if(isset($_POST['addnewentree'])){
		$entreename = $mysqli -> real_escape_string($_POST['entreename']);
		$entreedescription = $mysqli -> real_escape_string($_POST['entreedescription']);
		$entreeprice = $mysqli -> real_escape_string($_POST['entreeprice']);
		
		if(empty($entreename)){
			array_push($errors, "Doit avoir un nom");
		}
		if(empty($entreeprice)){
			array_push($errors, "Doit avoir un prix");
		}
		$user=$_SESSION['username'];
		$sqlfinder = "SELECT restaurant.* FROM restaurant WHERE restaurant.Restaurantid in(Select account.Restaurantid from account where Username='$user')";
		$temp=$mysqli -> query($sqlfinder);
		$row = mysqli_fetch_array($temp);
		$carteid=$row["Carteid"];
		$_SESSION['Carteid'] = $carteid;
		if(count($errors) == 0 && isset($entreedescription)) {
			
			$sql = "INSERT INTO entree (EntreeName, EntreeDescription,EntreePrice, Carteid) VALUES ('$entreename', '$entreedescription', '$entreeprice','$carteid')";
			$mysqli -> query($sql);
			header('location: restaurantpage.php');
		}else if (count($errors) == 0){
			$sql = "INSERT INTO entree (EntreeName,EntreePrice, Carteid) VALUES ('$entreename','$entreeprice','$carteid')";
			$mysqli -> query($sql);
			header('location: restaurantpage.php');
		}
	}
	
	if(isset($_POST['addnewdessert'])){
		$dessertname = $mysqli -> real_escape_string($_POST['dessertname']);
		$dessertdescription = $mysqli -> real_escape_string($_POST['dessertdescription']);
		$dessertprice = $mysqli -> real_escape_string($_POST['dessertprice']);
		
		if(empty($dessertname)){
			array_push($errors, "Doit avoir un nom");
		}
		if(empty($dessertprice)){
			array_push($errors, "Doit avoir un prix");
		}
		$user=$_SESSION['username'];
		$sqlfinder = "SELECT restaurant.* FROM restaurant WHERE restaurant.Restaurantid in(Select account.Restaurantid from account where Username='$user')";
		$temp=$mysqli -> query($sqlfinder);
		$row = mysqli_fetch_array($temp);
		$carteid=$row["Carteid"];
		$_SESSION['Carteid'] = $carteid;
		if(count($errors) == 0 && isset($dessertdescription)) {
			
			$sql = "INSERT INTO dessert (DessertName, DessertDescription,DessertPrice, Carteid) VALUES ('$dessertname', '$dessertdescription', '$dessertprice','$carteid')";
			$mysqli -> query($sql);
			header('location: restaurantpage.php');
		}else if (count($errors) == 0){
			$sql = "INSERT INTO dessert (DessertName,DessertPrice, Carteid) VALUES ('$dessertname','$dessertprice','$carteid')";
			$mysqli -> query($sql);
			header('location: restaurantpage.php');
		}
	}
	
	
	if(isset($_POST['addnewplat'])){
		$platname = $mysqli -> real_escape_string($_POST['platname']);
		$platdescription = $mysqli -> real_escape_string($_POST['platdescription']);
		$platprice = $mysqli -> real_escape_string($_POST['platprice']);
		
		if(empty($platname)){
			array_push($errors, "Doit avoir un nom");
		}
		if(empty($platprice)){
			array_push($errors, "Doit avoir un prix");
		}
		
		$user=$_SESSION['username'];
		$sqlfinder = "SELECT restaurant.* FROM restaurant WHERE restaurant.Restaurantid in(Select account.Restaurantid from account where Username='$user')";
		$temp=$mysqli -> query($sqlfinder);
		$row = mysqli_fetch_array($temp);
		$carteid=$row["Carteid"];
		$_SESSION['Carteid'] = $carteid;
		
		if(count($errors) == 0 && isset($platdescription)) {
			
			$sql = "INSERT INTO plat (PlatName, PlatDescription,PlatPrice, Carteid) VALUES ('$platname', '$platdescription', '$platprice','$carteid')";
			$mysqli -> query($sql);
			$_SESSION['platname'] = $platname;
			$_SESSION['platdescription'] = $platdescription;
			$_SESSION['platprice'] = $platprice;
			header('location: restaurantpage.php');
		}else if (count($errors) == 0){
			$sql = "INSERT INTO plat (PlatName,PlatPrice, Carteid) VALUES ('$platname','$platprice','$carteid')";
			$mysqli -> query($sql);
		
			header('location: restaurantpage.php');
			
			
		}
	}
	if(isset($_POST['addnewboisson'])){
		$boissonname = $mysqli -> real_escape_string($_POST['boissonname']);
		$boissonvolume = $mysqli -> real_escape_string($_POST['boissonvolume']);
		$boissonprice = $mysqli -> real_escape_string($_POST['boissonprice']);
		
		if(empty($boissonname)){
			array_push($errors, "Doit avoir un nom");
		}
		if(empty($boissonprice)){
			array_push($errors, "Doit avoir un prix");
		}
		if(empty($boissonvolume)){
			array_push($errors, "Doit avoir un volumz");
		}
		
		$user=$_SESSION['username'];
		$sqlfinder = "SELECT restaurant.* FROM restaurant WHERE restaurant.Restaurantid in(Select account.Restaurantid from account where Username='$user')";
		$temp=$mysqli -> query($sqlfinder);
		$row = mysqli_fetch_array($temp);
		$carteid=$row["Carteid"];
		$_SESSION['Carteid'] = $carteid;
		
		if(count($errors)==0) {
			
			$sql = "INSERT INTO boisson (BoissonName, BoissonVolume,BoissonPrice, Carteid) VALUES ('$boissonname', '$boissonvolume', '$boissonprice','$carteid')";
			$mysqli -> query($sql);
			header('location: restaurantpage.php');
		}
	}
	if(isset($_POST['addnewaddress'])){
		$streetname = $mysqli -> real_escape_string($_POST['streetname']);
		$cityname = $mysqli -> real_escape_string($_POST['cityname']);
		$numrue = $mysqli -> real_escape_string($_POST['numrue']);
		if(empty($streetname)){
			array_push($errors, "Doit avoir une ville");
		}
		if(empty($cityname)){
			array_push($errors, "Doit avoir une rue");
		}
		if(empty($numrue)){
			array_push($errors, "Doit avoir une numéro");
		}
		$user=$_SESSION['username'];
		$sqlfinder = "SELECT restaurant.* FROM restaurant WHERE restaurant.Restaurantid in(Select account.Restaurantid from account where Username='$user')";
		$temp=$mysqli -> query($sqlfinder);
		$row = mysqli_fetch_array($temp);
		$restoid=$row["Restaurantid"];
		if(count($errors)==0) {
			
			$sqlupdate = "UPDATE restaurant set Nomrue = '".$streetname."', Nomville =  '".$cityname."', Numville =  '".$numrue."' where (Restaurantid= '$restoid')";
			$mysqli -> query($sqlupdate);
			header('location: restaurantpage.php');
		}
	}
	if(isset($_POST['addnewhoraire'])){
		$openingtime = $mysqli -> real_escape_string($_POST['openingtime']);
		$closingtime = $mysqli -> real_escape_string($_POST['closingtime']);
		if(empty($closingtime)){
			array_push($errors, "Doit avoir une heure de fermeture");
		}
		if(empty($openingtime)){
			array_push($errors, "Doit avoir une heure d'ouverture");
		}
		$user=$_SESSION['username'];
		$sqlfinder = "SELECT restaurant.* FROM restaurant WHERE restaurant.Restaurantid in(Select account.Restaurantid from account where Username='$user')";
		$temp=$mysqli -> query($sqlfinder);
		$row = mysqli_fetch_array($temp);
		$restoid=$row["Restaurantid"];
		if(count($errors)==0) {
			
			$sqlupdate = "UPDATE restaurant set HeureOuverture = '".$openingtime."', HeureFermeture =  '".$closingtime."' where (Restaurantid= '$restoid')";
			$mysqli -> query($sqlupdate);
			header('location: restaurantpage.php');
		}
	}
	if(isset($_POST['addnewphone'])){
		$phonenum = $mysqli -> real_escape_string($_POST['phonenum']);
		if(empty($phonenum)){
			array_push($errors, "Ne peut pas etre vide");
		}
		$user=$_SESSION['username'];
		$sqlfinder = "SELECT restaurant.* FROM restaurant WHERE restaurant.Restaurantid in(Select account.Restaurantid from account where Username='$user')";
		$temp=$mysqli -> query($sqlfinder);
		$row = mysqli_fetch_array($temp);
		$restoid=$row["Restaurantid"];
		if(count($errors)==0) {
			
			$sqlupdate = "UPDATE restaurant set NumTel = $phonenum where (Restaurantid= '$restoid')";
			$mysqli -> query($sqlupdate);
			header('location: restaurantpage.php');
		}
	}
	if(isset($_POST['addiflivre'])){
		$radiovalue = $mysqli -> real_escape_string($_POST['livre']);
		$user=$_SESSION['username'];
		$sqlfinder = "SELECT restaurant.* FROM restaurant WHERE restaurant.Restaurantid in(Select account.Restaurantid from account where Username='$user')";
		$temp=$mysqli -> query($sqlfinder);
		$row = mysqli_fetch_array($temp);
		$restoid=$row["Restaurantid"];
		if(count($errors)==0) {
			
			$sqlupdate = "UPDATE restaurant set Livreur = $radiovalue where (Restaurantid= '$restoid')";
			$mysqli -> query($sqlupdate);
			header('location: restaurantpage.php');
		}
	}
	
	if(isset($_POST['entertype'])){
		$selectvalue = $mysqli -> real_escape_string($_POST['formtypeanswer']);
		$user=$_SESSION['username'];
		$sqlfinder = "SELECT restaurant.* FROM restaurant WHERE restaurant.Restaurantid in(Select account.Restaurantid from account where Username='$user')";
		$temp=$mysqli -> query($sqlfinder);
		$row = mysqli_fetch_array($temp);
		$restoid=$row["Restaurantid"];
		if(count($errors)==0  && $selectvalue !=0) {
			
			$sqlupdate = "UPDATE restaurant set Typeid = $selectvalue where (Restaurantid= '$restoid')";
			$mysqli -> query($sqlupdate);
			header('location: restaurantpage.php');
		}
		if(count($errors)==0  && $selectvalue ==0) {
			
			$sqlupdate = "UPDATE restaurant set Typeid = NULL where (Restaurantid= '$restoid')";
			$mysqli -> query($sqlupdate);
			header('location: restaurantpage.php');
		}
	}
	
	
	if(isset($_POST['addnewtype'])){
		$newtype = $mysqli -> real_escape_string($_POST['newtype']);
		if(empty($newtype)){
			array_push($errors2, "Doit avoir un nom");
		}
		$user=$_SESSION['username'];
		$sqlfinder = "SELECT restaurant.* FROM restaurant WHERE restaurant.Restaurantid in(Select account.Restaurantid from account where Username='$user')";
		$temp=$mysqli -> query($sqlfinder);
		$row = mysqli_fetch_array($temp);
		$restoid=$row["Restaurantid"];
		if(count($errors2)==0) {
			
			$sqlupdate = "INSERT INTO typedecuisine (Nomdutype) VALUES ('$newtype')";
			$sqlupdate2 = "UPDATE restaurant set Typeid = $selectvalue where (Restaurantid= '$restoid')";
			$mysqli -> query($sqlupdate);
			$mysqli -> query($sqlupdate2);
			header('location: restaurantpage.php');
		}
	}
	
	if(isset($_POST['commandeaddr'])){
		
		$streetname = $mysqli -> real_escape_string($_POST['streetname']);
		$cityname = $mysqli -> real_escape_string($_POST['cityname']);
		$numrue = $mysqli -> real_escape_string($_POST['numrue']);
		$numtel = $mysqli -> real_escape_string($_POST['phonenum']);
		$numtel = str_replace(' ', '', $numtel);

		$comid=$_POST['commandeid'];
		$restoid=$_POST['restoid'];
		if(empty($streetname)){
			$streetname="0";
		}
		if(empty($cityname)){
			$cityname="0";
		}
		if(empty($numrue)){
			$numrue="0";
		}
		if(empty($numtel)){
			$numtel="0";
		}
		
		$sqlupdate = "INSERT into commandevalide (Commandeid, Nomrue,Nomville,Numville,NumTel,Restoid,completed) VALUES ('$comid', '$streetname','$cityname','$numrue','$numtel', '$restoid','0') ";
		$mysqli -> query($sqlupdate);
		
		
		
		
		
	}
	if(isset($_POST['commandephone'])){
		$numtel = $mysqli -> real_escape_string($_POST['phonenum']);
		$numtel = str_replace(' ', '', $numtel);
		$comid=$_POST['commandeid'];
		$restoid=$_POST['restoid'];		
		$sqlupdate = "INSERT into commandevalide (Commandeid, Nomrue,Nomville,Numville,NumTel,Restoid,completed) VALUES ('$comid', '0','0','0','$numtel', '$restoid','0') ";
		$mysqli -> query($sqlupdate);
	}

?>












