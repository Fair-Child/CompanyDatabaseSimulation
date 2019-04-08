<html>
<body>
<a href="Employee.php">Back</a>
<form action="" method="post">
   <b>You must enter proper SIN and PIN in order to add</b>
   SIN: <input type="number" name="sin"><br>
   PID: <input type="number" name="pid"><br>
    Hour: <input type="number" name="hour"><br>
    <b>please enter date in the following format: YYYY-MM-DD</b>
    WorkOnStartDate: <input type="text" name="startdate"><br>
    WorkOnEndDate: <input type="text" name="enddate"><br>
  
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
    $sin=$_POST['sin'];
    $pid=$_POST['pid'];
    $hour=$_POST['hour'];
    $WorkOnStartDate=$_POST['startdate'];
    $WorkOnEndDate=$_POST['enddate'];



if(empty($sin)){
    echo "enter SIN number";
    }
if(empty($pid)){
    echo "enter SIN number";
    }

//check pid existence
    if(!empty($sin)){
        $check_sin_exist="SELECT * FROM Employee WHERE SIN=$sin;";
        $result=mysqli_query($conn, $check_sin_exist);
        $num_rows=$result->num_rows;
        if($num_rows==0){
            echo "sin doesn't exsit";
        }
    }
    if(!empty($pid)){
        $check_pid_exist="SELECT * FROM Project WHERE PID=$pid;";
        $result=mysqli_query($conn, $check_pid_exist);
        $num_rows=$result->num_rows;
        if($num_rows==0){
            echo "pid doesn't exsit";
        }
    }
    if(!empty($hour)){
        if($hour<0){
            echo "please enter proper hour";
        }

    }
if($num_rows==0 && $num_rows==0)
    {
    exit("invilid input");
    }

$query_pid="INSERT INTO WorkOn (PID,SIN,Hour,WorkOnStartDate,WorkOnEndDate) VALUES ($pid,$sin,$hour,$startdate,$enddate);";
mysqli_query($conn, $query_pid);
echo $query_pid;

echo ("add datas successful");






      
}


?>