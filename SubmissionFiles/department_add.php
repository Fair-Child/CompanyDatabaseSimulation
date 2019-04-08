<html>
<body>

<h1>Department add</h1>

<form action="" method="post">

    DName: <input type="text" name="dname"><br>
    <input type="submit" value="Add Department" name="1">

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
    $dnam=$_POST['dname'];

    if (!empty($dnam)){
        $query = "SELECT * FROM Department WHERE DName='$dnam';";
        $result=mysqli_query($conn, $query);
        $num_rows = $result->num_rows;
        if($num_rows==0) {
            $query = "INSERT INTO Department(DName) VALUES ('$dnam');";
            mysqli_query($conn, $query);
            echo "$query has been added successfully";
        }
        else{
            echo "the department you want to add is already exist, pleace input again.";
        }
    }
    else{
        echo "must input DName to add Department, please input again.";
    }


}

?>

