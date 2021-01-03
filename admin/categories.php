<?php
// ===================================================
// category Page
// ===================================================
ob_start();

session_start();
$pageTitle = 'Categories';
if (isset($_SESSION['Username'])){

          include 'init.php';

          if ( isset($_GET['do']))
          {
             $do = $_GET['do'];
           }
          else {
             $do = 'Manage';
           }


          if ($do == 'Manage'){
            $sort ='ASC';
            $sort_array = array('ASC', 'DESC');
            if (isset($_GET['sort']) && in_array($_GET['sort'],$sort_array)){
              $sort = $_GET['sort'];
            }
            $stmt2 = $con->prepare("SELECT * FROM categories WHERE parent = 0 ORDER BY Ordering $sort ");

            $stmt2->execute();

            $cats = $stmt2->fetchAll();
            ?>

            <h1 class="text-center">Manage Categories</h1>
            <div class="container categories">
                  <div class="card">
                    <div class="card-header">
                    <i class="fa fa-edit"></i> Manage Categories
                      <div class="option float-right">
                      <i class="fa fa-sort"></i> Ordering: [
                        <a class="<?php if ($sort == 'ASC'){ echo 'active';} ?>" href="?sort=ASC">Asc</a> |
                        <a class="<?php if ($sort == 'DESC'){ echo 'active';} ?>" href="?sort=DESC">Desc</a> ]
                        <i class="fa fa-eye"></i> View : [
                        <span class="active" data-view="full">Full</span> |
                        <span data-view="classic">Classic</span> ]
                      </div>
                    </div>
                    <div class="card-body">
                      <?php
                     foreach ($cats as $cat){
                       echo "<div class='cat'>";
                          echo "<div class='hidden-buttons'>";
                          echo "<a href='categories.php?do=Edit&catid=" .$cat['ID']."' class='btn btn-sm btn-primary'><i class='fa fa-edit'></i> Edit</a>";
                          echo "<a href='categories.php?do=Delete&catid=" .$cat['ID']."' class='confirm btn btn-sm btn-danger'><i class='fas fa-user-minus'></i> Delete</a>";
                          echo "</div>";

                          echo "<h3>".$cat['Name'].'</h3>';
                          echo "<div class='full-view'>";
                                echo "<p>"; if($cat['Description']==''){echo 'This Category has no description';} else {echo $cat['Description'];} echo"</p>";
                                if($cat['Visability'] == 1 ){ echo '<span class="visability"><i class="fa fa-eye"></i> Hidden</span>';}
                                if($cat['Allow_Comment'] == 1 ){ echo '<span class="commenting"><i class="fas fa-times"></i> Comment Disabled</span>';}
                                if($cat['Allow_Ads'] == 1 ){ echo '<span class="advertises"><i class="fas fa-times"></i> Ads Disabled</span>';}
                                // get child cats
                                $childCats =  getAllFrom("*" , "categories" ,"WHERE parent = {$cat['ID']}", "" , "ID" ,'ASC');
                                if(!empty($childCats)){
                                  echo '<h4 class="child-head">Child Categories</h4>';
                                  echo '<ul class="list-unstyled child-cats">';
                                  foreach ($childCats as $c) {
                                    echo "<li  class='child-link'>
                                    <a href='categories.php?do=Edit&catid=" .$c['ID']."'>".$c['Name'].'</a>';
                                    echo "<a href='categories.php?do=Delete&catid=" .$c['ID']."' class='show-delete confirm'> Delete</a>";
                                    echo '</li>';
                                  }
                                  echo '</ul>';

                                }
                          echo "</div>";
                        echo "</div>";

                        echo "<hr />";
                     }
                        ?>
                    </div>
                  </div>
                  <a class="add-category btn btn-primary" href="categories.php?do=Add"><i class="fa fa-plus"></i>Add New Category</a>
            </div>


            <?php

          }elseif ($do == 'Add') { ?>

            <h1 class="text-center">Add New Category</h1>
            <div class="container">
              <form class="form-horizontal" action="?do=Insert" method="POST">
                <!-- start   name feild -->
                <div class="form-group form-group-lg">
                  <label class="col-sm-2 control-label">Name</label>
                  <div class="col-sm-10 col-md-4">
                    <input type="text" name="name" class="form-control" autocomplete="off" required="required" placeholder="Name of category"/>
                  </div>
                </div>
                <!-- end  name -->
                <!-- start description feild -->
                <div class="form-group form-group-lg">
                  <label class="col-sm-2 control-label">Description</label>
                  <div class="col-sm-10 col-md-4">

                    <input type="text" name="description" class="form-control"    placeholder="Describe the category"/>
                    <!-- <i class="show-pass fas fa-eye fa-2x"></i> -->
                  </div>
                </div>
                <!-- end description -->
                <!-- start ordering feild -->
                <div class="form-group form-group-lg">
                  <label class="col-sm-2 control-label">Ordering</label>
                  <div class="col-sm-10 col-md-4">
                    <input type="text" name="ordering" class="form-control" placeholder="Number to arrenge the Categories" />
                  </div>
                </div>
                <!-- end ordering -->
                <!-- start category_type feild -->
                <div class="form-group form-group-lg">
                  <label class="col-sm-2 control-label">Category Parent</label>
                  <div class="col-sm-10 col-md-4">
                    <select name="parent">
                      <option value="0">None</option>
                      <?php
                      $allCats =  getAllFrom("*" , "categories" , "WHERE parent = 0" ,"" , "ID" , $ordering = 'ASC');
                      foreach ($allCats as $cat) {
                        echo "<option value='".$cat['ID']."'>".$cat['Name']."</option>";
                      }
                       ?>
                    </select>
                  </div>
                </div>
                <!-- end category_type -->
                <!-- start visability feild -->
                <div class="form-group form-group-lg">
                  <label class="col-sm-2 control-label">Visible</label>
                  <div class="col-sm-10 col-md-4">
                    <div>
                      <input id="vis-yes" type="radio" name="visability" value="0" checked />
                      <lable for="vis-yes">Yes</lable>
                    </div>
                    <div>
                      <input id="vis-no"  type="radio" name="visability" value="1" />
                      <lable for="vis-no">No</lable>
                    </div>
                  </div>
                </div>
                <!-- end visability -->
                <!-- start commenting feild -->
                <div class="form-group form-group-lg">
                  <label class="col-sm-2 control-label">Allow Commenting</label>
                  <div class="col-sm-10 col-md-4">
                    <div>
                      <input id="com-yes" type="radio" name="commenting" value="0" checked />
                      <lable for="com-yes">Yes</lable>
                    </div>
                    <div>
                      <input id="com-no"  type="radio" name="commenting" value="1" />
                      <lable for="com-no">No</lable>
                    </div>
                  </div>
                </div>
                <!-- end commenting -->
                <!-- start commenting feild -->
                <div class="form-group form-group-lg">
                  <label class="col-sm-2 control-label">Allow Ads</label>
                  <div class="col-sm-10 col-md-4">
                    <div>
                      <input id="ads-yes" type="radio" name="ads" value="0" checked />
                      <lable for="ads-yes">Yes</lable>
                    </div>
                    <div>
                      <input id="ads-no"  type="radio" name="ads" value="1" />
                      <lable for="ads-no">No</lable>
                    </div>
                  </div>
                </div>
                <!-- end commenting -->
                <!-- start submit feild -->
                <div class="form-group form-group-lg">
                  <div class="col-sm-offset-2 col-sm-10">
                    <input type="submit" value="Add Category" class="btn btn-primary btn-lg" />
                  </div>
                </div>
                <!-- end submit -->

              </form>
            </div>

            <?php
           }elseif ($do == 'Insert') {
                   if ($_SERVER['REQUEST_METHOD'] == 'POST'){
                     echo  "<h1 class='text-center'>Insert Category</h1>";
                     echo "<div class='container'>";
                   // get the variables from the form
                   $name =$_POST['name'];
                   $desc =$_POST['description'];
                   $parent=$_POST['parent'];

                   $order=$_POST['ordering'];

                   $visable =$_POST['visability'];
                   $comment =$_POST['commenting'];

                   $ads =$_POST['ads'];


                   //check if no error proced update

                             // check if caregory exist in database
                             $check =  checkItem("Name","categories",$name);
                             if ($check == 1){

                               $theMsg =  "<div class= 'alert alert-danger'>Sorry this category is exist </div>";
                               redirectHome($theMsg , 'back');
                             }else{
                             //insert Category into the database with this Info
                             $stmt= $con->prepare("INSERT INTO
                                                  categories ( Name , Description	, parent  , Ordering  , Visability ,Allow_Comment, Allow_Ads)
                                                  VALUES(:zname , :zdesc ,:zparent , :zorder , :zvisable ,:zcomment, :zads)");
                             $stmt->execute(array(
                               'zname' => $name ,
                               'zdesc' => $desc,
                               'zparent' => $parent,
                               'zorder' => $order ,
                               'zvisable' => $visable,
                               'zcomment' => $comment,
                               'zads' => $ads


                             ));
                             // echo succes message
                             $theMsg = "<div class='alert alert-success'>".$stmt->rowCount() . 'Record Inserted </div>';
                             redirectHome($theMsg,'back');
                             }

                   }else {
                     echo "<div class='container'>";
                     $theMsg ='<div class="alert alert-danger">sorry you canot browse this page dirct</div>';
                     redirectHome($theMsg , 'back' );
                     echo "</div>";

                   }
                   echo "</div>";


           }elseif ($do == 'Edit') {  // Edit Page

             // check if get request cat id is numeric and get th int vaue of it
             if(isset($_GET['catid']) && is_numeric($_GET['catid']) ){
               $catid = intval($_GET['catid']);
             } else{
               $catid = 0;
             }
             // select all data depend on this id
             $stmt= $con->prepare("select
                                   *
                                  from
                                        `categories`
                                   where
                                        ID=?
                                  ");
              // execute query
               $stmt->execute(array($catid));
               // fetch the data
               $cat = $stmt->fetch();
               // the row count
               $count = $stmt->rowCount();
               // if there is such id show the form
               if($stmt->rowCount()>0){


             // echo $userid;
             ?>

                         <h1 class="text-center">Edit Category</h1>
                         <div class="container">
                           <form class="form-horizontal" action="?do=Update" method="POST">
                             <input type="hidden" name="catid" value="<?php echo $catid?>"/>

                             <!-- start   name feild -->
                             <div class="form-group form-group-lg">
                               <label class="col-sm-2 control-label">Name</label>
                               <div class="col-sm-10 col-md-4">
                                 <input type="text" name="name" class="form-control"   required="required" placeholder="Name of category" value="<?php echo $cat['Name'];?>"/>
                               </div>
                             </div>
                             <!-- end  name -->
                             <!-- start description feild -->
                             <div class="form-group form-group-lg">
                               <label class="col-sm-2 control-label">Description</label>
                               <div class="col-sm-10 col-md-4">

                                 <input type="text" name="description" class="form-control"    placeholder="Describe the category" value="<?php echo $cat['Description'];?>"/>
                                 <!-- <i class="show-pass fas fa-eye fa-2x"></i> -->
                               </div>
                             </div>
                             <!-- end description -->
                             <!-- start ordering feild -->
                             <div class="form-group form-group-lg">
                               <label class="col-sm-2 control-label">Ordering</label>
                               <div class="col-sm-10 col-md-4">
                                 <input type="text" name="ordering" class="form-control" placeholder="Number to arrenge the Categories" value="<?php echo $cat['Ordering'];?>" />
                               </div>
                             </div>
                             <!-- end ordering -->
                             <!-- start category_type feild -->
                             <div class="form-group form-group-lg">
                               <label class="col-sm-2 control-label">Category Parent </label>
                               <div class="col-sm-10 col-md-4">
                                 <select name="parent">
                                   <option value="0">None</option>
                                   <?php
                                   $allCats =  getAllFrom("*" , "categories" , "WHERE parent = 0" ,"" , "ID" , $ordering = 'ASC');
                                   foreach ($allCats as $c) {
                                     echo "<option value='".$c['ID']."'";
                                     if($cat['parent']==$c['ID']){
                                       echo " selected ";
                                     }
                                     echo ">".$c['Name']."</option>";
                                   }
                                    ?>
                                 </select>
                               </div>
                             </div>
                             <!-- end category_type -->
                             <!-- start visability feild -->
                             <div class="form-group form-group-lg">
                               <label class="col-sm-2 control-label">Visible</label>
                               <div class="col-sm-10 col-md-4">
                                 <div>
                                   <input id="vis-yes" type="radio" name="visability" value="0" <?php if($cat['Visability']==0){echo "checked";}?>  />
                                   <lable for="vis-yes">Yes</lable>
                                 </div>
                                 <div>
                                   <input id="vis-no"  type="radio" name="visability" value="1"<?php if($cat['Visability']==1){echo "checked";}?> />
                                   <lable for="vis-no">No</lable>
                                 </div>
                               </div>
                             </div>
                             <!-- end visability -->
                             <!-- start commenting feild -->
                             <div class="form-group form-group-lg">
                               <label class="col-sm-2 control-label">Allow Commenting</label>
                               <div class="col-sm-10 col-md-4">
                                 <div>
                                   <input id="com-yes" type="radio" name="commenting" value="0" <?php if($cat['Allow_Comment']==0){echo "checked";}?>  />
                                   <lable for="com-yes">Yes</lable>
                                 </div>
                                 <div>
                                   <input id="com-no"  type="radio" name="commenting" value="1" <?php if($cat['Allow_Comment']==1){echo "checked";}?>/>
                                   <lable for="com-no">No</lable>
                                 </div>
                               </div>
                             </div>
                             <!-- end commenting -->
                             <!-- start commenting feild -->
                             <div class="form-group form-group-lg">
                               <label class="col-sm-2 control-label">Allow Ads</label>
                               <div class="col-sm-10 col-md-4">
                                 <div>
                                   <input id="ads-yes" type="radio" name="ads" value="0" <?php if($cat['Allow_Ads']==0){echo "checked";}?>  />
                                   <lable for="ads-yes">Yes</lable>
                                 </div>
                                 <div>
                                   <input id="ads-no"  type="radio" name="ads" value="1" <?php if($cat['Allow_Ads']==1){echo "checked";}?> />
                                   <lable for="ads-no">No</lable>
                                 </div>
                               </div>
                             </div>
                             <!-- end commenting -->
                             <!-- start submit feild -->
                             <div class="form-group form-group-lg">
                               <div class="col-sm-offset-2 col-sm-10">
                                 <input type="submit" value="Save" class="btn btn-primary btn-lg" />
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
             $theMsg = '<div class="alert alert-danger"> no such id</div>';
             redirectHome($theMsg);
             echo "</div>";

           }

           }else if ($do== 'Update'){

             echo  "<h1 class='text-center'>Update Category</h1>";
             echo "<div class='container'>";
             if ($_SERVER['REQUEST_METHOD'] == 'POST'){
             // get the variables from the date_create_from_form
             $id   =$_POST['catid'];
             $name =$_POST['name'];
             $desc=$_POST['description'];
             $order =$_POST['ordering'];
             $parent =$_POST['parent'];
             $visable =$_POST['visability'];
             $comment =$_POST['commenting'];
             $ads =$_POST['ads'];


                       //update the database with this Info

                       $stmt =$con->prepare("UPDATE
                                                `categories`
                                            SET
                                                Name = ? ,
                                                Description = ? ,
                                                Ordering =? ,
                                                parent =? ,
                                                Visability = ? ,
                                                Allow_Comment = ? ,
                                              	Allow_Ads = ?

                                            where
                                                ID = ?");
                       $stmt->execute(array($name , $desc , $order, $parent , $visable , $comment , $ads , $id ));
                       // echo succes message
                       $theMsg ="<div class='alert alert-success'>".$stmt->rowCount() . 'Record Updated </div>';
                       redirectHome($theMsg , 'back' );

             }else {
               $theMsg =  '<div class="alert alert-danger">sorry you canot browse this page dirct</div>';
               redirectHome($theMsg );

             }
             echo "</div";


           }elseif($do == 'Delete'){
             echo  "<h1 class='text-center'>Delete Category</h1>";
             echo "<div class='container'>";
               // check if get request cat id is numeric and get th int vaue of it
               if(isset($_GET['catid']) && is_numeric($_GET['catid']) ){
                 $catid = intval($_GET['catid']);
               } else{
                 $catid = 0;
               }
               // select  data depend on this id

               $check = checkItem('ID','categories',$catid);
                 // if there is such id show the form
                 if(  $check>0){
                   $stmt = $con->prepare("DELETE FROM categories WHERE ID = :zid");
                   $stmt->bindParam(":zid", $catid);
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

          include $tpl.'footer.php';

}
else{
  // echo 'you are not authorized to view this page';
  header('Location: index.php');
  exit();
}
