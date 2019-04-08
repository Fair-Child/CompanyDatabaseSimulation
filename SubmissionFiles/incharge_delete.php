<html>
<body>

<h1>InCharge delete</h1>

<form action="" method="post">

    PID: <input type="number" name="pid"><br>
    <input type="submit" value="Delete incharge" name="1">

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
    $pid=$_POST['pid'];

    if(!empty($pid)) {
        $query = "SELECT * FROM InCharge WHERE PID=$pid;";
        $result=mysqli_query($conn, $query);
        $num_rows = $result->num_rows;
        if($num_rows!=0){
            $query = "DELETE FROM InCharge WHERE PID=$pid;";
            $result=mysqli_query($conn, $query);
            if ($result==1){
                echo "$query has been deleted successfully.";
            }
            else{
                echo "$query check the PID, please input again.";
            }
        }
        else{
            echo "the InCharge you want to delete does not exist, please input again.";
        }
    }
    else{
        echo "must input PID to delete Project, please input again.";
    }

}

?>