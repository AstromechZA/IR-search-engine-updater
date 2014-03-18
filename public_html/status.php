<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'config.php';

$con=mysqli_connect($host,$user,$password,$database);

// Check connection
if (mysqli_connect_errno())
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$result = mysqli_query($con, "SELECT * FROM ir_update_state ORDER BY ts DESC;");

while($row = mysqli_fetch_array($result))
{
    echo $row;
    echo "<br>";
}

mysqli_close($con);

?>