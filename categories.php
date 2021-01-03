<?php
 session_start();
  include 'init.php';
  ?>

<div class="container">
  <h1 class="text-center">Show Category Items</h1>
  <div class="row">
    <?php
    if(isset($_GET['pageid']) && is_numeric($_GET['pageid']) ){
      $catrgory = intval($_GET['pageid']);
    $allitems = getAllFrom("*" , "items" , "where Cat_ID = {$catrgory}", "AND Approve = 1", "item_ID");
    foreach ($allitems as $item) {
      echo '<div class="col-sm-6 col-md-3">';
        echo '<div class="img-thumbnail item-box">';
            echo '<span class="price-tag">'.$item['Price'].'</span>';
            echo '<img class="img-fluid" src="img.png" alt="" />';
              echo '<div class="caption">';
                echo '<h3>'.'<a href="items.php?itemid='. $item['item_ID'].'">'.$item['Name'].'</a>'.'</h3>';
                echo '<p>' . $item['Description']. ' </p>';
                echo '<div class="regdate">'.'<p>' . $item['Add_Date']. '</p>'.' </div>';
              echo '</div>';
        echo '</div>';
      echo '</div>';
      }
    } else{
    echo "You Must Add Page ID";
    }

    ?>
  </div>
</div>

<?php
  include $tpl.'footer.php';
?>
