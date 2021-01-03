<?php
include 'connect.php';
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

// include navbar on all pages expect the one with $noNavbar Varibable
if (!isset($noNavbar)){include $tpl."navbar.php";}


 ?>
