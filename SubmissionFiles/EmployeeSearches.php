
<table>


<?php
/**
 * Created by IntelliJ IDEA.
 * User: JY
 * Date: 2018-04-07
 * Time: 5:56 PM
 */

require_once ('DBConnector.php');

function populate_table($sql_result)
{
    if (mysqli_num_rows($sql_result)==0)
    {
        echo "<h2>There exists no data for your query</h2>";
    }
    else {
        echo "<table border='1'>";

        $first = true;

        while ($row = mysqli_fetch_assoc($sql_result)) {
            if ($first) {
                echo "<tr>";
                foreach ($row as $k => $v) {
                    echo "<th width='50%'>" . $k . "</th>";
                }
                echo "</tr>";
            }
            echo "<tr>";
            foreach ($row as $k => $v) {
                echo "<td width='50%'>" . $v . "</td>";
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

if (isset($_POST['showSubordinates']))
{
    $SIN = $_POST['SIN'];
    $NAME = $_POST['Name'];
    echo "<h1>Employees supervised by $NAME </h1> <br>";
    $sqlQuery = "SELECT SIN, Name FROM SuperOf join Employee on SubordinateSIN=SIN where SupervisorSin='$SIN'";
    $result = $DB->parse_query($sqlQuery);
    populate_table($result);
?>
Add/Modify/Remove Subordinate:<br>
<form action="index.html" method="get">
  <button type="submit" formaction="add_sub_sup.php">add</button><br>
  <button type="submit" formaction="delete_sub_sup.php">delete</button><br>
  <button type="submit" formaction="edit_sub_sup.php">edit</button><br>
</form>
<?
}

elseif (isset($_POST['showProjects']))
{
    $SIN = $_POST['SIN'];
    $NAME = $_POST['Name'];
    echo "<h1>Projects participated by $NAME </h1> <br>";

    $sqlQuery = "SELECT WorkOn.PID, PName as ProjectName, PL as ProjectLeaderSIN, Name as ProjectLeaderName, Hour, WorkOnStartDate, WorkOnEndDate 
              FROM WorkOn LEFT JOIN Project ON WorkOn.PID=Project.PID LEFT JOIN InCharge ON WorkOn.PID = InCharge.PID LEFT JOIN Employee ON Employee.SIN=PL WHERE WorkOn.SIN=$SIN";

    $result = $DB->parse_query($sqlQuery);
    populate_table($result);

?>
Add/Modify/Remove Project participated:<br>
<form action="index.html" method="get">
  <button type="submit" formaction="workon_add.php">add</button><br>
  <button type="submit" formaction="employee_delete_project.php">delete</button><br>
  <button type="submit" formaction="workon_update.php">edit</button><br>
</form>
<?


}
elseif (isset($_POST['showDependents']))
{
    $SIN = $_POST['SIN'];
    $NAME = $_POST['Name'];
    echo "<h1>Dependent(s) of $NAME </h1> <br>";

    $sqlQuery = "SELECT DSIN AS DependentSIN, DependentName, DependentGender, DependentBirth AS DependentBirthDate FROM 
HaveDependent NATURAL JOIN Dependent WHERE SIN='$SIN'";
    //echo $sqlQuery;
    $result = $DB->parse_query($sqlQuery);
    populate_table($result);
?>
Add/Modify/Remove dependent:<br>
<form action="index.html" method="get">
  <button type="submit" formaction="employee_add_dependent.php">add</button><br>
  <button type="submit" formaction="employee_delete_dependent.php">delete</button><br>
  <button type="submit" formaction="employee_edit_dependent.php">edit</button><br>
</form>
<?
}

elseif (isset($_POST['showSalary']))
{
    $SIN = $_POST['SIN'];
    $NAME = $_POST['Name'];
    echo "<h1>Total salary of $NAME based on hours spent on projects</h1> <br>";
    echo "Hour/Project Distribution Detail<br>";
    $sqlQuery = "SELECT WorkOn.PID, PName, Hour, WorkOnStartDate, WorkOnEndDate FROM WorkOn LEFT JOIN Project ON WorkOn.PID = Project.PID WHERE SIN=$SIN";
//    echo $sqlQuery;
    $result = $DB->parse_query($sqlQuery);

    populate_table($result);

    echo "<b>Total hour: </b>";
    $sqlQuery = "SELECT SUM(Hour) AS TotalHour FROM WorkOn WHERE SIN=$SIN";
//    echo $sqlQuery;
    $result = $DB->parse_query($sqlQuery);
    $row = mysqli_fetch_assoc($result);
    $hours = $row['TotalHour'];
    echo $hours . "<br>";

    echo "<b>Total salary: </b>";
    $sqlQuery = "SELECT Salary FROM Employee WHERE SIN=$SIN";
    $result = $DB->parse_query($sqlQuery);
    $row = mysqli_fetch_assoc($result);
    $salary = $row['Salary'];
    echo $salary."$/h X " . $hours . " = " . ($hours*$salary);

}
elseif (isset($_POST['delete'])){
    $SIN = $_POST['SIN'];
    $deleteQuery = "DELETE FROM Employee WHERE SIN=$SIN";
    $deleteResult = $DB->parse_query($deleteQuery);
    if ($deleteResult==1)
        echo "Successfully deleted Employee #$SIN<br>";
    else{
        echo "Failed to delete Employee #$SIN<br>";
        echo $deleteQuery;
        echo $deleteResult;
    }
}
elseif (isset($_POST['edit'])){
    $SIN = $_POST['SIN'];
    $Name = $_POST['Name'];
    $DID = $_POST['DID'];
    $BirthDate = $_POST['BirthDate'];
    $HomeAddress = $_POST['HomeAddress'];
    $PhoneNumber = $_POST['PhoneNumber'];
    $Gender = $_POST['Gender'];
    $Salary = $_POST['Salary'];
    $LeftOn = $_POST['LeftOn'];
    $SupervisorSIN = $_POST['SupervisorSIN'];

    $getExistingDIDsQuery = "SELECT DNumber FROM Department";
    $getExistingDIDsResult = $DB->parse_query($getExistingDIDsQuery);
    $getExistingSINQuery = "SELECT SIN FROM Employee WHERE SIN<>$SIN";
    $getExistingSINResult = $DB->parse_query($getExistingSINQuery);

    echo "
    <form action='' method='post'>
        SIN: <input type='text' name='SIN' value='$SIN' readonly><br>
        Name: <input type='text' name='Name' value='$Name'><br>
        Department Assigned to: <select name='DID'><option value=''></option>";
    while ($row = mysqli_fetch_assoc($getExistingDIDsResult)){
        $tempDID = $row['DNumber'];
        if ($tempDID==$DID)
            echo "<option value=$tempDID selected='selected'>$tempDID</option>";
        else
            echo "<option value=$tempDID>$tempDID</option>";
    }
    echo "</select><br>
        Birth Date: <input type='date' name='BirthDate' value='$BirthDate'><br>
        Home Address: <input type='text' name='HomeAddress' value='$HomeAddress'><br>
        Phone Number: <input type='number' name='PhoneNumber' value='$PhoneNumber' min=\"100\" max=\"999999999\"><br>
        Gender: <select name=\"Gender\">";
    if ($Gender=="?"){
        echo "<option value=\"?\" selected='selected'>?</option>
            <option value=\"F\">Female</option>
            <option value=\"M\">Male</option>
        </select><br>";
    }
    elseif ($Gender=="M") {
        echo "<option value=\"?\">?</option>
            <option value=\"F\">Female</option>
            <option value=\"M\" selected='selected'>Male</option>
        </select><br>";
    }
    elseif ($Gender=="F") {
        echo "<option value=\"?\">?</option>
            <option value=\"F\" selected='selected'>Female</option>
            <option value=\"M\">Male</option>
        </select><br>";
    }
    echo"
        Salary: <input type='number' name='Salary' value='$Salary' step=\"0.01\"><br>
        End Date: <input type='date' name='LeftOn' value='$LeftOn'><br>
        Supervisor SIN: <select name=\"SupervisorSIN\"><option value=''></option>";
    while ($row = mysqli_fetch_assoc($getExistingSINResult)){
        $tempSIN = $row['SIN'];
        if ($tempSIN==$SIN)
            echo "<option value=$tempSIN selected='selected'>$tempSIN</option>";
        else
            echo "<option value=$tempSIN>$tempSIN</option>";
    }
    echo "</select><br>
    <input type=\"submit\" value=\"Submit\" name=\"submitEdition\">
    </form>";

}
elseif (isset($_POST['submitEdition'])){
    $SIN = $_POST['SIN'];
    $Name = $_POST['Name'];
    $DID = $_POST['DID'];
    $BirthDate = $_POST['BirthDate'];
    $HomeAddress = $_POST['HomeAddress'];
    $PhoneNumber = $_POST['PhoneNumber'];
    $Gender = $_POST['Gender'];
    $Salary = $_POST['Salary'];
    $LeftOn = $_POST['LeftOn'];
    $SupervisorSIN = $_POST['SupervisorSIN'];

    if ($BirthDate=="")
        $BirthDate="0000-00-00";
    else
        $BirthDate="'$BirthDate'";
    if ($LeftOn=="")
        $LeftOn="NULL";
    else
        $LeftOn="'$LeftOn'";
    if ($PhoneNumber=="")
        $PhoneNumber="NULL";

    $employeeQuery = "UPDATE Employee SET Name='$Name', Birth='$BirthDate', HomeAddress='$HomeAddress', Phone=$PhoneNumber, Gender='$Gender', Salary=$Salary, EndDate=$LeftOn WHERE SIN=$SIN";



    $DB->parse_query("START TRANSACTION;");

    $ifAssigned = "SELECT SIN FROM Assign WHERE SIN=$SIN";
    if (mysqli_num_rows($DB->parse_query($ifAssigned))==0) {
        if ($DID != "") {
            $assignQuery = "INSERT INTO Assign(SIN, DNumber, AssignStartDate) VALUES ($SIN, $DID, NOW())";
            $assignResult = $DB->parse_query($assignQuery);
        } else
            $assignResult = 1;
    }
    else {
        if ($DID!=""){
            $assignQuery = "UPDATE Assign SET DNumber=$DID, AssignStartDate=NOW() WHERE SIN=$SIN";
            $assignResult = $DB->parse_query($assignQuery);
        } else
            $assignResult = 1;

    }

    $ifSupervised = "SELECT SubordinateSIN FROM SuperOf WHERE SubordinateSIN=$SIN";
    if (mysqli_num_rows($DB->parse_query($ifSupervised))==0) {
        if ($SupervisorSIN != "")
        {
            $supervisorQuery = "INSERT INTO SuperOf(SupervisorSIN, SubordinateSIN) VALUES ($SupervisorSIN, $SIN)";
            $supervisorResult = $DB->parse_query($supervisorQuery);
        }
        else
            $supervisorResult=1;
    }
    else
    {
        if ($SupervisorSIN != "")
        {
            $supervisorQuery = "UPDATE SuperOf SET SupervisorSIN=$SupervisorSIN WHERE SubordinateSIN=$SIN";
            $supervisorResult = $DB->parse_query($supervisorQuery);
        }
        else
            $supervisorResult=1;
    }


    echo "$employeeQuery<br>$assignQuery<br>$supervisorQuery<br>";

    $employeeResult = $DB->parse_query($employeeQuery);


    if ($employeeResult==1 && $assignResult==1 && $supervisorResult==1) {
        $DB->parse_query("COMMIT;");
        echo "<h3>Successfully submitted changes</h3>";
    }
    else{
        $DB->parse_query("ROLLBACK;");
        echo "<h3>Failed to submit changes, please verify your inputs</h3>";
        if ($employeeResult!=1){
            echo $employeeQuery;
            echo $employeeResult;
            echo "<br>Failed to submit changes for Employee general information (Name, Birth Date, Home Address, Phone Number, Gender, Salary, End Date<br>";}
        if ($assignResult!=1){
            echo $assignQuery;
            echo $assignResult;
            echo "<br>Failed to change Department assigned to $Name<br>";}
        if ($supervisorResult!=1){
            echo $supervisorQuery;
            echo $supervisorResult;
            echo "<br>Failed to change Supervisor of $Name to $SupervisorSIN<br>";}
    }
}

?>

</table>
