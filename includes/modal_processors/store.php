<?php

require "../dbh.inc.php";

$productID = $_POST['product'];

$sql = "SELECT * FROM products WHERE id = $productID;";
$result = mysqli_query($conn, $sql);

while($row=mysqli_fetch_assoc($result)) {
	$prdNum = $row['prod_num'];
	$prdName = $row['name'];
	$prdImg = $row['file_name'];
	$prdDesc = $row['description'];
	
	
	?>

		<div class="productDetailContent">
			<h2><?php echo $prdName; ?></h2>
			<div class="left">
				<img src="assets/img/products/jewelry/<?php echo $prdImg ?>" alt="">
			</div>
			<div class="right">
				<p><?php echo $prdDesc ?></p>
				<p>Dimensions</p>
			</div>
			<div class="bottom">
				<form target='paypal' action='https://www.paypal.com/cgi-bin/webscr' method='post'>
					<input type='hidden' name='add' value='1'>
					<input type='hidden' name='cmd' value='_cart'>
					<input type='hidden' name='business' value='jewelryspellbound@gmail.com'>
					<input type='hidden' name='item_name' value='<?php echo $prdName; ?> - <?php echo $prdNum; ?>'>
					<input type='hidden' name='amount' value='".$price."'>
					<input type='hidden' name='no_note' value='1'>
					<input type='hidden' name='currency_code' value='USD'>
					<input type='hidden' name='bn' value='PP-ShopCartBF'>
					<input type='hidden' name='undefined_quantity' value='1'>
					<button type='submit' class='addToCart'><span class='payPalTag'><span> Add to Cart</button>
				</form>	
			</div>
		</div>


	<?php
	
}