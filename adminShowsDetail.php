<?php
	$page_class = 'admin landing secondary';
	$page_title = 'Admin - Edit Show';

//	if(!isset($_SESSION))
//	 {
//		 header("Location:index.php");
//	 }

if (isset($_GET['showID'])) {
	$this_show = intval($_GET['showID']);
}
	$showName;
	$showAbrv;
	$showURL;
	$showLogo;

	require "includes/header.inc.php";
?>


<div class="contentContainer">
	<?php
		if(!isset($_SESSION['user-id'])) {
			echo "<section>";
			echo "<p>You do not have permission to view this page.</p>";
			echo "</section>";
		} else { 

		$sql = "SELECT * FROM shows WHERE id = $this_show;";
		$result = mysqli_query($conn, $sql);
		$resultCheck = mysqli_num_rows($result);
				
		if ($resultCheck > 0) {
			while ($row = mysqli_fetch_assoc($result)) {
				$showName 	= $row['showName'];
				$showAbrv 	= $row['showAbrv'];
				$showURL 	= $row['showURL'];
				$showLogo 	= $row['showLogo'];
			?>
			<h2>Edit Show Info</h2>
			<section class="itemDetail">
				<form action="includes/adminShowUpdate.inc.php" method="POST">
					<input type="hidden" name="id" value="<?php echo $this_show; ?>">
					<div class="formGroup">
						<label for="showNameUpdate">Name</label>
						<input type="text" id="showNameUpdate" name="show_name_update" value="<?php echo $showName; ?>" required>
					</div>
					<div class="formGroup">
						<label for="showShortNameUpdated">Short name</label>
						<input type="text" id="showShortNameUpdate" name="show_short_name_update" value="<?php echo $showAbrv; ?>" required aria-describedby="showShortNameHelp">
						<p class="help" id="showShortNameHelp">e.g. "wasteland", "flashback", etc</p>
					</div>
					<div clss="formGroup">
						<label for="showURLUpdate">Show URL</label>
						<input type="text" id="showURLUpdate" name="show_url_update" value="<?php echo $showURL; ?>" required>
					</div>
					<div class="formGroup">
						<label for="showLogoUpdate">Show logo</label>
						<input type="text" id="showLogoUpdate" name="show_logo_update" value="<?php echo $showLogo; ?>" aria-describedby="showLogoHelp">
						<p class="help" id="showLogoHelp">assets/img/logos-shows/foo.png</p>
					</div>
					<div class="formGroup">
						<ul class="formControls">
							<li><button type="submit" name="show-update">Submit</button></li>
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