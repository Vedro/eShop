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
switch ($_SESSION['user']['gender']) 
{
    case 'f':
        $gender = "Mrs";
        break;
    case 'm':
        $gender = "Mr";
        break;
    default:
        $gender = "";
        break;
} 

if (isset($_GET['m']) && $_GET['m'] == 'update') 
{
    $msg_success .= "<div class='alert alert-success'>Your profile has been updated successfully.</div>";
}
?>

<!-- <?= debug($_SESSION['user']['id_user']); ?>
<?= debug($_SESSION['user']); ?> -->

<h1><?= $page ?></h1>

<?= $msg_success ?>

<p>Please find your informations below:</p>

<div class="jumbotron jumbotron-fluid">
    <div class="container">
    <!-- /\ DON'T FORGET TO UPDATE THE PICTURE DYNAMICALLY /\ -->
        <img src="<?= URL . 'uploads/img/' . $_SESSION['user']['picture'] ?>" width="25%">
        <hr class="my-4">
        <h1 class="display-4"><?= $_SESSION['user']['pseudo'] ?></h1>
        <hr class="my-4">
        <p class="lead"><?php echo $gender . ' ' . $_SESSION['user']['firstname'] . ' ' . $_SESSION['user']['lastname'] ?></p>
        <p class="lead"><?= $_SESSION['user']['email'] ?></p>
        <p class="lead"><?= $_SESSION['user']['address'] ?></p>
        <p class="lead"><?= $_SESSION['user']['zip_code'] . ' ' .  $_SESSION['user']['city']?></p>
        <hr class="my-4">
        <a class="btn btn-primary btn-lg" href="<?=URL?>signup.php?id=<?=$_SESSION['user']['id_user']?>"><i class='fas fa-pen'></i></a>
        <a class="btn btn-danger btn-lg" href="?d=true" role="button">Delete account</a>
    </div>
</div>

<?php
    require_once("inc/footer.php");
?>