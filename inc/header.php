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

    <title>MyEshop.com | Best deal$ online</title>

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
      <a class="navbar-brand" href="<?php URL ?>index.php">MyEshop.com</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav mr-auto">

          <li class="nav-item active">
          <!-- CAREFUL to call the right link here -->
            <a class="nav-link" href="<?php URL ?>index.php">Home <span class="sr-only">(current)</span></a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="<?php URL ?>eshop.php">Eshop</a>
          </li>

          <?php if (!userConnect()) : ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="https://example.com" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Connect</a>
            <div class="dropdown-menu" aria-labelledby="dropdown01">
              <a class="dropdown-item" href="<?php URL ?>login.php">Login</a>
              <a class="dropdown-item" href="<?php URL ?>signup.php">Signup</a>
            </div>
          </li>
          <?php else: ?>
          <li class="nav-item">
          <!-- CAREFUL to call the right link here -->
            <a class="nav-link" href="<?php URL ?>profile.php">Profile</a>
          </li>
          <?php endif; ?>

          <li class="nav-item">
          <!-- CAREFUL to call the right link here -->
            <a class="nav-link" href="#">Contact</a>
          </li>

          <?php if (userConnect()) : ?>
          <li class="nav-item">
            <a class="nav-link" href="<?php URL ?>logout.php">Logout</a>
          </li>
          <?php else: ?>
          <?php endif; ?>

          <?php if (userAdmin()) : ?>
          <li class="nav-item">
            <a class="nav-link" href="<?php URL ?>admin\product_form.php">BackOffice</a>
          </li>
          <?php else: ?>
          <?php endif; ?>

        </ul>
      </div>
    </nav>

    <main role="main" class="container">
        <div class="starter-template">


