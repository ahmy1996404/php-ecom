<?php
session_start();
$pageTitle = 'Members';
if (isset($_SESSION['Username'])){

include 'init.php';

if ( isset($_GET['do'])){ $do = $_GET['do'];}
else { $do = 'Manage';}

// start manage page

if ($do == 'Manage'){// manage members page
  $query='';
  if (isset($_GET['page'])&&$_GET['page']=='Pending'){
    $query = 'AND RegStatus = 0';
  }
  // select all users except admin
  $stmt = $con->Prepare("SELECT * from users where GroupID != 1 $query ORDER BY  UserID DESC");
// execute stmt
  $stmt->execute();
  // assign to variables
  $rows = $stmt->fetchAll();
  if(!empty($rows)){
  ?>
  <h1 class="text-center">Manage Member</h1>
  <div class="container">
    <div class="table-responsive">
      <table class="main-table manage-members text-center table table-bordered">
        <tr>
          <td>#id</td>
          <td>Avatar</td>
          <td>Username</td>
          <td>Email</td>
          <td>Full Name</td>
          <td>Register Date</td>
          <td>Control</td>
        </tr>
        <?php
        foreach ($rows as $row) {
          echo "<tr>";
          echo "<td>".$row['UserID']."</td>";

          echo "<td>";
          if (empty($row['avatar'])) {
            echo "No Image";

          }else {
            echo "<img src ='uploads/avatars/".$row['avatar']."' alt=''/>";
          }
          echo "</td>";
          echo "<td>".$row['Username']."</td>";
          echo "<td>".$row['Email']."</td>";
          echo "<td>".$row['FullName']."</td>";
          echo "<td>".$row['regDate']."</td>";
          echo "<td>
          <a href='members.php?do=Edit&userid=".$row['UserID']."' class='btn btn-success'><i class='fas fa-edit'></i>Edit</a>
          <a href='members.php?do=Delete&userid=".$row['UserID']."' class='btn btn-danger confirm'><i class='fas fa-user-minus'></i>Delete</a>";
          if ($row['RegStatus']== 0){
            echo "<a href='members.php?do=Activate&userid=".$row['UserID']."' class='btn btn-info activate'><i class='fas fa-check'></i>Activate</a>";

          }
          "</td>";
          echo"</tr>";
        }

        ?>

      </table>
    </div>
    <a href="members.php?do=Add" class="btn btn-primary"><i class="fas fa-plus"></i>New member</a>
  </div>
<?php
}
else {
  echo '<div class="container">';
    echo '<div class ="nice-message">
      There Is No Member To Show
    </div> ';
   echo '<a href="members.php?do=Add" class="btn btn-primary"><i class="fas fa-plus"></i>New member</a>';

  echo '</div>';
}
?>
<?PHP }elseif ($do == 'Add') { // add members page

 ?>
<h1 class="text-center">Add New Member</h1>
<div class="container">
  <form class="form-horizontal" action="?do=Insert" method="POST" enctype="multipart/form-data">
    <!-- start user name feild -->
    <div class="form-group form-group-lg">
      <label class="col-sm-2 control-label">Username</label>
      <div class="col-sm-10 col-md-4">
        <input type="text" name="username" class="form-control" autocomplete="off" required="required" placeholder="Username To Login into Shop"/>
      </div>
    </div>
    <!-- end user name -->
    <!-- start Password feild -->
    <div class="form-group form-group-lg">
      <label class="col-sm-2 control-label">Password</label>
      <div class="col-sm-10 col-md-4">

        <input type="password" name="password" class=" password form-control" autocomplete="new-password" required="required" placeholder="Password must be hard and complex"/>
        <!-- <i class="show-pass fas fa-eye fa-2x"></i> -->
      </div>
    </div>
    <!-- end Password -->
    <!-- start Email feild -->
    <div class="form-group form-group-lg">
      <label class="col-sm-2 control-label">Email</label>
      <div class="col-sm-10 col-md-4">
        <input type="email" name="email" class="form-control" required="required" placeholder="Email Must Be Valid" />
      </div>
    </div>
    <!-- end Email -->
    <!-- start Fullname feild -->
    <div class="form-group form-group-lg">
      <label class="col-sm-2 control-label">Fullname</label>
      <div class="col-sm-10 col-md-4">
        <input type="text" name="full" class="form-control" required="required" placeholder="Full Name Appear In Your Profile Page"/>
      </div>
    </div>
    <!-- end Fullname -->
    <!-- start Avatar feild -->
    <div class="form-group form-group-lg">
      <label class="col-sm-2 control-label">User Avatar</label>
      <div class="col-sm-10 col-md-4">
        <input type="file" name="avatar" class="form-control" required="required" />
      </div>
    </div>
    <!-- end Avatar -->
    <!-- start submit feild -->
    <div class="form-group form-group-lg">
      <div class="col-sm-offset-2 col-sm-10">
        <input type="submit" value="Add Member" class="btn btn-primary btn-lg" />
      </div>
    </div>
    <!-- end submit -->

  </form>
</div>
<?php
}elseif ($do == 'Insert') {// insert page

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
  echo  "<h1 class='text-center'>Update Member</h1>";
  echo "<div class='container'>";
  // UPLOAD VARIBLES

  $avatarName = $_FILES['avatar']['name'];
  $avatarSize = $_FILES['avatar']['size'];
  $avatarTmp = $_FILES['avatar']['tmp_name'];
  $avatarType = $_FILES['avatar']['type'];

// list of allowed file typed to upload

  $avatarAllowedExtensions = array("jpeg","jpg","png","gif");

  // get avatar extentions

  $avatarExtention = explode('.',$avatarName);
  $avatarExtention = strtolower(end($avatarExtention));


// get the variables from the date_create_from_form
$user =$_POST['username'];
$pass =$_POST['password'];
$email=$_POST['email'];
$name =$_POST['full'];
$hashedPass = sha1($pass);
// validate the form
$formErrors = array();
if (strlen($user)<4){
  $formErrors[] = '<div class="alert alert-danger"> username cant be less than <strong>4 chars</strong> </div>';

}
if (strlen($user)>20){
  $formErrors[] = '<div class="alert alert-danger">  username cant be more than <strong>20 chars</strong> </div>';

}
if (empty($user)){
  $formErrors[] = '<div class="alert alert-danger">  username cant be <strong>empty</strong></div>';

}
if (empty($pass)){
  $formErrors[] = '<div class="alert alert-danger">  password cant be <strong>empty</strong></div>';

}
if (empty($name)){
  $formErrors[] ='<div class="alert alert-danger">  Full name cant be <strong>empty</strong></div>';
}
if (empty($email)){
  $formErrors[] ='<div class="alert alert-danger">  Email cant be <strong>empty</strong></div>';
}
if (!empty($avatarName) && !in_array($avatarExtention , $avatarAllowedExtensions )){

  $formErrors[] ='<div class="alert alert-danger">  This Extenton Is n\'t <strong>Allowed</strong></div>';

}
if (empty($avatarName )){

  $formErrors[] ='<div class="alert alert-danger">  Avatar is <strong>Required</strong></div>';

}
if ($avatarSize > 4194304){

  $formErrors[] ='<div class="alert alert-danger">  Avatar can\'t ba Larger Than <strong>4MB</strong></div>';

}
// loop into error aray and print it
foreach ($formErrors as $error) {
echo $error ;
}

//check if no error proced update
        if (empty($formErrors)){
          $avatar = rand(0,1000000) . '_' . $avatarName;
          move_uploaded_file($avatarTmp,"uploads\avatars\\".$avatar);
          // check if useer exist in database
          $check =  checkItem("Username","users",$user);
          if ($check == 1){

            $theMsg =  "<div class= 'alert alert-danger'>Sorry this user is exist </div>";
            redirectHome($theMsg , 'back');
          }else{
          //insert user into the database with this Info
          $stmt= $con->prepare("INSERT INTO
                               users ( Username , Email  , FullName  , Password ,RegStatus, regDate , avatar)
                               VALUES(:zuser , :zemail , :zname , :zpass ,1, now() , :zavatar)");
          $stmt->execute(array(
            'zuser' => $user ,
            'zemail' => $email,
            'zname' => $name ,
            'zpass' => $hashedPass,
            'zavatar' => $avatar
          ));
          // echo succes message
          $theMsg = "<div class='alert alert-success'>".$stmt->rowCount() . 'Record Inserted </div>';
          redirectHome($theMsg,'back');
          }
        }
}else {
  echo "<div class='container'>";
  $theMsg ='<div class="alert alert-danger">sorry you canot browse this page dirct</div>';
  redirectHome($theMsg , 'back' );
  echo "</div>";

}
echo "</div>";



}elseif ($do == 'Edit') {  // Edit Page

  // check if get request user id is numeric and get th int vaue of it
  if(isset($_GET['userid']) && is_numeric($_GET['userid']) ){
    $userid = intval($_GET['userid']);
  } else{
    $userid = 0;
  }
  // select all data depend on this id
  $stmt= $con->prepare("select
                        *
                       from
                             `users`
                        where
                             UserID=?
                         LIMIT 1");
   // execute query
    $stmt->execute(array($userid));
    // fetch the data
    $row = $stmt->fetch();
    // the row count
    $count = $stmt->rowCount();
    // if there is such id show the form
    if($stmt->rowCount()>0){


  // echo $userid;
  ?>
  <h1 class="text-center">Edit Member</h1>
  <div class="container">
    <form class="form-horizontal" action="?do=Update" method="POST">
      <input type="hidden" name="userid" value="<?php echo $userid?>"/>
      <!-- start user name feild -->
      <div class="form-group form-group-lg">
        <label class="col-sm-2 control-label">Username</label>
        <div class="col-sm-10 col-md-4">
          <input type="text" name="username" class="form-control" value="<?php echo $row['Username']?>" autocomplete="off" required="required"/>
        </div>
      </div>
      <!-- end user name -->
      <!-- start Password feild -->
      <div class="form-group form-group-lg">
        <label class="col-sm-2 control-label">Password</label>
        <div class="col-sm-10 col-md-4">
          <input type="hidden" name="oldpassword" value="<?php echo $row['Password']?>" />

          <input type="password" name="newpassword" class="form-control" autocomplete="new-password" placeholder="Leave blank if you dont want to change"/>
        </div>
      </div>
      <!-- end Password -->
      <!-- start Email feild -->
      <div class="form-group form-group-lg">
        <label class="col-sm-2 control-label">Email</label>
        <div class="col-sm-10 col-md-4">
          <input type="email" name="email" class="form-control"value="<?php echo $row['Email']?>" required="required" />
        </div>
      </div>
      <!-- end Email -->
      <!-- start Fullname feild -->
      <div class="form-group form-group-lg">
        <label class="col-sm-2 control-label">Fullname</label>
        <div class="col-sm-10 col-md-4">
          <input type="text" name="full" class="form-control" value="<?php echo $row['FullName']?>" required="required"/>
        </div>
      </div>
      <!-- end Fullname -->
      <!-- start submit feild -->
      <div class="form-group form-group-lg">
        <div class="col-sm-offset-2 col-sm-10">
          <input type="submit" value="save" class="btn btn-primary btn-lg" />
        </div>
      </div>
      <!-- end submit -->

    </form>
  </div>
  <?php
}
// if there is no such id
else {
  echo "<div class='container'>";
  $theMsg = '<div class="alert alert-danger"> no user with this id</div>';
  redirectHome($theMsg);
  echo "</div>";

}
}
else if ($do== 'Update'){// upadate page

echo  "<h1 class='text-center'>Update Member</h1>";
echo "<div class='container'>";
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
// get the variables from the date_create_from_form
$id   =$_POST['userid'];
$user =$_POST['username'];
$email=$_POST['email'];
$name =$_POST['full'];
// password trick
$pass='';
if (empty($_POST['newpassword'])){
  $pass = $_POST['oldpassword'];
}else {
  $pass = sha1($_POST['newpassword']);
}
// validate the form
$formErrors = array();
if (strlen($user)<4){
  $formErrors[] = '<div class="alert alert-danger"> username cant be less than <strong>4 chars</strong> </div>';

}
if (strlen($user)>20){
  $formErrors[] = '<div class="alert alert-danger">  username cant be more than <strong>20 chars</strong> </div>';

}
if (empty($user)){
  $formErrors[] = '<div class="alert alert-danger">  username cant be <strong>empty</strong></div>';

}
if (empty($name)){
  $formErrors[] ='<div class="alert alert-danger">  Full name cant be <strong>empty</strong></div>';
}
if (empty($email)){
  $formErrors[] ='<div class="alert alert-danger">  Email cant be <strong>empty</strong></div>';
}
// loop into error aray and print it
foreach ($formErrors as $error) {
echo $error ;
}

//check if no error proced update
        if (empty($formErrors)){
          $stmt2 = $con->prepare("select
                                          *
                                  from
                                          users
                                  WHERE
                                          Username = ?
                                  AND
                                          UserID != ?
                                  ");
            $stmt2->execute(array($user , $id));

            $count = $stmt2->rowCount();

            if($count == 1){
              $theMsg ="<div class='alert alert-danger'>". 'Sorry This Record Is Exist </div>';
              redirectHome($theMsg , 'back' );
            }
            else {


          //update the database with this Info

          $stmt =$con->prepare("UPDATE `users` SET Username = ? , Email = ? , FullName =? , Password = ? where UserID = ?");
          $stmt->execute(array($user , $email , $name , $pass , $id ));
          // echo succes message
          $theMsg ="<div class='alert alert-success'>".$stmt->rowCount() . 'Record Updated </div>';
          redirectHome($theMsg , 'back' );

        }

        }
}else {
  $theMsg =  '<div class="alert alert-danger">sorry you canot browse this page dirct</div>';
  redirectHome($theMsg );

}
echo "</div";

}elseif($do == 'Delete'){//Delete Memper page
  echo  "<h1 class='text-center'>Delete Member</h1>";
  echo "<div class='container'>";
    // check if get request user id is numeric and get th int vaue of it
    if(isset($_GET['userid']) && is_numeric($_GET['userid']) ){
      $userid = intval($_GET['userid']);
    } else{
      $userid = 0;
    }
    // select  data depend on this id

    $check = checkItem('userid','users',$userid);
      // if there is such id show the form
      if(  $check>0){
        $stmt = $con->prepare("DELETE FROM users WHERE UserID = :zuser");
        $stmt->bindParam(":zuser", $userid);
        $stmt->execute();
        $theMsg = "<div class='alert alert-success'>".$stmt->rowCount() . 'Record Deleted </div>';
        redirectHome($theMsg,'back');
      }
      else{
        $theMsg =  "<div class='alert alert-danger'>This Id is Not Exist</div>";
        redirectHome($theMsg);

      }
      echo "</div>";
}
elseif($do == 'Activate'){//activate Memper page
  echo  "<h1 class='text-center'>Activate Member</h1>";
  echo "<div class='container'>";
    // check if get request user id is numeric and get th int vaue of it
    if(isset($_GET['userid']) && is_numeric($_GET['userid']) ){
      $userid = intval($_GET['userid']);
    } else{
      $userid = 0;
    }
    // select  data depend on this id

    $check = checkItem('userid','users',$userid);
      // if there is such id show the form
      if(  $check>0){
        $stmt = $con->prepare("UPDATE users SET RegStatus = 1 WHERE UserID = ?");
        $stmt->execute(array($userid));
        $theMsg = "<div class='alert alert-success'>".$stmt->rowCount() . 'Record Activated </div>';
        redirectHome($theMsg,'back');
      }
      else{
        $theMsg =  "<div class='alert alert-danger'>This Id is Not Exist</div>";
        redirectHome($theMsg);

      }
      echo "</div>";
}

include $tpl.'footer.php';

}
else{
  // echo 'you are not authorized to view this page';
  header('Location: index.php');
  exit();


}
