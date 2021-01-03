<?php
// ===================================================
// Template Page
// ===================================================
ob_start();

session_start();
$pageTitle = '';
if (isset($_SESSION['Username'])){

          include 'init.php';

          if ( isset($_GET['do'])){ $do = $_GET['do'];}
          else { $do = 'Manage';}

          // start manage page

          if ($do == 'Manage'){

           }elseif ($do == 'Add') {

           }elseif ($do == 'Insert') {

           }elseif ($do == 'Edit') {

           }else if ($do== 'Update'){

           }elseif($do == 'Delete'){

           }
          elseif($do == 'Activate'){

          }

          include $tpl.'footer.php';

}
else{
  // echo 'you are not authorized to view this page';
  header('Location: index.php');
  exit();
}
ob_end_flush();

 ?>
 <?php
 // ===================================================
 // Template Page
 // ===================================================
 ob_start();

 session_start();
 $pageTitle = '';
 if (isset($_SESSION['Username'])){

           include 'init.php';



           include $tpl.'footer.php';

 }
 else{
   // echo 'you are not authorized to view this page';
   header('Location: index.php');
   exit();
 }
 ob_end_flush();

  ?>
