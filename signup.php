<?php
require_once("inc/header.php");

$page = "Sign up";

// debug($_POST);
if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])) 
{
    $req = "SELECT * FROM user WHERE id_user = :id_user";

    $result = $pdo->prepare($req);

    $result->bindValue(':id_user', $_GET['id'], PDO::PARAM_INT);

    $result->execute();

    if ($result->rowCount() == 1) 
    {
        $update_user = $result->fetch();
        // foreach ($update_user as $key => $value) 
        //     {
        //         $_SESSION['user'][$key] = $value;
        //     }
    }
}

if($_POST) 
{

///////////////////////////// check pseudo /////////////////////////////

    if(!empty($_POST['pseudo']))
    {
        $pseudo_verif = preg_match('#^[a-zA-Z0-9-._]{3,20}$#', $_POST['pseudo']);
        // function preg_match() allows me to check what infos will be  allowed in a result. It takes 2 'args':
        // - REGEX (REGular EXpressions)
        // - the STR/VAR to check
        // At the end, I will have a TRUE or FALSE condition

        if (!$pseudo_verif) 
        {
            $msg_error .= "<div class='alert alert-danger'>Your pseudo should contain letters (upper/lowercase), numbers, between 3 and 20 characters and only '.' and '_' are accepted.<br> Please try again !</div>";
        }
    }
    else 
    {
        $msg_error .= "<div class='alert alert-danger'>Please, enter a valid pseudo.</div>";
    }

///////////////////////////// check password /////////////////////////////

    if(!empty($_POST['password']))
    {
        $pwd_verif = preg_match('#^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[-+|*\'\?$@%_])([-+!*\$\'@%_\w]{6,15})$#', $_POST['password']);
        // it means we ask between 6 to 15 characters + 1 UPPER + 1 LOWER + 1 number + 1 symbol
        if (!$pwd_verif) 
        {
            $msg_error .= "<div class='alert alert-danger'>Your password should contain between 6 and 15 characters with at least 1 uppercase, 1 lowercase, 1 number and 1 symbol.<br> Please try again !</div>";
        }
    }
    elseif (isset($_POST['actual_pwd'])) 
    {
        $password = $_POST['actual_pwd'];
    }
    else 
    {
        $msg_error .= "<div class='alert alert-danger'>Please, enter a valid password.</div>";
    }

///////////////////////////// check email /////////////////////////////

    if (!empty($_POST['email'])) 
    {
        $email_verif = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        // function filter_var() allows us to check a result (STR -> email, URL ...). It takes 2 args:
        // - the result to check
        // - the method
        // It returns a BOOLEAN

        $forbidden_mails = [
            'mailinator.com',
            'yopmail.com',
            'mail.com'
        ];

        $email_domain = explode('@', $_POST['email']);
        // function explode() allows me to explode result into two parts regarding the element I've chosen

        // debug($email_domain);

        if (!$email_verif || in_array($email_domain[1], $forbidden_mails)) 
        {
            $msg_error .= "<div class='alert alert-danger'>Please, enter a valid email.</div>";
        }
    }
    else 
    {
        $msg_error .= "<div class='alert alert-danger'>Please, enter a valid email.</div>";
    }

    if (!isset($_POST['gender']) ||($_POST['gender'] != "m" && $_POST['gender'] != "f" && $_POST['gender'] != "o")) 
    {
        $msg_error .= "<div class='alert alert-danger'>Choose a valid gender.</div>";
    }

    // OTHER CHECKS POSSIBLE HERE

    if (!empty($_FILES['user_picture']['name']))
    // checking if I got a result for the 1st picture
    {
        // I give a random name for my picture
        $picture_name = $_POST['pseudo'] . '_' . time() . '-' . rand(1,999) . '_' . $_FILES['user_picture']['name'];

        $picture_name = str_replace(' ', '-', $picture_name);

        // we register the path of my files
        $picture_path = ROOT_TREE . 'uploads/img/' . $picture_name;

        $max_size = 2000000;
        
        if ($_FILES['user_picture']['size'] > $max_size || empty($_FILES['user_picture']['size']))
        {
            $msg_error .= "<div class='alert alert-danger'>Please select a 2Mo file maximum !</div>";
        }

        $type_picture = ['image/jpeg', 'image/png', 'image/gif'];

        if (!in_array($_FILES['user_picture']['type'], $type_picture) || empty($_FILES['user_picture']['type'])) 
        {
            $msg_error .= "<div class='alert alert-danger'>Please select a JPG/JPEG, PNG or GIF file.</div>";
        }
    }
    elseif (!empty($_POST['actual_picture'])) 
    {
        $picture_name = $_POST['actual_picture'];
        // if I update a product, I target the new input created with my $update_product
    }
    else 
    {
        $picture_name = 'default.jpg';
    }

    if (empty($msg_error)) 
    {
        $result = $pdo->prepare("SELECT pseudo FROM user WHERE pseudo = :pseudo");
        $result->bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);

        if ($result->rowcount() == 1) 
        {
            $msg_error .= "<div class='alert alert-secondary'>The pseudo $_POST[pseudo] is already taken, please choose another one</div>";
        }

        if (!empty($_POST['id_user']))
        // we register the update
        {
            $result_op = $pdo->prepare("UPDATE user SET pseudo=:pseudo,pwd=:pwd, firstname=:firstname, lastname=:lastname, email=:email, gender=:gender, city=:city, address=:address, zip_code=:zip_code, picture=:picture WHERE id_user = :id_user");

            $result_op->bindValue(':id_user', $update_user['id_user'], PDO::PARAM_INT);
            $result_op->bindValue(':pwd', $_POST['actual_pwd'], PDO::PARAM_STR);
        }
        else 
        {
            $result_op = $pdo->prepare("INSERT INTO user (pseudo, pwd, firstname, lastname, email, gender, city, zip_code, address, picture,  privilege) VALUES (:pseudo, :pwd, :firstname, :lastname, :email, :gender, :city, :zip_code, :address, :picture, 0)");

            $hashed_pwd = password_hash($_POST['password'], PASSWORD_BCRYPT);
        // function password_hash() allows us to encrypt the password in a much secure way than md5. It takes 2 args:
        // - the result to hash
        // - the method
            $result_op->bindValue(':pwd', $hashed_pwd, PDO::PARAM_STR);
            
        }
        
        $result_op->bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
        $result_op->bindValue(':firstname', $_POST['firstname'], PDO::PARAM_STR);
        $result_op->bindValue(':lastname', $_POST['lastname'], PDO::PARAM_STR);
        $result_op->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
        $result_op->bindValue(':gender', $_POST['gender'], PDO::PARAM_STR);
        $result_op->bindValue(':city', $_POST['city'], PDO::PARAM_STR);
        $result_op->bindValue(':address', $_POST['address'], PDO::PARAM_STR);
        $result_op->bindValue(':zip_code', $_POST['zip_code'], PDO::PARAM_STR);
        $result_op->bindValue(':picture', $picture_name, PDO::PARAM_STR);

        // STOPPED HERE -- define default avatar + copy img
        if ($result_op->execute()) 
        {

            if (!empty($_FILES['user_picture']['name'])) 
            {
                copy($_FILES['user_picture']['tmp_name'], $picture_path);
            }

            if(empty($_POST['id_user']))
            {
                header('location:login.php');
            }
            else
            {
                $_POST['picture'] = $picture_name;
                foreach ($_POST as $key => $value) 
                {
                    $_SESSION['user'][$key] = $value;
                }
                header('location:profile.php?m=update');
            }
        
        }
    }
}

