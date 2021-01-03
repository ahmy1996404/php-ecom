<?php
// ===================================================
// Items Page
// ===================================================
ob_start();

session_start();
$pageTitle = 'Items';
if (isset($_SESSION['Username'])){

          include 'init.php';

          if ( isset($_GET['do'])){ $do = $_GET['do'];}
          else { $do = 'Manage';}

          // start manage page

          if ($do == 'Manage'){// manage members page

             $stmt = $con->Prepare("SELECT
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
                                    ORDER BY
                                          item_ID DESC");
          // execute stmt
            $stmt->execute();
            // assign to variables
            $items = $stmt->fetchAll();
            if (!empty($items)){
            ?>
            <h1 class="text-center">Manage Items</h1>
            <div class="container">
              <div class="table-responsive">
                <table class="main-table text-center table table-bordered">
                  <tr>
                    <td>#id</td>
                    <td>Name</td>
                    <td>Description</td>
                    <td>Price</td>
                    <td>Adding Date</td>
                    <td>Category</td>
                    <td>Username</td>
                    <td>Control</td>
                  </tr>
                  <?php
                  foreach ($items as $item) {
                    echo "<tr>";
                    echo "<td>".$item['item_ID']."</td>";
                    echo "<td>".$item['Name']."</td>";
                    echo "<td>".$item['Description']."</td>";
                    echo "<td>".$item['Price']."</td>";
                    echo "<td>".$item['Add_Date']."</td>";
                    echo "<td>".$item['category_name']."</td>";
                    echo "<td>".$item['UserName']."</td>";
                    echo "<td>
                    <a href='items.php?do=Edit&itemid=".$item['item_ID']."' class='btn btn-success'><i class='fas fa-edit'></i>Edit</a>
                    <a href='items.php?do=Delete&itemid=".$item['item_ID']."' class='btn btn-danger confirm'><i class='fas fa-user-minus'></i>Delete</a>";
                    if ($item['Approve']== 0){
                      echo "<a href='items.php?do=Approve&itemid=".$item['item_ID']."' class='btn btn-info activate'><i class='fas fa-check'></i>Approve</a>";

                    }
                    "</td>";
                    echo"</tr>";
                  }

                  ?>

                </table>
              </div>
              <a href="items.php?do=Add" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i>New Item</a>
            </div>
                <?php
                }
                else {
                  echo '<div class="container">';
                    echo '<div class ="nice-message">
                      There Is No Items To Show
                    </div> ';
                    echo '<a href="items.php?do=Add" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i>New Item</a>';
                  echo '</div>';
                }
                 ?>
          <?PHP
           }elseif ($do == 'Add') {
             ?>

               <h1 class="text-center">Add New Item</h1>
               <div class="container">
                 <form class="form-horizontal" action="?do=Insert" method="POST">
                   <!-- start   name feild -->
                   <div class="form-group form-group-lg">
                     <label class="col-sm-2 control-label">Name</label>
                     <div class="col-sm-10 col-md-4">
                       <input type="text" name="name" class="form-control"  required="required" placeholder="Name of Item"/>
                     </div>
                   </div>
                   <!-- end  name -->
                   <!-- start   description feild -->
                   <div class="form-group form-group-lg">
                     <label class="col-sm-2 control-label">Description</label>
                     <div class="col-sm-10 col-md-4">
                       <input type="text" name="description" class="form-control"  required="required" placeholder="Description of Item"/>
                     </div>
                   </div>
                   <!-- end  description -->
                   <!-- start   price feild -->
                   <div class="form-group form-group-lg">
                     <label class="col-sm-2 control-label">Price</label>
                     <div class="col-sm-10 col-md-4">
                       <input type="text" name="price" class="form-control"  required="required" placeholder="Price of Item"/>
                     </div>
                   </div>
                   <!-- end  price -->
                   <!-- start   country feild -->
                   <div class="form-group form-group-lg">
                     <label class="col-sm-2 control-label">Country</label>
                     <div class="col-sm-10 col-md-4">
                       <input type="text" name="country" class="form-control"  required="required" placeholder="Country of Made"/>
                     </div>
                   </div>
                   <!-- end  country -->
                   <!-- start   Statues feild -->
                   <div class="form-group form-group-lg">
                     <label class="col-sm-2 control-label">Status</label>
                     <div class="col-sm-10 col-md-4">
                       <select   name="status">
                         <option value="0">...</option>
                         <option value="1">New</option>
                         <option value="2">Like New</option>
                         <option value="3">Used</option>
                         <option value="4">Old</option>
                       </select>
                     </div>
                   </div>
                   <!-- end  Statues -->
                   <!-- start   members feild -->
                   <div class="form-group form-group-lg">
                     <label class="col-sm-2 control-label">Member</label>
                     <div class="col-sm-10 col-md-4">
                       <select   name="member">
                         <option value="0">...</option>
                         <?php
                         $allMembers = getAllFrom("*" , "users" , "", "" , "UserID" );

                         foreach ($allMembers as $user ) {
                           echo "<option value='" .$user['UserID']. "'>" .$user['Username']. "</option>";
                         }
                          ?>
                       </select>
                     </div>
                   </div>
                   <!-- end  members -->
                   <!-- start   Category feild -->
                   <div class="form-group form-group-lg">
                     <label class="col-sm-2 control-label">Category</label>
                     <div class="col-sm-10 col-md-4">
                       <select   name="category">
                         <option value="0">...</option>
                         <?php
                         $allCats = getAllFrom("*" , "categories" , "WHERE parent=0", "" , "ID" );

                         foreach ($allCats as $cat ) {
                           echo "<option value='" .$cat['ID']. "'>" .$cat['Name']. "</option>";
                           $childCats = getAllFrom("*" , "categories" , "WHERE parent={$cat['ID']}", "" , "ID" );
                           foreach ($childCats as $child) {
                             echo "<option value='" .$child['ID']. "'>--- " .$child['Name']. "</option>";

                           }

                         }
                          ?>
                       </select>
                     </div>
                   </div>
                   <!-- end  Category -->
                   <!-- start   Tags feild -->
                   <div class="form-group form-group-lg">
                     <label class="col-sm-2 control-label">Tags</label>
                     <div class="col-sm-10 col-md-4">
                       <input type="text" name="tags" class="form-control"    placeholder="Separate Tags With Comma (,)"/>
                     </div>
                   </div>
                   <!-- end  tags -->
                   <!-- start submit feild -->
                   <div class="form-group form-group-lg">
                     <div class="col-sm-offset-2 col-sm-10">
                       <input type="submit" value="Add Item" class="btn btn-primary btn-sm" />
                     </div>
                   </div>
                   <!-- end submit -->

                 </form>
               </div>

               <?php

           }elseif ($do == 'Insert') {// insert page

           if ($_SERVER['REQUEST_METHOD'] == 'POST'){
             echo  "<h1 class='text-center'>Insert item</h1>";
             echo "<div class='container'>";
           // get the variables from the date_create_from_form
           $name =$_POST['name'];
           $desc =$_POST['description'];
           $price=$_POST['price'];
           $country =$_POST['country'];
           $status =$_POST['status'];
           $member =$_POST['member'];
           $cat =$_POST['category'];
           $tags = $_POST['tags'];
           // validate the form
           $formErrors = array();
           if (empty($name)){
             $formErrors[] = '<div class="alert alert-danger"> Name canot be <strong>empty</strong> </div>';

           }
           if (empty($desc)){
             $formErrors[] = '<div class="alert alert-danger">  Description canot be <strong>empty</strong> </div>';

           }
           if (empty($price)){
             $formErrors[] = '<div class="alert alert-danger">  Price canot be <strong>empty</strong></div>';

           }
           if (empty($country)){
             $formErrors[] = '<div class="alert alert-danger">  Country  canot be <strong>empty</strong></div>';

           }
           if ($status == 0){
             $formErrors[] ='<div class="alert alert-danger">  You must choose <strong>status</strong></div>';
           }
           if ($member == 0){
             $formErrors[] ='<div class="alert alert-danger">  You must choose <strong>member</strong></div>';
           }
           if ($cat == 0){
             $formErrors[] ='<div class="alert alert-danger">  You must choose <strong>category</strong></div>';
           }
           // loop into error aray and print it
           foreach ($formErrors as $error) {
           echo $error ;
           }

           //check if no error proced update
                   if (empty($formErrors)){

                     //insert user into the database with this Info
                     $stmt= $con->prepare("INSERT INTO
                                          items ( Name , Description  , Price  , Country_Made , Status , Add_Date , Cat_ID , Member_ID , tags)
                                          VALUES(:zname , :zdesc , :zprice , :zcountry , :zstatus , now() , :zcat , :zmember , :ztags )");
                     $stmt->execute(array(
                       'zname' => $name ,
                       'zdesc' => $desc ,
                       'zprice' => $price ,
                       'zcountry' => $country ,
                       'zstatus' => $status ,
                       'zcat'=> $cat ,
                       'zmember'=> $member,
                       'ztags'=> $tags


                     ));
                     // echo succes message
                     $theMsg = "<div class='alert alert-success'>".$stmt->rowCount() . 'Record Inserted </div>';
                     redirectHome($theMsg,'back');

                   }
           }else {
             echo "<div class='container'>";
             $theMsg ='<div class="alert alert-danger">sorry you canot browse this page dirct</div>';
             redirectHome($theMsg );
             echo "</div>";

           }
           echo "</div>";


           }elseif ($do == 'Edit') {
             // Edit Page

              // check if get request item id is numeric and get th int vaue of it
              if(isset($_GET['itemid']) && is_numeric($_GET['itemid']) ){
                $itemid = intval($_GET['itemid']);
              } else{
                $itemid = 0;
              }
              // select all data depend on this id
              $stmt= $con->prepare("select
                                    *
                                   from
                                         `items`
                                    where
                                         item_ID=?
                                           ");
               // execute query
                $stmt->execute(array($itemid));
                // fetch the data
                $item = $stmt->fetch();
                // the row count
                $count = $stmt->rowCount();
                // if there is such id show the form
                if($stmt->rowCount()>0){


              // echo $userid;
              ?>

                             <h1 class="text-center">Edit Item</h1>
                             <div class="container">
                               <form class="form-horizontal" action="?do=Update" method="POST">
                                 <input type="hidden" name="itemid" value="<?php echo $itemid?>"/>

                                 <!-- start   name feild -->
                                 <div class="form-group form-group-lg">
                                   <label class="col-sm-2 control-label">Name</label>
                                   <div class="col-sm-10 col-md-4">
                                     <input type="text" name="name" class="form-control"  required="required" placeholder="Name of Item" value="<?php echo $item['Name'] ?>"/>
                                   </div>
                                 </div>
                                 <!-- end  name -->
                                 <!-- start   description feild -->
                                 <div class="form-group form-group-lg">
                                   <label class="col-sm-2 control-label">Description</label>
                                   <div class="col-sm-10 col-md-4">
                                     <input type="text" name="description" class="form-control"  required="required" placeholder="Description of Item" value="<?php echo $item['Description'] ?>"/>
                                   </div>
                                 </div>
                                 <!-- end  description -->
                                 <!-- start   price feild -->
                                 <div class="form-group form-group-lg">
                                   <label class="col-sm-2 control-label">Price</label>
                                   <div class="col-sm-10 col-md-4">
                                     <input type="text" name="price" class="form-control"  required="required" placeholder="Price of Item" value="<?php echo $item['Price'] ?>" />
                                   </div>
                                 </div>
                                 <!-- end  price -->
                                 <!-- start   country feild -->
                                 <div class="form-group form-group-lg">
                                   <label class="col-sm-2 control-label">Country</label>
                                   <div class="col-sm-10 col-md-4">
                                     <input type="text" name="country" class="form-control"  required="required" placeholder="Country of Made" value="<?php echo $item['Country_Made'] ?>"/>
                                   </div>
                                 </div>
                                 <!-- end  country -->
                                 <!-- start   Statues feild -->
                                 <div class="form-group form-group-lg">
                                   <label class="col-sm-2 control-label">Status</label>
                                   <div class="col-sm-10 col-md-4">
                                     <select   name="status">
                                       <option value="1"<?php if($item['Status']==1){ echo 'selected' ;} ?>>New</option>
                                       <option value="2"<?php if($item['Status']==2){ echo 'selected' ;} ?>>Like New</option>
                                       <option value="3"<?php if($item['Status']==3){ echo 'selected' ;} ?>>Used</option>
                                       <option value="4"<?php if($item['Status']==4){ echo 'selected' ;} ?>>Old</option>
                                     </select>
                                   </div>
                                 </div>
                                 <!-- end  Statues -->
                                 <!-- start   members feild -->
                                 <div class="form-group form-group-lg">
                                   <label class="col-sm-2 control-label">Member</label>
                                   <div class="col-sm-10 col-md-4">
                                     <select   name="member">
                                       <?php
                                       $allMembers = getAllFrom("*" , "users" , "", "" , "UserID" );
                                       foreach ($allMembers as $user ) {
                                         echo "<option value='" .$user['UserID']. "'";
                                        if($item['Member_ID']==$user['UserID']){
                                          echo 'selected' ;
                                        }
                                        echo">" .$user['Username']. "</option>";
                                       }
                                        ?>
                                     </select>
                                   </div>
                                 </div>
                                 <!-- end  members -->
                                 <!-- start   Category feild -->
                                 <div class="form-group form-group-lg">
                                   <label class="col-sm-2 control-label">Category</label>
                                   <div class="col-sm-10 col-md-4">
                                     <select   name="category">
                                       <?php
                                       $stmt2 = $con->prepare('SELECT * FROM categories');
                                       $stmt2->execute();
                                       $cats = $stmt2->fetchAll();
                                       foreach ($cats as $cat ) {
                                         echo "<option value='" .$cat['ID']. "'";
                                         if($item['Cat_ID']==$cat['ID']){
                                           echo 'selected' ;
                                         }
                                        echo " >" .$cat['Name']. "</option>";
                                       }
                                        ?>
                                     </select>
                                   </div>
                                 </div>
                                 <!-- end  Category -->
                                 <!-- start   Tags feild -->
                                 <div class="form-group form-group-lg">
                                   <label class="col-sm-2 control-label">Tags</label>
                                   <div class="col-sm-10 col-md-4">
                                     <input type="text" name="tags" class="form-control"    placeholder="Separate Tags With Comma (,)"  value="<?php echo $item['tags'] ?>"/>
                                   </div>
                                 </div>
                                 <!-- end  tags -->
                                 <!-- start submit feild -->
                                 <div class="form-group form-group-lg">
                                   <div class="col-sm-offset-2 col-sm-10">
                                     <input type="submit" value="Save Item" class="btn btn-primary btn-sm" />
                                   </div>
                                 </div>
                                 <!-- end submit -->

                               </form>

                               <?php
                                 // select all users except admin
                                 $stmt = $con->Prepare("SELECT
                                                             comments.* , users.Username
                                                       from
                                                             comments
                                                       INNER join
                                                             users
                                                       ON
                                                             users.UserID = comments.user_id
                                                        WHERE item_id=?");
                               // execute stmt
                                 $stmt->execute(array($itemid));
                                 // assign to variables
                                 $rows = $stmt->fetchAll();

                                 if (! empty($rows)){
                                 ?>
                                 <h1 class="text-center">Manage [ <?php echo $item['Name'] ?> ] Comments</h1>
                                   <div class="table-responsive">
                                     <table class="main-table text-center table table-bordered">
                                       <tr>
                                         <td>Comment</td>
                                         <td>User Name</td>
                                         <td>Added Date</td>
                                         <td>Control</td>
                                       </tr>
                                       <?php
                                       foreach ($rows as $row) {
                                         echo "<tr>";
                                         echo "<td>".$row['comment']."</td>";
                                         echo "<td>".$row['Username']."</td>";
                                         echo "<td>".$row['comment_date']."</td>";
                                         echo "<td>
                                         <a href='comments.php?do=Edit&comid=".$row['c_id']."' class='btn btn-success'><i class='fas fa-edit'></i>Edit</a>
                                         <a href='comments.php?do=Delete&comid=".$row['c_id']."' class='btn btn-danger confirm'><i class='fas fa-user-minus'></i>Delete</a>";
                                         if ($row['status']== 0){
                                           echo "<a href='comments.php?do=Approve&comid=".$row['c_id']."' class='btn btn-info activate'><i class='fas fa-check'></i>Approve</a>";

                                         }
                                         "</td>";
                                         echo"</tr>";
                                       }

                                       ?>

                                     </table>
                                   </div>
                                 <?php } ?>
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

           }else if ($do== 'Update'){

             echo  "<h1 class='text-center'>Update Item</h1>";
             echo "<div class='container'>";
             if ($_SERVER['REQUEST_METHOD'] == 'POST'){
             // get the variables from the date_create_from_form
             $id   =$_POST['itemid'];
             $name =$_POST['name'];
             $desc =$_POST['description'];
             $price=$_POST['price'];
             $country =$_POST['country'];
             $status =$_POST['status'];
             $member =$_POST['member'];
             $cat =$_POST['category'];
             $tags =$_POST['tags'];


             // validate the form
             $formErrors = array();
             if (empty($name)){
               $formErrors[] = '<div class="alert alert-danger"> Name canot be <strong>empty</strong> </div>';

             }
             if (empty($desc)){
               $formErrors[] = '<div class="alert alert-danger">  Description canot be <strong>empty</strong> </div>';

             }
             if (empty($price)){
               $formErrors[] = '<div class="alert alert-danger">  Price canot be <strong>empty</strong></div>';

             }
             if (empty($country)){
               $formErrors[] = '<div class="alert alert-danger">  Country  canot be <strong>empty</strong></div>';

             }
             if ($status == 0){
               $formErrors[] ='<div class="alert alert-danger">  You must choose <strong>status</strong></div>';
             }
             if ($member == 0){
               $formErrors[] ='<div class="alert alert-danger">  You must choose <strong>member</strong></div>';
             }
             if ($cat == 0){
               $formErrors[] ='<div class="alert alert-danger">  You must choose <strong>category</strong></div>';
             }
             // loop into error aray and print it
             foreach ($formErrors as $error) {
             echo $error ;
             }

             //check if no error proced update
                     if (empty($formErrors)){
                       //update the database with this Info

                       $stmt =$con->prepare("UPDATE
                                                  `items`
                                              SET Name = ? ,
                                                  Description = ? ,
                                                  Price =? ,
                                                  Country_Made = ? ,
                                                  Status = ? ,
                                                  Cat_ID = ? ,
                                                  Member_ID = ?,
                                                  tags = ?

                                              where
                                                  item_ID = ?");
                       $stmt->execute(array($name , $desc , $price , $country , $status ,$cat, $member ,$tags, $id  ));
                       // echo succes message
                       $theMsg ="<div class='alert alert-success'>".$stmt->rowCount() . 'Record Updated </div>';
                       redirectHome($theMsg , 'back' );


                     }
             }else {
               $theMsg =  '<div class="alert alert-danger">sorry you canot browse this page dirct</div>';
               redirectHome($theMsg );

             }
             echo "</div";

           }elseif($do == 'Delete'){
             echo  "<h1 class='text-center'>Delete Item</h1>";
             echo "<div class='container'>";
               // check if get request itemid is numeric and get th int vaue of it
               if(isset($_GET['itemid']) && is_numeric($_GET['itemid']) ){
                 $itemid = intval($_GET['itemid']);
               } else{
                 $itemid = 0;
               }
               // select  data depend on this id

               $check = checkItem('item_ID','items',$itemid);
                 // if there is such id show the form
                 if(  $check>0){
                   $stmt = $con->prepare("DELETE FROM items WHERE item_ID = :zid");
                   $stmt->bindParam(":zid", $itemid);
                   $stmt->execute();
                   $theMsg = "<div class='alert alert-success'>".$stmt->rowCount() . 'Record Deleted </div>';
                   redirectHome($theMsg,'back');
                 }
                 else{
                   $theMsg =  "<div class='alert alert-danger'>This Id is Not Exist</div>";
                   redirectHome($theMsg, 'back');

                 }
                 echo "</div>";
           }
          elseif($do == 'Approve'){
            echo  "<h1 class='text-center'>Approve Item</h1>";
            echo "<div class='container'>";
              // check if get request itemid is numeric and get th int vaue of it
              if(isset($_GET['itemid']) && is_numeric($_GET['itemid']) ){
                $itemid = intval($_GET['itemid']);
              } else{
                $itemid = 0;
              }
              // select  data depend on this id

              $check = checkItem('item_ID','items',$itemid);
                // if there is such id show the form
                if(  $check>0){
                  $stmt = $con->prepare("UPDATE items SET Approve = 1 WHERE item_ID = ?");
                  $stmt->execute(array($itemid));
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
ob_end_flush();

 ?>
