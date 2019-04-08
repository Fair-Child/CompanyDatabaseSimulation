<html>
<body>

<h1>Assign update</h1>

<form action="" method="post">

    SIN: <input type="number" name="sin"><br>
    DNumber: <input type="number" name="dnum"><br>
    AssignStartDate: <input type="date" name="startdate"><br>
    AssignEndDate: <input type="date" name="enddate"><br>
    <input type="submit" value="Update assign" name="1">

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
    $sin = $_POST['sin'];
    $dnum = $_POST['dnum'];
    $startdate = $_POST['startdate'];
    $enddate = $_POST['enddate'];

    if (!empty($sin) && (!empty($dnum) || !empty($startdate) || !empty($enddate) )) {
        $check = "SELECT * FROM Assign WHERE SIN=$sin;";
        $result = mysqli_query($conn, $check);
        $num_rows = $result->num_rows;
        if ($num_rows != 0) {
            $first="UPDATE Assign SET AssignStartDate='$startdate'";
            $second=" WHERE SIN=$sin;";

            if ($dnum!=0){
                $first.=", DNumber=$dnum";
            }

            if ($enddate!=0){
                $first.=", AssignEndDate='$enddate'";
            }

            $query = $first . $second;

            $result = mysqli_query($conn, $query);
            if ($result==1){
                echo "$query has been updated successfully.";
            }
            else{
                echo "$query must input AssignStartDate, please input again";
            }
        }
        else {
            echo "the Assign you want to update does not exist, please input again.";
        }
    }
    else{
        echo "must input SIN and at least one other attribute to update Assign, please input again.";
    }
}
?>
