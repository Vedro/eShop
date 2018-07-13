<?php

require_once("inc/header.php");

//$_POST
if ($_POST) 
{
    foreach ($_POST as $key => $value) 
    {
        $_POST[$key] = addslashes($value);
        // way to avoid xss injection when entering values
    }

    if (!empty($_FILES['picture']['name']))
    // checking if I got a result for the 1st picture
    {
        // I give a random name for my picture
        $picture_name = $_POST['title'] . '_' . $_POST['reference'] . '_' . time() . '-' . rand(1,999) . '_' . $_FILES['picture']['name'];

        $picture_name = str_replace(' ', '-', $picture_name);

        // we register the path of my files
        $picture_path = ROOT_TREE . 'uploads/img/' . $picture_name;

        $max_size = 2000000;
        
        if ($_FILES['picture']['size'] > $max_size || empty($_FILES['picture']['size']))
        {
            $msg_error .= "<div class='alert alert-danger'>Please select a 2Mo file maximum !</div>";
        }

        $type_picture = ['image/jpeg', 'image/png', 'image/gif'];

        if (!in_array($_FILES['picture']['type'], $type_picture) || empty($_FILES['picture']['type'])) 
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

        if (!empty($_POST['id_user']))
        // we register the update
        {
            $result = $pdo->prepare("UPDATE user SET pseudo=:pseudo, pwd=:pwd, firstname=:firstname, lastname=:lastname, email=:email, gender=:gender, city=:city, zip_code=:zip_code, address=:address, picture=:picture, privilege=:privilege WHERE id_user = :id_user");


            $result->bindValue(':id_user', $_POST['id_user'], PDO::PARAM_INT);

            $result->bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
            $result->bindValue(':pwd', $_POST['pwd'], PDO::PARAM_STR);
            $result->bindValue(':firstname', $_POST['firstname'], PDO::PARAM_STR);
            $result->bindValue(':lastname', $_POST['lastname'], PDO::PARAM_STR);
            $result->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
            $result->bindValue(':gender', $_POST['gender'], PDO::PARAM_STR);
            $result->bindValue(':city', $_POST['city'], PDO::PARAM_STR);
            $result->bindValue(':zip_code', $_POST['zip_code'], PDO::PARAM_STR);
            $result->bindValue(':address', $_POST['address'], PDO::PARAM_STR);
            $result->bindValue(':picture', $picture_name, PDO::PARAM_STR);
            $result->bindValue(':privilege', $_POST['privilege'], PDO::PARAM_INT);

        }


        if ($result->execute())
        // if the request was inserted in the DTB
        {
            if (!empty($_FILES['picture']['name'])) 
            {
                copy($_FILES['picture']['tmp_name'], $picture_path);
            }

            if (!empty($_POST['id_user'])) 
            {
                header('location:users_list.php?m=update');
            }
        }
    }
}



//////////////////////////////////////////////////////////////////////////////////////////////////////////

$result = $pdo->query("SELECT * FROM user");
$users = $result->fetchAll();

$content .= "<table class='table table-striped table-sm'>";
$content .= "<thead class='thead-dark'><tr>";


for ($i = 0; $i < $result->columnCount(); $i++) 
{
    $columns = $result->getColumnMeta($i);
    // getColumnMeta() allows me to get the name of the columns
    if($columns['name'] != 'pwd')
    if($columns['name'] == 'id_user')
    {
        $content .= "<th>" . ucfirst(str_replace('_', ' ', $columns['name'])) . "</th>";
    }
    else
    {
        $content .= "<th scope='col'>" . ucfirst(str_replace('_', ' ', $columns['name'])) . "</th>";
    }
}
$content .= '<th colspan="2">Actions</th>';
$content .= "</tr></thead><tbody>";


//UPDATE LINE IN THE TABLE ABOVE THE FIRST USER

