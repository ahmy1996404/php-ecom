<?php
  session_start();
  $pageTitle='Show Items';
  include 'init.php';
  // check if get request item id is numeric and get th int vaue of it
  if(isset($_GET['itemid']) && is_numeric($_GET['itemid']) ){
    $itemid = intval($_GET['itemid']);
  } else{
    $itemid = 0;
  }
  // select all data depend on this id
  $stmt= $con->prepare("SELECT
                               items.* ,
                               categories.Name AS category_name ,
                               users.UserName
                        from
                               items
                        INNER JOIN
                               categories
                        ON
                               categories.ID = items.Cat_ID
                        INNER JOIN
                               users
                        ON
                               users.UserID = items.Member_ID
                        where
                             item_ID=?
                        AND
                              Approve =1;
                               ");
   // execute query
    $stmt->execute(array($itemid));
    // fetch the data
    $item = $stmt->fetch();

    $count = $stmt->rowCount();

    if($stmt->rowCount()>0){

?>
  <h1 class="text-center"><?php echo $item['Name'];?></h1>
  <div class="container">
    <div class="row">
      <div class="col-md-3">
        <img class="img-fluid img-thumbnail center-block" src="img.png" alt="" />
      </div>
      <div class="col-md-9 item-info">
        <h2><?php echo $item['Name'];?></h2>
        <p><?php echo $item['Description'];?></p>
        <ul class="list-unstyled">

          <li>
            <i class="fas fa-calendar-alt fa-fw"></i>
            <span>Added Date : </span><?php echo $item['Add_Date'];?>
          </li>
          <li>
            <i class="fas fa-money-bill-wave fa-fw"></i>
            <span>Price : </span><?php echo $item['Price'];?>
          </li>
          <li>
            <i class="fas fa-building fa-fw"></i>
            <span>Made In : </span><?php echo $item['Country_Made'];?>
          </li>
          <li>
            <i class="fas fa-tags fa-fw"></i>
            <span>Category : <a href="categories.php?pageid=<?php echo $item['Cat_ID']; ?>"></span><?php echo $item['category_name'];?></a>
          </li>
          <li>
            <i class="fas fa-user fa-fw"></i>
            <span>Added By : <a href="#"></span><?php echo $item['UserName'];?></a>
          </li>
          <li class="tags-items">
            <i class="fas fa-tag fa-fw"></i>
            <span>Tags </span>
              <?php
                $allTags = explode(",",$item['tags']);
                foreach ($allTags as $tag) {
                  $tag = str_replace(' ', '' , $tag);
                  $lowertag= strtolower($tag);
                  if(!empty($tag)){

                    echo "<a href='tags.php?name={$lowertag}'>". $tag . '</a>';
                  }
                }
                ?>
          </li>
      </ul>
      </div>
    </div>
    <!-- start add comment -->
    <hr />
    <?php   if(isset($_SESSION['user'])){
      ?>
      <div class="row">
        <div class="offset-md-3">
          <div class="add-comment">
          Add your comment
          <form action="<?php echo $_SERVER['PHP_SELF'] .'?itemid='.$item['item_ID'] ?>" method="POST">
            <textarea name="comment" required></textarea>
            <input class="btn btn-primary" type="submit" value="Add comment" />
          </form>
          <?php
          if($_SERVER['REQUEST_METHOD']=='POST'){
            $comment=filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
            $userid=$_SESSION['uid'];
            $itemid=$item['item_ID'];
          }
          if(! empty($comment)){
            $stmt = $con->prepare ("INSERT INTO
                                      comments(comment,status,comment_date,item_id,user_id)
                                    VALUES
                                      (:zcomment , 0 , NOW(), :zitemid , :zuserid)");
            $stmt->execute(array(
              'zcomment' => $comment ,
              'zitemid' => $itemid,
              'zuserid' => $userid
            ));
            if($stmt){
              echo '<div class="alert alert-success">
              Comment Added
              </div>';
            }
          }
           ?>
        </div>
      </div>
    </div>
  <?php } else {
    echo '<a href="login.php">Login</a> or <a href="login.php">Register </a> to add comment';
  } ?>
    <!-- end add comment -->
    <hr />
    <?php
    $stmt = $con->Prepare("SELECT
                                comments.*  , users.Username
                          from
                                comments
                          INNER join
                                users
                          ON
                                users.UserID = comments.user_id

                          WHERE
                                item_id = ?
                          AND
                                status = 1
                          ORDER BY
                                c_id DESC");
  // execute stmt
    $stmt->execute(array(
      $item['item_ID']
    ));
    // assign to variables
    $comments = $stmt->fetchAll();
     ?>
      <?php
      foreach ($comments as $comment) {
        echo '<div class="comment-box">';
        echo '<div class="row">';
            echo '<div class="col-sm-2 text-center">'.'<img class="img-fluid img-thumbnail rounded-circle center-block" src="img.png" alt="" />'.$comment['Username'].'</div>';
            echo '<div class="col-sm-10">'.'<p class="lead">'.$comment['comment'].'</p>'.'</div>';
          echo "</div>";
        echo "</div>";
        echo "<hr />";
        }
       ?>

  </div>
<?php

}
else{
  echo "<div class='container'>";
  echo "<div class='alert alert-danger'>There 's no such ID or this item waiting approved</div>";
  echo '</div>';
}
  include $tpl.'footer.php';
  ob_end_flush();
?>
