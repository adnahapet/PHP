<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<!--
TITLE: View Records
AUTHOR: Armen Nahapetian
PURPOSE: View an entire table using abstracted function call
ORIGINALLY CREATED ON: 08/03/2016
LAST MODIFIED ON: 08/03/2016
LAST MODIFIED BY: Armen Nahapetian
MODIFICATION HISTORY: 
08/03/2016: Create date
-->

<head>
	<title>View Records</title>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<link href="database.css" type = "text/css" rel="stylesheet" />
</head>


<body>

<div>

<?php 
	
	include "sharedLibrary.php";
	
	//scrape table name from main menu
	$tableName = filter_input(INPUT_POST, "table");

	//calls table function with no filter for specific decklist
	showTable($tableName,NULL);
	
	
	
?>


</div>	

</body>

</html>