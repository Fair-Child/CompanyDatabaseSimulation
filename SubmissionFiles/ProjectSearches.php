<html>
<body>
<button type="button"
        onclick="window.open('', '_self', ''); window.close();">Close</button><br><br>
<table>

<?php
/**
 * Created by IntelliJ IDEA.
 * User: JY
 * Date: 2018-04-07
 * Time: 10:08 PM
 */

require_once ('DBConnector.php');

function populate_table($sql_result)
{
    if (mysqli_num_rows($sql_result)==0)
    {
        echo "<h2>There exists no data for your query</h2>";
    }
    else {
        echo "<b>Detail hour distribution per project member:</b><br>";
        echo "<table border='1'>";

        $first = true;

        while ($row = mysqli_fetch_assoc($sql_result)) {
            if ($first) {
                echo "<tr>";
                foreach ($row as $k => $v) {
                    echo "<th>" . $k . "</th>";
                }
                echo "</tr>";
            }
            echo "<tr>";
            foreach ($row as $k => $v) {
                echo "<td>" . $v . "</td>";
//                echo "";
                // echo $k."->".$v." ";
            }
            echo "</tr>";
            $first = false;
        }
        echo "</table>";
    }
}

$DB = new DBConnector();
$DB->connect();

if (isset($_POST['showMembers']))
{
    $PID = $_POST['PID'];
    $PName = $_POST['PName'];
    echo "<h1>Members of Project $PName </h1> <br>";

    $sqlQuery = "SELECT PID, PName as ProjectName, PL as ProjectLeader, SIN as MemberSIN, Name as MemberName FROM WorkOn NATURAL JOIN 
InCharge NATURAL JOIN Employee NATURAL JOIN Project WHERE PID=$PID";
//    echo $sqlQuery;
    $result = $DB->parse_query($sqlQuery);
    populate_table($result);
?>
Add/Modify/Remove Project participated:<br>
<form action="index.html" method="get">
  <button type="submit" formaction="employee_add_project.php">add</button><br>
  <button type="submit" formaction="employee_delete_project.php">delete</button><br>
  <button type="submit" formaction="workon_update.php">edit</button><br>
</form>
<?


}
elseif (isset($_POST['delete'])){
    $PID = $_POST['PID'];
    $deleteQuery = "DELETE FROM Project WHERE PID=$PID";
    $deleteResult = $DB->parse_query($deleteQuery);
    if ($deleteResult==1)
        echo "Successfully deleted Project #$PID<br>";
    else{
        echo "Failed to delete Project #$PID<br>";
        echo $deleteQuery;
        echo $deleteResult;
    }
}
elseif (isset($_POST['edit'])){
    $PID = $_POST['PID'];
    $PName = $_POST['PName'];
    $DID = $_POST['DID'];
    $Stage = $_POST['Stage'];
    $ProjectLeaderSIN = $_POST['ProjectLeaderSIN'];
    $Location = $_POST['Location'];

    $getExistingDIDsQuery = "SELECT DNumber FROM Department";
    $getExistingDIDsResult = $DB->parse_query($getExistingDIDsQuery);
    $getExistingLocationQuery = "SELECT Address FROM Location";
    $getExistingLocationResult = $DB->parse_query($getExistingLocationQuery);

    echo "
    <form action='' method='post'>
        PID: <input type='text' name='PID' value='$PID' readonly><br>
        Project Name: <input type='text' name='PName' value='$PName'><br>
        Department In Charge: <select name='DID'><option value=''></option>";
    while ($row = mysqli_fetch_assoc($getExistingDIDsResult)){
        $tempDID = $row['DNumber'];
        if ($tempDID==$DID)
            echo "<option value=$tempDID selected='selected'>$tempDID</option>";
        else
            echo "<option value=$tempDID>$tempDID</option>";
    }
    echo "</select><br>
        Stage: <select name=\"stage\">";
    if($Stage=="Preliminary"){
        echo "<option value=\"Preliminary\" selected='selected'>Preliminary</option>
            <option value=\"Intermediate\">Intermediate</option>
            <option value=\"Advanced\">Advanced</option>
            <option value=\"Complete\">Complete</option>
        </select><br>";
    }
    elseif ($Stage=="Intermediate"){
        echo "<option value=\"Preliminary\">Preliminary</option>
            <option value=\"Intermediate\" selected='selected'>Intermediate</option>
            <option value=\"Advanced\">Advanced</option>
            <option value=\"Complete\">Complete</option>
        </select><br>";
    }
    elseif ($Stage=="Advanced"){
        echo "<option value=\"Preliminary\">Preliminary</option>
            <option value=\"Intermediate\">Intermediate</option>
            <option value=\"Advanced\" selected='selected'>Advanced</option>
            <option value=\"Complete\">Complete</option>
        </select><br>";
    }
    elseif ($Stage=="Complete"){
        echo "<option value=\"Preliminary\">Preliminary</option>
            <option value=\"Intermediate\">Intermediate</option>
            <option value=\"Advanced\">Advanced</option>
            <option value=\"Complete\" selected='selected'>Complete</option>
        </select><br>";
    }

    echo "
    Project Leader SIN: <input type='number' name='ProjectLeaderSIN' value='$ProjectLeaderSIN' min='100000000' max='999999999'>    Please select an employee from the same Department in charge of this Project<br>
    Location: <select name='Location'>";

    while ($row = mysqli_fetch_assoc($getExistingLocationResult)){
        $tempLocation = $row['Address'];
        echo $tempLocation;
        if ($tempLocation==$Location)
            echo "<option value='$tempLocation' selected='selected'>$tempLocation</option>";
        else
            echo "<option value='$tempLocation'>$tempLocation</option>";
    }

    echo "</select><br>
    <input type=\"submit\" value=\"Submit\" name=\"submitEdition\">
    </form>
    ";
}
elseif (isset($_POST['submitEdition'])){
    $PID = $_POST['PID'];
    $PName = $_POST['PName'];
    $DID = $_POST['DID'];
    $Stage = $_POST['stage'];
    $ProjectLeaderSIN = $_POST['ProjectLeaderSIN'];
    $Location = $_POST['Location'];

    $projectQuery = "UPDATE Project SET PName = '$PName', Stage = '$Stage' WHERE PID=$PID";



    $DB->parse_query("START TRANSACTION;");

    $ifIncharge = "SELECT PID FROM InCharge WHERE PID=$PID";
    if (mysqli_num_rows($DB->parse_query($ifIncharge))==0)
    {
        if ($DID!="" && $ProjectLeaderSIN!=""){
            $departmentQuery="INSERT INTO InCharge (DNumber, PID, PL, ProjectStartDate) VALUES ($DID, $PID, $ProjectLeaderSIN, NOW())";
            $departmentResult = $DB->parse_query($departmentQuery);
        }
        else{
            echo "<br>Project assignment to Department has not been changed, you need to enter both DID and Project Leader SIN in order to perform update.<br>";
            $departmentResult = 1;
        }
    }
    else{
        if ($DID!="" && $ProjectLeaderSIN!=""){
            $departmentQuery = "UPDATE InCharge SET DNumber=$DID, PL=$ProjectLeaderSIN WHERE PID=$PID";
            $departmentResult = $DB->parse_query($departmentQuery);
        }
        else{
            echo "<br>Project assignment to Department has not been changed, you need to enter both DID and Project Leader SIN in order to perform update.<br>";
            $departmentResult = 1;
        }
    }
    $locationQuery = "INSERT INTO ProjectLocation(Address, PID) VALUES ('$Location', $PID)";


    echo "$projectQuery<br>$departmentQuery<br>$locationQuery<br>";

    $locationResult = $DB->parse_query($locationQuery);
    if ($locationResult!=1){
        $locationQuery = "UPDATE ProjectLocation SET Address='$Location' WHERE PID=$PID";
        $locationResult = $DB->parse_query($locationQuery);
    }
    $projectResult = $DB->parse_query($projectQuery);

    if ($projectResult==1 && $departmentResult==1 && $locationResult==1) {
        $DB->parse_query("COMMIT;");
        echo "<h3>Successfully submitted changes</h3>";
    }
    else{
        $DB->parse_query("ROLLBACK;");
        echo "<h3>Failed to submit changes, please verify your inputs</h3>";
        if ($projectResult!=1){
            echo $projectQuery;
            echo $projectResult;
            echo "Failed to submit changes for Project Name and Stage<br>";}
        if ($departmentResult!=1){
            echo $departmentQuery;
            echo $departmentResult;
            echo "Failed to submit changes for Department in charge of project and Project Leader SIN<br>";}
        if ($locationResult!=1){
            echo $locationQuery;
            echo $locationResult;
            echo "Failed to submit changes for Project Location<br>";
        }
    }
}
elseif (isset($_POST['showExpenses'])){
    $PID = $_POST['PID'];
    $PName = $_POST['PName'];
    echo "<h1>Total Expense of Project $PName </h1> <br>";

    $sqlQuery = "SELECT WorkOn.SIN, Name, Hour, Salary FROM WorkOn NATURAL JOIN Employee WHERE PID=$PID";
//    echo $sqlQuery;
    $result = $DB->parse_query($sqlQuery);
    populate_table($result);

    echo "<b>Total expense calculation:</b><br>";
    $result = $DB->parse_query($sqlQuery);
    if (mysqli_num_rows($result)>0){
        $totalExpense=0;
        while ($row = mysqli_fetch_assoc($result)) {
            $hour = $row['Hour'];
            $salary = $row['Salary'];
            $name = $row['Name'];
            $individualTotalExpense = $hour*$salary;
            echo "Total expense allocated for $name: $salary$/h X $hour = $individualTotalExpense$<br>";
            $totalExpense+=$individualTotalExpense;
        }
        echo "<h3>Total Expense: $totalExpense$</h3>";
    }
}

?>
</body>
</html>
