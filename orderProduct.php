<?php
session_start();
//get customer name from session
if(isset($_SESSION['name'])){
   $cname=$_SESSION['name'];
}else echo "session not run!";


//get datas from previous page
$pname=$_GET["pname"];
$price=$_GET["price"];
$status=$_GET["status"];


//corner case 1:product not available!
if($status!='available'){
	echo "Sorry,the product is no longer available now!<br>";
	echo"<a href='javascript:history.back(1);'>Back to previous page</a>";
	exit();
}

//set up database connection
$mysql_server_name="127.0.0.1";
$mysql_username="root";
$mysql_password="123456";
$mysql_database="p1";
$connection=mysqli_connect($mysql_server_name,$mysql_username,$mysql_password,$mysql_database) or die('Could not connect: ' . mysql_error());;

//corner case 2:check whether the customer exists
$queryCustomer="select * from customer where cname='".$cname."'";
$customerResult=mysqli_query($connection,$queryCustomer)or die("Couldn't execute queryCustomer: ". mysqli_error());
if($customerResult->num_rows==0){
	exit("Please register before order!");
}

//check whether there is pending order of the product for the customer
$queryPending="select * from purchase where cname='".$cname."' and pname='".$pname."'and status='pending'";
$pendingResult=mysqli_query($connection,$queryPending) or die ("Couldn't execute queryPending: " . mysqli_error());

//no such pending order->insert a new order
if($pendingResult->num_rows==0){
	$queryInsert="insert into purchase values('".$cname."','".$pname."',now(),'1','".$price."','pending')";
	mysqli_query($connection,$queryInsert) or die ("Couldn't execute queryInsert: " . mysqli_error());
	echo "<h1>Order Successfully!Thanks for your purchase!</h1>";
}else{//has such pending order->update the old order
	$queryUpdate="update purchase set putime=now(),quantity=quantity+1,puprice=puprice+'".$price."' where cname='".$cname."' and pname='".$pname."'and status='pending'";
	mysqli_query($connection,$queryUpdate) or die ("Couldn't execute queryUpdate: " . mysqli_error());
	echo "<h1>Reorder successfully!Thanks for your order!</h1>";
}
?>