<?php

require_once("inc/header.php");

$page = "Login";

// debug($_POST);

if ($_POST) 
{
    $req = "SELECT * FROM user WHERE pseudo = :pseudo";

    $result = $pdo->prepare($req);
    $result->bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);

    $test = $result->execute();

    if ($result->rowcount() > 0) 
    // if we select a pseudo in the DTB
    {
        $user = $result->fetch();
        
        if (password_verify($_POST['password'], $user['pwd'])) 
        // function password_verify() is link to password_hash(). It allows us to check the correspondance between 2 values:
        // - 1st arg: the value to check
        // - 2nd arg: the match value
        {
            // $_SESSION['pseudo'] = $user['pseudo']
            // $_SESSION['user']['firstname'] = $user['firstname']

            foreach ($user as $key => $value) 
            {
                if ($key != 'pwd') 
                {
                    $_SESSION['user'][$key] = $value;

                    header('location:profile.php');
                }
            }
        }
        else 
        {
            $msg_error .= "<div class='alert alert-danger'>Identification error. <br> Please try again !</div>";
        }
    }
    else 
    {
        $msg_error .= "<div class='alert alert-danger'>Identification error. <br> Please try again !</div>";
    }
}
?>

<h1><?= $page ?></h1>

<form action="" method="post">
<?= $msg_error ?>
    <div class="form-group">
        <input type="text" name="pseudo" placeholder="Your pseudo..." class="form-control">
    </div>
    <div class="form-group">
        <input type="password" name="password" placeholder="Your password..." class="form-control">
    </div>
    <input type="submit" value="login" class="btn btn-success btn-lg btn-block">
</form>

<?php

require_once("inc/footer.php");

?>