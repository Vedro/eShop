<?php

require_once('inc/header.php');

$page = 'Cart';

// if I want to delete product form the cart
if(isset($_GET['a']) && $_GET['a'] == 'delete') {
    if(isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])) 
    {
		$productToDelete = $_GET['id'];
        if($_SESSION['cart'][$productToDelete])
        {
			unset($_SESSION['cart'][$productToDelete]);
		}
        else 
        {
			header('location:cart.php');
		}
	}
    else 
    {
		header('location:cart.php');
	}
}

// if we want to delete the entire cart
if(isset($_GET['a']) && $_GET['a'] == 'empty') 
{
	unset($_SESSION['cart']);
	header('location:cart.php');
}

if($_POST)
{
	foreach($_SESSION['cart'] as $key => $value) {
		// $key = id_product
		//$value = array('quantity', 'picture', 'title', 'price')
		extract($value); // $quantity, $picture, $title, $price

		$result = $pdo -> query("SELECT stock FROM product WHERE id_product = $key");
		$product = $result -> fetch();
        
        // debug($product);

        if ($product['stock'] < $quantity) // if stock lower than quantity
        {
            if ($product['stock'] > 0) // stock ok but lower than quantity
            {
				$msg_error .= '<div class="alert alert-primary">The stock of ' . $title . ' isn\'t enough for your order. We only have ' . $product['stock'] . ' left. Please change the quantity</div>';

				$_SESSION['cart'][$key]['quantity'] = $product['stock'];
			}
            else // no stock at all
            {
				$msg_error .= '<div class="alert alert-primary">The product ' . $titre . ' is unfortunatelly not available anymore. We have deleted this product from your cart.</div>';

				unset($_SESSION['cart'][$key]);
			}
		}
	}

	// CAREFUL TO THE DELIVERY + PAYMENT (Stripe for instance)

	if(empty($msg_error))
	{ 

		$id_user = $_SESSION['user']['id_user'];
		$total = totalPrice();

		$result = $pdo->exec("INSERT INTO `order` (id_user, total_price, datetime, status) VALUES ($id_user, $total, NOW(), 'pending')");

		$id_order = $pdo->lastInsertID(); // return the last id_registered in the DTB

		// we register all the details in the table oredr_details
        foreach ($_SESSION['cart'] as $key => $value) 
        {
			extract($value);

			$result = $pdo->exec("INSERT INTO order_details (id_order, id_product, quantity, price) VALUES ($id_order, $key, $quantity, $price)");

			$result = $pdo->exec("UPDATE product SET stock = (stock - $quantity) WHERE id_product = $key"); // Update the stock in the product table
		}

		$msg_success .= '<div class="alert alert-success">Congratulations, your order #' . $id_order . ' is confirmed. You will receive a mail with all the informations related to it soon !</div>';

		unset($_SESSION['cart']); // we delete the cart
	}

}

// debug($_SESSION);

?>

<!-- Contenu HTML -->
<h1>Cart</h1>
<?= $msg_error ?>
<?= $msg_success ?>
<table border="1" style="border-collapse; cellpadding:7">
	<tr>
		<td colspan="6">Cart : <?= (productNumber()) ? productNumber() . ' products' : '' ?></td>
	</tr>	
	<tr>
		<th>Picture</th>
		<th>Title</th>
		<th>Quantity</th>
		<th>Unit price</th>
		<th>Total price</th>
		<th>Delete</th>
	</tr>

	<?php if(empty($_SESSION['cart'])) : ?>
		<tr>
			<td colspan="6">Your cart is empty. Please visit our <a href="eshop.php"><u>eShop.</u></a></td>
		</tr>
	<?php else : ?>
		<?php foreach ($_SESSION['cart'] as $key => $value) : ?>
			<?php extract($value) ?>
			<tr>
				<td><a href="product_page.php?id=<?= $key ?>"><img src="<?= URL ?>uploads/img/<?= $picture ?>" height="80"></a></td>
				<td><?= $title ?></td>
				<td><?= $quantity ?></td>
				<td><?= $price ?></td>
				<td><?= ($price * $quantity) ?></td>
				<td><a href="?a=delete&id=<?= $key ?>"><i class="far fa-trash-alt"></i></a></td>
			</tr>
		<?php endforeach; ?>
		<tr>
			<th colspan="4">TOTAL</th>
			<th colspan="2"><?= formatPrice(totalPrice()) ?>€</th>
		</tr>
		<tr>
			<td colspan="6"><a href="?a=empty"><em>Empty my cart.</em></a></td>
		</tr>

		<!-- If user is connected = PAY -->
		<?php if(userConnect()) : ?>
			<tr>
				<td colspan="6">
					<form method="post" action="">
						<input type="submit" name="validation" value="Buy the products" class="btn btn-primary">
					</form>
				</td>
			</tr>
		<?php else : ?>
			<tr>
				<td colspan="6">
					To buy the products, please <a href="login.php?p=panier"><u>login into your account</u></a>, or you could <a href="signup.php"><u>register yourself.</u></a>
				</td>
			</tr>
		<?php endif; ?>
	<?php endif; ?>
</table>

<?php
require_once('inc/footer.php'); 
?>