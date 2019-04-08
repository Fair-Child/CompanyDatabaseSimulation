<html>
<head>
    <title>Project Management</title>
</head>
<body>
<a href="index.html">Back</a>
<h1>Project Management</h1>
<form action="project_add.php" method="get" target="_blank">
    <input type="submit" value="Add Project">
</form>
<h3>Please enter full information for accurate result, or partial information for general results</h3>
<form name="searchForm" action="" method="post">
    <input type="submit" value="Show All Projects" name="showAll">
    <input type="submit" value="List Unassigned Projects" name="listUnassigned">
    <input type="submit" value="List Projects without Leader" name="listNoPL"><br><br><br>
    PID: <input type="number" name="PID" placeholder="Enter exact PID" title="Enter exact PID"><br>
    Project Name: <input type="text" name="pname" placeholder="Enter exact or partial name" title="Enter exact or partial name"><br>
    Department In Charge ID: <input type="number" name="DID" size="100" placeholder="Enter exact ID of department in charge" title="Enter exact ID of department in charge"><br>
    Department In Charge Name: <input type="text" name="department" size="50" placeholder="Enter exact or partial name of department in charge"><br>
    Project Leader SIN: <input type="number" name="SIN" size="35" placeholder="Enter exact ID of project leader" title="Enter exact ID of project leader"><br>
    Project Leader Name: <input type="text" name="plname" size="35" placeholder="Enter exact or partial name of project leader"><br>
    Stage: <select name="stage">
        <option value=""></option>
        <option value="Preliminary">Preliminary</option>
        <option value="Intermediate">Intermediate</option>
        <option value="Advanced">Advanced</option>
        <option value="Complete">Complete</option>
    </select><br>
    Location: <input type="text" name="location" size="35" placeholder="Enter exact or partial name of location"><br>
    Number of Members: <input type="number" name="membersLow" placeholder="Enter lower bound" title="Enter lower bound">
    <input type="number" name="membersHigh" placeholder="Enter higher bound" title="Enter higher bound"><br>
    Total Work Hours: <input type="number" name="hourLow" placeholder="Enter lower bound" title="Enter lower bound">
    <input type="number" name="hourHigh" placeholder="Enter higher bound" title="Enter higher bound"><br>
    <input type="submit" value="Search" name="search" onsubmit=validateForm() ><br>

</form>
</body>
</html>
<?php
/**
 * Created by IntelliJ IDEA.
 * User: JY
 * Date: 2018-04-07
 * Time: 9:41 PM
 */

require_once ('DBConnector.php');

function populate_table($sql_result)
{
    $first = true;
    if (mysqli_num_rows($sql_result)==0)
    {
        echo "<h2>There exists no data for your query</h2>";
    }
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
        echo "<form action=\"ProjectSearches.php\" method=\"post\" target=\"_blank\">";
        echo "<td><input type=\"submit\" value=\"Edit\" name=\"edit\"></td>
        <td><input type=\"submit\" value=\"Delete\" name=\"delete\" onclick=\"return confirm('Are you sure?')\"></td>";
        foreach ($row as $k => $v) {
            echo "<td width='50%' style=\"word-wrap: break-word\"><input name='$k' value='$v' readonly></td>";
//                echo "";
            // echo $k."->".$v." ";
        }
        echo "<td><input type=\"submit\" value=\"Members\" name=\"showMembers\"></td>
        <td><input type=\"submit\" value=\"Expenses\" name=\"showExpenses\"></td>
    </form>";
        echo "</tr>";
        $first = false;
    }
    echo "</table>";
}

$DB = new DBConnector();
$DB->connect();

