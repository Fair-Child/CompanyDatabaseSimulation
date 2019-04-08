<html>
<body>

<h1>Project add</h1>

<form action="" method="post">

    PName: <input type="text" name="pname"><br>
    Stage: <select name="stage">
            <option value=""></option>
            <option value="Preliminary">Preliminary</option>
            <option value="Intermediate">Intermediate</option>
            <option value="Advanced">Advanced</option>
            <option value="Complete">Complete</option>
            </select><br>
    <input type="submit" value="Add project" name="1">

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
    $pnam=$_POST['pname'];
    $psta=$_POST['stage'];

    if (!empty($pnam)&&!empty($psta)){
        $check = "SELECT * FROM Project WHERE PName='$pnam';";
        $result=mysqli_query($conn, $check);
        $num_rows = $result->num_rows;
        if($num_rows==0) {
            $query = "INSERT INTO Project (PName, Stage) VALUES ('$pnam','$psta');";
            $result = mysqli_query($conn, $query);
            echo "$query has been added successfully";
        }
        else{
            echo "the project you want to add is already exist, please input again.";
        }
    }

    elseif (!empty($pnam)){
        $check = "SELECT * FROM Project WHERE PName='$pnam';";
        $result=mysqli_query($conn, $check);
        $num_rows = $result->num_rows;
        if($num_rows==0) {
            $query = "INSERT INTO Project (PName) VALUES ('$pnam');";
            $result = mysqli_query($conn, $query);
            echo "$query has been added successfully";
        }
        else{
            echo "the project you want to add is already exist, please input again.";
        }
    }
    else{
        echo "must input PName to add Project, please input again.";
    }


}

?>
