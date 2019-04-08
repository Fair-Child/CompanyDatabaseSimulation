<html>
<body>

<h1>Department delete</h1>

<form action="" method="post">

    DNumber: <input type="number" name="dnumber"><br>
    <input type="submit" value="Delete department" name="1">


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
    $dnum=$_POST['dnumber'];

    if(!empty($dnum)) {
        $query = "SELECT * FROM Department WHERE DNumber=$dnum;";
        $result=mysqli_query($conn, $query);
        $num_rows = $result->num_rows;
        if($num_rows!=0){
            $query = "DELETE FROM Department WHERE DNumber=$dnum;";
            mysqli_query($conn, $query);
            echo "$query has been deleted successfully";
        }
        else{
            echo "the Department you want to delete does not exist, please input again.";
        }
    }
    else{
        echo "must input DNumber to delete Department, please input again";
    }

}

?>