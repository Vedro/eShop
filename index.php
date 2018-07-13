<?php
    require_once("inc/header.php");

    $page = "Welcome on MyEshop.com !"
?>

<h1><?= $page ?></h1>
<p class="lead">Please, feel free to buy a lot of stuff and spend all of your money. Ki$$e$ !</p>

<div id="carouselExampleSlidesOnly" class="carousel slide" data-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img class="d-block w-100" src="uploads/img/photo-1.jpg/800x400?auto=yes&bg=777&fg=555" alt="First slide">
    </div>
    <div class="carousel-item">
      <img class="d-block w-100" src="uploads/img/photo-2.jpg/800x400?auto=yes&bg=666&fg=444&text=Second slide" alt="Second slide">
    </div>
    <div class="carousel-item">
      <img class="d-block w-100" src="uploads/img/photo-3.jpg/800x400?auto=yes&bg=555&fg=333&text=Third slide" alt="Third slide">
    </div>
  </div>
</div>




      




<?php
    require_once("inc/footer.php");
?>