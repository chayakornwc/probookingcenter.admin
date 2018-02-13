<?php

$dbhost = '27.254.85.199';
$dbname = 'jitwilai_db';
$dbuser = 'jitwilai_db';
$dbpass = 'password.';

$dbconn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if (!$dbconn) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

echo "Success: A proper connection to MySQL was made! The " . $dbname . " database is great." . PHP_EOL;
echo "Host information: " . mysqli_get_host_info($dbconn) . PHP_EOL;

mysqli_close($dbconn);

