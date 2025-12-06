<?php
	$page_class = 'admin landing secondary';
	$page_title = 'Admin - Manage Products';

//	if(!isset($_SESSION))
//	 {
//		 header("Location:index.php");
//	 }

$update = false;
$create = false;
$delete = false;
$supplierFilter = false;
if (isset($_GET['updateStatus'])) {
	$updateStatus = $_GET['updateStatus'];
	$update = true;
}
if (isset($_GET['updatedProductName'])) {
	$updatedName = $_GET['updatedProductName'];
}

if (isset($_GET['createStatus'])) { 
	$createStatus = $_GET['createStatus'];
	$create = true;
}
if (isset($_GET['createdName'])) {
	$createdName = $_GET['createdName'];
}
if (isset($_GET['deleteStatus'])) {
	$deletedName = $_GET['deletedName'];
	$deleteStatus = $_GET['deleteStatus'];
	$delete = true;
}


// See what the product ID is, shows product category on the page
if (isset($_GET['pid'])) {
	$this_product = $_GET['pid'];
} else {
	$this_product = 2;
}
// See what the supplier ID is, shows the suppliers
if (isset($_GET['sid'])) {
	$this_supplier = $_GET['sid'];
	$supplierFilter = true;
} else {
	// otherwise show all suppliers
	$this_supplier = 0;
}

	require "includes/header.inc.php";
	require "includes/pagination.inc.php";
	$paginations = 0;
	$per_page = 10;
?>


