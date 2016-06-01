<?php
require dirname(__FILE__) . '/config.php';
require dirname(__FILE__) . '/bbcode.php';

$mysql = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DBNM);

if ($mysql->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

session_start();
