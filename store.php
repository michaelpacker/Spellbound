<?php
	$page_class = 'store catalog secondary';
	$page_title = 'Store';
	$paginations = 0;
	require "includes/header.inc.php";
	require "includes/pagination.inc.php";

	$this_product;
	$per_page = 9;
	$this_product = 2;
	$pg_num = 1;
	$admin = false;

	if (isset($_GET['pid'])) {
		$this_product = intval($_GET['pid']);
	} else {
		// Default to earrings
		$this_product = 2;
		
	}
if(!isset($_SESSION['user-id'])) {
	$admin = false;
} else {
	$admin = true;
}



?>
	<?php
		$sql = "SELECT typeName FROM product_types WHERE typeID = $this_product;";
		$result = mysqli_query($conn, $sql);
		$resultCheck = mysqli_num_rows($result);

		if ($resultCheck > 0) { 
			while ($row = mysqli_fetch_assoc($result)) {
				$typeName = $row['typeName'];
				
				echo "<div class='catalogContainer contentContainer' data-category='" .$typeName. "'>";	
			}
			
		} else {
			echo "<div class='catalogContainer contentContainer' data-category=''>";
		}
		
	?>
	
		<section class="catalogIntro">
			<p>We use <a href="http://www.paypal.com">PayPal</a> for fast and secure checkout.</p>
		</section> 
		<div class="storeNav">
			<nav aria-label="Product Categories">
				<ul>
					<li>
						<a href="store.php?pid=2&pg=1" class="store-nav-link" role="tab" id="earrings-tab">Earrings</a>
					</li>
					<li>
						<a href="store.php?pid=3&pg=1" class="store-nav-link" role="tab" id="necklaces-tab">Necklaces</a>
					</li>
				</ul>
			</nav>
		</div>
			<section class="productCategory" aria-labelledby="">
			<div class="productGrid test">
			<?php

				
				$sql = "SELECT * FROM products WHERE type = $this_product AND status = '1' ORDER BY name LIMIT $start, $per_page;";
				$result = mysqli_query($conn, $sql);
				$resultCheck = mysqli_num_rows($result);
				
				if ($resultCheck > 0) {

					while ($row = mysqli_fetch_assoc($result)) {
						$id = $row['id'];
						$price = $row['price_retail'];
						$title = $row['name'];
						$prod_num = $row['prod_num'];
						$desc = $row['description'];
						
						echo "<div class='card' data-name='".$prod_num."'>";// flip-card-container
							echo "<div class='cardContainer'>" // flip-card
								 ."<div class='cardFront'>"
									."<h3>".$title."</h3>"
									."<img src='assets/img/products/jewelry/".$row['file_name']."' alt='' aria-hidden='true'>"

									."<div class='cardDetails'>"
										."<p class='productPrice'>$".$price."</p>"	
										."<button class='detailToggle js-detailToggle' aria-hidden='true'><i class='fa fa-info-circle'></i><span class='visually-hidden'> More info</span></button>";
									echo "</div>"; // end .cardDetails
									if ($admin == true) {
										echo "<div class='edit-link'><a href='adminProductDetail.php?id=".$id."'>Edit this thang</a></div>";
									}
									echo "<form target='paypal' action='https://www.paypal.com/cgi-bin/webscr' method='post'>"
									."<input type='hidden' name='add' value='1'>"
									."<input type='hidden' name='cmd' value='_cart'>"
									."<input type='hidden' name='business' value='jewelryspellbound@gmail.com'>"
									."<input type='hidden' name='item_name' value='".$title." - ".$typeName." - ".$prod_num."'>"
									."<input type='hidden' name='amount' value='".$price."'>"
									."<input type='hidden' name='no_note' value='1'>"
									."<input type='hidden' name='currency_code' value='USD'>"
									."<input type='hidden' name='bn' value='PP-ShopCartBF'>"
									."<input type='hidden' name='undefined_quantity' value='1'>"
									."<button type='submit' class='addToCart'><span class='payPalTag'><span> Add to Cart</button>"
									."</form>";
							echo "</div>"; // end .cardFront
				?>
				
							<div class='cardBack'>
								<div class="cardDetails">
									<h4><?php echo $title; ?></h4>
									<button class='detailToggle js-detailToggle' aria-hidden="true"><i class='fa fa-times'></i><span class='visually-hidden'>Less Info</span></button>
								</div>
								<div class="cardDesc">
									<?php
										echo $desc;
									?>
								</div>
							</div>
						</div>
						<?php
						

						
								
						echo "</div>";// END .card
						
					}
					
					$sql = "SELECT * FROM products WHERE type = $this_product";
					$result = mysqli_query($conn, $sql);
					$resultCount = mysqli_num_rows($result);
					
					// If records are greater than 10, do pagination
					$paginations = ceil($resultCount / $per_page);
					
					

					
				} else {
					echo "<p>No items were found. Really - this shouldn't happen.</p>";
				}
			?>
			</div>
				
			<?php
					if ($paginations > 1) {
					echo "<div class='pagination'>";

						
						// Display pagination object
						echo "<ul>";
							
							if($page_counter == 1){
								// We're on the first page. Pages start with 1, not 0.
								echo "<li class='disabled'><i class='fas fa-caret-left'></i><span class='visually-hidden'> Previous</span></li>"; 
								echo "<li class='active'>1</li>";
								
								// Build out the next several list items for the first page pagination
								for($j = 1; $j < $paginations; $j++) { 
									$pg_num = $j + 1;
								  echo "<li><a href='store.php?pid=".$this_product."&pg=".$pg_num."'>".$pg_num."</a></li>";
							   
								}
								echo "<li class='disabled'><i class='fas fa-caret-right'></i><span class='visually-hidden'> Next</span></li>"; 
								
							}else{
								// Interior  pagination
								echo "<li><a href=store.php?pid=".$this_product."&pg=$previous><i class='fas fa-caret-left'></i><span class='visually-hidden'> Previous</span></a></li>"; 
								
								for($j=1; $j <= $paginations; $j++) {
								 
									if ($j == $page_counter) {
										$pg_num = $j + 1;
										echo "<li class='active'>".$j."</li>";
								 	
									} else {
										$pg_num = $j + 1;
										echo "<li><a href='store.php?pid=".$this_product."&pg=".$j."' >".$j."</a></li>";
								 }									
									
							  }


								if ($page_counter == $paginations) {
									echo "<li class='disabled'><i class='fas fa-caret-right'></i><span class='visually-hidden'> Next</span></li>";
								} else {
									
									echo "<li><a href='store.php?pid=".$this_product."&pg=".$next."'><i class='fas fa-caret-right'></i><span class='visually-hidden'> Next</span></a></li>";
								}

							} 
						
						echo "</ul>";
						echo "<p class='pageLocation'>Page " .$page_counter. " of " .$paginations. "</p>";
					echo "</div>";
					}
			?>
			</section>
			
		</div>	
	
		
	</div>
	<div class="modalOverlay" style="display: none;">
		<div class="modalContainer">
		</div>
	</div>
<?php
	require "includes/footer.inc.php";
?>
