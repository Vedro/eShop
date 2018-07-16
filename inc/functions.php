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



// function to add product in the cart
function addProduct($id_product, $quantity, $picture, $title, $price) 
{
    if(!isset($_SESSION['cart'])) 
    {
		$_SESSION['cart'] = array(); // if no cart, we create one
	}

    if(isset($_SESSION['cart'][$id_product])) 
    {
		$_SESSION['cart'][$id_product]['quantity'] += $quantity; // If the product is already in the cart, we just add the new quantity instead of create a new line in the cart
	}
    else 
    {
		$_SESSION['cart'][$id_product] = array();
		$_SESSION['cart'][$id_product]['quantity'] = $quantity;
		$_SESSION['cart'][$id_product]['picture'] = $picture;
		$_SESSION['cart'][$id_product]['title'] = $title;
		$_SESSION['cart'][$id_product]['price'] = $price;
	}
}

// function to count the number of product in the cart (bubble next to the cart)
function productNumber() 
{
	$productQuantity = 0; // we start the count at 0

    if (!empty($_SESSION['cart']))  // we are looking if the cart is created
    {
        foreach ($_SESSION['cart'] as $product) 
        {
			$productQuantity += $product['quantity']; // we gather all the quantity of the cart
		}
	}

	return $productQuantity;
}

// function to count the total price of the cart
function totalPrice() 
{
    $total = 0;
    
    if(!empty($_SESSION['cart'])) 
    {
        foreach ($_SESSION['cart'] as $product) 
        {
			$total += $product['price'] * $product['quantity'];
		}
	}
	
	return $total;
}

// We want to display a nice amout if we have a total price of 4 number
function formatPrice($total)
{
    if (strlen($total)>3 || strlen($total)>6) 
    {
		return number_format($total, 2,',','.');
    }
    else
    {
		return $total;
	}
}