<?php
require_once("inc/header.php");

$page = "Signup";

// debug($_POST);

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

    if (empty($msg_error)) 
    {
        // check if pseudo is available
        $result = $pdo->prepare("SELECT pseudo FROM user WHERE pseudo = :pseudo");
        $result->bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);

        $result->execute();

        if ($result->rowcount() == 1) 
        {
            $msg_error .= "<div class='alert alert-secondary'>The pseudo $_POST[pseudo] is already taken, please choose another one</div>";
        }
        else {
            $result = $pdo->prepare("INSERT INTO user (pseudo, pwd, firstname, lastname, email, gender, city, zip_code, address, privilege) VALUES (:pseudo, :pwd, :firstname, :lastname, :email, :gender, :city, :zip_code, :address, 0)");

            $hashed_pwd = password_hash($_POST['password'], PASSWORD_BCRYPT);
            // function password_hash() allows us to encrypt the password in a much secure way than md5. It takes 2 args:
            // - the result to hash
            // - the method
            $result->bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
            $result->bindValue(':pwd', $hashed_pwd, PDO::PARAM_STR);
            $result->bindValue(':firstname', $_POST['firstname'], PDO::PARAM_STR);
            $result->bindValue(':lastname', $_POST['lastname'], PDO::PARAM_STR);
            $result->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
            $result->bindValue(':gender', $_POST['gender'], PDO::PARAM_STR);
            $result->bindValue(':city', $_POST['city'], PDO::PARAM_STR);
            $result->bindValue(':address', $_POST['address'], PDO::PARAM_STR);
            $result->bindValue(':zip_code', $_POST['zc'], PDO::PARAM_STR);


            if ($result->execute()) 
            {
                header('location:login.php');
            }
        }
    }
}

// Keep the values entered by the user if problem during the page reloading
$pseudo = (isset($_POST['pseudo'])) ? $_POST['pseudo'] : '';
// If we receive a POST, the variable will keep the values or if no POST, value is empty
$firstname = (isset($_POST['firstname'])) ? $_POST['firstname'] : '';
$lastname = (isset($_POST['lastname'])) ? $_POST['lastname'] : '';
$email = (isset($_POST['email'])) ? $_POST['email'] : '';
$address = (isset($_POST['address'])) ? $_POST['address'] : '';
$zip_code = (isset($_POST['zc'])) ? $_POST['zc'] : '';
$city = (isset($_POST['city'])) ? $_POST['city'] : '';
$gender = (isset($_POST['gender'])) ? $_POST['gender'] : '';

if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])) 
{
    $req = "SELECT * FROM user WHERE id_user = :id_user";

    $result = $pdo->prepare($req);

    $result->bindValue(':id_user', $_GET['id'], PDO::PARAM_INT);

    $result->execute();

    if ($result->rowCount() == 1) 
    {
        $update_user = $result->fetch();
    }
}
// debug($update_product);

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

$action = (isset($update_user)) ? "Update" : "Add";


?>

<h1><?= $page ?></h1>

<form action="" method="post">
    <small id="emailHelp" class="form-text text-muted">We'll never use your datas for commercial use.</small>
    <?= $msg_error ?>
    <div class="form-group">
        <input type="text" name="pseudo" value="<?= $pseudo ?>"  placeholder="Choose a pseudo..." class="form-control">
    </div>
    <div class="form-group">
        <input type="password" name="password" placeholder="Choose a password..." class="form-control">
    </div>    
    <div class="form-group">
        <input type="firstname" name="firstname" value="<?= $firstname ?>" placeholder="Your firstname..." class="form-control">
    </div>
    <div class="form-group">
        <input type="lastname" name="lastname" value="<?= $lastname ?>" placeholder="Your lastname..." class="form-control">
    </div>
    <div class="form-group">
        <input type="email" name="email" value="<?= $email ?>" placeholder="Your email..." class="form-control">
    </div>
    <div class="form-group">
        <select name="gender" class="form-control">
            <option value="m" <?php if($gender == 'm'){echo 'selected';} ?>>Men</option>
            <option value="f"<?php if($gender == 'f'){echo 'selected';} ?>>Women</option>
            <option value="o"<?php if($gender == 'o'){echo 'selected';} ?>>Other</option>
        </select>
    </div>
    <div class="form-group">
        <input type="text" name="address" value="<?= $address ?>" placeholder="Address..." class="form-control">
    </div>
    <div class="form-group">
        <input type="text" name="zc" value="<?= $zip_code ?>" placeholder="Zip code..." class="form-control">
    </div>
    <div class="form-group">
        <input type="text" name="city" value="<?= $city ?>" placeholder="Your city..." class="form-control">
    </div>
    <input type="submit" value="Send" class="btn btn-success btn-lg btn-block"> 
</form>

<?php
    require_once("inc/footer.php");
?>