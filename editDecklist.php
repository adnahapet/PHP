<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<!--
TITLE: Edit Decklist
AUTHOR: Armen Nahapetian
PURPOSE: Edit Decklist
ORIGINALLY CREATED ON: 08/03/2016
LAST MODIFIED ON: 08/03/2016
LAST MODIFIED BY: Armen Nahapetian
MODIFICATION HISTORY: 
08/03/2016: Create date
-->

<head>
	<title>Edit Decklist</title>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<link href="database.css" type = "text/css" rel="stylesheet" />
</head>


<body>

<div>

<?php 
	
	include "sharedLibrary.php";
	session_start();
	
	$conn = dbconnect();
		
	$filter = $_SESSION["deckToEdit"];
	
	//filter indicating specific decklist
	//instead of inserting all table fields 
	//will ignore the auto_increment IDs
	$stmt = $conn->prepare("SELECT name FROM Deck WHERE deck_ID = $filter");
	$stmt->execute();
	$result = $stmt->fetch(PDO::FETCH_ASSOC);
	$name = $result['name'];
	echo "<h1> Deck: $name </h1>";
			
	//sets sql query to be executed below
	$sql = "SELECT card_ID, card_name, copies FROM Decklist WHERE deck_ID = $filter";
	
	//inserts query results into mini forms
		foreach($conn->query($sql) as $row){
			echo "<table id=\"magicTable\">
			<tr class=\"cardToBeUpdated\">
			
			<td>
			{$row["card_name"]}
			</td>
			
			<td>
			<form action=\"updateRecords.php\" method = \"post\" id=\"cardForm\">
				<input type=\"hidden\" value=\"{$row["card_ID"]}\" name=\"cardID\">
				<select name=\"copies\" value=\"{$row["copies"]}\">
							<option value=\"1\">1</option>
							<option value=\"2\">2</option>
							<option value=\"3\">3</option>
							<option value=\"4\">4</option>
				</select>
				<button type = \"submit\">
					Update
			</button>
			</form>
			</td>
			
			<td>
			<form action=\"deleteRecord.php\" method = \"post\" id=\"cardForm\">
			<input type=\"hidden\" value=\"{$row["card_ID"]}\" name=\"cardID\">
			 <button type = \"submit\">
					Remove from Decklist
			</button>
			</form>
			</td>	
			
			</tr>
			</table>";
		}
		
		
		echo"
		</table>
		<br><br>
		
		
		<a href=\"finalMain.php\" class=\"button\">Home</a>";
		
		

	
	
	
?>


</div>	

</body>

</html>