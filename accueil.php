<?php

 include('serveur.php');

 ?>
<html>
<header>
<link rel="stylesheet" type="text/css" href="styleaccueil.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>

</script>
</header>
<body>

	<div class="top">
		<form class="signin" action="accueil.php" method="POST">
		<?php include('errors.php'); ?>
			<div class="input-group"> 
				<label>Nom:</label>
				<input required type="text" id="user" style="width: 90%;" name="user"/>
				<label>Mot de passe:</label>
				<input required type="password" style="width: 90%;" id="pass" name="pass"/>
				<input type="submit" id="sub" name="login"/>
				<br>
				Pas encore membre? <a href="newmember.php"><br>Créer un compte</a> 
			</div>
		</form>
		<img class="gifimg" src="symbol.gif"/>
		</div>
	<hr>
	<br>
	<div class="backgroundimg">
	<br>
	<br>
	<?php 
	
	$mysqli = new mysqli("localhost","root","","livraison");
	if ($mysqli -> connect_errno) {
		echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
		exit();
	} 
	?>
	<div class="search">
	<form id="formtype" name="formtype" method="post" action="accueil.php">  
	
              
            <select class="searcharea" Name='formtypesearch'>  
            <option  value="0">Tous</option>  
            <?php  
				$sql = "SELECT * FROM typedecuisine ORDER BY Nomdutype";
				$temp=$mysqli -> query($sql);
            while($row=mysqli_fetch_array($temp)){  
                ?>  
                    <option  value="<?php echo $row['Typeid']; ?>"> <?php echo $row['Nomdutype'];?> 
                    </option>  
                <?php 
                }  
                ?>  
            </select>  
            <input type="submit" name="entertypesearch" class="serachbtn" value="Valider" />  
        </form> 
		
	</div>
		
			
	<table align='center'>
	<?php
		if(isset($_POST['entertypesearch'])){
			$selectvalue = $mysqli -> real_escape_string($_POST['formtypesearch']);
			if($selectvalue>0){
				$sqlfoodtypes = "SELECT * FROM typedecuisine where Typeid='$selectvalue'";
				$tempfoodtype=$mysqli -> query($sqlfoodtypes);
				while($rowfoodtype = mysqli_fetch_array($tempfoodtype)){
					echo "<tr><td  class='foodtype' colspan='3'><b><u>".$rowfoodtype['Nomdutype']."</u></b></td></tr>";
					$temptypeid=$rowfoodtype['Typeid'];
					$sqlrestaurants = "SELECT * FROM restaurant WHERE Typeid='$temptypeid'";
					$tempresto=$mysqli -> query($sqlrestaurants);
					while($rowresto = mysqli_fetch_array($tempresto)){
						$idresto=$rowresto['Restaurantid'];
						$sqlimageresto = "SELECT * FROM imagerestaurant WHERE id='$idresto'";
						$tempimgresto=$mysqli -> query($sqlimageresto);
						$rowimageresto = mysqli_fetch_array($tempimgresto);
						echo "<tr>";
						echo "<td class='img' style='width:25% ' >";
						echo "<img class= 'imageresto' src='images/".$rowimageresto['image']."' >";
						echo "</td>";
						echo "<td class='info' style='width:75% '>";
						?>
						<a href='visitorrestaurant.php?key=<?php echo $idresto;?>'><?php echo "<p class='name'>".$rowresto['RestaurantName']."</p>";?></a>
						<?php
						echo "<p>".$rowresto['NumVille']." ".$rowresto['Nomrue']." ".$rowresto['Nomville'].", Tahiti</p>";
						echo "<p>Numero de telephone: ".$rowresto['NumTel']."</p>";
						echo "</td>";
						echo "</tr>";
					}
				}
			}if ($selectvalue==0){
				$sqlfoodtypes = "SELECT * FROM typedecuisine ORDER BY Nomdutype";
				$tempfoodtype=$mysqli -> query($sqlfoodtypes);
				while($rowfoodtype = mysqli_fetch_array($tempfoodtype)){
					echo "<tr><td  class='foodtype' colspan='3'><b><u>".$rowfoodtype['Nomdutype']."</u></b></td></tr>";
					$temptypeid=$rowfoodtype['Typeid'];
					$sqlrestaurants = "SELECT * FROM restaurant WHERE Typeid='$temptypeid'";
					$tempresto=$mysqli -> query($sqlrestaurants);
					while($rowresto = mysqli_fetch_array($tempresto)){
						$idresto=$rowresto['Restaurantid'];
						$sqlimageresto = "SELECT * FROM imagerestaurant WHERE id='$idresto'";
						$tempimgresto=$mysqli -> query($sqlimageresto);
						$rowimageresto = mysqli_fetch_array($tempimgresto);
						echo "<tr>";
						echo "<td class='img' style='width:25% ' >";
						echo "<img class= 'imageresto' src='images/".$rowimageresto['image']."' >";
						echo "</td>";
						echo "<td class='info' style='width:75% '>";
						?>
						<a href='visitorrestaurant.php?key=<?php echo $idresto;?>'><?php echo "<p class='name'>".$rowresto['RestaurantName']."</p>";?></a>
						<?php
						echo "<p>".$rowresto['NumVille']." ".$rowresto['Nomrue']." ".$rowresto['Nomville'].", Tahiti</p>";
						echo "<p>Numero de telephone: ".$rowresto['NumTel']."</p>";
						echo "</td>";
						echo "</tr>";
						
					}
				}
			}
		}else{
			$sqlfoodtypes = "SELECT * FROM typedecuisine ORDER BY Nomdutype";
			$tempfoodtype=$mysqli -> query($sqlfoodtypes);
			while($rowfoodtype = mysqli_fetch_array($tempfoodtype)){
				echo "<tr><td  class='foodtype' colspan='3'><b><u>".$rowfoodtype['Nomdutype']."</u></b></td></tr>";
				$temptypeid=$rowfoodtype['Typeid'];
				$sqlrestaurants = "SELECT * FROM restaurant WHERE Typeid='$temptypeid'";
				$tempresto=$mysqli -> query($sqlrestaurants);
				while($rowresto = mysqli_fetch_array($tempresto)){
					$idresto=$rowresto['Restaurantid'];
					$sqlimageresto = "SELECT * FROM imagerestaurant WHERE id='$idresto'";
					$tempimgresto=$mysqli -> query($sqlimageresto);
					$rowimageresto = mysqli_fetch_array($tempimgresto);
					echo "<tr>";
					echo "<td class='img' style='width:25% ' >";
					echo "<img class= 'imageresto' src='images/".$rowimageresto['image']."' >";
					echo "</td>";
					echo "<td class='info' style='width:75% '>";
					?>
					<a href='visitorrestaurant.php?key=<?php echo $idresto;?>'><?php echo "<p class='name'>".$rowresto['RestaurantName']."</p>";?></a>
					<?php
					echo "<p>".$rowresto['NumVille']." ".$rowresto['Nomrue']." ".$rowresto['Nomville'].", Tahiti</p>";
					echo "<p>Numero de telephone: ".$rowresto['NumTel']."</p>";
					echo "</td>";
					echo "</tr>";
					
				}
			}
		}
	?>
	</table>
	<br><br>
	<br><br>
	<br>
	</div>
	
</body>
</html>