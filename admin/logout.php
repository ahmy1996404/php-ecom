<<?php
//start the session_start

session_start();
session_unset(); // unset session
session_destroy();
header('Location: index.php');
exit();
