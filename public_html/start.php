<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'config.php';

// long running process unix/windows
function execInBackground($cmd)
{
    if (substr(php_uname(), 0, 7) == "Windows")
    {
        pclose(popen("start /B ". $cmd, "r"));
    }
    else
    {
        exec($cmd . " > /dev/null &");
    }
}

// check iso date
$from = new DateTime($_GET["from"]);
$to = new DateTime($_GET["to"]);

// set to utc
$from->setTimeZone(new DateTimeZone('UTC'));
$to->setTimeZone(new DateTimeZone('UTC'));

// first check from < to
if(from >= to)
{
    echo '{"status":1, "error": "From date must be before To date."}';
}
// move on
else
{
    // check for running tasks
    $con=mysqli_connect($host,$user,$password,$database);

    if (mysqli_connect_errno())
    {
        echo '{"status":1, "error": "MYSQL:' . mysqli_connect_error() . '"}';
    }
    else
    {
        // query table
        $result = mysqli_query($con, "SELECT * FROM ir_update_state WHERE state='running' ORDER BY ts DESC;");

        $running = mysql_num_rows($result);
        if($running > 0)
        {
            $first = $row = mysqli_fetch_array($result)
            echo '{"status":1, "error": "There is already an update job running [' . $row['pid'] . ']."}';
        }
        else
        {

            $cmd = "ruby " . $update_script . " '" . $from->format('Y-m-d\Th:i:s\Z') . "' '" . $to->format('Y-m-d\Th:i:s\Z') . "'";
            execInBackground($cmd);


        }
    }
    mysqli_close($con);
}

?>