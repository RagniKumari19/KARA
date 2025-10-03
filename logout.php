<?php
include_once("db.php");
session_start();
session_unset();
session_destroy();


header("Location: login.php");
ob_end_flush();
exit(); 
?>