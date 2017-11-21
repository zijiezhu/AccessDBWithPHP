<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
                      "http://www.w3.org/TR/html401/loose.dtd">
<html>
<head>
  <meta http-equiv="Content-Type" 
      content="text/html; charset=iso-8859-1">
  <title>product</title>
</head>
<body >
<?php
//get data from previous page
$_SESSION['name']=$_GET["cname"];
$keyword=$_GET["keyword"];

//set up database connection
$mysql_server_name="127.0.0.1";
$mysql_username="root";
$mysql_password="123456";
$mysql_database="p1";
$connection=mysqli_connect($mysql_server_name,$mysql_username,$mysql_password,$mysql_database) or die('Could not connect: ' . mysql_error());;

//query products matching keyword
$queryProduct="select * from product where pdescription like '%{$keyword}%'";
$result=mysqli_query($connection,$queryProduct) or die ("Couldn't execute query: " . mysqli_error());

//corner case:no related product exists
if($result->num_rows==0){
	echo "No related products!<br>";
	echo"<a href='javascript:history.back(1);'>Back to main page</a>";
	exit();
}
//print table heading
echo "<table border = \"1\">\n";
echo "<tr>";
echo "<th>Product</th>";
echo "<th>Description</th>";
echo "<th>Price</th>";
echo "<th>Status</th>";
echo "<th></th>";
echo "</tr>";

//print query result in table
while($row=mysqli_fetch_array($result,MYSQLI_ASSOC)){
	    echo "\t<tr>\n";
		
	    foreach($row as $display){
	    	echo "\t\t<td>{$display}</td>\n";
		}
		//print order button for every product(pass data for next page to process the order) 
		echo "<td><input name=\"minus\"  type=\"button\" value=\"order\" onclick=\"location.href='orderProduct.php?pname=$row[pname]&price=$row[pprice]&status=$row[pstatus]'\"/></td>";
	    echo "\t</tr>\n";
}
echo "</table>\n";
?>
</body>
</html>
