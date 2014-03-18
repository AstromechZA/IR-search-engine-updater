<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'config.php';

$con=mysqli_connect($host,$user,$password,$database);

// Check connection
if (mysqli_connect_errno())
{
    echo '{"status":1, "error": "MYSQL:' . mysqli_connect_error() . '"}';
}
else
{
    $result = mysqli_query($con, "SELECT * FROM ir_update_state ORDER BY ts DESC;");

    echo '{"status":0, "current":[';
    while($row = mysqli_fetch_array($result))
    {
        echo '{"state":"' . $row['state'] . '", "pid":' . $row['pid'] . '}';
    }
    echo ']}';
}
mysqli_close($con);

?>