<html>
<body>

<h1>InCharge update</h1>

<form action="" method="post">

    DNumber: <input type="number" name="dnum"><br>
    PID: <input type="number" name="pid"><br>
    PL: <input type="number" name="pl"><br>
    ProjectStartDate: <input type="date" name="startdate"><br>
    ProjectEndDate: <input type="date" name="enddate"><br>

    <input type="submit" value="Update InCharge" name="1">

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

if (isset($_POST['1'])) {
    $dnum = $_POST['dnum'];
    $pid = $_POST['pid'];
    $pl = $_POST['pl'];
    $startdate = $_POST['startdate'];
    $enddate = $_POST['enddate'];

    if (!empty($dnum) && !empty($pid) && !empty($pl) && !empty($startdate) && !empty($enddate)) {
        $check = "SELECT * FROM InCharge WHERE PID=$pid;";
        $result = mysqli_query($conn, $check);
        $num_rows = $result->num_rows;
        if ($num_rows != 0) {
            $first="";
            $second=" WHERE SIN=$sin;";

            $query = "UPDATE InCharge SET DNumber=$dnum, PL=$pl, ProjectStartDate='$startdate', ProjectEndDate='$enddate' WHERE PID=$pid;";

            $result = mysqli_query($conn, $query);
            if ($result==1){
                echo "$query has been updated successfully.";
            }
            else{
                echo "$query check foreign key, please input again";
            }
        }
        else {
            echo "the InCharge you want to update does not exist, please input again.";
        }
    }
    else{
        echo "must input DNumber, PID, PL, ProjectStartDate, ProjectEndDate to update InCharge, please input again.";
    }
}
?>
