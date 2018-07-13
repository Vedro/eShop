<?php
    require_once("inc/header.php");

    $page = "My profile";

    // debug($_SESSION);

    if (!userConnect()) 
    {
        header('location:login.php');
        exit();
        // the function exit allows me to read the header() and execute directly
        // if I don't do that, it will be read but will be interpreted a the end of the document
    }
?>

<h1><?= $page ?></h1>

<p>Please find your informations below:</p>

<ul>
    <li>Firstname: <?= $_SESSION['user']['firstname'] ?></li>
    <li>Lastname: <?= $_SESSION['user']['lastname'] ?></li>
    <li>Email: <?= $_SESSION['user']['email'] ?></li>
</ul>


<?php
    require_once("inc/footer.php");
?>