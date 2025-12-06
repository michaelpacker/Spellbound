<?php
	$page_class = 'admin landing secondary';
	$page_title = 'Admin - Add sales';

//	if(!isset($_SESSION))
//	 {
//		 header("Location:index.php");
//	 }
		$update = false;
		$create = false;
		$cancel = false;
			


		if (isset($_GET['show'])) {
			$showID = $_GET['show'];
		}
		if (isset($_GET['date'])) {
			// The name of the thing being updated
			$showDate = $_GET['date'];
		}

		if (isset($_GET['location'])) { 
			$locCity = $_GET['location'];
		}
		if (isset($_GET['venue'])) {
			$locVenue = $_GET['createdName'];
		}


	require "includes/header.inc.php";


	function writeDates($a, $b, $c, $d, $sid, $locid, $venid) {
		
		$formatted_beg_date = gmdate('l F j, Y', $a);
		$formatted_end_date = gmdate('l F j, Y', $b);
		$formatted_mid_date = gmdate('l F j, Y', $c);
		
		if ($d == 86400) {
			// 2 day show			
			
			$dateRange = "<table>";
			$dateRange .= "<tr><th>".$formatted_beg_date."</th>";
			$dateRange .= "<td><a href='adminSalesAdd.php?show=".$sid."&date=".$a."&location=".$locid."&venue=".$venid."'>Add sales</td></tr>";
			$dateRange .= "<tr><th>".$formatted_end_date."</th>";
			$dateRange .= "<td><a href='adminSalesAdd.php?show=".$sid."&date=".$b."&location=".$locid."&venue=".$venid."'>Add sales</td></tr>";
			$dateRange .= "</table>";
			
		} elseif ($d == 172800) {
			// 3 day show
			
			$dateRange = "<table>";
			$dateRange .= "<tr><th>".$formatted_beg_date."</th>";
			$dateRange .= "<td><a href='adminSalesAdd.php?show=".$sid."&date=".$a."&location=".$locid."&venue=".$venid."'>Add sales</td></tr>";
			$dateRange .= "<tr><th>".$formatted_mid_date."</th>";
			$dateRange .= "<td><a href='adminSalesAdd.php?show=".$sid."&date=".$c."&location=".$locid."&venue=".$venid."'>Add sales</td></tr>";
			$dateRange .= "<tr><th>".$formatted_end_date."</th>";
			$dateRange .= "<td><a href='adminSalesAdd.php?show=".$sid."&date=".$a."&location=".$locid."&venue=".$venid."'>Add sales</td></tr>";
			$dateRange .= "</table>";
			
		} elseif ($d == 0) {
			// 1 day show
			$dateRange = "<table>";
			$dateRange .= "<tr><th>".$formatted_beg_date."</th>";
			$dateRange .= "<td><a href='adminSalesAdd.php?show=".$sid."&date=".$a."&location=".$locid."&venue=".$venid."'>Add sales</td></tr>";
			$dateRange .= "</table>";
		} elseif ($d >= 172801) {
			// more than 3 day show
			$dateRange = "<p>More than a three day show.</p>";
		} else {
			// some error occured
			$dateRange = "<p>Something went wrong.</p>";
		}
		return $dateRange;
	}

?>


<div class="contentContainer">
	
	<h2>Add sales</h2>
	
	

	
	
	<?php 
		
	
		$showDate = gmdate("l F j, Y", $showDate);
		
		$sql = "SELECT * FROM shows WHERE id = $showID";
		 
			$result = mysqli_query($conn, $sql);
			$resultCheck = mysqli_num_rows($result);


			if ($resultCheck > 0) {
				while ($row = mysqli_fetch_assoc($result)) {
					
					$id = $row['id'];
					$name = $row['showName'];
					$url = $row['showURL'];

					
					if ($id === $showID) {
					?>
	
						<h3><?php echo $name; ?> - <?php echo $showDate; ?></h3>
	
					<?php
					}
					
				} // End result while loop

			} // End result check
	
	?>
	<form action="" method="">
		<h4>Item</h4>
		<!--
		<div class="form-section">
			<div class="formGroup">
				<fieldset>
					<legend>Quantity</legend>
					<div role="list">
						<div class="listitem">
							<label><input type="radio" name="quantity" value="1" checked> One (1)</label>
						</div>
						<div class="listitem">
							<label><input type="radio" name="quantity" value="2"> Two (2)</label>
						</div>
						<div class="listitem">
							<label><input type="radio" name="quantity" value="3"> Three (3)</label>
						</div>
						<div class="listitem">
							<label><input type="radio" name="quantity" value="4"> Four (4)</label>
						</div>
						<div class="listitem">
							<label><input type="radio" name="quantity" value="5"> Five (5)</label>
						</div>
						<div class="listitem">
							<label><input type="radio" name="quantity" value="6"> Six (6)</label>
						</div>
					</div>
				</fieldset>
				
			</div>
-->
			<div class="formGroup">
				<fieldset>
					<legend>Type</legend>
					<div role="list">
					
					<?php

						$sql = "SELECT * FROM product_types ORDER BY popularity ASC";

						$result = mysqli_query($conn, $sql);
						$resultCheck = mysqli_num_rows($result);

						if ($resultCheck > 0) {

							while ($row = mysqli_fetch_assoc($result)) {
								$name = $row['typeName'];
								echo "<div role='listitem'>";
								echo "<label>";
								echo "<input type='radio' name='productType' value='".$name."' /> ".$name;
								echo "</label>";
								
							}
						}

					?>
					</div>
				</fieldset>
			</div>
			<div class="formGroup">
					Bat
					Cat
					Critter
					Dragon
					Moon
					Occult
					Pagan
					Pop culture
					Satanic
					Shark
					Skeleton
					Skull
					Slasher
					Snake
					Space
					Weapon
					Egyptian
					Big head
			</div>
			<div class="formGroup">
				<label for="saleAmount">
					Total sale
				</label>
				<input id="saleAmount" type="number" min="0.01" step="0.01" max="2500" value="25.67">
			</div>
		</div>

		
	
	</form>
	
	

	</div>
</div><!-- Is this end of Main -->
<?php
	require 'includes/footer.inc.php';
?>