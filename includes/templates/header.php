<!DOCKTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title><?php getTitle()?></title>

    <link rel="stylesheet" href="<?php echo $css; ?>all.css" />
    <link rel="stylesheet" href="<?php echo $css; ?>jquery-ui-1.9.1.custom.css" />
    <link rel="stylesheet" href="<?php echo $css; ?>jquery.selectBoxIt.css" />
    <link rel="stylesheet" href="<?php echo $css; ?>bootstrap.min.css" />
    <link rel="stylesheet" href="<?php echo $css; ?>frontend.css" />


  </head>
<body>
  <div class="upper-bar">
    <div class="container ">
      <?php
        $pageTitle='Login';
        // print_r($_SESSION);
        if (isset($_SESSION['user'])){ ?>

          <img class="my-image rounded-circle img-thumbnail" src="img.png" alt="" />
          <div class="btn-group my-info">
            <span class="btn btn-default dropdown-toggle" data-toggle="dropdown"><?php echo $sessionUser ?> <span class="caret"></span> </span>
            <ul class="dropdown-menu">
              <li>
                <a class="dropdown-item" href="profile.php">My Profile</a>
              </li>
              <li>
                <a class="dropdown-item" href="newad.php">New Item</a>
              </li>
              <li>
                <a class="dropdown-item" href="profile.php#my_ads">My Items</a>
              </li>
              <li>
                <a class="dropdown-item" href="logout.php">Logout</a>
              </li>


            </ul>
          </div>

          <?php


        }
        else {
       ?>
      <a href="login.php"><span class="">login/SignUp</span></a>
    <?php } ?>
    </div>
  </div>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">

    <a class="navbar-brand" href="index.php">HomePage</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#app-nav" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="app-nav">
      <ul class="navbar-nav ml-auto ">
        <?php
        $allCats =  getAllFrom("*" , "categories" ,"WHERE parent = 0", "" , "ID" ,'ASC');

        foreach ($allCats as $cat) {
          echo '<li><a class="nav-link" href="categories.php?pageid='.$cat['ID'].'">'.$cat['Name'].'</a></li>';
        }
        ?>

      </ul>



    </div>
  </div>
  </nav>
