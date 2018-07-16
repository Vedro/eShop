<?php

require_once("inc/header.php");

$page = "Sign in";

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

<h1 class="h3 mb-3 font-weight-normal"><?= $page ?></h1>


<form class="form-signin" action="" method="post">
    
    <img class="mb-4" src="uploads/img/eshop_icon2.png" alt="" width="150" height="150">

    <?= $msg_error ?>

    <input type="text" id="inputEmail" class="form-control" placeholder="Username" name="pseudo">

    <input type="password" id="inputPassword" class="form-control" placeholder="Password" name="password">

    <div class="checkbox mb-3">
        <label>
        <input type="checkbox" value="forgot-pass"> Forgot password?
        </label>
    </div>

    <button class="btn btn-lg btn-primary btn-block" type="submit" value="login">Sign in</button>
    
</form>





<?php

require_once("inc/footer.php");

?>