<?php

//#1 a secured way to connect to the db
$db['db_host'] = "localhost";
$db['db_user'] = "newuser";
$db['db_pass'] = "password";
$db['db_name'] = "cms-udemy";

foreach ($db as $key => $value) {
    define(strtoupper($key), $value);
}

//#3 easiest way to connect to a db
$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
//$connection = mysqli_connect('localhost', 'newuser', 'password', 'cms-udemy');
//    if ($connection) {
//
//        echo "We are connected!";
//}