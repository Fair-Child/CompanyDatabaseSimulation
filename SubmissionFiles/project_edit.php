
<html>
<body>

<h1>project edit</h1>

<form action="" method="post">
    PID: <input type="text" name="id"><br>
    PName: <input type="text" name="pname"><br>
    Stage: <input type="text" name="stage"><br>
    <input type="submit" value="Show ID" name="1">
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
            echo "success";
           }
            else{
             die("Connection failed: " . mysqli_connect_error());
            }

             if (isset($_POST['1'])){
            $o=$_POST['id'];
            $l=$_POST['pname'];
            $p=$_POST['stage'];
    $query = "update Project set PName='$l',Stage='$p' where pid=$o;";
  $result=mysqli_query($conn, $query);
    echo $query;
    }
  ?>