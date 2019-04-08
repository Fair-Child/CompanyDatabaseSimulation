<html>
<body>
<a href="Employee.php">Back</a>
<form action="" method="post">
   Supervisor: <input type="number" name="sup"><br>
    Subordinate: <input type="number" name="sub"><br>
    <input type="submit" value="add" name="1">

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
        $check_single_sub="SELECT * FROM SuperOf WHERE SubordinateSIN='$sub';";
        $result_sub=mysqli_query($conn, $check_single_sub);
        $num_rows_of_sub=$result_sub->num_rows_of_sub;
        if($num_rows_of_sub==1){
                echo "A subordiante can only have one Supervisor";
        }
        else if($num_rows_of_sub==0){
            $check_employee = "SELECT * FROM Employee WHERE Employee.SIN=$sup || Employee.SIN=$sub;";
            $result_emp=mysqli_query($conn, $check_emp);
            $num_rows_of_emp=$result_emp->num_rows_of_emp;
            if($num_rows_of_emp==2){
                $query = "INSERT INTO SuperOf (SupervisorSIN, SubordinateSIN) VALUES ($sup,$sub);";
                $result = mysqli_query($conn, $query);
                echo "$query has been added successfully";
            }
            
        }
    }
    else{
        echo "empty input";
        }
}
















?>