<html>
<body>

<h1>Department update</h1>

<form action="" method="post">
    DNumber: <input type="number" name="dnumber"><br>
    DName: <input type="text" name="dname"><br>
    <input type="submit" value="Update department" name="1">
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
    $dnam=$_POST['dname'];

    if (!empty($dnum)&&!empty($dnam)){
        $check = "SELECT * FROM Department WHERE DNumber=$dnum;";
        $result=mysqli_query($conn, $check);
        $num_rows = $result->num_rows;
        if($num_rows!=0) {
            $query = "UPDATE Department SET DName='$dnam' WHERE DNumber=$dnum;";
            $result = mysqli_query($conn, $query);
            echo "$query has been updated successfully";
        }
        else{
            echo "the Department you want to update does not exist, please input again.";
        }
    }
    else{
        echo "must input both DNumber and DName to update Department, please input again.";
    }

}
?>
