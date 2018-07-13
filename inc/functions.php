<?php

function debug($var, $mode = 1)
{
    echo "<div class='alert alert-warning'>";

        $trace = debug_backtrace();
        // function debug_backtrace() allows us to track the place where the function is called [multi array]
        $trace = array_shift($trace);
        // function array_shift() allows me to have access to the result in a simple array

        echo "The debug was called in the file $trace[file] at the line $trace[line] <hr>";

        echo '<pre>';
            switch ($mode) 
            {
                case '1':
                    var_dump($var);
                    break;
                
                default:
                    print_r($var);
                    break;
            }
        echo '</pre>';

    echo "</div>";
}

// function to check if the user is connected

function userConnect()
{
    if (isset($_SESSION['user'])) 
    {
        return TRUE;
    }
    else 
    {
        return FALSE;
    }

    // if (isset($_SESSION['user'])) return TRUE; else return FALSE;
    // when you have just one condition => we can write this way
}

function userAdmin()
{
    if (userConnect() && $_SESSION['user']['privilege'] == 1) 
    {
        return TRUE;
    }
    else 
    {
        return FALSE;
    }

    // if (userConnect() && $_SESSION['user']['privilege'] == 1) return TRUE; else return FALSE;
}