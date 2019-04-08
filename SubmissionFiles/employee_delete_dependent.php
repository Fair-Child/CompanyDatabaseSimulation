<html>
<body>
<a href="Employee.php">Back</a>
<form action="" method="post">
   <b>You must enter proper SIN and PIN in order to delete</b>
   SIN: <input type="number" name="sin"><br>
   DSIN: <input type="number" name="dsin"><br>
    <input type="submit" value="delete" name="1">

</form>
</body>
</html>

<?php
require_once ('DBConnector.php');
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
    $dsin=$_POST['dsin'];
//check pid existence
    if(!empty($sin)){
        $check_sin_exist="SELECT * FROM HaveDependent WHERE SIN=$sin;";
        $result=mysqli_query($conn, $check_sin_exist);
        $num_rows=$result->num_rows;
        if($num_rows==0){
            exit("sin doesn't exsit");
        }
   }

    if(!empty($dsin)){
        $check_csin_exist="SELECT * FROM Dependent WHERE DSIN=$dsin;";
        $result=mysqli_query($conn, $check_csin_exist);
        $num_rows=$result->num_rows;
        if($num_rows==0){
            exit("dsin doesn't exsit");
        }    
    }


  $query_one="delete from HaveDependent where SIN=$sin;";
  mysqli_query($conn, $query_one);
  $query_two="delete from Dependent where DSIN=$dsin;";
  mysqli_query($conn, $query_two);
  echo $query_two;
  echo ("delete data successful");     
  
}


?>