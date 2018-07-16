<?php require_once("init.php") ?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="The best eshop in DA world">
    <meta name="author" content="VeÐrø">
    <!-- CAREFUL to create favicons -->
    <link rel="icon" href="">

    <title>myEshop.com | Best deal$ online</title>

    <!-- Bootstrap core CSS -->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">

    <!-- My CSS -->
    <link href="css/style.css" rel="stylesheet">

    <!-- Link for icons -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
  </head>

  <body>

    <!-- CAREFUL to call the right link here -->
    <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
      <a class="navbar-brand" href="<?php URL ?>index.php">myEshop.com</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav mr-auto">

          <li class="nav-item">
            <a class="nav-link" href="<?php URL ?>eshop.php">Shop</a>
          </li>

          <?php if (!userConnect()) : ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Connect</a>
            
            <div class="dropdown-menu" aria-labelledby="dropdown01">
              <a class="dropdown-item" href="<?php URL ?>login.php">Sign in</a>
              <a class="dropdown-item" href="<?php URL ?>signup.php">Sign up</a>
            </div>
          </li>

          <?php else: ?>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Hello <?= $_SESSION['user']['firstname']; ?></a>
            
            <div class="dropdown-menu" aria-labelledby="dropdown01">
              <a class="dropdown-item" href="<?php URL ?>profile.php">Profile</a>
              <a class="dropdown-item" href="<?php URL ?>logout.php">Logout</a>
            </div>
          </li>
         
          
          <li class="nav-item">
            <!-- CAREFUL to call the right link here -->
            <a class="nav-link" href="#">Contact</a>
          </li>
          
          <?php endif; ?>
          
          <?php if (userAdmin()) : ?>
          <li class="nav-item">
            <a class="nav-link" href="<?php URL ?>admin\product_form.php">Admin Panel</a>
          </li>
          <?php else: ?>
          <?php endif; ?>

          <li>
            <a class="nav-link" href="<?=URL?>cart.php"><i class="fas fa-shopping-cart"></i><?php if(productNumber()){echo'<span class="bubble">' . ' ' . productNumber() . '</span>';} ?></a>
          </li>

        </ul>
      </div>
    </nav>



    <main role="main" class="container">
        <div class="starter-template">


