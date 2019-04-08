<html>
<body>

<h1>InCharge add</h1>

<form action="" method="post">

    DNumber: <input type="number" name="dnum"><br>
    PID: <input type="number" name="pid"><br>
    PL: <input type="number" name="pl"><br>
    ProjectStartDate: <input type="date" name="startdate"><br>
    ProjectEndDate: <input type="date" name="enddate"><br>

    <input type="submit" value="Add InCharge" name="1">

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


if (isset($_POST['1'])) {
    $dnum = $_POST['dnum'];
    $pid = $_POST['pid'];
    $pl = $_POST['pl'];
    $startdate = $_POST['startdate'];
    $enddate = $_POST['enddate'];

    if (!empty($dnum) && !empty($pid) && !empty($pl) && !empty($startdate)) {
        $query = "SELECT * FROM InCharge WHERE PID=$pid;";
        $result = mysqli_query($conn, $query);
        $num_rows = $result->num_rows;
        if ($num_rows == 0) {
            $first="INSERT INTO InCharge(PID, ProjectStartDate, PL, Dnumber";
            $second=" VALUES ($pid, '$startdate', $pl, $dnum";

            if ($enddate != "") {
                $first .= ", ProjectEndDate";
                $second .= ", '$enddate'";
            }

            $first .= ")";
            $second .= ");";

            $query = $first . $second;

            $result=mysqli_query($conn, $query);
            if ($result==1){
                echo "$query has been added successfully";
            }
            else{
                echo "$query check the PID, ProjectStartDate and PL, please input again";
            }

        } else {
            echo "the InCharge you want to add is already exist, please input again.";
        }
    } else {
        echo "must input DNumber, PID, PL, ProjectStartDate to add InCharge, please input again.";
    }


}

?>