<?php
require_once("inc/header.php");

$page = "eShop";

$result_cat = $pdo->query("SELECT DISTINCT category FROM product");

$content .= "<ul class='list-group'>";
    $content .= "<li class='list-group-item'><a href='eshop.php'>All</a></li>";
    while ($category = $result_cat->fetch()) 
    {
        $content .= "<li class='list-group-item'><a href='?cat=$category[category]'>$category[category]</a></li>";
    }
$content .= "</ul>";

if (isset($_GET['cat']) && !empty($_GET['cat'])) 
{
    $result_prod = $pdo->prepare("SELECT * FROM product WHERE category = :category");

    $result_prod->bindValue(':category', $_GET['cat'], PDO::PARAM_STR);

    $result_prod->execute();

    $products = $result_prod->fetchAll();
}
else 
{
    $result_prod = $pdo->query("SELECT * FROM product");

    $products = $result_prod->fetchAll();
}

?>

<h1><?= $page ?></h1>
    
    <div class='row'>
        <div class='col-md-2'>
            <?= $content ?>
        </div>

        <div class='col-md-10'>
            <div class='row'>
            
                <?php foreach ($products as $product) : ?>
                
                    <div class="card" style="width: 18rem;" id="product-eshop">
                        <img class="card-img-top rounded" src="<?=URL?>uploads/img/<?=$product['picture']?>" alt="<?=$product['title']?>">
                        <div class="card-body">
                            <h5 class="card-title"><?=$product['title']?></h5>
                            <p class="card-text"><?= substr($product['description'], 0, 40) ?>...</p>
                            <a href="product_page.php?id=<?= $product['id_product'] ?>" class="btn btn-primary">See the product</a>
                        </div>
                    </div>
            
                <?php endforeach; ?>
            </div>
        </div>
    </div>


<?php
    require_once("inc/footer.php");
?>