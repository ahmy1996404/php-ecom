<?php
session_start();
$pageTitle='Login';
// print_r($_SESSION);
if (isset($_SESSION['user'])){
  header('Location: index.php');
}
include 'init.php';
//check if user coming from http post request
if($_SERVER['REQUEST_METHOD']=='POST'){

  if (isset($_POST['login'])){
    $user = $_POST['username'];
    $pass = $_POST['password'];
    $hashedPass = sha1($pass);
    // check if user is exist
    $stmt= $con->prepare("select
                                UserID , Username , password
                         from
                               `users`
                          where
                               Username=?
                           and
                               Password=?");
    $stmt->execute(array($user , $hashedPass));

    $get = $stmt->fetch();

    $count = $stmt->rowCount();
    // echo $count;
    // if count >0 this mean database contain record about this Username
    if ($count>0){
      // echo 'welcome'.$username;
      $_SESSION['user']= $user;//register session name
      $_SESSION['uid']= $get['UserID'];//register session name
      header('Location: index.php');
      exit();
    }
  } else{
    $formErrors = array();
    $username = $_POST['username'];
    $password = $_POST['password'];
    $password2 = $_POST['password2'];
    $email = $_POST['email'];
    if(isset($username)){
      $filterdUser = filter_var($username, FILTER_SANITIZE_STRING);
      if (strlen($filterdUser)<4) {
          $formErrors[]= 'Username Must Be Larger Than 4 Chars';
      }
    }
    if(isset($password)&& isset($password2)){
      if(empty($password)){
        $formErrors[]= 'Sorry Password Not Match';
      }
      $pass1 = sha1($password);
      $pass2 = sha1($password2);
      if($pass1 !== $pass2){
        $formErrors[]= 'Sorry Password Not Match';

      }
    }
    if(isset($email)){
      $filterdEmail = filter_var( $email, FILTER_SANITIZE_EMAIL);
      if(filter_var($filterdEmail,FILTER_VALIDATE_EMAIL) != true){
        $formErrors[]= 'This Email is Not Valid';

      }
    }
    if (empty($formErrors)){
      // check if useer exist in database
      $check =  checkItem("Username","users",$username);
      if ($check == 1){

        $formErrors[]= 'Sorry Username is  Exist';

      }else{
      //insert user into the database with this Info
      $stmt= $con->prepare("INSERT INTO
                           users ( Username , Email    , Password ,RegStatus, regDate)
                           VALUES(:zuser , :zemail , :zpass ,0, now())");
      $stmt->execute(array(
        'zuser' => $username ,
        'zemail' => $email,
         'zpass' => sha1($password)
      ));
      // echo succes message
    $succesMsg = 'You are Now Registered User';
      }
    }
  }
}
 ?>

  <div class="container login-page">
    <h1 class="text-center">
      <span class="signin selected" data-class="login">Login</span> | <span class="register" data-class="signup">SignUp</span>
    </h1>
<!-- start login form -->
    <form class="login" action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
      <div class="input-container">
        <input class="form-control" type="text" name="username" autocomplete="off" placeholder="Type Your User Name"  required="required"/>
      </div>
      <div class="input-container">
        <input class="form-control" type="password" name="password" autocomplete="new-password" placeholder="Type Your Password"  required/>
      </div>
      <input class="btn btn-primary btn-block" type="submit"  name="login" value="Login" />
    </form>
<!-- end login form -->
<!-- start signup form -->
    <form class="signup" action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
      <div class="input-container">
        <input pattern=".{4,}" title="Username Must Be more Than 4 Chars"class="form-control" type="text" name="username" autocomplete="off" placeholder="Type Your User Name" required/>
      </div>
      <div class="input-container">
        <input minlength="4" class="form-control" type="password" name="password" autocomplete="new-password" placeholder="Type Your Password" required/>
      </div>
      <div class="input-container">
        <input minlength="4" class="form-control" type="password" name="password2" autocomplete="new-password" placeholder="Retype Your Password" required/>
      </div>
      <div class="input-container">
        <input class="form-control" type="email" name="email"  placeholder="Type Your Email" required/>
      </div>
      <input class="btn btn-success btn-block" type="submit" name="signup" value="Sign Up" />
    </form>
<!-- end signup form -->
    <div class="the-errors text-center">
      <?php
        if(!empty($formErrors)){
          foreach ( $formErrors as $error) {
            echo '<div class="msg error">'.$error .'</div>';
          }
        }
        if(isset($succesMsg)){
          echo '<div class="msg success">'.$succesMsg .'</div>';

        }
        ?>
    </div>
  </div>

 <?php
 include $tpl .'footer.php'
  ?>
