<?php
	$page_class = 'admin product';
	$page_title = 'Admin - Add a Product';

//	if(!isset($_SESSION))
//	 {
//		 header("Location:index.php");
//	 }
	$this_product = '';
	if (isset($_GET['id'])) {
		$this_product = $_GET['id'];
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
		?>	

						
		<h2>Add a Product</h2>
		<section class="productDetail">
			<form action="includes/adminProductCreate.inc.php" method="POST">
			<div class="formGroup">
				<label for="productType">Type</label>
				<select id="productType" name="product_type">
					<?php
					$sqlTypes = "SELECT * FROM product_types ORDER BY typeName;";
					$resultTypes = mysqli_query($conn, $sqlTypes);

					while(($row = mysqli_fetch_assoc($resultTypes)) !== NULL) {
						$type = $row['typeName'];
						$typeDisplay = ucfirst($type);
						echo "<option value='".$row['typeID']."'>".$typeDisplay."</option>";
					}
					?>
				</select>
			</div>
			<div class="formGroup">
				<label for="productName">Product Number</label>
				<input type="text" id="productNumber" name="product_number" required>
			</div>
			<div class="formGroup">
				<label for="productName">Name</label>
				<input type="text" id="productName" name="product_name" required>
			</div>
			<div class="formGroup">
				<label for="productDescSp">What we call it</label>
				<input type="text" id="productDescSp" name="product_desc_sp" required>
			</div>
			<div class="formGroup">
				<fieldset>
					<legend>Status</legend>
					<ul>
						<li><label for="statusActive"><input class="radio" type="radio" name="product_status" value="1" is="statusActive"> Active</label></li>
						<li><label for="statusInactive"><input class="radio" type="radio" name="product_status" value="0" id="statusInactive" checked>Inactive</label></li>
					</ul>
				</fieldset>
			</div>
			<div class="formGroup">
				<label for="productDesc">Product description</label>
				<textarea id="productDesc" name="product_desc" required></textarea>
			</div>
			<div class="formGroup">
				<label for="wholesalePrice">Wholessale price</label>
				<input type="number" id="wholesalePrice" name="wholesale_price" step=".01" required>
			</div>
			<div class="formGroup">
				<label for="retailPrice">Retail price</label>
				<input type="number" id="retailPrice" name="retail_price" step=".01" required>
			</div>
			<div class="formGroup">
				<label for="imagePath">Image path</label>
				<input type="text" id="imagePath" name="image_path" required aria-describedby="imagePathHelp">
				<p id="imagePathHelp" class="formHelp">Ex: ear/alch/e395_black_star.jpg</p>
			</div>
			<div class="formGroup">
				<label for="inventoryCount">Inventory</label>
				<input type="number" id="inventoryCount" name="inventory_count" value="0">
			</div>
			<div class="formGroup">
				<label for="productMaterial">Material(s)</label>
				<input type="text" id="product_material" name="product_material" aria-describedby="material_help">
				<p id="material_help" class="helpText">Comma separated values, no spaces please</p>
			</div>
			<div class="formGroup">
				<label for="productType">Supplier</label>
				<select id="productType" name="product_supplier">
					<option value="" selected>No primary supplier</option>
					<?php
					$sql = "SELECT * FROM suppliers ORDER BY name;";
					$results = mysqli_query($conn, $sql);

					while(($row = mysqli_fetch_assoc($results)) !== NULL) {
						$supplier = $row['name'];
						echo "<option value='".$row['supplierID']."'>".$supplier."</option>";
					}
					?>
				</select>
			</div>
			<div class="formGroup">
				<label for="supplierURL">Supplier Product Catalog URL</label>
				<input type="url" id="supplierURL" name="supplier_url" aria-describedby="catalog_url_help">
				<p id="catalog_url_help" class="helpText">Web address where you would find this product in the suppliers catalog</p>
			</div>
			<div class="formGroup">
				<ul class="formControls">
					<li><button type="submit" name="product-create">Add this product</button></li>
					<li><a href="adminProducts.php">Cancel</a></li>
				</ul>
			</div>
			</form>
	</section>
	
	<?php } ?>
</div>
<?php
	require 'includes/footer.inc.php';
?>