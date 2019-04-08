<?php

require_once ('DBConnector.php');
$DB = new DBConnector();
$DB->connect();
$query = "SELECT DSIN, DependentName FROM Dependent";

$result = $DB->parse_query($query);


if (mysqli_num_rows($result)==0)
{
    echo "<h2>There exists no data for your query</h2>";
}
else {
    echo "<table border='1'>";

    $first = true;

    while ($row = mysqli_fetch_assoc($result)) {
        if ($first) {
            echo "";
        }
        echo "INSERT INTO Dependent (DSIN, DependentName) VALUES(";
        $count = 0;
        foreach ($row as $k => $v) {
            if ($count==2)
                break;
            if ($count==1)
                echo '\''.$v.'\'';
            if ($count==0)
                echo ",";
//                echo "";
            // echo $k."->".$v." ";
            $count++;
        }
        echo ");<br>";
        $first = false;
    }
}


?>



