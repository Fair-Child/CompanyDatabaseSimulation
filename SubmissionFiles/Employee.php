<html>
<head>
    <title>Employee Management</title>
    <meta charset="UTF-8">
</head>
<body>
<a href="index.html">Back</a>
<h1>Employee Management</h1>
<form action="employee_add.php" method="get" target="_blank">
    <input type="submit" value="Add Employee">
</form>
<h3>Please enter full information for accurate result, or partial information for general results</h3>
<form name="searchForm" action="" method="post">
    <input type="submit" value="Show All Employees" name="showAll">
    <input type="submit" value="List Unassigned Employees" name="listUnassigned">
    <input type="submit" value="List Employees without Supervisor" name="listUnsupervised"><br><br><br>
    SIN: <input type="number" name="SIN" placeholder="Enter exact SIN" title="Enter exact SIN" min="100000000" max="999999999"><br>
    Name: <input type="text" name="name" placeholder="Enter exact or partial name" title="Enter exact or partial name"><br>
    Department ID: <input type="number" name="DID" placeholder="Enter exact department ID" title="Enter exact department ID"><br>
    Department: <input type="text" name="department" size="35" placeholder="Enter exact or partial department name" title="Enter exact or partial department name"><br>
    Gender: <select name="gender">
                <option value=""></option>
                <option value="?">?</option>
                <option value="F">F</option>
                <option value="M">M</option>
            </select><br>
    Salary: <input type="number" name="salaryLow" placeholder="Enter lower bound" title="Enter lower bound">
            <input type="number" name="salaryHigh" placeholder="Enter higher bound" title="Enter higher bound"><br>
    <input type="checkbox" name="isManager" value="manager"> Is Manager<br>
    <input type="checkbox" name="isPL" value="pl"> Is Project Leader<br>
    <input type="submit" value="Search" name="search" onsubmit=validateForm() ><br>

</form>

</body>
</html>
<?php
/**
 * Created by IntelliJ IDEA.
 * User: JY
 * Date: 2018-04-07
 * Time: 5:54 PM
 */
require_once ('DBConnector.php');

function populate_table($sql_result)
{
    $first = true;

    echo "<table width='10%' border='1'>";
    $counter = 0;
    while ($row = mysqli_fetch_assoc($sql_result)) {
        if ($first) {
            echo "<tr><th></th><th></th>";
            foreach ($row as $k => $v) {
                if ($counter<2) {
                    echo "<th id='fixed' width='50%' style=\"word-wrap: break-word\">" . $k . "</th>";
                    $counter++;
                }
                else
                    echo "<th width='50%' style=\"word-wrap: break-word\">" . $k . "</th>";
            }
            echo "</tr>";
        }
        echo "<tr>";
        echo "<form action=\"EmployeeSearches.php\" method=\"post\" target=\"_blank\">";
        echo "<td><input type=\"submit\" value=\"Edit\" name=\"edit\"></td>
        <td><input type=\"submit\" value=\"Delete\" name=\"delete\" onclick=\"return confirm('Are you sure?')\"></td>";
        foreach ($row as $k => $v) {
            echo "<td width='50%' style=\"word-wrap: break-word\"><input name='$k' value='$v' readonly></td>";
//                echo "";
            // echo $k."->".$v." ";
        }
        echo "<td><input type=\"submit\" value=\"Subordinates\" name=\"showSubordinates\"></td>
    <td><input type=\"submit\" value=\"Projects\" name=\"showProjects\"></td>
    <td><input type=\"submit\" value=\"Salary and Hours\" name=\"showSalary\"></td>
    <td><input type=\"submit\" value=\"Dependents\" name=\"showDependents\"></td>
    </form>";
        echo "</tr>";
        $first = false;
    }
    echo "</table>";
}

$DB = new DBConnector();
$DB->connect();

