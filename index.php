<?php
	$page_class = 'home landing shows calendar';
	$page_title = 'Home';
	require "includes/header.inc.php";
?>
	<!-- <div class="section intro">
		<p>Spooky * Sparkly * Jewelry</p>
	</div> -->
<?php
	require "includes/nav.inc.php";
?>
	<div class="hero-product alchemy-bat section">

	</div>
	<div class="section about">
			
			<h2><span class="teaser">About us</h2>
			<div class="blurb">
				<p>We've been vending at horror conventions, b-movie festivals, fan expos, and art markets for about twenty years.</p>
				<p>We don't have an online store because we have just too many ear rings, necklaces, pins and accessories to list.</p>
				<p>If you saw something you liked on our table, and you missed picking it up, contact us and we'll do our best to help you out!</p>
				
			</div>
	</div>
<!-- bringing shows in from their own page, because it's not like we have anything else on the site -->
	<div class="showsContainer">
			
			<?php
				$sql = "SELECT * FROM show_schedule 
				INNER JOIN shows ON show_schedule.showID = shows.id 
				INNER JOIN show_locations ON show_schedule.locationID = show_locations.id 
				WHERE endDate >= CURDATE()
				ORDER BY beginDate";
				$result = mysqli_query($conn, $sql);
				$resultCheck = mysqli_num_rows($result);
		
			if ($resultCheck > 0) {
				echo "<h2>Shows</h2>";
				echo "<div class='blurb'>";
				echo "<p class='intro'>Visit our table at the next show for a huge variety of spooky sparklies and event exclusives.</p>";
				echo "</div>";
				
				while ($row = mysqli_fetch_assoc($result)) {
						$id = $row['id'];
						$name = $row['showName'];
						$abrv = $row['showAbrv'];
						$url = $row['showURL'];
						$logo = $row['showLogo'];
						$locVenue = $row['venue'];
						$locStreet = $row['street'];
						$locCity = $row['city'];
						$locState = $row['state'];
						$locZip = $row['zip'];
						// $begin = $row['beginDate'];
						$end = $row['endDate'];
						$begDate = new DateTime($row['beginDate']);
    					$formatted_beg_date = $begDate->format('F j'); // shows Month and day - example: January 1
						$formatted_beg_date_month = $begDate->format('F'); // shows Month - example: January
						
						$endDate = new DateTime($row['endDate']);
    					$formatted_end_date = $endDate->format('j, Y');
						$formatted_end_date_month = $endDate->format('F');
					
						$same_day = False;
						$same_month = False;
						
						// Figure out if it's a one day event
						// Figure out if it's an event that begins in one month and ends in another
						if ($endDate == $begDate) {
							$same_day = True;
							$formatted_beg_date = $begDate->format('F j, Y');
						} else if ($formatted_beg_date_month == $formatted_end_date_month) {
							$same_month = True;
						}
						
					
					echo "<div class='show-info'>";
							
					
				// if "has show logo" is set to true, do this. Else do that.
					
					if (empty($logo)) {
						echo "<h3>".$name."</h3><p>";
					} else {
						echo "<span data-name='".$name."' class='show-logo ".$abrv."'></span>";
						echo "<h3 class='sr-only'>".$name."</h3><p>";
					}
					
					if ($same_day) {
						// Event is a single day event
						echo "<p>".$formatted_beg_date."</p>";
					} else if ($same_month) {
						// Event is a multi-day event, in the same month
					 	echo "<p>".$formatted_beg_date." - ".$formatted_end_date."</p>";
					} else {
						// Event is a multi-day event, ending in a different month
						echo "<p>".$formatted_beg_date." - ".$formatted_end_date_month." ".$formatted_end_date."</p>";
					}
								
					echo	$locVenue."<br>"
						    .$locStreet."<br>"
							.$locCity.", ".$locState."</p>";
					
					echo "<p><a href='".$url."'><span class='sr-only'>".$name." </span>Show info</a></p>"
						."</div>";
					
				}
			} else {
				echo "<p class='intro'>We don't have any shows scheduled right now. Check back soon.</p>";
			}
			?>
	</div>
<?php
	require 'includes/footer.inc.php';
?>

