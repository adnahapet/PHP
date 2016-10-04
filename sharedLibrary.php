<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<!--
TITLE: Shared Library
AUTHOR: Armen Nahapetian
PURPOSE: Make database connections and function calls easier
ORIGINALLY CREATED ON: 08/03/2016
LAST MODIFIED ON: 08/06/2016
LAST MODIFIED BY: Armen Nahapetian
MODIFICATION HISTORY: 
08/03/2016: Create date
08/06/2016: Added multiple functions to filter decklist views and edit decklists
-->

<head>
	<title>Shared Library</title>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
</head>


<body>

<div>

<?php 
	//variables for database connection
	$servername = "localhost";
	$username = "anahapet";
	$password = "Einkrieg67";
	$dbname = "anahapet_db";
	$conn = "";
	
	function dbconnect(){
		
		global $servername, $username, $password, $dbname;
		
		try{
		$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
		// set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		return $conn;
		
		}
		catch(PDOException $e)
			{
				//echo error message on failure
				echo $stmt . "<br>" . $e->getMessage();
			}
	}
	
	//function to print any of the 3 forms needed to add records to the database
	function echoForm($tableName){
		
		echo"<h1 class=\"addRecord\"> Add a ";
		
		if($tableName=="Card"){
		echo " Card </h1>
		<form action=\"insertRecord.php\" method = \"post\" id=\"cardForm\">
		<input type =\"hidden\" name = \"table\" value = \"Card\" />
		<fieldset>
		  <legend>Name</legend>
		  <input type =\"text\"
				name= \"name\"
				value= \"Counterspell\" />
		</fieldset>
		
		<fieldset>
		<legend>Casting Cost</legend>
		  <input type =\"text\"
				name= \"castingCost\"
				value= \"UU\" />
		</fieldset>
		
		<fieldset>
		<legend>Color</legend>
		<select name=\"color\" value=\"blue\">
			<option value=\"red\">Red</option>
			<option value=\"green\">Green</option>
			<option value=\"blue\">Blue</option>
			<option value=\"black\">Black</option>
			<option value=\"white\">White</option>
		</select>
		</fieldset>
		
		<fieldset>
		<legend>Type</legend>
		<select name=\"type\" value=\"instant\">
			<option value=\"artifact\">Artifact</option>
			<option value=\"land\">Land</option>
			<option value=\"summon\">Summon</option>
			<option value=\"sorcery\">Sorcery</option>
			<option value=\"instant\">Instant</option>
		</select>
		</fieldset>
		
		<fieldset>
	        <button type = \"submit\">
	          submit record
	        </button>
	    </fieldset>
	        
	    </form>
		";
		}
		elseif($tableName=="Decklist"){
			
			//fetches deck_IDs and the associated names
			$conn = dbconnect();
			$stmt = $conn->prepare("SELECT deck_ID, name FROM Deck");
			$stmt->execute();
			
			
			//inserts data from query into select menu displaying deck name but holding deck_ID as value
			//same loop is used to insert card_ID and card name into a drop-down list
			echo "
			Decklist Entry </h1>
			<form action=\"insertRecord.php\" method = \"post\" id=\"cardForm\">
					<input type =\"hidden\" name = \"table\" value = \"Decklist\" />
					
					<fieldset>
						<legend> Deck Name </legend> <select name=\"deck_ID\"> ";
						
						while($result = $stmt->fetch(PDO::FETCH_ASSOC) ):
							$id = $result['deck_ID'];
							$name = $result['name']; 
							echo '<option value="'.$id.'">'.$name.'</option>';
						endwhile;
						
					echo " </select> </fieldset>
					
					<fieldset>
						<legend> Card Name </legend> <select name=\"card_ID\"> ";
						$stmt = $conn->prepare("SELECT card_ID, name FROM Card");
			
						$stmt->execute();
						
						while($result = $stmt->fetch(PDO::FETCH_ASSOC) ):
							$id = $result['card_ID'];
							$name = $result['name']; 
							echo '<option value="'.$id.'">'.$name.'</option>';
						endwhile;
					
					echo " </select></fieldset>
					
					<fieldset>
						<legend> Number </legend>
						<select name=\"copies\" value=\"1\">
							<option value=\"1\">1</option>
							<option value=\"2\">2</option>
							<option value=\"3\">3</option>
							<option value=\"4\">4</option>
						</select>
					</fieldset>
					
					<fieldset>
						<button type = \"submit\">
						submit record
						</button>
					</fieldset>
					";
		}
		else{
			echo "
		New Deck </h1>
		<form action=\"insertRecord.php\" method = \"post\" id=\"cardForm\">
		<input type =\"hidden\" name = \"table\" value = \"Deck\" />
		<fieldset>
		  <legend>Deck Name</legend>
		  <input type =\"text\"
				name= \"name\"
				value= \"Elves and Elephants\" />
		</fieldset>
		
		<fieldset>
		<legend>Creator Name</legend>
		  <input type =\"text\"
				name= \"creator\"
				value= \"Rick Swan\" />
		</fieldset>	

		<fieldset>
	        <button type = \"submit\">
	          submit record
	        </button>
	    </fieldset>
	        
	    </form>
		";
		}
		
		echo "<br><br> <a href=\"finalMain.php\" class=\"button\">Home</a>";
	}
	
	//function to make query that will insert new record into database
	function completeInsertion($tableName,$fields,$values){
		
			
			global	$conn;
			
			//uses two sub strings to insert field names and values into a complete query
			$innerQuery = "(";
			foreach ($fields as $Field){
			$innerQuery.= "$Field,";
			} 
			$innerQuery = substr($innerQuery, 0, strlen($innerQuery) - 1);
			
			//finishes query
			$queryToMake = "INSERT INTO $tableName $innerQuery) VALUES (";
			foreach ($fields as $Fields){
			$queryToMake .= ":$Fields,";
			} 
			$queryToMake = substr($queryToMake, 0, strlen($queryToMake) - 1);
			$queryToMake .= ")";
		
			$stmt = $conn->prepare("$queryToMake");
			
			//binds parameters with the values scraped from the form		
			for($i=0; $i < sizeof($fields); $i++){
				$stmt->bindParam(":$fields[$i]", $values[$i]);
			}
					
			$stmt->execute();
			
			//supplementary query to update missing deck and card names from the decklist linking table
			//given more time I would change this. data is available in deck and card tables respectively and is redundant
			//currently it makes the showTable function easier to utilize for all tables and still be readable
			if($tableName=="Decklist"){
				$deckNameQuery = "SELECT name FROM Deck WHERE deck_ID = $values[1]";
				$stmt  = $conn->prepare($deckNameQuery);
				$stmt->execute();
				$result = $stmt->fetch();
				$deckName = $result["name"];
				$deckNameQuery = "UPDATE Decklist  
				SET deck_name = \"$deckName\"
				WHERE deck_ID = $values[1]";
				$stmt  = $conn->prepare($deckNameQuery);
				$stmt->execute();
				
				
				$cardNameQuery = "SELECT name FROM Card WHERE card_ID = $values[0]";
				$stmt  = $conn->prepare($cardNameQuery);
				$stmt->execute();
				$result = $stmt->fetch();
				$cardName = $result["name"];
				$cardNameQuery = "UPDATE Decklist 
				SET card_name = \"$cardName\"
				WHERE card_ID = $values[0]";
				$stmt  = $conn->prepare($cardNameQuery);
				$stmt->execute();
				
			}
			echo "<h1>database updated</h1>
			
			<br>
			
			<a href=\"finalMain.php\" class=\"button\">Home</a>
			";
		
	}
	
	//function to show an entire table
	function showTable($tableName,$filter){
		
		//gets all field names from database
		$conn = dbconnect();
		$stmt = $conn->prepare("SHOW COLUMNS FROM $tableName");
		$stmt->execute();
		
		//insert field names into array
		while($result = $stmt->fetch(PDO::FETCH_ASSOC) ):
				$fields[] = $result['Field'];
		endwhile;
	
		//if filter is null indicates that the user is not requesting a specific decklist
		//inserts all field names into table header tags
		if($filter==NULL){
			echo " <table id=\"magicTable\">
				<tr>";
				
			for($i=0; $i < sizeof($fields); $i++){
				echo "<th> $fields[$i] </th>";
			} 
		
			echo "</tr>";
			
			//sets sql query to be execute below
			$sql = "SELECT * FROM $tableName";
		}
		
		//filter indicating specific decklist
		//instead of inserting all table fields 
		//will ignore the auto_increment IDs
		if($filter != NULL){
			//session used for editing decklist
			//carries deck_ID to editDecklist.php
			$_SESSION["deckToEdit"] = $filter;
			$stmt = $conn->prepare("SELECT name FROM Deck WHERE deck_ID=$filter");
			$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			$name = $result['name'];
			echo "<h1> Deck: $name </h1>
			<br> <br> <table id=\"magicTable\"> 
			<tr><th>Card Name</th><th>Number of Copies</th>";
			
			//sets alternate sql query to be executed below
			$sql = "SELECT card_name, copies FROM $tableName WHERE deck_ID = $filter";
			//limits fields array to 2 to keep loop below in bounds
			array_splice($fields,2);
		}
	
		//for each to print all rows from query into table rows
		foreach($conn->query($sql) as $row){
			echo "<tr>";
			for($i = 0; $i <sizeof($fields); $i++){
				echo "<td>{$row[$i]}</td>";
			}
			echo "</tr>";
		}
		
		//end table tag
		echo"
		</table>
		<br><br>";
		
		//if decklist is being viewed
		//calls card counter function to display information about specific decklist
		if($filter!=NULL){
			cardCounter($filter);
			echo "<a href=\"editDecklist.php\" class=\"button\">Edit</a>";
		}
		echo"
		
		<a href=\"finalMain.php\" class=\"button\">Home</a>";
		
	}
	
	//function to count specific types of cards
	//totals decklist
	//displays information below table
	//could probably clean up a little around the query with a join or combining query for card_ID and copies
	//was easier to keep track of on firstpass this way
	function cardCounter($deckID){
		
		//variables for connection and for totaling
		$conn = dbconnect();
		$total = 0;
		$land = 0;
		$summon = 0;
		$instant = 0;
		$sorcery = 0;
		$artifact = 0;
		
		//selects the number of cards for every card in the decklist
		$numberQuery = "SELECT copies FROM Decklist WHERE deck_ID = $deckID";
		$stmt  = $conn->prepare($numberQuery);
		$stmt->execute();
		
		//totals the number of all cards in the deck
		while($result = $stmt->fetch(PDO::FETCH_ASSOC) ):
			$number = $result['copies'];
			$total += $number;
		endwhile;
		
		
		//selects all specific cards from decklist 
		$cardQuery = "SELECT card_ID from Decklist WHERE deck_ID = $deckID";
		$stmt = $conn->prepare($cardQuery);
		$stmt->execute();
		
		//goes through each card in the decklist
		while($result = $stmt->fetch(PDO::FETCH_ASSOC) ):
		
			$cardID = $result['card_ID'];
			
			//fetches card type
			$typeQuery = "SELECT type from Card WHERE card_ID = $cardID";
			$stmt2 = $conn->prepare($typeQuery);
		    $stmt2->execute();
			$typeResult = $stmt2->fetch(PDO::FETCH_ASSOC);
			$type = $typeResult['type'];
			
			//fetches number of that card
			$numQuery = "SELECT copies from Decklist where card_ID = $cardID";
			$stmt3 = $conn->prepare($numQuery);
		    $stmt3->execute();
			$numResult = $stmt3->fetch(PDO::FETCH_ASSOC);
			$num = $numResult['copies'];
			
			//adds number of that card to its type total
			switch ($type) {
				case "land":
					$land += $num;
					break;
				case "summon":
					$summon += $num;
					break;
				case "instant":
					$instant += $num;
					break;
				case "artifact":
					$artifact += $num;
					break;
				case "sorcery":
					$sorcery += $num;
					break;
				default:
					//should never happen
					echo "error";
			}
		
		endwhile;
		
		//calculates % of total deck that is traditional mana sources
		$manaRatio = $land / $total * 100;
		
		//prints totals
		echo "<table class=\"totalTable\">
		
		<tr><td class=\"cardCount\">All</td>   <td class=\"cardCount\"> $total</td></tr>
		<tr><td class=\"cardCount\">Lands</td> <td class=\"cardCount\"> $land </td></tr>
		<tr><td class=\"cardCount\">Instants</td> <td class=\"cardCount\"> $instant </td></tr>
		<tr><td class=\"cardCount\">Sorceries</td><td class=\"cardCount\"> $sorcery</td> </tr>
		<tr><td class=\"cardCount\">Summons</td><td class=\"cardCount\"> $summon </td></tr>
		<tr><td class=\"cardCount\">Artifacts</td><td class=\"cardCount\"> $artifact</td></tr>
		<tr><td class=\"cardCount\">Mana</td><td class=\"cardCount\"> $manaRatio % Mana</td></tr>
		</table> <br><br>";

		
		
	}

?>


</div>	

</body>

</html>