<?php
ob_start();
  session_start();
  $pageTitle='Profile';
  include 'init.php';
  if(isset($_SESSION['user'])){
    $getUser = $con->prepare("SELECT * FROM users Where Username = ?");

    $getUser->execute(array($sessionUser));

    $info = $getUser->fetch();

    $userid = $info['UserID'];
?>
  <h1 class="text-center">My Profile</h1>
  <div class="information block">
    <div class="container">
      <div class="card card-primary">
        <div class="card-header">
          My Information
        </div>
        <div class="card-body">
          <ul class="list-unstyled">

            <li>
              <i class="fa fa-unlock-alt fa-fw"></i>
              <span>Login Name</span> : <?php echo $info['Username'];?>
            </li>
            <li>
              <i class="fa fa-envelope fa-fw"></i>
              <span>Email</span> : <?php echo $info['Email'];?>
            </li>
            <li>
              <i class="fa fa-user fa-fw"></i>
              <span>Full Name</span> : <?php echo $info['FullName'];?>
            </li>
            <li>
              <i class="fas fa-calendar-alt"></i>
              <span>Register Date</span> : <?php echo $info['regDate'];?>
             </li>
            <li>
              <i class="fa fa-tags fa-fw"></i>
              <span>Fav Category</span> : <?php echo $info['regDate'];?>
            </li>

        </ul>
        <a href="" class="btn btn-default">
          Edit Information
        </a>

        </div>
      </div>
    </div>
  </div>
  <div id="my_ads" class="my-ads block">
    <div class="container">
      <div class="card card-primary">
        <div class="card-header">
          My Items
        </div>
        <div class="card-body">
            <?php
            $myItems =   getAllFrom("*" , "items" , "WHERE Member_ID = $userid " , "" , "item_ID" );

                if(!empty($myItems)) {
                    echo '<div class="row">';
                    foreach ($myItems as $item) {
                      echo '<div class="col-sm-6 col-md-3">';
                        echo '<div class="img-thumbnail item-box">';
                          if($item['Approve']==0){
                            echo '<span class= "approve-status">Waiting Approved</span>';
                          }
                            echo '<span class="price-tag">'.'$'.$item['Price'].'</span>';
                            echo '<img class="img-fluid" src="img.png" alt="" />';
                              echo '<div class="caption">';
                                echo '<h3>'. '<a href="items.php?itemid='. $item['item_ID'].'">'. $item['Name'].'</a>'.'</h3>';
                                echo '<p>' . $item['Description']. ' </p>';
                                echo '<div class="regdate">'.'<p>' . $item['Add_Date']. '</p>'.' </div>';
                              echo '</div>';
                        echo '</div>';
                      echo '</div>';
                    }
                  echo '</div>';

          }else {
            echo'Sorry there isn\'t Ads To Show , <a href= "newad.php" >Create New Ad</a>';
          }
            ?>
        </div>
      </div>
    </div>
  </div>
  <div class="my-comments block">
    <div class="container">
      <div class="card card-primary">
        <div class="card-header">
          Latest Comments
        </div>
        <div class="card-body">
<?php

        $myComments =  getAllFrom("comment" , "comments" , "WHERE user_id = $userid " , "" , "c_id" );


          if(!empty($myComments)){
            foreach ($myComments as $comment) {
              echo '<p>' . $comment['comment'] . '</p>';
            }
          }
          else{
            echo 'There \'s No Comments To Show ';
          }
?>
        </div>
      </div>
    </div>
  </div>
<?php
}
else {
  header('Location: login.php');
  exit();
}
  include $tpl.'footer.php';
  ob_end_flush();

?>
