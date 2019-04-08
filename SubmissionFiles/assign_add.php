<html>
<body>

<h1>Assign add</h1>

<form action="" method="post">

    SIN: <input type="number" name="sin"><br>
    DNumber: <input type="number" name="dnumber"><br>
    AssignStartDate: <input type="date" name="assignstartdate"><br>
    AssignEndDate: <input type="date" name="assignenddate"><br>

    <input type="submit" value="Add assign" name="1">

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
    $sin = $_POST['sin'];
    $dnum = $_POST['dnumber'];
    $assignstartdate = $_POST['assignstartdate'];
    $assignenddate = $_POST['assignenddate'];

    if (!empty($sin) && !empty($assignstartdate) && !empty($dnum)) {
        $query = "SELECT * FROM Assign WHERE SIN=$sin;";
        $result = mysqli_query($conn, $query);
        $num_rows = $result->num_rows;
        if ($num_rows == 0) {
            $first="INSERT INTO Assign(SIN, AssignStartDate, DNumber";
            $second=" VALUES ($sin, '$assignstartdate', $dnum";

            if ($assignenddate != "") {
                $first .= ", AssignEndDate";
                $second .= ", '$assignenddate'";
            }

            $first .= ")";
            $second .= ");";

            $query = $first . $second;

            $result=mysqli_query($conn, $query);
            if ($result==1){
                echo "$query has been added successfully";
            }
            else{
                echo "$query check the SIN, AssignStartDate, please input again";
            }

        } else {
            echo "the Assign you want to add is already exist, please input again.";
        }
    } else {
        echo "must input SIN and AssignStartDate to add Assign, please input again.";
    }


}

?>