<html>
<head>
    <title>Department Management</title>
</head>
<body>
<a href="index.html">Back</a>
<h1>Department Management</h1>

<h3>Please enter full information for accurate result, or partial information for general results</h3>

<form action="department_add.php" method="get" target="_blank">
    <input type="submit" value="Add department">
</form>

<form name="searchForm" action="" method="post">
    <input type="submit" value="Show All Department" name="showAll">  <input type="submit" value="Show Departments without Manager" name="withoutManager" onsubmit=validateForm() ><br><br>
    <h3>Search by:</h3>
    Department ID: <input type="number" name="DID" placeholder="Enter exact department ID" title="Enter exact department ID"><br>
    Department: <input type="text" name="department" size="35" placeholder="Enter exact or partial department name" title="Enter exact or partial department name"><br>
    Manager SIN: <input type="text" name="manager" placeholder="Enter exact Manager SIN"><br>
    Number of Members: <input type="number" name="membersLow" placeholder="Enter lower bound" title="Enter lower bound">
    <input type="number" name="membersHigh" placeholder="Enter higher bound" title="Enter higher bound"><br>
    <input type="submit" value="Search" name="search" onsubmit=validateForm() ><br>
</form>
</body>
</html>
<?php


require_once ('DBConnector.php');

function populate_table($sql_result){
    $first = true;

    if (mysqli_num_rows($sql_result)==0)
    {
        echo "<h2>There exists no data for your query</h2>";
    }
    else {
        echo "<table border='1'>";
        while ($row = mysqli_fetch_assoc($sql_result)) {

            if ($first) {
                echo "<tr><th></th><th></th>";
                foreach ($row as $k => $v) {
                    echo "<th>" . $k . "</th>";
                }
                echo "</tr>";
            }
            echo "<tr>";
            echo "<form action=\"DepartmentSearches.php\" method=\"post\" target=\"_blank\">";
            echo "<td><input type=\"submit\" value=\"Edit\" name=\"edit\"></td>
            <td><input type=\"submit\" value=\"Delete\" name=\"delete\" onclick=\"return confirm('Are you sure?')\"></td>";
            foreach ($row as $k => $v) {
                echo "<td><input name='$k' value='$v' readonly></td>";
//                echo "";
                // echo $k."->".$v." ";
            }
            echo "<td><input type=\"submit\" value=\"Show employees\" name=\"showEmployees\"></td>
    <td><input type=\"submit\" value=\"Projects\" name=\"showProjects\"></td>
    <td><input type=\"submit\" value=\"Expenses\" name=\"showExpenses\"></td>
    <td><input type=\"submit\" value=\"Locations\" name=\"showLocations\"></td>
    </form>";
            echo "</tr>";
            $first = false;
        }
        echo "</table>";
    }
}

$DB = new DBConnector();
$DB->connect();

if (isset($_POST['showAll'])) {

    $sqlQuery = "SELECT Department.DNumber AS DepartmentNumber, DName AS DepartmentName, IsManager.SIN AS ManagerSIN, Name AS ManagerName, ManagerStartDate, ManagerEndDate,
(SELECT COUNT(*) FROM Assign WHERE Assign.DNumber=Department.DNumber) AS NoOfMembers
FROM Department LEFT JOIN IsManager ON Department.DNumber=IsManager.DNumber LEFT JOIN Employee ON IsManager.SIN=Employee.SIN ORDER BY DepartmentNumber";
    $result = $DB->parse_query($sqlQuery);
    populate_table($result);
}

elseif (isset($_POST['withoutManager'])){
    $sqlQuery = "SELECT Department.DNumber AS DepartmentNumber, DName AS DepartmentName, IsManager.SIN AS ManagerSIN, Name AS ManagerName, ManagerStartDate, ManagerEndDate,
(SELECT COUNT(*) FROM Assign WHERE Assign.DNumber=Department.DNumber) AS NoOfMembers
FROM Department LEFT JOIN IsManager ON Department.DNumber=IsManager.DNumber LEFT JOIN Employee ON IsManager.SIN=Employee.SIN 
WHERE Department.DNumber NOT IN (SELECT DNumber FROM IsManager)
ORDER BY DepartmentNumber";

    $result = $DB->parse_query($sqlQuery);
    populate_table($result);
}
elseif (isset($_POST['search'])){

    $query = "SELECT Department.DNumber AS DepartmentNumber, DName AS DepartmentName, IsManager.SIN AS ManagerSIN, Name AS ManagerName, ManagerStartDate, ManagerEndDate,
(SELECT COUNT(*) FROM Assign WHERE Assign.DNumber=Department.DNumber) AS NoOfMembers
FROM Department LEFT JOIN IsManager ON Department.DNumber=IsManager.DNumber LEFT JOIN Employee ON IsManager.SIN=Employee.SIN WHERE ";

    $DNumber = $_POST['DID'];
    $department = $_POST['department'];
    $managerSIN = $_POST['manager'];
    $membersLow = $_POST['membersLow'];
    $membersHigh = $_POST['membersHigh'];

    if ($DNumber=="" && $department=="" && $managerSIN=="" && $membersLow=="" && $membersHigh=="")
        die("Please fill at least 1 field");
    else{
        $searchCondition = "";
        $firstAddition = true;
        if ($DNumber!=""){
            if ($firstAddition==false)
                $searchCondition.=" AND Department.DNumber=$DNumber";
            else{
                $searchCondition.=" Department.DNumber=$DNumber";
                $firstAddition=false;
            }
        }
        if ($department!=""){
            if ($firstAddition==false)
                $searchCondition.=" AND DName LIKE '%$department%'";
            else{
                $searchCondition.=" DName LIKE '%$department%'";
                $firstAddition=false;
            }
        }
        if ($managerSIN!=""){
            if ($firstAddition==false)
                $searchCondition.=" AND IsManager.SIN=$managerSIN";
            else{
                $searchCondition.=" IsManager.SIN=$managerSIN";
                $firstAddition=false;
            }
        }
        if ($membersLow!=""){
            if ($firstAddition==false)
                $searchCondition.=" AND (SELECT COUNT(*) FROM Assign WHERE Assign.DNumber=Department.DNumber)>=$membersLow";
            else{
                $searchCondition.=" (SELECT COUNT(*) FROM Assign WHERE Assign.DNumber=Department.DNumber)>=$membersLow";
                $firstAddition=false;
            }
        }
        if ($membersHigh!=""){
            if ($firstAddition==false)
                $searchCondition.=" AND (SELECT COUNT(*) FROM Assign WHERE Assign.DNumber=Department.DNumber)<=$membersHigh";
            else{
                $searchCondition.=" (SELECT COUNT(*) FROM Assign WHERE Assign.DNumber=Department.DNumber)<=$membersHigh";
                $firstAddition=false;
            }
        }
        $query.=$searchCondition;

        echo $query;
        $result = $DB->parse_query($query);
        populate_table($result);
    }
}
?>