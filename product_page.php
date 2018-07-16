<?php
    require_once("inc/header.php");

    if(isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id']))
    {
        $result = $pdo->prepare("SELECT * FROM product WHERE id_product = :id_product");
        $result->bindValue(':id_product', $_GET['id'], PDO::PARAM_INT);

        $result->execute();

        if($result->rowCount() == 1)
        {
            $product_details = $result->fetch();

            extract($product_details);
        }
        else 
        {
            header('location:eshop.php?m=error');
            exit();
        }
    

        if($_POST) { // si clique sur ajouter au panier
            if (!empty($_POST['quantity']) && is_numeric($_POST['quantity']) && $_POST['quantity'] != 0) 
            {
                addProduct($id_product, $_POST['quantity'], $picture, $title, $price);
                header('location:cart.php');
            } 
        }    

        // we want to display product within the same category in order to maximpise the benefits of the website
        $suggest_resultat = $pdo->query("SELECT * FROM product WHERE category = '$product_details[category]' AND id_product != '$product_details[id_product]' ORDER BY price DESC LIMIT 0,3");
        $suggestion = $suggest_resultat->fetchAll();

    }
    else
    {
        header('location:eshop.php?m=error');
        exit();
    }

    $page = "$title";

    debug($product_details);

?>

        <h1><?= $page ?></h1>
       
        <img src="uploads/img/<?=$picture?>" width="20%" alt="<?=$title?>">

        <p>Product details:</p>
        <ul>
            <li>Reference: <strong><?=$reference?></strong></li>
            <li>Category: <strong><?=$category?></strong></li>
            <li>Color: <strong><?=$color?></strong></li>
            <li>Size: <strong><?=$size?></strong></li>
            <li>Gender: <strong><?php if($gender == 'm'){echo 'Men';}elseif($gender == 'f'){echo 'Women';}else{echo 'Mixed';} ?></b></li></strong></li>
            <li>Price: <strong style='color: darkblue;'><?=$price?> €</strong><br><em>all taxes included</em></li>
        </ul>

        <?php if($stock > 0) : ?>
            <fieldset> 
                <legend>Buy the product : </legend>
                <form method="post" action="">
                    <label>Quantity</label>
                    <select name="quantity" class="form-control">
                        <?php for ($i=1; $i <= $stock; $i++) { 
                            echo '<option>' . $i . '</option>';
                        } ?>
                    </select>
                    <input type="submit" value="Add to cart" class="btn btn-primary">
                </form>
            </fieldset>
        <?php else : ?>
            <div class='alert alert-secondary'>Sorry, we don't have stock anymore !</div>
        <?php endif; ?>

        <div class="profil">
            <h2>Other members also bought :</h2>
            <?php foreach ($suggestion as $key => $value) : ?>
                <div>
                    <h3><?= $value['title'] ?></h3>
                    <a href=""><img src="uploads/img/<?= $value['picture'] ?>" height="100"></a>
                    <p style="font-weight: bold; font-size: 20px"> <?= $value['price'] ?> €</p>
                    <p style="height: 40px"> <?= substr($value['description'], 0, 40) ?> '</p>
                    <a style="padding:5px 15px; border:1px solid red; color: red; border-radius:4px" href="product_page.php?id=<?= $value['id_product'] ?>">See the product</a>
                </div>
            <?php endforeach; ?>
        </div>
    
<?php
    require_once("inc/footer.php");
?>