<?php
session_start();
$pageTitle = 'Comments';
if (isset($_SESSION['Username'])){

include 'init.php';

if ( isset($_GET['do'])){ $do = $_GET['do'];}
else { $do = 'Manage';}

// start manage page

if ($do == 'Manage'){// manage members page

  // select all users except admin
  $stmt = $con->Prepare("SELECT
                              comments.* , items.Name , users.Username
                        from
                              comments
                        INNER JOIN
                              items
                        ON
                              items.item_ID = comments.item_id
                        INNER join
                              users
                        ON
                              users.UserID = comments.user_id
                        ORDER BY
                              c_id DESC");
// execute stmt
  $stmt->execute();
  // assign to variables
  $comments = $stmt->fetchAll();
  if(!empty($comments)){
  ?>
  <h1 class="text-center">Manage Comments</h1>
  <div class="container">
    <div class="table-responsive">
      <table class="main-table text-center table table-bordered">
        <tr>
          <td>#id</td>
          <td>Comment</td>
          <td>Item Name</td>
          <td>User Name</td>
          <td>Added Date</td>
          <td>Control</td>
        </tr>
        <?php
        foreach ($comments as $comment) {
          echo "<tr>";
          echo "<td>".$comment['c_id']."</td>";
          echo "<td>".$comment['comment']."</td>";
          echo "<td>".$comment['Name']."</td>";
          echo "<td>".$comment['Username']."</td>";
          echo "<td>".$comment['comment_date']."</td>";
          echo "<td>
          <a href='comments.php?do=Edit&comid=".$comment['c_id']."' class='btn btn-success'><i class='fas fa-edit'></i>Edit</a>
          <a href='comments.php?do=Delete&comid=".$comment['c_id']."' class='btn btn-danger confirm'><i class='fas fa-user-minus'></i>Delete</a>";
          if ($comment['status']== 0){
            echo "<a href='comments.php?do=Approve&comid=".$comment['c_id']."' class='btn btn-info activate'><i class='fas fa-check'></i>Approve</a>";

          }
          "</td>";
          echo"</tr>";
        }

        ?>

      </table>
    </div>
  </div>
  <?php
  }
  else {
    echo '<div class="container">';
      echo '<div class ="nice-message">
        There Is No Comment To Show
      </div> ';
    echo '</div>';
  }
   ?>
<?PHP
}elseif ($do == 'Edit') {  // Edit Page

  // check if get request user id is numeric and get th int vaue of it
  if(isset($_GET['comid']) && is_numeric($_GET['comid']) ){
    $comid = intval($_GET['comid']);
  } else{
    $comid = 0;
  }
  // select all data depend on this id
  $stmt= $con->prepare("select
                        *
                       from
                             `comments`
                        where
                             c_id=?");
   // execute query
    $stmt->execute(array($comid));
    // fetch the data
    $row = $stmt->fetch();
    // the row count
    $count = $stmt->rowCount();
    // if there is such id show the form
    if($stmt->rowCount()>0){


  // echo $userid;
  ?>
  <h1 class="text-center">Edit Comment</h1>
  <div class="container">
    <form class="form-horizontal" action="?do=Update" method="POST">
      <input type="hidden" name="comid" value="<?php echo $comid?>"/>
      <!-- start comment feild -->
      <div class="form-group form-group-lg">
        <label class="col-sm-2 control-label">Comment</label>
        <div class="col-sm-10 col-md-4">
          <textarea class="form-control" name="comment"><?php echo $row['comment'] ?></textarea>
        </div>
      </div>
      <!-- end comment -->

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

echo  "<h1 class='text-center'>Update Comments</h1>";
echo "<div class='container'>";
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
// get the variables from the date_create_from_form
$comid   =$_POST['comid'];
$comment =$_POST['comment'];

          //update the database with this Info

          $stmt =$con->prepare("UPDATE `comments` SET comment = ? where c_id = ?");
          $stmt->execute(array($comment , $comid ));
          // echo succes message
          $theMsg ="<div class='alert alert-success'>".$stmt->rowCount() . 'Record Updated </div>';
          redirectHome($theMsg , 'back' );



}else {
  $theMsg =  '<div class="alert alert-danger">sorry you canot browse this page dirct</div>';
  redirectHome($theMsg );

}
echo "</div";

}elseif($do == 'Delete'){//Delete Memper page
  echo  "<h1 class='text-center'>Delete Comment</h1>";
  echo "<div class='container'>";
    // check if get request comid is numeric and get th int vaue of it
    if(isset($_GET['comid']) && is_numeric($_GET['comid']) ){
      $comid = intval($_GET['comid']);
    } else{
      $comid = 0;
    }
    // select  data depend on this id

    $check = checkItem('c_id','comments',$comid);
      // if there is such id show the form
      if(  $check>0){
        $stmt = $con->prepare("DELETE FROM comments WHERE c_id = :zid");
        $stmt->bindParam(":zid", $comid);
        $stmt->execute();
        $theMsg = "<div class='alert alert-success'>".$stmt->rowCount() . 'Record Deleted </div>';
        redirectHome($theMsg,'back');
      }
      else{
        $theMsg =  "<div class='alert alert-danger'>This Id is Not Exist</div>";
        redirectHome($theMsg );

      }
      echo "</div>";
}
elseif($do == 'Approve'){//activate Memper page
  echo  "<h1 class='text-center'>Approve comment</h1>";
  echo "<div class='container'>";
    // check if get request user id is numeric and get th int vaue of it
    if(isset($_GET['comid']) && is_numeric($_GET['comid']) ){
      $comid = intval($_GET['comid']);
    } else{
      $comid = 0;
    }
    // select  data depend on this id

    $check = checkItem('c_id','comments',$comid);
      // if there is such id show the form
      if(  $check>0){
        $stmt = $con->prepare("UPDATE comments SET status = 1 WHERE c_id = ?");
        $stmt->execute(array($comid));
        $theMsg = "<div class='alert alert-success'>".$stmt->rowCount() . 'Record Approved </div>';
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
