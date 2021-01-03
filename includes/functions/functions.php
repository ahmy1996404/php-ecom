<?php

// get   all function v2
// function to get  all rec  from database table

function getAllFrom($field , $table , $where = NULL, $and = NULL , $orderfield , $ordering ="DESC"){
  global $con;

  $getAll = $con->prepare("SELECT $field From $table $where $and ORDER BY $orderfield $ordering  ");

  $getAll->execute();

  $all = $getAll->fetchAll();

  return $all;

}


// function check if user status active
// function check the RegStatus of user

function checkUserStatus($user){
  global $con;
  $stmt= $con->prepare("select
                            Username , RegStatus
                       from
                             `users`
                        where
                             Username=?
                         and
                             RegStatus = 0");
  $stmt->execute(array($user));

  $status = $stmt->rowCount();

  return $status;

}











 // title func that echo the page title in case page has the variable $pageTitle and echo Defult title for other v1
function getTitle(){
  global $pageTitle;
  if(isset($pageTitle)){
    echo $pageTitle;
  }
  else{
    echo 'Default';
  }
}
//redirect function this function accept parameters v2
// $theMsg = Echo the  Message
// $url = the link that will redirect
//$second = Seconds befor re direct
function redirectHome($theMsg, $url = null , $seconds = 3){
  if ($url === null){
    $url = 'index.php';
    $link = 'Home Page';

  } else {
    if(isset($_SERVER['HTTP_REFERER'])&& $_SERVER['HTTP_REFERER']!==''){
      $url = $_SERVER['HTTP_REFERER'];
      $link = 'Previous Page';

    }
    else{
      $url = 'index.php';
      $link = 'Home Page';

    }
  }
  echo $theMsg;
  echo "<div class='alert alert-info'>you will be redirected to $link after $seconds seconds</div>";
  header("refresh:$seconds;url=$url");
  exit();
}

// function to check if  item in database v1
// $select = the item to select
// $from = the table to select from
// $value= the value of select

function checkItem($select , $from , $value){
  global $con;
  $statment = $con->prepare("SELECT $select FROM $from WHERE $select = ?");
  $statment->execute(array($value));
  $count = $statment->rowCount();
  return $count;
}

// count number of items func v1
// function to count num of items rows
// $item = the item to count
// $table = the table to choose from
function countItems($item ,$table){
  global $con;
  $stmt2= $con->prepare("SELECT COUNT($item) FROM $table");
  $stmt2 ->execute();
  return $stmt2->fetchColumn();
}

// get latest records function v1
// function to get latest items from data pase [ users , items , comments]
// $select= field to select
// $table  = table choose from
// $limit = no of record set
function getLatest($select , $table, $order , $limit=5){
  global $con;
  $getStmt = $con->prepare("SELECT $select From $table ORDER BY $order DESC Limit $limit");
  $getStmt->execute();
  $rows = $getStmt->fetchAll();
  return $rows;
}
