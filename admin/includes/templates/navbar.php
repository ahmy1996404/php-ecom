<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container">

  <a class="navbar-brand" href="dashboard.php"><?php echo lang('Home_Admin')?></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#app-nav" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="app-nav">
    <ul class="navbar-nav mr-auto">

        <li><a class="nav-link" href="categories.php"><?php echo lang('Categories')?></a></li>
        <li><a class="nav-link" href="items.php"><?php echo lang('Items')?></a></li>
        <li><a class="nav-link" href="members.php"><?php echo lang('Members')?></a></li>
        <li><a class="nav-link" href="comments.php"><?php echo lang('Comments')?></a></li>




    </ul>
    <ul class="nav navbar-nav navbar-right">

      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          ahmed
        </a>
        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
          <li><a class="dropdown-item" href="../index.php"><?php echo lang('Visit_Shop')?></a></li>
          <li><a class="dropdown-item" href="members.php?do=Edit&userid=<?php echo $_SESSION['ID'];?>"><?php echo lang('Edit_Profile')?></a></li>
          <li><a class="dropdown-item" href="#"><?php echo lang('Setting')?></a></li>
        <li>  <a class="dropdown-item" href="logout.php"><?php echo lang('Logout')?></a></li>
        </ul>
      </li>

    </ul>


  </div>
</div>
</nav>
