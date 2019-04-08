<html>
<body>

<h1>Project update</h1>

<form action="" method="post">

    PID: <input type="text" name="pid"><br>
    PName: <input type="text" name="pname"><br>
    Stage: <select name="stage">
        <option value=""></option>
        <option value="Preliminary">Preliminary</option>
        <option value="Intermediate">Intermediate</option>
        <option value="Advanced">Advanced</option>
        <option value="Complete">Complete</option>
        </select><br>
    <input type="submit" value="Update project" name="1">

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
    $pnam=$_POST['pname'];
    $psta=$_POST['stage'];

    if (!empty($pid)&&!empty($pnam)&&!empty($psta)){
        $check = "SELECT * FROM Project WHERE PID=$pid;";
        $result=mysqli_query($conn, $check);
        $num_rows = $result->num_rows;
        if($num_rows!=0) {
            $query = "UPDATE Project SET PName='$pnam',Stage='$psta' WHERE PID=$pid;";
            $result = mysqli_query($conn, $query);
            echo "$query has been updated successfully.";
        }
        else{
            echo "the Project you want to update does not exist, please input again.";
        }
    }
    elseif (!empty($pid)&&!empty($pnam)){
        $check = "SELECT * FROM Project WHERE PID=$pid;";
        $result=mysqli_query($conn, $check);
        $num_rows = $result->num_rows;
        if($num_rows!=0) {
            $query = "UPDATE Project SET PName='$pnam' WHERE PID=$pid;";
            $result = mysqli_query($conn, $query);
            echo "$query has been updated successfully.";
        }
        else{
            echo "the Project you want to update does not exist, please input again.";
        }
    }
    elseif (!empty($pid)&&!empty($psta)){
        $check = "SELECT * FROM Project WHERE PID=$pid;";
        $result=mysqli_query($conn, $check);
        $num_rows = $result->num_rows;
        if($num_rows!=0) {
            $query = "UPDATE Project SET Stage='$psta' WHERE PID=$pid;";
            $result = mysqli_query($conn, $query);
            echo "$query has been updated successfully.";
        }
        else{
            echo "the Project you want to update does not exist, please input again.";
        }
    }
    else{
        echo "must input PID and at least one other attribute to update Project, please input again.";
    }

}
?>
