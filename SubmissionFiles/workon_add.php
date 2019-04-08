<html>
<body>

<h1>WorkOn add</h1>

<form action="" method="post">

    SIN: <input type="number" name="sin"><br>
    PID: <input type="number" name="pid"><br>
    Hour: <input type="number" name="hour"><br>
    WorkOnStartDate: <input type="date" name="startdate"><br>
    WorkOnEndDate: <input type="date" name="enddate"><br>
    <input type="submit" value="Add WorkOn" name="1">

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
    echo "connection success";
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
	echo $hour;

    if (!empty($sin)&&!empty($pid)&&!empty($startdate)){
        $query = "SELECT * FROM WorkOn WHERE SIN=$sin AND PID=$pid AND WorkOnStartDate='$startdate';";
        $result=mysqli_query($conn, $query);
        $num_rows = $result->num_rows;

        if($num_rows==0) {
            $check1= "SELECT * FROM Employee WHERE SIN=$sin AND StartDate<'$startdate';";
            $check2= "SELECT * FROM Project WHERE PID=$pid;";

            if (!empty($check1)&&!empty($check2)){
                $first = "INSERT INTO WorkOn(SIN, PID, WorkOnStartDate";
                $second = "  VALUES($sin, $pid, '$startdate'";

                if ($hour != "") {
                    $first .= ", Hour";
                    $second .= ", $hour";
                }
                if ($enddate != "") {
                    $first .= ", WorkOnEndDate";
                    $second .= ", '$enddate'";
                }

                $first .= ")";
                $second .= ");";

                $query = $first . $second;
                mysqli_query($conn, $query);

                echo "$query has been added successfully";
            }
            else{
                echo "the SIN and/or PID does not exist and/or WorkOnStartDate early than the employee start date, please input again.";
            }

        }
        else{
            echo "the WorkOn you want to add is already exist, pleace input again.";
        }
    }
    else{
        echo "must input SIN, PID and WorkOnStartDate to add WorkOn, please input again.";
    }


}

?>