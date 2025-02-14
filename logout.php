<?php
session_start();
session_unset();
session_destroy();
header("Location:http://localhost/librairy/login.php");
exit();
?>