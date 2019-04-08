<?php
/**
 * Created by IntelliJ IDEA.
 * User: JY
 * Date: 2018-03-24
 * Time: 12:41 PM
 */

class DBConnector
{
    var $username = "oyc353_4";
    var $password = "Coyote18";
    var $servername = "oyc353.encs.concordia.ca";
    var $dbname = "oyc353_4";
    var $connection_status;

    var $connection;

    function connect(){
        $this->connection = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        if ($this->connection->connect_error){
            $this->connection_status = false;
        }
        else
            $this->connection_status = true;
    }

    function parse_query($sqlQuery){
        if ($this->connection_status){
            $result = $this->connection->query($sqlQuery);
            return $result;
        }
        else
            return "Unsuccessful DB request";
    }
}