<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<!--
TITLE: Magic Database Menu
AUTHOR: Armen Nahapetian
PURPOSE: Navigation Menu for Magic Database 
ORIGINALLY CREATED ON: 08/03/2016
LAST MODIFIED ON: 08/03/2016
LAST MODIFIED BY: Armen Nahapetian
MODIFICATION HISTORY: 
08/03/2016: Create Date
08/06/2016: Modified into PHP and added new menu options
-->

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<title>Magic Database</title>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<link href="database.css" type = "text/css" rel="stylesheet" />
</head>


<body id="home">

<div>

<?php
	
	include "sharedLibrary.php";
	session_start();
	
	//gets list of deck names for view decklist menu
	$conn = dbconnect();
	$stmt = $conn->prepare("SELECT deck_ID, name FROM Deck");
	$stmt->execute();
	

	//echo of main menu options
	//uses fetch from query above to get ids and names for decks
	//then inserts into a select option below
	echo "
	
	<h1 id=\"home\">Magic Database Menu</h1> 
	<br><br>
	<form action=\"addRecord.php\" method = \"post\" id=\"main\">
	<legend>Add a Record</legend>
	<fieldset>
	    <input type=\"radio\" name=\"table\" value=\"Deck\"> Deck <br>
		<input type=\"radio\" name=\"table\" value=\"Card\"> Card <br>
		<input type=\"radio\" name=\"table\" value=\"Decklist\"> Decklist <br>
	</fieldset>
	<fieldset>
	    <button type = \"submit\" name=\"submit\">
	    submit 
	    </button>
	</fieldset>
	</form>
	
	<br> <br>
	
	<form action=\"viewRecords.php\" method = \"post\" id=\"main\">
	<legend>View a Table</legend>
	<fieldset>
	    <input type=\"radio\" name=\"table\" value=\"Deck\"> Deck <br>
		<input type=\"radio\" name=\"table\" value=\"Card\"> Card <br>
		<input type=\"radio\" name=\"table\" value=\"Decklist\"> Decklist <br>
	</fieldset>
	<fieldset>
	    <button type = \"submit\" name=\"submit\">
	    submit 
	    </button>
	</fieldset>
	
	</form>
	
	<br><br>
	
	
	<form action=\"viewFilteredDecklist.php\" method = \"post\" id=\"main\">
	<input type =\"hidden\" name = \"table\" value = \"Decklist\" />
	<fieldset>
	<legend> View/Edit a Decklist </legend> <select name=\"deck_ID\"> ";
		while($result = $stmt->fetch(PDO::FETCH_ASSOC)):
		$id = $result['deck_ID'];
		$name = $result['name']; 
		echo '<option value="'.$id.'" >'.$name.'</option>';
		endwhile;
						
		echo " </select> </fieldset>
	
		
		<fieldset>
		<button type = \"submit\" name=\"submit\">
		submit 
		</button>
		</fieldset>
	</form>";
	
	?>
	

</div>

</body>

</html>