<?php

    require_once('inc/init.php');

    unset($_SESSION['user']);
    // we delete only the datas linked to the user session

    header('location:index.php');
