<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<!--
TITLE: Insert Record
AUTHOR: Armen Nahapetian
PURPOSE: Insert Record 
ORIGINALLY CREATED ON: 08/03/2016
LAST MODIFIED ON: 08/03/2016
LAST MODIFIED BY: Armen Nahapetian
MODIFICATION HISTORY: 
08/03/2016: Create date
-->

<head>
	<title>Insert Record</title>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<link href="database.css" type = "text/css" rel="stylesheet" />
</head>


<body>

<div>

<?php 
	include "sharedLibrary.php";
	
	global $dbname;
	$tableName = filter_input(INPUT_POST, "table");
		
	//calls sharedLib connect function
	$conn = dbconnect();
	
	//gets all fields from tablename specified in main menu
	$stmt = $conn->prepare("SHOW COLUMNS FROM $tableName");
	$stmt->execute();
	
	//if field is found in the form that was submitted from main menu
	//field is added to field array (to avoid auto-increment fields)
	//form is then scraped for those values and added to value array
	while($result = $stmt->fetch(PDO::FETCH_ASSOC) ):
		if(filter_has_var(INPUT_POST,$result['Field'])){
			$fields[] = $result['Field'];
		}
		$field = $result['Field'];
		if(filter_has_var(INPUT_POST, $field)){
			$value = filter_input(INPUT_POST, $field);
			$values[] = $value;
			}
	endwhile;
		
	//submit all data to sharedLib function
	completeInsertion($tableName,$fields,$values);
			
			
	
?>


</div>	

</body>

</html>