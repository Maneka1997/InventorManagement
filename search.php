<?php
$servername='localhost';
$username='root';
$password='';
$dbname = "inventorymanagement";
$conn=mysqli_connect($servername,$username,$password,"$dbname");

  if(!$conn){
      die('Could not Connect MySql Server:' .mysqli_error());
    }

	mysqli_connect("localhost", "root", "") or die("Error connecting to database: ".mysqli_error());
	/*
		localhost - it's location of the mysql server, usually localhost
		root - your username
		third is your password
		
		if connection fails it will stop loading the page and display an error
	*/
	
	mysqli_select_db($conn,"inventorymanagement") or die(mysqli_error());
	/* tutorial_search is the name of database we've created */
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Search results</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<style>
        table,th,td{
            border:1px solid black;
        }

		h1{align:center;}

		div {
  border: 5px outset red;
  background-color: red;
  text-align: center;
}
    </style>   
</head>
<body>
<?php

if(isset($_GET["buttonSearch"])){
	$query = $_GET['query']; 
	// gets value sent over search form
	
	$min_length = 3;
	// you can set minimum length of the query if you want
	$addrOne="mySearch.html";
	if(strlen($query) >= $min_length){ // if query length is more or equal minimum length then
		
		$query = htmlspecialchars($query); 
		// changes characters used in html to their equivalents, for example: < to &gt;
		
		$query = mysqli_real_escape_string($conn,$query);
		// makes sure nobody uses SQL injection
		
		$raw_results = mysqli_query($conn,"SELECT * FROM inventory
			WHERE (`itemName` LIKE '%".$query."%') OR (`itemCatagory` LIKE '%".$query."%') OR 
            (`itemBrand` LIKE '%".$query."%') OR (`itemQuantity` LIKE '%".$query."%') OR (`itemPrice` LIKE '%".$query."%')") or die(mysql_error());
			
		// * means that it selects all fields, you can also write: `id`, `title`, `text`
		// articles is the name of our table
		
		// '%$query%' is what we're looking for, % means anything, for example if $query is Hello
		// it will match "hello", "Hello man", "gogohello", if you want exact match use `title`='$query'
		// or if you want to match just full word so "gogohello" is out use '% $query %' ...OR ... '$query %' ... OR ... '% $query'
		//$addrOne="mySearch.html";
		if(mysqli_num_rows($raw_results) > 0){ // if one or more rows are returned do following
			
            echo"<h1>Search Results </h1>";

			while($results = mysqli_fetch_array($raw_results)){
			// $results = mysql_fetch_array($raw_results) puts data from database into array, while it's valid it does the loop
			$msg="</td><td>";
				echo "<table><tr><th>Item Name</th><th>Item Catagory</th><th>Item Brand</th><th>Item Quantity</th><th>Item Price</th></tr><td>".$results['itemName'].$msg.$results['itemCatagory'].$msg.$results['itemBrand'].$msg.$results['itemQuantity'].$msg.$results['itemPrice']."</table>";
				
				echo "<a href='".$addrOne."'>dashboard</a>";
				// posts results gotten from database(title and text) you can also show id ($results['id'])
			}
			
		}
		else{ // if there is no matching rows do following
			echo "<div><h1 >No Results</h1></div>";
			echo "<br><a href='".$addrOne."'>Search Again</a>";
		}
		
	}
	else{ // if query length is less than minimum
		echo "<div><h1 >Sorry, minimum length is ".$min_length."</h1></div>";
		echo "<a href='".$addrOne."'>Search Again</a>";
	}
	 
}
?>
</body>
</html>