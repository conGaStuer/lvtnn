<?php
session_start();

session_unset();

session_destroy();

header("Access-Control-Allow-Origin: http://localhost:8080");
exit();
?>