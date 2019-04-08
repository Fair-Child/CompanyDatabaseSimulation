<html>
<body>

<h1>Employee delete</h1>

<form action="" method="post">

    SIN: <input type="number" name="sin"><br>
    <input type="submit" value="Delete employee" name="1">

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
    if(!empty($sin)) {
        $query = "SELECT * FROM Employee WHERE SIN=$sin;";
        $result=mysqli_query($conn, $query);
        $num_rows = $result->num_rows;
        if($num_rows!=0){
            $query = "DELETE FROM Employee WHERE SIN=$sin;";
            mysqli_query($conn, $query);
            echo "$query has been delete successfully.";
        }
        else{
            echo "the employee you want to delete does not exist, please input again.";
        }
    }
    else{
        echo "must input SIN to delete Employee, please input again.";
    }

}



?>