// // Keep the values entered by the user if problem during the page reloading
// $pseudo = (isset($_POST['pseudo'])) ? $_POST['pseudo'] : '';
// // If we receive a POST, the variable will keep the values or if no POST, value is empty
// $firstname = (isset($_POST['firstname'])) ? $_POST['firstname'] : '';
// $lastname = (isset($_POST['lastname'])) ? $_POST['lastname'] : '';
// $email = (isset($_POST['email'])) ? $_POST['email'] : '';
// $address = (isset($_POST['address'])) ? $_POST['address'] : '';
// $zip_code = (isset($_POST['zc'])) ? $_POST['zc'] : '';
// $city = (isset($_POST['city'])) ? $_POST['city'] : '';
// $gender = (isset($_POST['gender'])) ? $_POST['gender'] : '';
// $picture = (isset($_POST['picture'])) ? $_POST['picture'] : '';

$pseudo = (isset($update_user)) ? $update_user['pseudo'] : '';
$password = (isset($update_user)) ? $update_user['pwd'] : '';
$firstname = (isset($update_user)) ? $update_user['firstname'] : '';
$lastname = (isset($update_user)) ? $update_user['lastname'] : '';
$email = (isset($update_user)) ? $update_user['email'] : '';
$gender = (isset($update_user)) ? $update_user['gender'] : '';
$city = (isset($update_user)) ? $update_user['city'] : '';
$zip_code = (isset($update_user)) ? $update_user['zip_code'] : '';
$address = (isset($update_user)) ? $update_user['address'] : '';
$picture = (isset($update_user)) ? $update_user['picture'] : '';

