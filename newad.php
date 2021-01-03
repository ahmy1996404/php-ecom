<?php
  session_start();
  $pageTitle='new ad';
  include 'init.php';
  if(isset($_SESSION['user'])){
    if($_SERVER['REQUEST_METHOD']=='POST'){
      $formErrors = array();

      $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
      $desc =filter_var($_POST['description'] , FILTER_SANITIZE_STRING);
      $price =filter_var($_POST['price'] , FILTER_SANITIZE_NUMBER_INT);
      $country =filter_var($_POST['country'] ,FILTER_SANITIZE_STRING);
      $status =filter_var($_POST['status'] , FILTER_SANITIZE_NUMBER_INT);
      $category =filter_var($_POST['category'] ,FILTER_SANITIZE_NUMBER_INT );
      $tags =filter_var($_POST['tags'] ,FILTER_SANITIZE_STRING );

      if(strlen($name)<4){
        $formErrors[]= 'Item title Maust Be At Least 4 Chars';
      }

      if(strlen($desc)<10){
        $formErrors[]= 'Item Description Maust Be At Least 10 Chars';
      }

      if(strlen($country)<2){
        $formErrors[]= 'Item Country Maust Be At Least 2 Chars';
      }

      if(empty($price)){
        $formErrors[]= 'Item Price Maust Be Not Empty';
      }

      if(empty($status)){
        $formErrors[]= 'Item Price Maust Be Not Empty';
      }

      if(empty($category)){
        $formErrors[]= 'Item Price Maust Be Not Empty';
      }
      //check if no error proced update
              if (empty($formErrors)){

                //insert user into the database with this Info
                $stmt= $con->prepare("INSERT INTO
                                     items ( Name , Description  , Price  , Country_Made , Status , Add_Date , Cat_ID , Member_ID ,tags )
                                     VALUES(:zname , :zdesc , :zprice , :zcountry , :zstatus , now() , :zcat , :zmember , :ztags )");
                $stmt->execute(array(
                  'zname' => $name ,
                  'zdesc' => $desc ,
                  'zprice' => $price ,
                  'zcountry' => $country ,
                  'zstatus' => $status ,
                  'zcat'=> $category ,
                  'zmember'=> $_SESSION['uid'],
                  'ztags'=> $tags ,


                ));
                // echo succes message
                if($stmt){

                  $succesMsg = 'Item Added';

                }
              }

    }
?>
  <h1 class="text-center">Create New Ad</h1>
  <div class="creat-ad block">
    <div class="container">
      <div class="card card-primary">
        <div class="card-header">
          Create New Ad
        </div>
        <div class="card-body">
          <div class="row">

            <div class="col-md-8">
                  <form class="form-horizontal main-form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                    <!-- start   name feild -->
                    <div class="form-group form-group-lg">
                      <label class="col-sm-2 control-label">Name</label>
                      <div class="col-sm-10 col-md-9">
                        <input type="text" name="name" class="form-control  live-name"  required="required" placeholder="Name of Item"/>
                      </div>
                    </div>
                    <!-- end  name -->
                    <!-- start   description feild -->
                    <div class="form-group form-group-lg">
                      <label class="col-sm-2 control-label">Description</label>
                      <div class="col-sm-10 col-md-9">
                        <input type="text" name="description" class="form-control   live-desc"  required="required" placeholder="Description of Item"/>
                      </div>
                    </div>
                    <!-- end  description -->
                    <!-- start   price feild -->
                    <div class="form-group form-group-lg">
                      <label class="col-sm-2 control-label">Price</label>
                      <div class="col-sm-10 col-md-9">
                        <input type="text" name="price" class="form-control   live-price"  required="required" placeholder="Price of Item"/>
                      </div>
                    </div>
                    <!-- end  price -->
                    <!-- start   country feild -->
                    <div class="form-group form-group-lg">
                      <label class="col-sm-2 control-label">Country</label>
                      <div class="col-sm-10 col-md-9">
                        <input type="text" name="country" class="form-control"  required="required" placeholder="Country of Made"/>
                      </div>
                    </div>
                    <!-- end  country -->
                    <!-- start   Statues feild -->
                    <div class="form-group form-group-lg">
                      <label class="col-sm-2 control-label">Status</label>
                      <div class="col-sm-10 col-md-9">
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

                    <!-- start   Category feild -->
                    <div class="form-group form-group-lg">
                      <label class="col-sm-2 control-label">Category</label>
                      <div class="col-sm-10 col-md-9">
                        <select   name="category">
                          <option value="0">...</option>
                          <?php
                          $cats =  getAllFrom('*','categories','','','ID');

                          foreach ($cats as $cat ) {
                            echo "<option value='" .$cat['ID']. "'>" .$cat['Name']. "</option>";
                          }
                           ?>
                        </select>
                      </div>
                    </div>
                    <!-- end  Category -->
                    <!-- start   Tags feild -->
                    <div class="form-group form-group-lg">
                      <label class="col-sm-3 control-label">Tags</label>
                      <div class="col-sm-10 col-md-9">
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
            <div class="col-md-4">
               <div class="img-thumbnail item-box live-preview">
                 <span class="price-tag">0</span>
                  <img class="img-fluid" src="img.png" alt="" />
                    <div class="caption">'
                      <h3>title</h3>
                      <p>description </p>
                    </div>
              </div>
            </div>

          </div>
          <!-- start looping throght errors -->
          <?php
          if(!empty($formErrors)){
            foreach ($formErrors as $error ) {
              echo '<div class="alert alert-danger">'.$error.'</div>';
            }
          }
          if(isset($succesMsg)){
            echo '<div class="alert alert-success">'.$succesMsg .'</div>';

          }

           ?>
          <!-- end looping throght errors -->

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

?>
