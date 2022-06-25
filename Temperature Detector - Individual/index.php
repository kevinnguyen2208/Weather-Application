

<meta http-equiv="refresh" content="5" url="index.php" >


<h1 style ="text-align:center;">TEMPERATURE </h1>

<?php
$outputStatusUpdate =$_GET["outputStatus"];

$servername="localhost";
$username="tempUser";
$password="123456";
$db="tempApp";

$conn = new mysqli($servername,$username,$password,$db);
if($conn -> connect_error){
	print"CONNECTION FAILED";
die("Connection Failed ".$conn->connect_error);

}

$sql="SELECT MIN(temp) FROM tempApp.temp";
$result=$conn->query($sql);
$row=$result->fetch_assoc();
print "<p style='text-align:center; font-size:20px;'>Minimum recorded temperature: " .$row["MIN(temp)"]."<font><sup>°C</sup></font></p>";

$sql="SELECT MAX(temp) FROM tempApp.temp";
$result=$conn->query($sql);
$row=$result->fetch_assoc();
print "<p style='text-align:center; font-size:20px;'>Maximum recorded temperature: " .$row["MAX(temp)"]."<font><sup>°C</sup></font></p>";

$sql="SELECT ROUND(AVG(temp), 1) AS Mean FROM tempApp.temp";
$result=$conn->query($sql);
$row=$result->fetch_assoc();
print "<p style='text-align:center; font-size:20px;'>Mean recorded temperature: " .$row["Mean"]."<font><sup>°C</sup></font></p>";

$sql="SET @rowindex:=1";
$result=$conn->query($sql);
$sql="SELECT ROUND(AVG(t.temp),1) AS Median FROM (SELECT @rowindex := @rowindex + 1 AS rowindex, temp.temp AS temp FROM temp ORDER BY temp.temp) AS t WHERE t.rowindex IN (FLOOR(@rowindex/2),CEIL(@rowindex/2))";
$result=$conn->query($sql);
$row=$result->fetch_assoc();
print "<p style='text-align:center; font-size:20px;'>Median recorded temperature: " .$row["Median"]."<font><sup>°C</sup></font></p>";

$sql="SELECT * FROM tempApp.temp ORDER BY update_time DESC limit 1";
$result=$conn->query($sql);

if($result->num_rows >0 ){
while($row=$result->fetch_assoc()){
print "<p style='text-align:center; font-size:200px; margin-top:10px;margin-bottom:10px;'>".$row["temp"]."<font><sup>°C</sup></font></p>";
print "<p style='text-align:center; font-size:30px; margin-top:10px;margin-bottom:10px;'>".$row["updatetime"]."</p>";

	if($row["temp"] > 20){
		print "<div style='text-align:center; color:red;'> ";
		print "<p style='text-align:center; font-size:20px;'>TOO HOT</p>";
		print "<form action='' method='POST'>";
		print "<input type='submit' name='UP' value='UP'></input>";
		print "<input type='submit' name='DOWN' value='DOWN'></input>";
		print "</form>";
		print "</div>";
		if(isset($_POST['UP'])){
			$sql="INSERT INTO buzzlevel (level) VALUES ('UP');";
			$result=$conn->query($sql);
		}
		else if(isset($_POST['DOWN'])){
			$sql="INSERT INTO buzzlevel (level) VALUES ('DOWN');";
			$result=$conn->query($sql);
		}
		
	}
	else if($row["temp"] < 15){
		print "<div style='text-align:center; color:blue;'> ";
		print "<p style='text-align:center; font-size:20px;'>TOO COLD</p>";
		print "<form action='' method='POST'>";
		print "<input type='submit' name='UP' value='UP'></input>";
		print "<input type='submit' name='DOWN' value='DOWN'></input>";
		print "</form>";
		print "</div>";
		if(isset($_POST['UP'])){
			$sql="INSERT INTO buzzlevel (level) VALUES ('UP');";
			$result=$conn->query($sql);
		}
		else if(isset($_POST['DOWN'])){
			$sql="INSERT INTO buzzlevel (level) VALUES ('DOWN');";
			$result=$conn->query($sql);
		}
		
	}
	else {
		print "<p style='text-align:center; font-size:20px; color:green;'>NORMAL TEMPERATURE</p>";
	}
}
}
else{
echo "Temp table is EMPTY";

}


$sql="select *from outputStatus order by update_time desc limit 1";
$result=$conn->query($sql);


print"<br> <div style='border: 1px solid black;width:300px;margin-left:auto;margin-right:auto;padding:10px;text-align:center; '> ";


if($result->num_rows>0){
while($row=$result->fetch_assoc()){
	$outputStatus=$row["status"];
	$output_timestamp=$row["update_time"];
	 	if($outputStatus !=$outputStatusUpdate){ 
		$sqlOutputStatus="insert into outputStatus(status) values ('$outputStatusUpdate')";
		if($conn->query($sqlOutputStatus)==FALSE){
		echo "GET  ERROR";
}
}
}
}
else
{
echo "OUTPUT STATUS LOG EMPTY<br>";
 
}


if($outputStatus=="ON"){
print "Output is on since:".$output_timestamp."ON/OFF is not usable when temperature is not NORMAL TEMPERATURE<br>";
print "<a href='index.php?outputStatus=OFF'>Turn Output OFF</a>";
}
else if($outputStatus=="OFF"){
print "Output is off since:".$output_timestamp."ON/OFF is not usable when temperature is not NORMAL TEMPERATURE<br>";
print "<a href='index.php?outputStatus=ON'>Turn Output ON</a>";
}
else  
{
print "HIT RESET. ON/OFF is not usable when temperature is not NORMAL TEMPERATURE <br>";
	print "<a href='index.php?outputStatus=OFF'> RESET </a>";

}
print"</div>";
$conn->close();

?>


