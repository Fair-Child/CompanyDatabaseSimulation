<html>
<body>

<h1>Employee update</h1>

<form action="" method="post">

    SIN: <input type="number" name="sin"><br>
    Name: <input type="text" name="name"><br>
    Birth: <input type="date" name="birth"><br>
    HomeAddress: <input type="text" name="homeaddress"><br>
    Phone: <input type="number" name="phone"><br>
    Gender: <select name="gender">
                <option value=""></option>
                <option value="M">M</option>
                <option value="F">F</option>
            </select><br>
    Salary: <input type="number" name="salary"><br>
    StartDate: <input type="date" name="startdate"><br>
    EndDate: <input type="date" name="enddate"><br>
    <input type="submit" value="Update employee" name="1">

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
    $name=$_POST['name'];
    $birth=$_POST['birth'];
    $homeaddress=$_POST['homeaddress'];
    $phone=$_POST['phone'];
    $gender=$_POST['gender'];
    $salary=$_POST['salary'];
    $startdate=$_POST['startdate'];
    $enddate=$_POST['enddate'];



    if (!empty($sin)&&!empty($name)&&!empty($startdate)) {
        $check = "SELECT * FROM Employee WHERE SIN=$sin;";
        $result = mysqli_query($conn, $check);
        $num_rows = $result->num_rows;
        if ($num_rows != 0) {
            $first = "UPDATE Employee SET Name='$name',StartDate='$startdate'";
            $last = " WHERE SIN=$sin;";

            if ($birth != "") {
                $first .= ", Birth='$birth'";
            }

            if ($homeaddress != "") {
                $first .= ", HomeAddress='$homeaddress'";
            }

            if ($phone != "") {
                $first .= ", Phone=$phone";
            }

            if ($gender != "") {
                $first .= ", Gender='$gender'";
            }

            if ($salary != "") {
                $first .= ", Salary=$salary";
            }

            if ($startdate != "") {
                $first .= ", StartDate='$startdate'";
            }

            if ($enddate != "") {
                $first .= ", EndDate='$enddate'";
            }

            $query = $first . $last;

            $result = mysqli_query($conn, $query);
            echo "$query has been updated successfully.";

        }
        else{
            echo "the employee you want to update does not exist, please input again.";
        }
    }

    else{
        echo "must input SIN , Name and StartDate to update Employee, please input again.";
    }

}
?>