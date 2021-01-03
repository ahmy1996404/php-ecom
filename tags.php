<?php
 session_start();
  include 'init.php';
  ?>

<div class="container">
    <?php
    if(isset($_GET['name'])){
      $tag = $_GET['name'];
    echo  '<h1 class="text-center">'.$tag.'</h1>';
    echo '<div class="row">';

    $tagitems = getAllFrom("*" , "items" , "where tags like '%$tag%' ", "AND Approve = 1", "item_ID");
    foreach ($tagitems as $item) {
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
    echo "You Must Enter Tag Name";
    }

    ?>
  </div>
</div>

<?php
  include $tpl.'footer.php';
?>
