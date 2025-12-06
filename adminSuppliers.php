<?php
	$page_class = 'admin landing secondary';
	$page_title = 'Admin - Manage Products';

//	if(!isset($_SESSION))
//	 {
//		 header("Location:index.php");
//	 }
$update = false;
$create = false;
if (isset($_GET['updateStatus'])) {
	$updateStatus = $_GET['updateStatus'];
	$update = true;
}
if (isset($_GET['updatedName'])) {
	$updatedName = $_GET['updatedName'];
}

if (isset($_GET['createStatus'])) { 
	$createStatus = $_GET['createStatus'];
	$create = true;
}
if (isset($_GET['createdName'])) {
	$createdName = $_GET['createdName'];
}


	require "includes/header.inc.php";
?>


<div class="contentContainer">
	
	<h2>Manage Suppliers</h2>
	<div class="tools">
		<?php
		if(isset($_SESSION['user-id'])) {
			echo "<ul class='tools'><li>";
			echo "<a href='adminSupplierCreate.php'>Add a supplier</a>";
			echo "</li></ul>";
		}
		?>
	</div>
	<div class="updateResponse">
	<?php
		if ($update == true) {
			if ($updateStatus === 'success') {
				echo "<div class='success message'>";
					echo "<p><span class='messageSupllierName messageName'>" .$updatedName. "</span> was successfully updated.</p>";
				echo "</div>";
			} else {
				echo "<div class='error message'>";
					echo "<p>There was a problem with updating <span class='messageSupplierName messageName'>" .$updatedName. "</span>. Try again or give up.</p>";
				echo "</div>";
			}
		}
		if ($create == true) {
			if ($createStatus === 'success') { 
				echo "<div class='success message'>";
					echo "<p><span class='messageSupplierName messageName'>" .$createdName. "</span> was successfully created.</p>";
				echo "</div>";
			} else {
				echo "<div class='error message'>";
					echo "<p>There was a problem with creating the supplier called <span class='messageSupplierName messageName'>" .$createdName. "</span>. Try again or give up.</p>";
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
			<section class="productCategory">
				<h2>Suppliers</h2>
				<div class="showGrid">
				<?php
					$sql = "SELECT * FROM suppliers ORDER BY name";
					$result = mysqli_query($conn, $sql);
					$resultCheck = mysqli_num_rows($result);

					if ($resultCheck > 0) {
						?>
						<table>
							<caption class="visually-hidden">Suppliers</caption>
							<thead>
								<tr>
									<th scope="col">Name</th>
									<th scope="col">URL</th>
									<th scope="col">Email</th>
									<th scope="col">Actions</th>
								</tr>
							</thead>
							<tbody>
						<?php
						while ($row = mysqli_fetch_assoc($result)) {
							$id = $row['supplierID'];
							$name = $row['name'];
							$email = $row['email'];
							$url = $row['url'];
							?>
							<tr>
								<td><a href="adminSuppliersDetail.php?supplier_id=<?php echo $id; ?>"><?php echo $name; ?></a></td>
								<td><?php echo $url; ?></td>
								<td><?php echo $email; ?></td>
								<td class="actions"><ul><li><a href="adminSuppliersDetail.php?supplier_id=<?php echo $id; ?>">Edit</a></li><li>Delete</li></ul></td>
							</tr>
							<?php

						}
						?>
						</tbody>
						</table>
					<?php
					}
				?>
				</div>
			</section>

		<?php	
		}
	?>
</div>
<?php
	require 'includes/footer.inc.php';
?>