if (isset($_POST['showAll'])) {
    $sqlQuery = "SELECT Project.PID, PName, InCharge.DNumber AS DID, DName AS DepartmentInCharge, Stage, PL AS ProjectLeaderSIN, Name AS ProjectLeaderName,
(SELECT COUNT(*) FROM WorkOn WHERE WorkOn.PID=Project.PID) AS NoOfMembers,
(SELECT SUM(Hour) FROM WorkOn WHERE WorkOn.PID=Project.PID) AS TotalWorkHours, Address AS Location 
FROM Project LEFT JOIN InCharge ON Project.PID=InCharge.PID 
LEFT JOIN Department ON InCharge.DNumber=Department.DNumber
LEFT JOIN Employee ON PL=SIN LEFT JOIN ProjectLocation ON Project.PID=ProjectLocation.PID
ORDER BY PID";
    $result = $DB->parse_query($sqlQuery);

    populate_table($result);
}
elseif (isset($_POST['listUnassigned'])) {
    $sqlQuery = "SELECT Project.PID, PName, InCharge.DNumber AS DID, DName AS DepartmentInCharge, Stage, PL AS ProjectLeaderSIN, Name AS ProjectLeaderName,
(SELECT COUNT(*) FROM WorkOn WHERE WorkOn.PID=Project.PID) AS NoOfMembers,
(SELECT SUM(Hour) FROM WorkOn WHERE WorkOn.PID=Project.PID) AS TotalWorkHours, Address AS Location 
FROM Project LEFT JOIN InCharge ON Project.PID=InCharge.PID 
LEFT JOIN Department ON InCharge.DNumber=Department.DNumber
LEFT JOIN Employee ON PL=SIN LEFT JOIN ProjectLocation ON Project.PID=ProjectLocation.PID
WHERE Project.PID NOT IN (SELECT PID FROM InCharge)
ORDER BY PID";
    $result = $DB->parse_query($sqlQuery);

    populate_table($result);
}
elseif (isset($_POST['listNoPL'])) {
    $sqlQuery = "SELECT Project.PID, PName, InCharge.DNumber AS DID, DName AS DepartmentInCharge, Stage, PL AS ProjectLeaderSIN, Name AS ProjectLeaderName,
(SELECT COUNT(*) FROM WorkOn WHERE WorkOn.PID=Project.PID) AS NoOfMembers,
(SELECT SUM(Hour) FROM WorkOn WHERE WorkOn.PID=Project.PID) AS TotalWorkHours, Address AS Location 
FROM Project LEFT JOIN InCharge ON Project.PID=InCharge.PID 
LEFT JOIN Department ON InCharge.DNumber=Department.DNumber
LEFT JOIN Employee ON PL=SIN LEFT JOIN ProjectLocation ON Project.PID=ProjectLocation.PID
WHERE PL IS NULL 
ORDER BY PID";
    $result = $DB->parse_query($sqlQuery);

    populate_table($result);
}
elseif (isset($_POST['search'])) {
    $query = "SELECT Project.PID, PName, InCharge.DNumber AS DID, DName AS DepartmentInCharge, Stage, PL AS ProjectLeaderSIN, Name AS ProjectLeaderName,
(SELECT COUNT(*) FROM WorkOn WHERE WorkOn.PID=Project.PID) AS NoOfMembers,
(SELECT SUM(Hour) FROM WorkOn WHERE WorkOn.PID=Project.PID) AS TotalWorkHours, Address AS Location 
FROM Project LEFT JOIN InCharge ON Project.PID=InCharge.PID 
LEFT JOIN Department ON InCharge.DNumber=Department.DNumber
LEFT JOIN Employee ON PL=SIN LEFT JOIN ProjectLocation ON Project.PID=ProjectLocation.PID
WHERE ";

    $PID = $_POST['PID'];
    $pname = $_POST['pname'];
    $DID = $_POST['DID'];
    $department = $_POST['department'];
    $SIN = $_POST['SIN'];
    $plname = $_POST['plname'];
    $stage = $_POST['stage'];
    $location = $_POST['location'];
    $membersLow = $_POST['membersLow'];
    $membersHigh = $_POST['membersHigh'];
    $hourLow = $_POST['hourLow'];
    $hourHigh = $_POST['hourHigh'];

    if ($PID=="" && $pname=="" && $DID=="" && $department=="" && $SIN==""
        && $plname=="" && $stage=="" && $location=="" && $membersLow==""
        && $membersHigh=="" && $hourLow=="" && $hourHigh=="")
        die("Please fill at least 1 field");
    else{
        $searchCondition = "";
        $firstAddition = true;
        if ($PID!=""){
            if ($firstAddition==false)
                $searchCondition.=" AND PID=$PID";
            else{
                $searchCondition.=" PID=$PID";
                $firstAddition=false;
            }
        }
        if ($pname!=""){
            if ($firstAddition==false)
                $searchCondition.=" AND PName LIKE '%$pname%'";
            else{
                $searchCondition.=" PName LIKE '%$pname%'";
                $firstAddition=false;
            }
        }
        if ($DID!=""){
            if ($firstAddition==false)
                $searchCondition.=" AND InCharge.DNumber=$DID";
            else{
                $searchCondition.=" InCharge.DNumber=$DID";
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
        if ($SIN!=""){
            if ($firstAddition==false)
                $searchCondition.=" AND PL=$SIN";
            else{
                $searchCondition.=" PL=$SIN";
                $firstAddition=false;
            }
        }
        if ($plname!=""){
            if ($firstAddition==false)
                $searchCondition.=" AND Name LIKE '%$plname%'";
            else{
                $searchCondition.=" Name LIKE '%$plname%'";
                $firstAddition=false;
            }
        }
        if ($stage!=""){
            if ($firstAddition==false)
                $searchCondition.=" AND Stage='$stage'";
            else{
                $searchCondition.=" Stage='$stage'";
                $firstAddition=false;
            }
        }
        if ($location!=""){
            if ($firstAddition==false)
                $searchCondition.=" AND Address LIKE '%$location%'";
            else{
                $searchCondition.=" Address LIKE '%$location%'";
                $firstAddition=false;
            }
        }
        if ($membersLow!=""){
            if ($firstAddition==false)
                $searchCondition.=" AND (SELECT COUNT(*) FROM WorkOn WHERE WorkOn.PID=Project.PID)>=$membersLow";
            else{
                $searchCondition.=" (SELECT COUNT(*) FROM WorkOn WHERE WorkOn.PID=Project.PID)>=$membersLow";
                $firstAddition=false;
            }
        }
        if ($membersHigh!=""){
            if ($firstAddition==false)
                $searchCondition.=" AND (SELECT COUNT(*) FROM WorkOn WHERE WorkOn.PID=Project.PID)<=$membersHigh";
            else{
                $searchCondition.=" (SELECT COUNT(*) FROM WorkOn WHERE WorkOn.PID=Project.PID)<=$membersHigh";
                $firstAddition=false;
            }
        }
        if ($hourLow!=""){
            if ($firstAddition==false)
                $searchCondition.=" AND (SELECT SUM(Hour) FROM WorkOn WHERE WorkOn.PID=Project.PID)>=$hourLow";
            else{
                $searchCondition.=" (SELECT SUM(Hour) FROM WorkOn WHERE WorkOn.PID=Project.PID)>=$hourLow";
                $firstAddition=false;
            }
        }
        if ($hourHigh!=""){
            if ($firstAddition==false)
                $searchCondition.=" AND (SELECT SUM(Hour) FROM WorkOn WHERE WorkOn.PID=Project.PID)<=$hourHigh";
            else{
                $searchCondition.=" (SELECT SUM(Hour) FROM WorkOn WHERE WorkOn.PID=Project.PID)<=$hourHigh";
                $firstAddition=false;
            }
        }
        $query.=$searchCondition." ORDER BY PID";

        echo $query;
        $result = $DB->parse_query($query);
        populate_table($result);
    }
}
?>