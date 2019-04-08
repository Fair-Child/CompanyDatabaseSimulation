<html>
<body>

<h1>WorkOn delete</h1>

<form action="" method="post">

    SIN: <input type="number" name="sin"><br>
    PID: <input type="number" name="pid"><br>
    <input type="submit" value="Delete workon" name="1">

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

    if(!empty($pid)&&!empty($sin)) {
        $query = "SELECT * FROM WorkOn WHERE PID=$pid AND SIN=$sin;";
        $result=mysqli_query($conn, $query);
        $num_rows = $result->num_rows;
        if($num_rows!=0){
            $query = "DELETE FROM WorkOn WHERE PID=$pid AND SIN=$sin;";
            mysqli_query($conn, $query);
            echo "$query has been deleted successfully.";
        }
        else{
            echo "the workon you want to delete does not exist, please input again.";
        }
    }
    else{
        echo "must input SIN and PID to delete Project, please input again.";
    }

}

?>