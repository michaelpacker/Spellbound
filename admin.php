<?php
	$page_class = 'admin landing secondary';
	$page_title = 'Admin Dashboard';

//	if(!isset($_SESSION))
//	 {
//		 header("Location:index.php");
//	 }
	require "includes/header.inc.php";
?>


	<div class="contentContainer">
		<h2>Administrations</h2>
		<?php
			if(!isset($_SESSION['user-id'])) {
					echo "<section>";
					echo "<p>You do not have permission to view this page.</p>";
					echo "</section>";
				
			} else {
					
				?>
					<p>Welcome, <?php echo $user_fname; ?></p>
				
					<ul class="adminNav">
						<li class="adminLink"><a href="adminSales.php"><i class="fas fa-money-bill-wave"></i> </i>Show sales</a></li>
						<li class="adminLink"><a href="adminProducts.php"><i class="fas fa-bacon"></i> Products</a></li>
						<li class="adminLink"><a href="adminSuppliers.php"><i class="fas fa-truck"></i> Suppliers</a></li>
						<li class="adminLink"><a href="adminShows.php"><i class="fas fa-calendar-alt"></i> Shows</a></li>
					</ul>
				
				<?php
			}
		?>
	</div>


<?php
	require 'includes/footer.inc.php';
?>