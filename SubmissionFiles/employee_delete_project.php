<html>
<body>
<a href="Employee.php">Back</a>
<form action="" method="post">
   <b>You must enter proper SIN and PID in order to delete project</b><br>
   SIN: <input type="number" name="sin"><br>
   PID: <input type="number" name="pid"><br>
   <input type="submit" value="delete" name="1">

</form>
</body>
</html>

<?php
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
if(empty($sin)){
    echo "enter SIN number";
    }
if(empty($pid)){
    echo "enter pid number";
    }
//check sin existence
if(!empty($sin)){
        $check_SIN_exist="SELECT * FROM WorkOn WHERE WorkOn.SIN=$sin;";
        $result=mysqli_query($conn, $check_SIN_exist);
        $num_rows=$result->num_rows;
        if($num_rows==0){
            echo "SIN doesn't exsit ";
        }
}
    if(!empty($pid)){
        $check_pid_exist="SELECT * FROM WorkOn WHERE WorkOn.PID=$pid;";
        $result=mysqli_query($conn, $check_pid_exist);
        $num_rows=$result->num_rows;
        if($num_rows==0){
            echo "pid doesn't exsit ";
        }
    }
if($num_rows==0 || $num_rows==0){
    exit("invilid input");
    }

if(!empty($pid) && !empty($sin) ){
        $check="SELECT * FROM WorkOn WHERE WorkOn.PID=$pid && WorkOn.SIN=$sin;";
        $result=mysqli_query($conn, $check);
        $num_rows=$result->num_rows;
        if($num_rows==0){
            echo "pid and sin doesn't match ";
        }
        else{
            $query_delete_by_pid_and_sin="DELETE FROM WorkOn WHERE WorkOn.SIN=$sin && WorkOn.PID=$pid;";
            $result=mysqli_query($conn, $query_delete_by_pid_and_sin);
            echo "success delete";
        }
}
}







?>