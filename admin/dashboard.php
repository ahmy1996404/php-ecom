<?php

// header already send problem
ob_start(); // output buffering start

session_start();
// print_r($_SESSION);
if (isset($_SESSION['Username'])){
// echo 'Welcome'. $_SESSION['Username'];
$pageTitle='Dashboard';

include 'init.php';
// start dashboard page

?>
<div class="home-stats">

  <div class="container  text-center">

    <h1>Dashboard</h1>
    <div class="row">
      <div class="col-md-3">
        <div class="stat st-members">
          <i class="fa fa-users" style="position: absolute;font-size: 80px;top: 35px;left: 30px;"></i>
          <div class="info">
            Total Members
            <span><a href="members.php"><?php echo countItems('UserID','users')?></a></span>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="stat st-pending">
          <i class="fa fa-user-plus" style="position: absolute;font-size: 80px;top: 35px;left: 30px;"></i>
          <div class="info">
            Pending Members
            <span><a href="Members.php?do=Manage&page=Pending">
            <?php echo checkItem("RegStatus","users" , 0) ?>
            </a></span>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="stat st-items">
          <i class="fa fa-tag" style="position: absolute;font-size: 80px;top: 35px;left: 30px;"></i>
          <div class="info">
            Total Items
            <span><a href="items.php"><?php echo countItems('item_ID','items')?></a></span>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="stat st-comments">
          <i class="fa fa-comments" style="position: absolute;font-size: 80px;top: 35px;left: 30px;"></i>
        <div class="info">
          Total Comments
          <span><a href="comments.php"><?php echo countItems('c_id','comments')?></a></span>
        </div>
        </div>
      </div>

    </div>

  </div>
</div>
<div class="latest">
  <div class="container">
    <div class="row">
      <div class="col-sm-6">
        <div class="card">
          <?php $numLatestusers =5; ?>


          <div class="card-header">
            <i class="fas fa-users"></i> Latest <?php echo  $numLatestusers ;?> Registerd Users
            <span class="toggle-info float-right">
              <i class="fa fa-plus fa-lg"></i>
            </span>
          </div>
          <div class="card-body">
            <?php
            $latestUsers = getLatest("*", "users" , "UserID", $numLatestusers);
            ?>
            <ul class="list-unstyled latest-users">
            <?php
            if (!empty($latestUsers)) {

            foreach ($latestUsers as $user) {
              echo '<li>'.$user['Username'].
              '<a href="members.php?do=Edit&userid='.$user['UserID'].'"><span class="btn btn-success float-right">
              <i class="fa fa-edit"></i>Edit';
              if ($user['RegStatus']== 0){
                echo "<a href='members.php?do=Activate&userid=".$user['UserID']."' class='btn btn-info activate float-right'><i class='fas fa-check'></i>Activate</a>";
              }
              echo '</span></a></li>';
            }
          }
          else{
            echo 'There Is No User To Show';
          }
            ?>
              </ul>
          </div>
        </div>
      </div>
      <div class="col-sm-6">
        <div class="card">

          <?php $numLatestitems =5; ?>

          <div class="card-header">
            <i class="fas fa-tag"></i> Latest <?php echo $numLatestitems  ?> Items
            <span class="toggle-info float-right">
              <i class="fa fa-plus fa-lg"></i>
            </span>
          </div>
          <div class="card-body">
            <?php
            $latestItems = getLatest("*", "items" , "item_ID", $numLatestitems);
            ?>
            <ul class="list-unstyled latest-users">
            <?php
            if (!empty($latestItems)) {

            foreach ($latestItems as $item) {
              echo '<li>'.$item['Name'].
              '<a href="items.php?do=Edit&itemid='.$item['item_ID'].'"><span class="btn btn-success float-right">
              <i class="fa fa-edit"></i>Edit';
              if ($item['Approve']== 0){
                echo "<a href='items.php?do=Approve&itemid=".$item['item_ID']."' class='btn btn-info activate float-right'><i class='fas fa-check'></i>Approve</a>";
              }
              echo '</span></a></li>';
            }
          } else {
            echo 'There Is No Item To Show';

          }
            ?>
              </ul>
          </div>
        </div>
      </div>
    </div>
    <!-- start latest comment -->
    <div class="row">
      <div class="col-sm-6">
        <div class="card">
          <?php $numLatestComments =4; ?>


          <div class="card-header">
            <i class="fas fa-comments"></i> Latest <?php echo $numLatestComments  ?> comments
            <span class="toggle-info float-right">
              <i class="fa fa-plus fa-lg"></i>
            </span>
          </div>
          <div class="card-body">
          <?php
                  $stmt = $con->Prepare("SELECT
                                              comments.* , users.Username
                                        from
                                              comments
                                        INNER join
                                              users
                                        ON
                                              users.UserID = comments.user_id
                                        ORDER BY
                                              c_id DESC
                                        Limit
                                              $numLatestComments");
                // execute stmt
                  $stmt->execute();
                  // assign to variables
                  $comments = $stmt->fetchAll();
                  if (!empty($comments)) {

                  foreach ($comments as $comment) {
                    echo '<div class="comment-box">';
                      echo '<span class="member-n">'.'<a href="members.php?do=Edit&userid='.$comment['user_id'].'">'.$comment['Username'].'</a>'.'</span>';
                      echo '<p class="member-c">'.$comment['comment'].'</p>';
                    echo '</div>';
                  }
                }
                else{
                  echo "There Is No Comment To Show  ";
                }
           ?>
          </div>
        </div>
      </div>

    </div>
    <!-- ens latest comment -->

  </div>
</div>
<?php
// end dashboard
include $tpl.'footer.php';

}
else{
  // echo 'you are not authorized to view this page';
  header('Location: index.php');
  exit();
}
ob_end_flush();
?>
