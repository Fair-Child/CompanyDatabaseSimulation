<button type="button"
        onclick="window.open('', '_self', ''); window.close();">Close</button><br><br>
<table>

<?php
/**
 * Created by IntelliJ IDEA.
 * User: JY
 * Date: 2018-04-07
 * Time: 4:16 PM
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

if (isset($_POST['showEmployees']))
{
    $DNumber = $_POST['DepartmentNumber'];
    $DName = $_POST['DepartmentName'];
    echo "<h1>SIN of employees working under Department $DName </h1> <br>";

    $sqlQuery = "SELECT * FROM Assign NATURAL JOIN Employee WHERE DNumber='$DNumber'";
    $result = $DB->parse_query($sqlQuery);
    populate_table($result);

    echo "
    <form action=\"index.html\" method=\"get\">
  <button type=\"submit\" formaction=\"assign_add.php\">add</button><br>
  <button type=\"submit\" formaction=\"assign_delete.php\">delete</button><br>
  <button type=\"submit\" formaction=\"assign_update.php\">edit</button><br>
</form>
    ";


}
elseif (isset($_POST['delete'])){
    $DNumber = $_POST['DepartmentNumber'];
    $sqlQuery = "DELETE FROM Department WHERE DNumber=$DNumber";
    $result = $DB->parse_query($sqlQuery);
    if ($result==1)
        echo "Successfully deleted Department #$DNumber";
    echo "";
}
elseif (isset($_POST['edit'])){
    $DNumber = $_POST['DepartmentNumber'];
    $DName = $_POST['DepartmentName'];
    $ManagerSIN = $_POST['ManagerSIN'];
    $ManagerEndDate = $_POST['ManagerEndDate'];

    $getExistingMembers = "SELECT SIN FROM Assign WHERE DNumber=$DNumber";
    $getExistingMembersResult = $DB->parse_query($getExistingMembers);

    echo "
    <form action='' method='post'>
        Department Number: <input type='text' name='DID' value='$DNumber' readonly><br>
        Department Name: <input type='text' name='DName' value='$DName'><br>
        Department Manager SIN: <select name='ManagerSIN'><option value=''></option>";
    while ($row = mysqli_fetch_assoc($getExistingMembersResult)){
        $tempSIN = $row['SIN'];
        if ($tempSIN==$ManagerSIN)
            echo "<option value=$tempSIN selected='selected'>$tempSIN</option>";
        else
            echo "<option value=$tempSIN>$tempSIN</option>";
    }
    echo "</select><br>
        Manager End Date: <input type='date' name='ManagerEndDate' value='$ManagerEndDate'><br>
        <input type=\"submit\" value=\"Submit\" name=\"submitEdition\">
        </form>
    ";
}
elseif (isset($_POST['submitEdition'])){
    $DNumber = $_POST['DID'];
    $DName = $_POST['DName'];
    $ManagerSIN = $_POST['ManagerSIN'];
    $ManagerEndDate = $_POST['ManagerEndDate'];

    if ($ManagerEndDate==""){
        $ManagerEndDate="NULL";
    }
    else{
        $ManagerEndDate="'$ManagerEndDate'";
    }

    $departmentQuery = "UPDATE Department SET DName='$DName' WHERE DNumber=$DNumber";

    $DB->parse_query("START TRANSACTION;");
    if ($ManagerSIN!=""){//if user entered something not null in manager sin section
        $isManagerQuery = "SELECT SIN FROM IsManager WHERE DNumber=$DNumber";//check if department chosen by user already has a manager
        if (mysqli_num_rows($DB->parse_query($isManagerQuery))==0){//if no result, insert value into table
            $managerQuery = "INSERT INTO IsManager(SIN, DNumber, ManagerStartDate, ManagerEndDate) VALUES ($ManagerSIN, $DNumber, NOW(), $ManagerEndDate)";
            $managerResult = $DB->parse_query($managerQuery);
        }
        else{//if result shows up, then user are modifying the department manager to someone new
            $managerQuery = "UPDATE IsManager SET SIN=$ManagerSIN, ManagerStartDate=NOW(), ManagerEndDate=$ManagerEndDate WHERE DNumber=$DNumber";
            $managerResult=$DB->parse_query($managerQuery);
        }
    }
    else{//if user chose null value for manager sin section
        $managerResult = 1;//still set to 1 so that transaction goes through
    }

    $departmentResult = $DB->parse_query($departmentQuery);
    if ($departmentResult==1 && $managerResult==1){
        $DB->parse_query("COMMIT;");
        echo "<br>Successfully updated Department #$DNumber information<br>";
    }
    else{
        $DB->parse_query("ROLLBACK;");
        echo "<br>Failed to update Department #$DNumber information, please validate your inputs<br>";
        if ($departmentResult!=1){
            echo $departmentQuery."<br>";
        }
        if ($managerResult!=1){
            echo $managerQuery;
        }
    }

}
elseif (isset($_POST['showProjects']))
{
    $DNumber = $_POST['DepartmentNumber'];
    $DName = $_POST['DepartmentName'];
    echo "<h1>Project under charge of Department $DName </h1> <br>";

    $sqlQuery = "SELECT * FROM InCharge NATURAL JOIN Project WHERE DNumber='$DNumber'";
    $result = $DB->parse_query($sqlQuery);
    populate_table($result);

    echo "
    <form action=\"index.html\" method=\"get\">
  <button type=\"submit\" formaction=\"incharge_add.php\">add</button><br>
  <button type=\"submit\" formaction=\"incharge_delete.php\">delete</button><br>
  <button type=\"submit\" formaction=\"incharge_update.php\">edit</button><br>
</form>
    ";
}
elseif (isset($_POST['showLocations']))
{
    $DNumber = $_POST['DepartmentNumber'];
    $DName = $_POST['DepartmentName'];
    echo "<h1>Locations occupied by Department $DName </h1> <br>";

    $sqlQuery = "SELECT Address as Location FROM DepartmentLocation WHERE DNumber='$DNumber'";
    $result = $DB->parse_query($sqlQuery);
    populate_table($result);

    echo "<br>Delete Location from Department:<br>
<form action='' method='post'>
            Department: <input type='text' name='DID' value='$DNumber' readonly><br>
            Location: <select name='address'><option value=''></option>";
    $result = $DB->parse_query($sqlQuery);
    while ($row = mysqli_fetch_assoc($result)){
        $address = $row['Location'];
        echo "<option value=$address>$address</option>";
    }
    echo "</select><br>

    <input type='submit' name='deleteLocation' value='Delete Location'><br><br>
    Location: <select name='newLocation'><option value=''></option>";
    $result = $DB->parse_query("SELECT * FROM Location");
    while ($row = mysqli_fetch_assoc($result)){
        $address = $row['Address'];
        echo "<option value=$address>$address</option>";
    }
    echo "
    <input type='submit' name='addLocation' value='Add Location'><br>
    </form>
";
}
elseif (isset($_POST['deleteLocation'])){
    $address = $_POST['address'];
    $DID = $_POST['DID'];
    $sqlQuery = "DELETE FROM DepartmentLocation WHERE DNumber=$DID AND Address='$address'";
    if($DB->parse_query($sqlQuery)==1){
        echo "<br>Successfully deleted $address from Department $DID<br>";
    }
    else{
        echo "<br>Failed to delete $address from Department $DID<br>$sqlQuery<br>";
    }
}
elseif (isset($_POST['addLocation'])){
    $address = $_POST['newLocation'];
    $DID = $_POST['DID'];
    $sqlQuery = "INSERT INTO DepartmentLocation(DNumber, Address) VALUES ($DID, '$address')";
    if($DB->parse_query($sqlQuery)==1){
        echo "<br>Successfully added $address from Department $DID<br>";
    }
    else{
        echo "<br>Failed to add $address from Department $DID<br>$sqlQuery<br>";
    }
}
elseif (isset($_POST['showExpenses']))
{
    $DNumber = $_POST['DepartmentNumber'];
    $DName = $_POST['DepartmentName'];
    echo "<h1>Total Expenses of Department $DName </h1> <br>";

    echo "Hour distribution by Projects";
//    $sqlQuery = "SELECT SIN, Name, Salary, PID, PName, Hour FROM Assign NATURAL JOIN Employee
//NATURAL JOIN WorkOn NATURAL JOIN Project WHERE DNumber=$DNumber";
    $sqlQuery = "SELECT PID, PName, SUM(Hour) AS TotalHour FROM InCharge NATURAL JOIN Project
NATURAL JOIN WorkOn WHERE DNumber=$DNumber GROUP BY PID";
    echo $sqlQuery;
    $result = $DB->parse_query($sqlQuery);
    populate_table($result);

    echo "<h3>Calculation of Total Expenses</h3>";

    $totalExpense = 0;
    $result = $DB->parse_query($sqlQuery);
    if (mysqli_num_rows($result)>0){
        while ($row = mysqli_fetch_assoc($result)){
            $PID = $row['PID'];
            echo "<b>Calculation of expenses for Project " . $PID . ":</b><br>";
            $sqlQuery = "SELECT SIN, Hour FROM WorkOn WHERE PID=$PID";
            $queryResult = $DB->parse_query($sqlQuery);
            while ($sqlRow = mysqli_fetch_assoc($queryResult)){
                $SIN = $sqlRow['SIN'];
                $hour = $sqlRow['Hour'];
                $salary = mysqli_fetch_assoc($DB->parse_query("SELECT Salary from Employee WHERE SIN=$SIN"))['Salary'];

                echo "SIN: $SIN, Salary: $salary, Hours: $hour <br>";
                $employeeTotalExpense = $salary*$Hour;
                $totalExpense+=$employeeTotalExpense;
                echo "<u>Total salary expense allocated for Employee $SIN: $salary X $hour = $employeeTotalExpense</u><br>";
            }


        }
    }
    echo "<br><h3>Total Department Expense: " . $totalExpense . "</h3>";
}
else
    echo 'Something happened and we dont even know what';
?>
</table>