// DELETING REQUEST BY CLICKING ON THE TRASH ICON
if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id']))
{ //delete
    if(isset($_GET['action']) && ($_GET['action'] == 'delete'))
    {
        
            $req = "SELECT * FROM user WHERE id_user = :id_user";
        
            $result = $pdo->prepare($req);
        
            $result->bindValue(':id_user', $_GET['id'], PDO::PARAM_INT);
        
            $result->execute();
        
            // CHECK IF THE ID EXISTS
            if ($result->rowCount() == 1) 
            {
                $product = $result->fetch();
        
                $delete_req = "DELETE FROM user WHERE id_user = $user[id_user]";
        
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
                    header('location:users_list.php?m=success');
                }
                else
                {
                    header('location:users_list.php?m=fail');
                }
            }
            else 
            {
                header('location:users_list.php?m=fail');
            }
        } //update
        elseif(isset($_GET['action']) && ($_GET['action'] == 'update'))
        {
            $req = "SELECT * FROM user WHERE id_user = :id_user";

            $result = $pdo->prepare($req);

            $result->bindValue(':id_user', $_GET['id'], PDO::PARAM_INT);

            $result->execute();

            if ($result->rowCount() == 1) 
            {
                $update_user = $result->fetch();
            }
            $pseudo = (isset($update_user)) ? $update_user['pseudo'] : '';
            $firstname = (isset($update_user)) ? $update_user['firstname'] : '';
            $lastname = (isset($update_user)) ? $update_user['lastname'] : '';
            $email = (isset($update_user)) ? $update_user['email'] : '';
            $gender = (isset($update_user)) ? $update_user['gender'] : '';
            $city = (isset($update_user)) ? $update_user['city'] : '';
            $zip_code = (isset($update_user)) ? $update_user['zip_code'] : '';
            $picture = (isset($update_user)) ? $update_user['picture'] : '';
            $address = (isset($update_user)) ? $update_user['address'] : '';
            $privilege = (isset($update_user)) ? $update_user['privilege'] : '';
            $id_user = (isset($update_user)) ? $update_user['id_user'] : '';
            $pwd = (isset($update_user)) ? $update_user['pwd'] : '';


            $content .= "<tr><form action='' method='post' enctype='multipart/form-data'>";
            $content .= "<td>$id_user</td>";
            $content .= "<input  name='id_user' value=$id_user type='hidden'>";
            $content .= "<input  name='pwd' value=$pwd type='hidden'>";
            $content .= "<td><input style='width:100%;' type='text' name='pseudo' value=$pseudo></td>";
            $content .= "<td><input style='width:100%;' type='text' name='firstname' value=$firstname></td>";
            $content .= "<td><input style='width:100%;' type='text' name='lastname' value=$lastname></td>";
            $content .= "<td><input style='width:100%;' type='text' name='email' value=$email></td>";
            $content .= '<td><select name="gender" class="form-control">
                            <option value="m" <?php if($gender == "m"){echo "selected";} ?>>Men</option>
                            <option value="f"<?php if($gender == "f"){echo "selected";} ?>>Women</option>
                            <option value="o"<?php if($gender == "o"){echo "selected";} ?>>Other</option>
                        </select></td>';
            $content .= "<td><input style='width:100%;' type='text' name='city' value=$city></td>";
            $content .= "<td><input style='width:100%;' type='text' name='zip_code' value=$zip_code></td>";
            $content .= "<td><input style='width:100%;' type='text' name='address' value=$address></td>";
            $content .= "<td><input style='width:100%;' type='file' name='picture' value=$picture></td>";
            $content .= "<td><input style='width:100%;' type='text' name='privilege' value=$privilege></td>";
            $content .= "<td><input class='btn btn-success' style='width:100%;' type='submit' name='update-btn' value='update'></td>";
            $content .= "</form></tr>";
        }
    } 



foreach ($users as $user) 
{
    $content .= "<tr>";
    foreach ($user as $key => $value) 
    {
        if ($key == 'picture') 
        {
            $content .= '<td><img height="100" src="' . URL . 'uploads/img/' . $value . '" alt="profile pic"/></td>';
        }
        elseif($key !='pwd')
        {
            $content .= "<td>" . $value . "</td>";
        }
    }

    $content .= "<td><a href='?action=update&id=" . $user['id_user'] . "'><i class='fas fa-pen'></i></a></td>";

    $content .= "<td><a href='?action=delete&id=" . $user['id_user'] . "'><i class='fas fa-trash-alt'></i></a></td>";

    $content .= "</tr>";
}
$content .= "</tbody></table>";



if (isset($_GET['m']) && !empty($_GET['m']))
{
    switch ($_GET['m']) {
        case 'success':
            $msg_success .= "<div class='alert alert-success'>The user has been deleted.</div>";
        break;
        case 'fail':
            $msg_error .= "<div class='alert alert-danger'>The user couldn't have been deleted. Please try to reload the page or contact the server admin.</div>";
        break;
        case 'update':
            $msg_success .= "<div class='alert alert-success'>The user has been updated successfully.</div>";
        break;
        default:
            $msg_success .= "<div class='alert alert-secondary'>Don't understand, please try again</div>";
        break;
    }
}


?>


<h1>List of users</h1>


<!-- <?php debug(get_class_methods($result)) ?> -->
<!-- <?php debug($columns) ?> -->
<!-- I just get the last result of the debug $columns -->

<?= $msg_success ?>
<?= $msg_error ?>

<?= $content ?>




<?php

require_once('inc/footer.php');

?>