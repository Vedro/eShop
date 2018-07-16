<?php
    require_once("inc/header.php");

    $page = "Welcome on MyEshop.com !"
?>

<h1 class="welcome"><?= $page ?></h1>
<p class="lead">Please, feel free to buy a lot of stuff and spend all of your money. </p>


<div id="carouselExampleSlidesOnly" class="carousel slide" data-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img class="d-block w-100" src="photos/photo-4.jpg" alt="First slide">
    </div>
    <div class="carousel-item">
      <img class="d-block w-100" src="photos/photo-2.jpg" alt="Second slide">
    </div>
    <div class="carousel-item">
      <img class="d-block w-100" src="photos/photo-3.jpg" alt="Third slide">
    </div>
    <div class="carousel-item">
      <img class="d-block w-100" src="photos/photo-6.jpg" alt="Third slide">
    </div>
  </div>
</div>



<?php
    require_once("inc/footer.php");
?>