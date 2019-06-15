<?php
$host = "localhost";
$username = "root";
$password = "";
$db_name = "testing";
$con = mysqli_connect($host, $username, $password, $db_name);
mysqli_set_charset($con, "utf8");
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
