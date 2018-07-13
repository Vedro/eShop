<?php

require_once("inc/header.php");

// $result = $pdo->query("SELECT * FROM product");

// $products = $resul->fetchAll();

// echo '<table class="table">';
//     echo '<thead class="thead-dark">';
//         echo '<tr>';
//             for ($i=0; $i < $table->columnCount(); $i++) 
//             { 
//                 $column = $table->getColumnMeta($i);  
//                 echo '<th scope="col">' . ucfirst(str_replace("_", " ", $column['name'])) . '</th>';
//             }
//         echo '<tr>';
//     echo '</thead>';
//     foreach ($products as $product) 
//         {
//             echo '<tr>';
//                 foreach ($product as $key => $value) 
//                 {
//                     if ($key == 'picture') 
//                     {
//                         echo "<td><img style='width:25%;' src='../uploads/img/ . $value' class='img-thumbnail'></td>";
//                     }
//                     echo '<td>' . $value . '</td>';
//                 }
//             echo '</tr>';
//         }
// echo '</table>';

////////////////////////////    CORRECTION  ///////////////////////////////

$result = $pdo->query("SELECT * FROM product");
$products = $result->fetchAll();

$content .= "<table class='table table-striped'>";
$content .= "<thead class='thead-dark'><tr>";

for ($i = 0; $i < $result->columnCount(); $i++) 
{
    $columns = $result->getColumnMeta($i);
    // getColumnMeta() allows me to get the name of the columns
    $content .= "<th scope='col'>" . ucfirst(str_replace('_', ' ', $columns['name'])) . "</th>";
}
$content .= '<th colspan="2">Actions</th>';
$content .= "</tr></thead><tbody>";

foreach ($products as $product) 
{
    $content .= "<tr>";
    foreach ($product as $key => $value) 
    {
        if ($key == 'picture') 
        {
            $content .= '<td><img height="100" src="' . URL . 'uploads/img/' . $value . '" alt="' . $product['title'] . '"/></td>';
        } 
        else 
        {
            $content .= "<td>" . $value . "</td>";
        }
    }

    $content .= "<td><a href='" . URL . "admin/product_form.php?id=" . $product['id_product'] . "'><i class='fas fa-pen'></i></a></td>";

    $content .= "<td><a href='?id=" . $product['id_product'] . "'><i class='fas fa-trash-alt'></i></a></td>";

    $content .= "</tr>";
}
$content .= "</tbody></table>";

// DELETING REQUEST BY CLICKING ON THE TRASH ICON
if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])) 
{
    $req = "SELECT * FROM product WHERE id_product = :id_product";

    $result = $pdo->prepare($req);

    $result->bindValue(':id_product', $_GET['id'], PDO::PARAM_INT);

    $result->execute();

    // CHECK IF THE ID EXISTS
    if ($result->rowCount() == 1) 
    {
        $product = $result->fetch();

        $delete_req = "DELETE FROM product WHERE id_product = $product[id_product]";

        $delete_result = $pdo->exec($delete_req);

        //  DELETE ALSO THE PICTURE LINKED TO THE PRODUCT
        if ($delete_result) 
        {
            $picture_path = ROOT_TREE . 'uploads/img/' . $product['picture'];

            // BUT DON'T DELETE THE DEFAULT PICTURE 
            if (file_exists($picture_path) && $product['picture'] != 'default.png') 
            // function file_exists() allows us to be sur that we got this picture registered on the server
            {
                unlink($picture_path);
                // function unlink() allows us to delete a file from the server
            }
            header('location:product_list.php?m=success');
        }
        else
        {
            header('location:product_list.php?m=fail');
        }
    }
    else 
    {
        header('location:product_list.php?m=fail');
    }
}

// if (isset($_GET['m']) && $_GET['m'] == 'success') 
// {
//     $msg_success .= "<div class='alert alert-success'>The product has been deleted.</div>";
// }

// if (isset($_GET['m']) && $_GET['m'] == 'fail') 
// {
//     $msg_error .= "<div class='alert alert-danger'>The product couldn't have been deleted. Please try to reload the page or contact the server admin.</div>";
// }

if (isset($_GET['m']) && !empty($_GET['m']))
{
    switch ($_GET['m']) {
        case 'success':
            $msg_success .= "<div class='alert alert-success'>The product has been deleted.</div>";
        break;
        case 'fail':
            $msg_error .= "<div class='alert alert-danger'>The product couldn't have been deleted. Please try to reload the page or contact the server admin.</div>";
        break;
        case 'update':
            $msg_success .= "<div class='alert alert-success'>The product has been updated successfully.</div>";
        break;
        default:
            $msg_success .= "<div class='alert alert-secondary'>Don't understand, please try again</div>";
        break;
    }
}
?>

<h1>List of products</h1>


<!-- <?php debug(get_class_methods($result)) ?> -->
<!-- <?php debug($columns) ?> -->
<!-- I just get the last result of the debug $columns -->

<?= $msg_success ?>
<?= $msg_error ?>

<?= $content ?>


<?php

require_once('inc/footer.php');

?>