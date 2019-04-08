<html>
<body>

<h1>WorkOn update</h1>

<form action="" method="post">

    SIN: <input type="number" name="sin"><br>
    PID: <input type="number" name="pid"><br>
    Hour: <input type="number" name="hour"><br>
    WorkOnStartDate: <input type="date" name="startdate"><br>
    WorkOnEndDate: <input type="date" name="enddate"><br>
    <input type="submit" value="Update workon" name="1">

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
    $startdate=$_POST['startdate'];
    $enddate=$_POST['enddate'];
    $hour=$_POST['hour'];

    if (!empty($sin)&&!empty($pid)&&!empty($startdate)&&!empty($enddate)&&!empty($hour)){
        $check = "SELECT * FROM WorkOn WHERE PID=$pid AND SIN=$sin AND WorkOnStartDate='$startdate';";
        $result=mysqli_query($conn, $check);
        $num_rows = $result->num_rows;
        if($num_rows!=0) {
            $query = "UPDATE WorkOn SET WorkOnEndDate='$enddate',hour='$hour' WHERE PID=$pid AND SIN=$sin AND WorkOnStartDate='$startdate';";
            $result = mysqli_query($conn, $query);
            echo "$query has been updated successfully.";
        }
        else{
            echo "the WorkOn you want to update does not exist, please input again.1";
        }
    }
    elseif (!empty($pid)&&!empty($sin)&&!empty($startdate)&&!empty($enddate)){
        $check = "SELECT * FROM WorkOn WHERE PID=$pid AND SIN=$sin AND WorkOnStartDate='$startdate';";
        $result=mysqli_query($conn, $check);
        $num_rows = $result->num_rows;
        if($num_rows!=0) {
            $query = "UPDATE WorkOn SET WorkOnEndDate='$enddate' WHERE PID=$pid AND SIN=$sin AND WorkOnStartDate='$startdate';";
            $result = mysqli_query($conn, $query);
            echo "$query has been updated successfully.";
        }
        else{
            echo "the WorkOn you want to update does not exist, please input again.2";
        }
    }
    elseif (!empty($sin)&&!empty($pid)&&!empty($startdate)&&!empty($hour)){
        $check = "SELECT * FROM WorkOn WHERE PID=$pid AND SIN=$sin AND WorkOnStartDate='$startdate';";
        $result=mysqli_query($conn, $check);
        $num_rows = $result->num_rows;
        if($num_rows!=0) {
            $query = "UPDATE WorkOn SET hour='$hour' WHERE PID=$pid AND SIN=$sin AND WorkOnStartDate='$startdate';";
            $result = mysqli_query($conn, $query);
            echo "$query has been updated successfully.";
        }
        else{
            echo "the WorkOn you want to update does not exist, please input again.3";
        }
    }
    else{
        echo "must input SIN, PID, WorkOnStartDate and at least one other attribute to update WorkOn, please input again.";
    }

}
?>
