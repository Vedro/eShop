<?php
    require_once("inc/header.php");

    // debug($_GET);

    if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id']))
    {
        $result = $pdo->prepare("SELECT * FROM product WHERE id_product = :id_product");

        $result->bindValue(':id_product', $_GET['id'], PDO::PARAM_INT);

        $result->execute();

        if ($result->rowCount() == 1) 
        {
            $product_details = $result->fetch();

            // debug($product_details);

            extract($product_details);
        }
        else
        {
            header('location:eshop.php?m=error');
        }
    }
    else 
    {
        header('location:eshop.php?m=error');
    }

    $page = "$title";
?>

<h1><?= $page ?></h1>

<img src="uploads/img/<?= $picture ?>" width="20%" alt="<?=$title?>">

<p>Product details:</p>
<ul>
    <li>Reference: <strong><?= $reference?></strong></li>
    <li>Category: <strong><?= $category?></strong></li>
    <li>Color: <strong><?= $color?></strong></li>
    <li>Size: <strong><?= $size?></strong></li>
    <li>Gender: <strong><?= $gender?></strong></li>
    <li>Price: <strong style="color:darkblue;"><?= $price?> € <em>All taxes included</em></strong></li>
</ul>


<?php
    require_once("inc/footer.php");
?>