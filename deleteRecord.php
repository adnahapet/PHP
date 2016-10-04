<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<!--
TITLE: Delete Records
AUTHOR: Armen Nahapetian
PURPOSE: View an entire table using abstracted function call
ORIGINALLY CREATED ON: 08/06/2016
LAST MODIFIED ON: 08/06/2016
LAST MODIFIED BY: Armen Nahapetian
MODIFICATION HISTORY: 
08/06/2016: Create date
-->

<head>
	<title>Delete Records</title>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<link href="database.css" type = "text/css" rel="stylesheet" />
</head>


<body>

<div>

<?php 
	
	include "sharedLibrary.php";
	session_start();
	
	//scrape data from form
	$cardID = filter_input(INPUT_POST, "cardID");
	$deckID = $_SESSION["deckToEdit"];
	
	
	//calls sharedLib connect function
	$conn = dbconnect();
	
	//delete query for specified card
	$deleteQuery = "DELETE FROM Decklist  
				WHERE deck_ID = $deckID AND card_ID = $cardID";
				

	$stmt = $conn->prepare($deleteQuery);
	
	$stmt->execute();

	echo "Database Updated ";
	
	//calls sharedLib function to print table with $filter activated
	showTable("Decklist",$deckID);
	
	
	
?>


</div>	

</body>

</html>