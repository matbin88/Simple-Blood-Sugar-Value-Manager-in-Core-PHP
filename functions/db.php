<?php

$con = mysqli_connect('localhost', 'root', '', 'sblms');
$url = 'http://' . $_SERVER['HTTP_HOST'].'/sblms';

/* function createTabel(){
    global $con;
    $query =    "CREATE TABLE users(
                id INT(11) AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL,
                password TEXT NOT NULL,
                token TEXT NOT NULL,
                activation tinyint(4)  NOT NULL Default 0,
                admin tinyint(4)  NOT NULL Default 0)";
    $con->query($query);
}
createTabel(); */

function escape($string)
{
    global $con;
    return mysqli_real_escape_string($con, $string);
}


function row_count($result)
{
    return mysqli_num_rows($result);
}

function query($query)
{
    global $con;
    return mysqli_query($con, $query);
}

function confirm($result)
{
    global $con;
    if (!$result) {
        die("QUERY FAILED " . mysqli_error($con));
    }
}