<div class="contentContainer">
	
	<h2>Manage Products</h2>
	<div class="tools">
		<?php
		if(isset($_SESSION['user-id'])) {
			echo "<ul class='productTools'><li class='adminLink'>";
			echo "<a href='adminProductCreate.php'><i class='fas fa-plus'></i> Add a product</a>";
			echo "</li></ul>";
		}
		?>
	</div>
	<div class="updateResponse">
	<?php
		if ($update == true) {
			if ($updateStatus === 'success') {
				echo "<div class='success message'>";
					echo "<p><span class='messageProductName'>" .$updatedName. "</span> was successfully updated.</p>";
				echo "</div>";
			} else {
				echo "<div class='error message'>";
					echo "<p>There was a problem with updating <span class='messageProductName'>" .$updatedName. "</span>. Try again or give up.</p>";
				echo "</div>";
			}
		}
		if ($create == true) {
			if ($createStatus === 'success') { 
				echo "<div class='success message'>";
					echo "<p><span class='messageProductName'>" .$createdName. "</span> was successfully created.</p>";
				echo "</div>";
			} else {
				echo "<div class='error message'>";
					echo "<p>There was a problem with creating the item called <span class='messageProductName'>" .$createdName. "</span>. Try again or give up.</p>";
				echo "</div>";
			}
		}
		if ($delete == true) {
			if ($deleteStatus === 'success') {
				echo "<div class='success message'>";
					echo "<p><span class='messageProductName'>".$deletedName."</span> was successfully deleted.</p>";
				echo "</div>";
			} else {
				echo "<div class='error message'>";
					echo "<p>There was a problem deleting <span class='messageProductName'>" .$deletedName. "</span>. Try again or give up.</p>";
				echo "</div>";
			}
		}
	?>
	</div>
	
	
	<?php
		if(!isset($_SESSION['user-id'])) {
			echo "<section>";
			echo "<p>You do not have permission to view this page.</p>";
			echo "</section>";
		} else {
		?>
			<div class="adminFilter">
				<form method="GET" action="">
					<select name="pid" id="productCategorySelect" class="filterSelect" aria-label="Select a product category">
					<?php
						// build the options for the product type select filter
						// form submitted by js on change // STORE ADMIN SELECT
						$sql = "SELECT * FROM product_types ORDER BY typeName;";
						$result = mysqli_query($conn, $sql);
						$resultCheck = mysqli_num_rows($result);

						if ($resultCheck <= 0) { 
							echo "<p>No product types found. Something is really wrong.</p>";
						} else {
							while ($row = mysqli_fetch_assoc($result)) { 
								$name = $row['typeName'];
								$typeID = $row['typeID'];

								if ($typeID == '4') {
									// if type is boxes
									// concatinate the type name with the suffix 'es'
									// makes the name plural
									$name .= 'es';
								} else if ($typeID == '2') {
									// if type is earrings, then just give it the the name
									// earrings is already plural
									$name = $name;
								} else {
									// concatinate everything else with just 's'
									$name .= 's';
								}

								if ($this_product == $typeID) {
									echo "<option value='" .$typeID. "' selected>".ucfirst($name)."</option>";
								} else {
									echo "<option value='" .$typeID. "'>".ucfirst($name)."</option>";
								}
							}
						}
					?>
					</select>
				
				<!-- build supplier filter -->
				<!-- we still have product category, now we need to know what suppliers belong in the category -->
				<!-- 
					Suppliers is its own table. List suppliers, with All as a default
					On select, get supplier id val
				-->
				
					<?php
						$suppliersArr = array("0");
						// try to return suppliers only associated with this product type
						$sql = "SELECT * FROM products WHERE type = $this_product;";
						$result = mysqli_query($conn, $sql);
						$resultCheck = mysqli_num_rows($result);
			
						if ($resultCheck > 0) {

							echo "<select name='sid' id='supplierSelect' class='filterSelect' aria-label='Supplier filter'>";
							
							while ($row = mysqli_fetch_assoc($result)) {

								$supplier = $row['supplier'];
								$supplierName;

								// see if the supplier is already in the array
								// if not, then push to the array
		
								foreach($suppliersArr as $k => $v) { 
									if(!in_array($supplier, $suppliersArr)){
										array_push($suppliersArr, $supplier);
									}
								}
								
							}
						
							// now build the select list for suppliers
							// if 0, then value is "all"
						
							foreach($suppliersArr as $k => $v) {
								
								if ($v == 0) {
									echo "<option value='".$v." selected'>All suppliers</option>";
								} else {
									
									if ($this_supplier == $v) {
										echo "<option value='".$v."' selected>";
									} else {
										echo "<option value='".$v."'>";
									}
									
									$sqlSupplier = "SELECT name FROM suppliers WHERE supplierID = $v";
									$resultSupplier = mysqli_query($conn, $sqlSupplier);
									while ($rowSupplier = mysqli_fetch_assoc($resultSupplier)) {
										echo $rowSupplier['name'];
									}
									
									echo "</option>";
								}
								
								
							}
							
							// JS, on change, submit the page again
							// page needs to check for supplier filter now
							echo "</select>";
						}

					?>
					</select>
				</form>
				
			</div>
			<section class='productCategory'>
			<?php
				// $sql = "SELECT * FROM products WHERE type = $this_product;";
				// $resultTotal = mysqli_query($conn, $sql);
				// $resultCount = mysqli_num_rows($resultTotal);
				
				// See if the supplier filter is set. If so, return a table based on the value selected
				if ($this_supplier > 0) {
					$sql = "SELECT * FROM products WHERE type = $this_product AND supplier = $this_supplier ORDER BY name;";
				} else {
					$sql = "SELECT * FROM products WHERE type = $this_product ORDER BY name;";
				}
			
				$result = mysqli_query($conn, $sql);
				$resultCheck = mysqli_num_rows($result);
				$resultCount = $resultCheck;
			
				if ($resultCheck <= 0) { 
					echo "<p>Ain't none o' that in the database yet.</p>";
				} else {
					echo "<p><strong>".$resultCount."</strong> total items</p>";
						?>
						<table>
							<caption class="visually-hidden">Earrings</caption>
							<thead>
								<tr>
									<th></th>
									<th scope="col">Name</th>
									<th scope="col">Product number</th>
									<th scope="col">Wholesale</th>
									<th scope="col">Retail</th>
									<th scope="col">Supplier</th>
									<th scope="col">Actions</th>
								</tr>
							</thead>
							<tbody>
						<?php
					while ($row = mysqli_fetch_assoc($result)) {
							$id = $row['id'];
							$name = $row['name'];
							$desc_sp = $row['desc_sp'];
							$number = $row['prod_num'];
							$wholesale = $row['price_wholesale'];
							$retail = $row['price_retail'];
							$supplier = $row['supplier'];
							$x = $row['file_name'];
							$type = $row['type'];
						?>
							<tr>
								<td>
									<img src="assets/img/products/jewelry/<?php echo $x; ?>" alt="" style="width: 100px;">
								</td>
								<td><a href="adminProductDetail.php?id=<?php echo $id; ?>"><?php echo $name; ?></a><br>(<?php echo $desc_sp; ?>)</td>
								<td><?php echo $number; ?></td>
								<td>$<?php echo $wholesale; ?></td>
								<td>$<?php echo $retail; ?></td>
								<td><?php 
										
									$sqlSupplier = "SELECT name FROM suppliers WHERE supplierID = $supplier";
									$resultSupplier = mysqli_query($conn, $sqlSupplier);
									while ($rowSupplier = mysqli_fetch_assoc($resultSupplier)) {
										echo $rowSupplier['name'];
									}
									
									?></td>
								<td class="actions">
									<ul>
										<li><a href="adminProductDetail.php?id=<?php echo $id; ?>">Edit</a></li>
										<li><a class="js-toggleModal js-deleteProduct" data-id="<?php echo $id; ?>" data-name="<?php echo $name; ?>" data-type="<?php echo $type; ?>" href="#">Delete</a></li>
									</ul>
								</td>
							</tr>
						<?php
					} // End WHILE loop
					?>
						</tbody>
					</table>
				<?php
					
					
					$paginations = ceil($resultCount / $per_page);

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
								  echo "<li><a href='?pid=".$this_product."&pg=".$pg_num."'>".$pg_num."</a></li>";
							   
								}
								echo "<li><a href='?pid=".$this_product."&pg=".$next."'><i class='fas fa-caret-right'></i><span class='visually-hidden'> Next</span></a></li>"; 
								
							}else{
								// Interior  pagination
								echo "<li><a href='?pid=".$this_product."&pg=$previous'><i class='fas fa-caret-left'></i><span class='visually-hidden'> Previous</span></a></li>"; 
								
								for($j=1; $j <= $paginations; $j++) {
								 
									if ($j == $page_counter) {
										$pg_num = $j + 1;
										echo "<li class='active'>".$j."</li>";
								 	
									} else {
										$pg_num = $j + 1;
										echo "<li><a href='?pid=".$this_product."&pg=".$j."' >".$j."</a></li>";
								 }									
									
							  }


								if ($page_counter == $paginations) {
									echo "<li class='disabled'><i class='fas fa-caret-right'></i><span class='visually-hidden'> Next</span></li>";
								} else {
									
									echo "<li><a href='?pid=".$this_product."&pg=".$next."'><i class='fas fa-caret-right'></i><span class='visually-hidden'> Next</span></a></li>";
								}

							} 
						
						echo "</ul>";
						echo "<p class='pageLocation'>Page " .$page_counter. " of " .$paginations. "</p>";
					echo "</div>";
					}
					
					
				}// End IF statement
			?>
			</section>
	
		<?php
			
			
			
		}
	?>

	<!-- MODAL CONTENT -->
	<div class="modalOverlay" style="display: none;">
		<!-- Delete Product -->
		<div class="modalWindow" id="deleteProduct" style="display: none;">
			<div class="modalContent">
				<h2>Delete <span class="deletedProductName"></span>?</h2>
				<p>Hey! Are you <em>sure</em> you want to delete <span class="deletedProductName"></span>?</p>
				<p><strong>This can't be undone!</strong></p>
				<form action="includes/adminProductDelete.inc.php" method="POST">
					<div class="formGroup">
						<input type="hidden" id="deletedProductID" name="deleted-product-id" value="">
						<input type="hidden" id="deletedProductName" name="deleted-product-name" value="">
						<input type="hidden" id="deletedProductType" name="deleted-product-type" value="">
						<ul class="formControls">
							<li>
								<button type="submit" class="js-submitModal" name="product-delete">Yes, delete it</button>
							</li>
							<li>
								<button type="button" class="js-closeModal">No, don't delete</button>
							</li>
						</ul>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- END modal content -->

</div>
<?php
	require 'includes/footer.inc.php';
?>