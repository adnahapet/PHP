<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<!--
TITLE: Update Records
AUTHOR: Armen Nahapetian
PURPOSE: View an entire table using abstracted function call
ORIGINALLY CREATED ON: 08/03/2016
LAST MODIFIED ON: 08/03/2016
LAST MODIFIED BY: Armen Nahapetian
MODIFICATION HISTORY: 
08/03/2016: Create date
-->

<head>
	<title>Update Records</title>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<link href="database.css" type = "text/css" rel="stylesheet" />
</head>


<body>

<div>

<?php 
	
	include "sharedLibrary.php";
	session_start();
	
	//scrape data from form
	$newCopies = filter_input(INPUT_POST, "copies");
	$cardID = filter_input(INPUT_POST, "cardID");
	$deckID = $_SESSION["deckToEdit"];
	
	
	//calls sharedLib connect function
	$conn = dbconnect();
	
	
	//update query with data from form
	$updateQuery = "UPDATE Decklist  
				SET copies = $newCopies
				WHERE card_ID = $cardID";
				
	
	$stmt = $conn->prepare($updateQuery);
	
	$stmt->execute();

	echo "Database Updated ";
	
	//calls sharedLib function to print table with $filter activated
	showTable("Decklist",$deckID);
	
	
	
	
?>


</div>	

</body>

</html>