if (isset($_POST['showAll'])) {
//    die("showall");
    $sqlQuery = "SELECT SIN, Name, DNumber AS DID, DName AS Department, AssignStartDate AS AssignedOn, SupervisorSIN, Birth AS BirthDate, HomeAddress,
        Phone AS PhoneNumber, Gender, Salary, StartDate AS HiredOn, EndDate AS LeftOn FROM Employee NATURAL JOIN Assign
        NATURAL JOIN Department LEFT JOIN SuperOf ON SIN=SuperOf.SubordinateSIN 
        UNION
        SELECT SIN, Name, -1, 'UNASSIGNED', '0000-00-00', SupervisorSIN, Birth AS BirthDate, HomeAddress,
        Phone AS PhoneNumber, Gender, Salary, StartDate AS HiredOn, EndDate AS LeftOn FROM Employee LEFT JOIN SuperOf ON SIN=SuperOf.SubordinateSIN 
        WHERE SIN NOT IN
        (SELECT SIN FROM Employee NATURAL JOIN Assign NATURAL JOIN Department)
        ORDER BY SIN";
    $result = $DB->parse_query($sqlQuery);
    populate_table($result);
}
if (isset($_POST['listUnassigned'])) {
    $sqlQuery = "
        SELECT SIN, Name, 'UNASSIGNED', '0000-00-00', SupervisorSIN, Birth AS BirthDate, HomeAddress,
        Phone AS PhoneNumber, Gender, Salary, StartDate AS HiredOn, EndDate AS LeftOn FROM Employee LEFT JOIN SuperOf ON SIN=SubordinateSIN
        WHERE SIN NOT IN
        (SELECT SIN FROM Employee NATURAL JOIN Assign NATURAL JOIN Department)
        ORDER BY SIN";
    $result = $DB->parse_query($sqlQuery);
    populate_table($result);
}
elseif (isset($_POST['listUnsupervised'])) {
//    die("showall");
    $sqlQuery = "
        SELECT SIN, Name, DNumber AS DID, DName AS Department, AssignStartDate AS AssignedOn, Birth AS BirthDate, HomeAddress,
        Phone AS PhoneNumber, Gender, Salary, StartDate AS HiredOn, EndDate AS LeftOn FROM Employee NATURAL JOIN Assign
        NATURAL JOIN Department WHERE SIN NOT IN (SELECT SubordinateSIN FROM SuperOf)
        ORDER BY SIN";
    $result = $DB->parse_query($sqlQuery);
    populate_table($result);
}
elseif (isset($_POST['search'])) {
    $first = "SELECT SIN, Name, DNumber as DID, DName AS Department, AssignStartDate AS AssignedOn,Birth AS BirthDate, HomeAddress,
        Phone AS PhoneNumber, Gender, Salary, StartDate AS HiredOn, EndDate AS LeftOn FROM Employee NATURAL JOIN Assign
        NATURAL JOIN Department WHERE";
    $last = "SELECT SIN, Name, 'UNASSIGNED', '0000-00-00',Birth AS BirthDate, HomeAddress,
        Phone AS PhoneNumber, Gender, Salary, StartDate AS HiredOn, EndDate AS LeftOn FROM Employee WHERE SIN NOT IN
        (SELECT SIN FROM Employee NATURAL JOIN Assign NATURAL JOIN Department) AND";

    $SIN = $_POST['SIN'];
    $name = $_POST['name'];
    $DID = $_POST['DID'];
    $department = $_POST['department'];
    $gender = $_POST['gender'];
    $salaryLow = $_POST['salaryLow'];
    $salaryHigh = $_POST['salaryHigh'];
    $isManager = $_POST['isManager'];
    $isPL = $_POST['isPL'];

//    echo "$isManager, $isPL<br>";

    if ($SIN=="" && $name=="" && $DID=="" && $department=="" && $gender=="" && $salaryLow=="" && $salaryHigh=="" && $isManager=="" && $isPL=="")
        die("Please fill at least 1 field");
    else {
        $searchCondition = "";
        $firstAddition = true;
        if ($SIN != "") {
            if ($firstAddition == false)
                $searchCondition .= " AND SIN=$SIN";
            else {
                $searchCondition .= " SIN=$SIN";
                $firstAddition = false;
            }
        }
        if ($name != "") {
            if ($firstAddition == false)
                $searchCondition .= " AND Name LIKE '%$name%'";
            else {
                $searchCondition .= " Name LIKE '%$name%'";
                $firstAddition = false;
            }
        }
        if ($DID != "") {
            if ($firstAddition == false)
                $searchCondition .= " AND DNumber=$DID";
            else {
                $searchCondition .= " DNumber=$DID";
                $firstAddition = false;
            }
        }
        if ($department != "") {
            if ($firstAddition == false)
                $searchCondition .= " AND Department.DName LIKE '%$department%'";
            else {
                $searchCondition .= " Department.DName LIKE '%$department%'";
                $firstAddition = false;
            }
        }
        if ($gender != "") {
            if ($firstAddition == false)
                $searchCondition .= " AND Gender='$gender'";
            else {
                $searchCondition .= " Gender='$gender'";
                $firstAddition = false;
            }
        }
        if ($salaryLow != "") {
            if ($firstAddition == false)
                $searchCondition .= " AND Salary>=$salaryLow";
            else {
                $searchCondition .= " Salary>=$salaryLow";
                $firstAddition = false;
            }
        }
        if ($salaryHigh != "") {
            if ($firstAddition == false)
                $searchCondition .= " AND Salary<=$salaryHigh";
            else {
                $searchCondition .= " Salary<=$salaryHigh";
                $firstAddition = false;
            }
        }
        if ($isManager != "") {
            if ($firstAddition == false)
                $searchCondition .= " AND SIN IN (SELECT SIN FROM IsManager)";
            else {
                $searchCondition .= " SIN IN (SELECT SIN FROM IsManager)";
                $firstAddition = false;
            }
        }
        if ($isPL != "") {
            if ($firstAddition == false)
                $searchCondition .= " AND SIN IN (SELECT PL FROM InCharge)";
            else {
                $searchCondition .= " SIN IN (SELECT PL FROM InCharge)";
                $firstAddition = false;
            }
        }

        $first .= $searchCondition;
        $last .= $searchCondition;

        echo $first."<br>";
        echo $last."<br>";

        $firstResult = $DB->parse_query($first);
        $lastResult = $DB->parse_query($last);


        if ($firstResult==1){
            populate_table($firstResult);
        }
        if($lastResult==1){
            populate_table($lastResult);
        }

        if (($firstResult==0 && $lastResult==0) || (mysqli_num_rows($firstResult)==0 && mysqli_num_rows($lastResult)==0))
        {
            echo "<h2>There exists no data for your query</h2>";
        }
    }
}
?>