<html>
<body>
<a href="Employee.php">Back</a>
<form action="" method="post">
   <b>You must enter proper DSIN in order to edit</b>
   DSIN: <input type="number" name="dsin"><br>
   DependentName: <input type="text" name="name"><br>
   DependentGender:   <select  id="sid" name="sex">  
            <option>---select---</option>  
            <option>M</option>  
            <option>F</option>  
            <option>?</option>  
    </select> 
   Dependent_birth: <input type="date" name="birth"><br>
    <input type="submit" value="edit" name="1">

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
    $dsin=$_POST['dsin'];
    $name=$_POST['name'];
    $sex=$_POST['sex'];
    $birth=$_POST['birth'];


  
if(!empty($dsin)){
        $check_dsin_exist="SELECT * FROM Dependent WHERE DSIN=$dsin;";
        $result=mysqli_query($conn, $check_dsin_exist);
        $num_rows=$result->num_rows;
        if($num_rows==0){
            echo "dsin doesn't exsit";
        }
    }



  $query_two="UPDATE Dependent set DependentName='$name',DependentGender='$sex',dependentBirth='$birth' where DSIN='$dsin';";
  mysqli_query($conn, $query_two);
    echo $query_two;
  echo ("edit datas successful");
      
}


?>