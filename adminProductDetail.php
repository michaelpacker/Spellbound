<?php
	$page_class = 'admin product';
	$page_title = 'Admin - Product Detail';

//	if(!isset($_SESSION))
//	 {
//		 header("Location:index.php");
//	 }
	$this_product = '';
	if (isset($_GET['id'])) {
		$this_product = intval($_GET['id']);
	}
	require "includes/header.inc.php";
?>


<div class="contentContainer">
	<?php
		if(!isset($_SESSION['user-id'])) {
			echo "<section>";
			echo "<p>You do not have permission to view this page.</p>";
			echo "</section>";
		} else {
			
		$sql = "SELECT * FROM products WHERE id = $this_product;";
		$result = mysqli_query($conn, $sql);
		$resultCheck = mysqli_num_rows($result);

		if ($resultCheck > 0) {

			while ($row = mysqli_fetch_assoc($result)) {
				$thisType = $row['type'];
				$thisMaterial = $row['material'];
				$thisSupplier = $row['supplier'];
				$thisURL = $row['url'];
				$thisStatus = $row['status'];
				?>
						
				<h2>Edit Product: <?php echo $row['name']; ?></h2>
				<section class="itemDetail">
					<form action="includes/adminProductUpdate.inc.php" method="POST">
						<input type="hidden" name="id" value="<?php echo $row['id']; ?>">
						<div class="formGroup">
							<label for="productName">Product Number</label>
							<input type="text" id="productNumber" name="product_number" value="<?php echo $row['prod_num']; ?>" required>
						</div>
						<div class="formGroup">
							<label for="productName">Name</label>
							<input type="text" id="productName" name="product_name" value="<?php echo $row['name']; ?>" required>
						</div>
						<div class="formGroup">
							<label for="productDescSp">What we call it</label>
							<input type="text" id="productDescSp" name="product_desc_sp" value="<?php echo $row['desc_sp']; ?>" required>
						</div>
						<div class="formGroup">
							<fieldset>
								<legend>Status</legend>
								<ul>
									<?php 
										if ($thisStatus == 1) {
										?>
											<li><label for="statusActive"><input class="radio" type="radio" name="product_status" value="1" id="statusActive" checked> Active</label></li>
											<li><label for="statusInactive"><input class="radio" type="radio" name="product_status" value="0" id="statusInactive"> Inactive</label></li>
										<?php
										} else {
										?>
											<li><label for="statusActive"><input class="radio" type="radio" name="product_status" value="1" id="statusActive"> Active</label></li>
											<li><label for="statusInactive"><input class="radio" type="radio" name="product_status" value="0" id="statusInactive" checked> Inactive</label></li>
										<?php
										}
									?>

								</ul>
							</fieldset>
						</div>
						<div class="formGroup">
							<label for="productDesc">Product description</label>
							<textarea id="productDesc" name="product_desc" required aria-describedby="char_limit"><?php echo $row['description']; ?></textarea>
							<p id="char_limit" class="formHelp">Character limit: <span class="char_limit_count" aria-live="polite">000</span>/500</p>
						</div>
						<div class="formGroup">
							<label for="wholesalePrice">Wholessale price</label>
							<input type="number" id="wholesalePrice" name="wholesale_price" step=".01" value="<?php echo $row['price_wholesale']; ?>" required>
						</div>
						<div class="formGroup">
							<label for="retailPrice">Retail price</label>
							<input type="number" id="retailPrice" name="retail_price" step=".01" value="<?php echo $row['price_retail']; ?>" required>
						</div>
						<div class="formGroup">
							<label for="imagePath">Image path</label>
							<input type="text" id="imagePath" name="image_path" value="<?php echo $row['file_name']; ?>" required aria-describedby="imagePathHelp">
							<p id="imagePathHelp" class="formHelp">Ex: ear/alch/e395_black_star.jpg</p>
							<!-- figure out if the product is jewlery or what and then build the image path -->
						</div>
						<div class="formGroup">
							<label for="inventoryCount">Inventory</label>
							<input type="number" id="inventoryCount" name="inventory_count" value="<?php echo $row['count']; ?>">
						</div>
						<div class="formGroup">
							<label for="productType">Type</label>
							<select id="productType" name="product_type">
								<?php
								$sqlTypes = "SELECT * FROM product_types ORDER BY typeName;";
								$resultTypes = mysqli_query($conn, $sqlTypes);

								while(($row = mysqli_fetch_assoc($resultTypes)) !== NULL) {
									$type = $row['typeName'];
									$type_id = $row['typeID'];
									$typeDisplay = ucfirst($type);

									if ($thisType == $type_id) {
										echo "<option value='".$row['typeID']."' selected>".$typeDisplay."</option>";
									} else {
										echo "<option value='".$row['typeID']."'>".$typeDisplay."</option>";
									}
								}
								?>
							</select>
						</div>
						<div class="formGroup">
							<label for="productMaterial">Material</label>
							<input type="text" id="product_material" name="product_material" value="<?php echo $thisMaterial; ?>" aria-describedby="materialHelp">
							<p id="materialHelp" class="formHelp">Comma separated values, no spaces please</p>
						</div>
						<div class="formGroup">
							<label for="productSupplier">Supplier</label>
							<select id="productSupplier" name="product_supplier">
								<?php
								$sqlSuppliers = "SELECT * FROM suppliers ORDER BY name;";
								$resultSuppliers = mysqli_query($conn, $sqlSuppliers);

								while(($row = mysqli_fetch_assoc($resultSuppliers)) !== NULL) {
									$supplier_name = $row['name'];
									$supplier_id = $row['supplierID'];

									if ($thisSupplier == $supplier_id) {
										echo "<option value='".$supplier_id."' selected>".$supplier_name."</option>";
									} else {
										echo "<option value='".$supplier_id."'>".$supplier_name."</option>";
									}
								}
								?>
							</select>
						</div>
						<div class="formGroup">
							<label for="supplierURL">Supplier Catalog URL</label>
							<input type="url" id="supplierURL" name="supplier_url" value="<?php echo $thisURL;  ?>">
						</div>
						<div class="formGroup">
							<ul class="formControls">
								<li><button type="submit" name="product-update">Update</button></li>
								<li><a href="adminProducts.php">Cancel</a></li>
							</ul>
						</div>
					</form>
				<?php
			}
		} else {
			echo "<h2>Edit Product</h2>";
			echo "<p>Product not found. Fuckity-doo.</p>";
		}
		?>
	</section>
<?php } ?>
		
</div>
<?php
	require 'includes/footer.inc.php';
?>