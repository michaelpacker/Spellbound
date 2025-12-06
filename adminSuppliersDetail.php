<?php
	$page_class = 'admin landing secondary';
	$page_title = 'Admin - Edit Supplier';

//	if(!isset($_SESSION))
//	 {
//		 header("Location:index.php");
//	 }

if (isset($_GET['supplier_id'])) {
	$this_supplier = intval($_GET['supplier_id']);
}
	$supplierName;
	$supplierURL;
	$supplierEmail;

	require "includes/header.inc.php";
?>


<div class="contentContainer">
	
	<?php
		if(!isset($_SESSION['user-id'])) {
			echo "<section>";
			echo "<p>You do not have permission to view this page.</p>";
			echo "</section>";
		} else { 

		$sql = "SELECT * FROM suppliers WHERE supplierID = $this_supplier;";
		$result = mysqli_query($conn, $sql);
		$resultCheck = mysqli_num_rows($result);
				
		if ($resultCheck > 0) {
			while ($row = mysqli_fetch_assoc($result)) {
				$supplierName 	= $row['name'];
				$supplierURL 	= $row['url'];
				$supplierEmail 	= $row['email'];
			?>
			<h2>Edit Supplier Info</h2>
			<section class="itemDetail">
				<form action="includes/adminSupplierUpdate.inc.php" method="POST">
					<input type="hidden" name="id" value="<?php echo $this_supplier; ?>">
					<div class="formGroup">
						<label for="supplierNameUpdate">Name</label>
						<input type="text" id="supplierNameUpdate" name="supplier_name_update" value="<?php echo $supplierName; ?>" required>
					</div>
					<div clss="formGroup">
						<label for="supplierURLUpdate">Supplier URL</label>
						<input type="text" id="supplierURLUpdate" name="supplier_url_update" value="<?php echo $supplierURL; ?>">
					</div>
					<div clss="formGroup">
						<label for="supplierEmailUpdate">Supplier Email</label>
						<input type="email" id="supplierEmailUpdate" name="supplier_email_update" value="<?php echo $supplierEmail; ?>">
					</div>
					<div class="formGroup">
						<ul class="formControls">
							<li><button type="submit" name="supplier-update">Submit</button></li>
							<li><a href="adminSuppliers.php">Cancel</a></li>
						</ul>
					</div>
				</form>
			</section>
			<?php
			}
		} else {
			echo "<section>";
			echo "<p>No supplier found.</p>";
			echo "</section>";
		}
	}
	?>
	
</div>

<?php
	require 'includes/footer.inc.php';
?>