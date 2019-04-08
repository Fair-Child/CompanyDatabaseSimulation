<a href="index.html">Back</a><br>
<form method="get" action="">
    <input type="submit" name="addLocation" value="Add New Location"><br><br><br>
</form>
<!--<input type="button" value="Refresh Page" onClick="window.location.reload()"><br>-->
<?php
/**
 * Created by IntelliJ IDEA.
 * User: JY
 * Date: 2018-04-11
 * Time: 12:35 AM
 */

require_once ("DBConnector.php");

$DB = new DBConnector();
$DB->connect();

function populate_table($sql_result)
{
    $first = true;

    echo "<table width='10%' border='1'>";
    $counter = 0;
    while ($row = mysqli_fetch_assoc($sql_result)) {
        if ($first) {
            echo "<tr><th></th>";
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
        echo "<form action=\"\" method=\"post\">";
        echo "
        <td><input type=\"submit\" value=\"Delete\" name=\"delete\" onclick=\"return confirm('Are you sure?')\"></td>";
        foreach ($row as $k => $v) {
            echo "<td width='50%' style=\"word-wrap: break-word\"><input name='$k' value='$v' readonly></td>";
//                echo "";
            // echo $k."->".$v." ";
        }
        echo "
    </form>";
        echo "</tr>";
        $first = false;
    }
    echo "</table>";
}

$getLocations="SELECT Address FROM Location";

$result = $DB->parse_query($getLocations);
populate_table($result);

if (isset($_GET['addLocation'])) {
    //die("add location");
    echo "
    <form action='' method='post'>
    Location Address: <input name='locationAddress' type='text'><br>
    <input type=\"submit\" name=\"submitLocation\" value=\"Submit Location\"><br>
</form>
    ";
}
if (isset($_POST['submitLocation'])) {
    $address = $_POST['locationAddress'];
    $sqlQuery = "INSERT INTO Location(Address) VALUES ('$address')";
    echo $sqlQuery;
    $result = $DB->parse_query($sqlQuery);
    if ($result==1)
        echo "Successfully added new Location $address";
    else{
        echo "Failed to add new Location<br>$sqlQuery<br>";
    }
}
elseif (isset($_POST['delete'])) {
    $address = $_POST['Address'];
    $sqlQuery = "DELETE FROM Location WHERE Address='$address'";
    if ($DB->parse_query($sqlQuery)==1){
        echo "<br>Successfully deleted $address from Locations<br>";
    }
    else{
        echo "<br>Failed to delete $address from Locations<br>$sqlQuery";
    }
}