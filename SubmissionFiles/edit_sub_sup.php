<html>
<body>
<a href="Employee.php">Back</a>
<form action="" method="post">
 <h1>please provide Supervisor SIN in order to edit his/her subordinates</h1>
   Supervisor: <input type="number" name="sup"><br>
    Subordinate: <input type="number" name="sub"><br>
    <input type="submit" value="edit" name="1">

</form>
</body>
</html>
<?php
require_once ('DBConnector.php');
$servername = "oyc353.encs.concordia.ca";
$username = "oyc353_4";
$password = "Coyote18";
$dbname = "oyc353_4";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if($conn){
    echo "Connection success";
    echo "<br>";
}
else{
    die("Connection failed: " . mysqli_connect_error());
}


if (isset($_POST['1'])){
    $sup=$_POST['sup'];
	$sub=$_POST['sub'];
    if (!empty($sub) && !empty($sup)){
        $check = "SELECT * FROM SuperOf WHERE SubordinateSIN='$sub' && SupervisorSIN='$sup';";
        $result=mysqli_query($conn, $check);
        $num_rows = $result->num_rows;
        if($num_rows!=0) {
            $query = "UPDATE SuperOf SET SubordinateSIN='$sub';";
            $result = mysqli_query($conn, $query);
        }
        else{
            echo "Subordinate doesn't exist, please correct your input";
        }
    }   

	if (empty($sup) && empty($sub) ){
	echo "please enter some data";	
	} 
}






?>