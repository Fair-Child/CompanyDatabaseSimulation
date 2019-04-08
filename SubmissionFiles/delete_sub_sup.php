<html>
<body>
<a href="Employee.php">Back</a>
<form action="" method="post">
   Supervisor: <input type="number" name="sup"><br>
    Subordinate: <input type="number" name="sub"><br>
    <input type="submit" value="delete" name="1">

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

    if (!empty($sup)&&!empty($sub)){
        $check = "SELECT * FROM SuperOf WHERE SupervisorSIN='$sup' && SubordinateSIN='$sub';";
        $result=mysqli_query($conn, $check);
        $num_rows = $result->num_rows;
        if($num_rows==1) {
            $query = "DELETE FROM SuperOf WHERE SupervisorSIN='$sup' && SubordinateSIN='$sub';";
            $result = mysqli_query($conn, $query);
            echo "$query has been added successfully";
        }
        else{
            echo "doesn't exist, please correct your input";
        }
    }

    
    else{
        echo "invilid input, please input again.";
    }


}






?>