$id_user = (isset($update_user)) ? $update_user['id_user'] : '';

$action = (isset($update_user)) ? "Update" : "Sign Up";

// debug($update_user);
// debug($_POST);

?>
<!-- <?= debug($_POST) ?>
<?= debug($_SESSION['user'])?> -->

<h1 class="h3 mb-3 font-weight-normal"><?= $action ?></h1>

<form class="form-signin" action="" method="post" enctype="multipart/form-data">
  
    <img class="mb-4" src="uploads/img/eshop_icon2.png" alt="" width="150" height="150">

    <small class="form-text text-muted"> Any information collected from our users will not be sold, shared, or rented to others in ways different from what is disclosed in our <a href="">privacy statement</a>.</small>

    <br>
  
    <?= $msg_error ?>


  
        <input type="hidden" name="id_user" value="<?= $id_user ?>">

        <input type="text" name="pseudo" value="<?= $pseudo ?>"  placeholder="Choose a pseudo..." class="form-control">
        <?php
            if (isset($update_user)) 
            {
                echo "<input name='actual_pwd' value='$password' type='hidden'></input>";
            }
            else
            {
                echo '<div class="form-group">';
                    echo '<input type="password" name="password" placeholder="Choose a password..." class="form-control">';
                echo '</div>';
            }
        ?>
    </div>

    <div class="form-group">
        <input type="firstname" name="firstname" value="<?= $firstname ?>" placeholder="Your firstname..." class="form-control">
        <input type="lastname" name="lastname" value="<?= $lastname ?>" placeholder="Your lastname..." class="form-control">   
    </div>

    <div class="form-group">
        <input type="email" name="email" value="<?= $email ?>" placeholder="Your email..." class="form-control">
    </div>
    
    <div class="form-group">
        <select name="gender" class="form-control" style="padding:6px;">
            <option value="m" <?php if($gender == 'm'){echo 'selected';} ?>>Men</option>
            <option value="f"<?php if($gender == 'f'){echo 'selected';} ?>>Women</option>
            <option value="o"<?php if($gender == 'o'){echo 'selected';} ?>>Other</option>
        </select>
    </div>

    <div class="form-group">
        <input type="text" name="address" value="<?= $address ?>" placeholder="Address..." class="form-control">
        <input type="text" name="zip_code" value="<?= $zip_code ?>" placeholder="Zip code..." class="form-control">
        <input type="text" name="city" value="<?= $city ?>" placeholder="Your city..." class="form-control">
    </div>

    <div class="form-group">
        <label for="user_picture">Upload your profile picture...</label>
        <input type="file" class="form-control-file" id="user_picture" name="user_picture">
        <?php
            if (isset($update_user)) 
            {
                echo "<input name='actual_picture' value='$picture' type='hidden'></input>";
                echo "<img style='width:25%;' src='". URL . "uploads/img/$picture'>";
            }
        ?>
    </div>

    <div class="checkbox mb-2">
        <label>
        <input type="checkbox" value="remember-me"> Subscribe to our monthly newsletter
        </label>
    </div>

    <input type="submit" value="<?= $action ?>" class="btn btn-primary btn-lg btn-block"> 

</form>














<?php
    require_once("inc/footer.php");
?>