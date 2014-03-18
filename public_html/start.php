<?php

header('Content-Type: application/json; charset=utf-8');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Cache-Control: no-cache');
header('Pragma: no-cache');
header('Access-Control-Allow-Origin: *');

include 'config.php';

// check iso date
$from = new DateTime($_GET["from"]);
$to = new DateTime($_GET["to"]);

// set to utc
$from->setTimeZone(new DateTimeZone('UTC'));
$to->setTimeZone(new DateTimeZone('UTC'));

// first check from < to
if($from >= $to)
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

        $running = $result->num_rows;
        if($running > 0)
        {
            $first = $row = mysqli_fetch_array($result);
            echo '{"status":1, "error": "There is already an update job running [' . $row['pid'] . ']."}';
        }
        else
        {

            $cmd = "ruby " . $update_script . " -from " . $from->format('Y-m-d\Th:i:s\Z') . " -to " . $to->format('Y-m-d\Th:i:s\Z') . "";

            $pid = exec($cmd . " > /dev/null & echo $!");

            echo '{"status":0, "pid": ' . $pid . '}';
        }
    }
    mysqli_close($con);
}

?>