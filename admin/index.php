<?php
session_start();
$noNavbar = '';
$pageTitle='Login';
// print_r($_SESSION);
if (isset($_SESSION['Username'])){
  header('Location: dashboard.php');

}
include 'init.php';

 //check if user coming from http post request

 if($_SERVER['REQUEST_METHOD']=='POST'){
   $username = $_POST['user'];
   $password = $_POST['pass'];
   $hashedPass = sha1($password);
   // echo $username.' '.$password.' '.$hashedPass;

   // check if user is exist
   $stmt= $con->prepare("select
                         UserID ,  Username , password
                        from
                              `users`
                         where
                              Username=?
                          and
                              Password=?
                          and
                              GroupID=1
                          LIMIT 1");
   $stmt->execute(array($username , $hashedPass));
   $row = $stmt->fetch();
   $count = $stmt->rowCount();
   // echo $count;
   // if count >0 this mean database contain record about this Username
   if ($count>0){
     // echo 'welcome'.$username;
     $_SESSION['Username']= $username;//register session name
     $_SESSION['ID']= $row['UserID'];//register session id

     header('Location: dashboard.php');
     exit();
   }
 }
  ?>
<form class="login" action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
  <h4 class="text-center">Admin Login</h4>
  <input class="form-control" type="text" name="user" placeholder="Username" autocomplete="off" />
  <input class="form-control" type="password" name="pass" placeholder="password" autocomplete="new-password" />
  <input class="btn btn-primary btn-block" type="submit" value="login" />

</form>

<?php include $tpl.'footer.php'; ?>
