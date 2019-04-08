<html>
<body>
<a href="Employee.php">Back</a>
<form action="" method="post">
   <b>You must enter proper SIN and PIN in order to add</b>
   SIN: <input type="number" name="sin"><br>
   DSIN: <input type="number" name="dsin"><br>
   DependentName: <input type="text" name="name"><br>
   DependentGender:   <select  id="sid" name="sex">  
            <option>---select---</option>  
            <option>M</option>  
            <option>F</option>  
            <option>?</option>  
    </select> 
   Dependent_birth: <input type="date" name="birth"><br>
    <input type="submit" value="add" name="1">

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
    $name=$_POST['name'];
    $sex=$_POST['sex'];
    $birth=$_POST['birth'];


//check pid existence
    if(!empty($sin)){
        $check_sin_exist="SELECT * FROM Employee WHERE SIN=$sin;";
        $result=mysqli_query($conn, $check_sin_exist);
        $num_rows=$result->num_rows;
        if($num_rows==0){
            echo "sin doesn't exsit";
        }
    }
  $query_one="insert into HaveDependent (SIN,DSIN) values ($sin,$dsin);";
  mysqli_query($conn, $query_one);
  
  $query_two="INSERT INTO Dependent (DSIN,DependentName,DependentGender,dependentBirth) VALUES ($dsin,'$name','$sex','$birth');";
  mysqli_query($conn, $query_two);
  echo $query_two;
  echo ("add datas successful");
      
}


?>