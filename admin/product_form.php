<?php

require_once('inc/header.php');

if ($_POST) 
{
    foreach ($_POST as $key => $value) 
    {
        $_POST[$key] = addslashes($value);
        // way to avoid xss injection when entering values
    }

    // debug($_POST);
    // debug($_FILES);

    if (!empty($_FILES['product_picture']['name']))
    // checking if I got a result for the 1st picture
    {
        // I give a random name for my picture
        $picture_name = $_POST['title'] . '_' . $_POST['reference'] . '_' . time() . '-' . rand(1,999) . '_' . $_FILES['product_picture']['name'];

        $picture_name = str_replace(' ', '-', $picture_name);

        // we register the path of my files
        $picture_path = ROOT_TREE . 'uploads/img/' . $picture_name;

        $max_size = 2000000;
        
        if ($_FILES['product_picture']['size'] > $max_size || empty($_FILES['product_picture']['size']))
        {
            $msg_error .= "<div class='alert alert-danger'>Please select a 2Mo file maximum !</div>";
        }

        $type_picture = ['image/jpeg', 'image/png', 'image/gif'];

        if (!in_array($_FILES['product_picture']['type'], $type_picture) || empty($_FILES['product_picture']['type'])) 
        {
            $msg_error .= "<div class='alert alert-danger'>Please select a JPG/JPEG, PNG or GIF file.</div>";
        }
    }elseif (isset($_POST['actual_picture'])) 
    {
        $picture_name = $_POST['actual_picture'];
        // if I update a product, I target the new input created with my $update_product
    }
    else 
    {
        $picture_name = 'default.png';
    }

    //  OTHER CHECK POSSIBLE HERE

    if (empty($msg_error)) 
    {

        if (!empty($_POST['id_product']))
        // we register the update
        {
            $result = $pdo->prepare("UPDATE product SET reference=:reference, category=:category, title=:title, description=:description, color=:color, size=:size, gender=:gender, picture=:picture, picture1=NULL, price=:price, stock=:stock WHERE id_product = :id_product");

            $result->bindValue(':id_product', $_POST['id_product'], PDO::PARAM_INT);
        }
        else
        // we register for the first time ine the DTB
        {
            $result = $pdo->prepare("INSERT INTO product (reference, category, title, description, color, size, gender, picture, picture1, price, stock) VALUE (:reference, :category, :title, :description, :color, :size, :gender, :picture, NULL, :price, :stock)");
        }

        $result->bindValue(':reference', $_POST['reference'], PDO::PARAM_STR);
        $result->bindValue(':category', $_POST['category'], PDO::PARAM_STR);
        $result->bindValue(':title', $_POST['title'], PDO::PARAM_STR);
        $result->bindValue(':description', $_POST['description'], PDO::PARAM_STR);
        $result->bindValue(':color', $_POST['color'], PDO::PARAM_STR);
        $result->bindValue(':size', $_POST['size'], PDO::PARAM_STR);
        $result->bindValue(':gender', $_POST['gender'], PDO::PARAM_STR);
        $result->bindValue(':picture', $picture_name, PDO::PARAM_STR);
        $result->bindValue(':price', $_POST['price'], PDO::PARAM_STR);
        $result->bindValue(':stock', $_POST['stock'], PDO::PARAM_INT);

        if ($result->execute())
        // if the request was inserted in the DTB
        {
            if (!empty($_FILES['product_picture']['name'])) 
            {
                copy($_FILES['product_picture']['tmp_name'], $picture_path);
            }

            if (!empty($_POST['id_product'])) 
            {
                header('location:product_list.php?m=update');
            }
        }
    }
}

if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])) 
{
    $req = "SELECT * FROM product WHERE id_product = :id_product";

    $result = $pdo->prepare($req);

    $result->bindValue(':id_product', $_GET['id'], PDO::PARAM_INT);

    $result->execute();

    if ($result->rowCount() == 1) 
    {
        $update_product = $result->fetch();
    }
}
// debug($update_product);

$reference = (isset($update_product)) ? $update_product['reference'] : '';
$category = (isset($update_product)) ? $update_product['category'] : '';
$title = (isset($update_product)) ? $update_product['title'] : '';
$description = (isset($update_product)) ? $update_product['description'] : '';
$color = (isset($update_product)) ? $update_product['color'] : '';
$size = (isset($update_product)) ? $update_product['size'] : '';
$gender = (isset($update_product)) ? $update_product['gender'] : '';
$picture = (isset($update_product)) ? $update_product['picture'] : '';
$price = (isset($update_product)) ? $update_product['price'] : '';
$stock = (isset($update_product)) ? $update_product['stock'] : '';

