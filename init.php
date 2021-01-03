<?php

// error reporting
ini_set('display_errors', 'On');
error_reporting(E_ALL);

include 'admin/connect.php';

$sessionUser = '';
if(isset($_SESSION['user'])){
  $sessionUser =$_SESSION['user'];
  
}

 //routs
$tpl = 'includes/templates/';
$lang = 'includes/languages/';
$func = 'includes/functions/';
$css = 'layout/css/';
$js = 'layout/js/';

// include the important files
include $func."functions.php";
include $lang."english.php";
include $tpl."header.php";

 ?>
