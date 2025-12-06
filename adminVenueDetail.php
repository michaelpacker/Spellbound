<?php
	$page_class = 'admin landing secondary';
	$page_title = 'Admin - Edit Venue';

//	if(!isset($_SESSION))
//	 {
//		 header("Location:index.php");
//	 }

if (isset($_GET['venueID'])) {
	$this_venue = intval($_GET['venueID']);
}
	$venueName;
	$venueStreet;
	$venueCity;
	$venueState;
	$venueZIP;

	require "includes/header.inc.php";
?>


<div class="contentContainer">
	<?php
		if(!isset($_SESSION['user-id'])) {
			echo "<section>";
			echo "<p>You do not have permission to view this page.</p>";
			echo "</section>";
		} else { 

		$sql = "SELECT * FROM show_locations WHERE id = $this_venue;";
		$result = mysqli_query($conn, $sql);
		$resultCheck = mysqli_num_rows($result);
				
		if ($resultCheck > 0) {
			while ($row = mysqli_fetch_assoc($result)) {
				$venueName 	= $row['venue'];
				$venueStreet = $row['street'];
				$venueCity = $row['city'];
				$venueState = $row['state'];
				$venueZIP = $row['zip'];
			?>
			<h2>Edit Venue Info</h2>
			<section class="itemDetail">
				<form action="includes/adminVenueUpdate.inc.php" method="POST">
					<input type="hidden" name="id" value="<?php echo $this_venue; ?>">
					<div class="formGroup">
						<label for="venueNameUpdate">Name</label>
						<input type="text" id="venueNameUpdate" name="venue_name_update" value="<?php echo $venueName; ?>" required>
					</div>
					<div class="formGroup">
						<label for="venueStreetUpdate">Street</label>
						<input type="text" id="venueStreetUpdate" name="venue_street_update" value="<?php echo $venueStreet; ?>" required>
					</div>
					<div class="formGroup">
						<label for="venueCityUpdate">City</label>
						<input type="text" id="venueCityUpdate" name="venue_city_update" value="<?php echo $venueCity ?>" required>
					</div>
					<div class="formGroup">
						<label for="venueStateUpdate">State</label>
						<input type="text" id="venueStateUpdate" name="venue_state_update" value="<?php echo $venueState ?>" required>
					</div>
					<div class="formGroup">
						<label for="venueZipUpdate">ZIP</label>
						<input id="venueZipUpdate" name="venue_zip_update" type="number" value="<?php echo $venueZIP ?>" required>
					</div>
					<div class="formGroup">
						<ul class="formControls">
							<li><button type="submit" name="venue-update">Submit</button></li>
							<li><a href="adminShows.php">Cancel</a></li>
						</ul>
					</div>
				</form>
			<?php
			}
		} else {
			echo "<p>No show found.</p>";
		}
	}
	?>
	</section>
</div>

<?php
	require 'includes/footer.inc.php';
?>