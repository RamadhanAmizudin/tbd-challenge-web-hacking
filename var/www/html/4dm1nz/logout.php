<?php
require __DIR__ . '/init.php';

if(!isAuth()) {
    redirect('login.php');
}

$_SESSION = null;
session_destroy();

redirect('index.php');