$id_product = (isset($update_product)) ? $update_product['id_product'] : '';

$action = (isset($update_product)) ? "Update" : "Add";

?>


<h1 class="h2"><?= $action ?></h1>

<form action="" method="post" enctype="multipart/form-data">
    <?= $msg_error ?>
    <!-- <?= $msg_success ?> -->

    <!-- <?= $picture_name ?> -->
    <input type="hidden" name="id_product" value="<?= $id_product ?>">
    <div class="form-group">
        <input type="text" name="reference" placeholder="Product reference..." class="form-control" value="<?= $reference ?>">
    </div>

    <div class="form-group">
        <input type="text" name="category" placeholder="Product category..." class="form-control" value="<?= $category ?>">
    </div>

    <div class="form-group">
        <input type="text" name="title" placeholder="Product title..." class="form-control" value="<?= $title ?>">
    </div>

    <div class="form-group">
        <textarea class="form-control" name="description" id="" cols="30" rows="5" placeholder="Description of the product"><?= $description ?></textarea>
    </div>

    <div class="form-group">
        <select class="form-control" name="color">
            <option disabled <?php if(!isset($update_product)){echo 'selected';} ?> selected>Color of the product...</option>
            <option <?php if($color == 'black'){echo 'selected';}?>>black</option>
            <option <?php if($color == 'white'){echo 'selected';}?>>white</option>
            <option <?php if($color == 'red'){echo 'selected';}?>>red</option>
            <option <?php if($color == 'orange'){echo 'selected';}?>>orange</option>
            <option <?php if($color == 'green'){echo 'selected';}?>>green</option>
            <option <?php if($color == 'pink'){echo 'selected';}?>>pink</option>
            <option <?php if($color == 'brown'){echo 'selected';}?>>brown</option>
            <option <?php if($color == 'blue'){echo 'selected';}?>>blue</option>
            <option <?php if($color == 'purple'){echo 'selected';}?>>purple</option>
            <option <?php if($color == 'grey'){echo 'selected';}?>>grey</option>
            <option <?php if($color == 'indigo'){echo 'selected';}?>>indigo</option>
        </select>    
    </div>

    <div class="form-group">
        <select class="form-control" name="size">
            <option disabled <?php if(empty($size)){echo 'selected';} ?> selected>Size of the product...</option>
            <option <?php if($size == 'XS'){echo 'selected';}?>>xs</option>
            <option <?php if($size == 'S'){echo 'selected';}?>>s</option>
            <option <?php if($size == 'M'){echo 'selected';}?>>m</option>
            <option <?php if($size == 'L'){echo 'selected';}?>>l</option>
            <option <?php if($size == 'XL'){echo 'selected';}?>>xl</option>
            <option <?php if($size == 'XXL'){echo 'selected';}?>>xxl</option>
        </select>    
    </div>

    <div class="form-group">
        <select class="form-control" name="gender">
            <option disabled selected
            >Gender...</option>
            <option <?php if($gender == 'm'){echo 'selected';}?> value="m">Men</option>
            <option <?php if($gender == 'f'){echo 'selected';}?> value="f">Women</option>
            <option <?php if($gender == 'u'){echo 'selected';}?> value="u">Unisex</option>
        </select>    
    </div>

    <div class="form-group">
        <label for="product_picture">Product picture</label>
        <input type="file" class="form-control-file" id="product_picture" name="product_picture">
    <?php
        if (isset($update_product)) 
        {
            echo "<input name='actual_picture' value='$picture' type='hidden'></input>";
            echo "<img style='width:25%;' src='". URL . "uploads/img/$picture'>";
        }

    ?>
    </div>
    
    <div class="form-group">
        <label for="product_picture">Second product picture (optional)</label>
        <input type="file" class="form-control-file" id="product_picture1" name="product_picture1">
    </div>

    <div class="form-group">
        <input type="text" class="form-control" name="price" palceholder="Price of the product" value="<?= $price ?>">
    </div>

    <div class="form-group">
        <input type="number" class="form-control" name="stock" placeholder="Product stock..." value="<?= $stock ?>">
    </div>

    <input type="submit" value="<?= $action ?> product" class="btn btn-info btn-lg btn-block">
</form>
        
<?php

require_once('inc/footer.php');

?>