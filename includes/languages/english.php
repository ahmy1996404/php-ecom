<?php
function lang($phrase){
  static $lang = array(
    //home page
  // 'message'=>'welcome'
  // ,'admin'=>'administrator'
  //setting


// ------------------navbar--------------------
  'Home_Admin' =>'Home' ,
   'Categories'=> 'Categories',
   'Items'     => 'Items',
   'Members'   => 'Members',
   'Comments'=>  'Comments',
   'Statistics'=>  'Statistics',
   'Logs'       => 'Logs',
   'Edit_Profile'=>'Edit Profile',
   'Setting'     =>'Setting',
   'Logout'      =>'Logout'

  );
  return $lang[$phrase];
}
// $lang = array(
//   'osama'=> 'zero'
// );
// echo $lang['osama'];
//  ?>
