<?php
// categories => [manage |edit | update | add | insert | delete | stats]
$do = '';
if ( isset($_GET['do'])){
  $do = $_GET['do'];
}
else {
  $do = 'Manage';
}
// if the page is main page
if($do == 'Manage'){
  echo 'Welcome you are in manage category';
  echo '<a href="?do=Add">Add New Category +</a>';
} elseif ($do == 'Add'){
  echo 'welcome you are in add category page';
}elseif($do == "Insert")
{
  echo 'welcome you are in insert category page';

}
 else{
  echo 'error there isnot page with this name';
}
