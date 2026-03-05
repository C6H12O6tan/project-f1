<?php
session_start();
session_unset(); 
session_destroy(); 

header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Location: login.php"); 
exit();
?>
