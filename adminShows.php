<?php
	$page_class = 'admin landing secondary';
	$page_title = 'Admin - Manage Shows';

//	if(!isset($_SESSION))
//	 {
//		 header("Location:index.php");
//	 }
		$update = false;
		$create = false;
		$cancel = false;
		if (isset($_GET['updateStatus'])) {
			$updateStatus = $_GET['updateStatus'];
			$update = true;
		}
		if (isset($_GET['updatedName'])) {
			// The name of the thing being updated
			$updatedName = $_GET['updatedName'];
		}

		if (isset($_GET['createStatus'])) { 
			$createStatus = $_GET['createStatus'];
			$create = true;
		}
		if (isset($_GET['createdName'])) {
			$createdName = $_GET['createdName'];
		}

		// Updated event dates
		if (isset($_GET['cancelStatus'])) {
			$cancelStatus = $_GET['cancelStatus'];
			$cancel = true;
		}

		if (isset($_GET['updatedEventDateStart'])) {
			$updatedDateStart = $_GET['updatedEventDateStart'];
		}
		if (isset($_GET['updatedEventDateEnd'])) {
			$updatedDateEnd = $_GET['updatedEventDateEnd'];
		}


	require "includes/header.inc.php";
?>

<main class="admin">
<div class="contentContainer">
	
	<h2>Manage Shows</h2>
	<div class="tools">
		<?php
		if(isset($_SESSION['user-id'])) {
			echo "<ul class='tools'><li>";
			echo "<a class='js-toggleModal js-scheduleShow' href='#'>Schedule a show</a>";
			echo "</li><li>";
			echo "<a class='js-toggleModal js-addShow' href='#'>Add a show</a>";
			echo "</li><li>";
			echo "<a class='js-toggleModal js-addVenue' href='#'>Add a venue</a>";
			echo "</li></ul>";
		}
		?>
	</div>
	<div class="updateResponse">
		<?php
			if ($update == true) {
				if ($updateStatus === 'success') {
					echo "<div class='success message'>";
						echo "<p><span class='messageShowName'>" .$updatedName. "</span> was successfully updated.</p>";
					echo "</div>";
				} else {
					echo "<div class='error message'>";
						echo "<p>There was a problem with updating <span class='messageShowName'>" .$updatedName. "</span>. Try again or give up.</p>";
					echo "</div>";
				}
			}
			if ($create == true) {
				if ($createStatus === 'success') { 
					echo "<div class='success message'>";
						echo "<p><span class='messageShowName'>" .$createdName. "</span> was successfully created.</p>";
					echo "</div>";
				} else {
					echo "<div class='error message'>";
						echo "<p>There was a problem creating the show <span class='messageShowName'>" .$createdName. "</span>. Try again or give up.</p>";
					echo "</div>";
				}
			}
			if ($cancel == true) {
				if ($cancelStatus === 'success') {
					echo "<div class='success message'>";
						echo "<p><span class='messageShowName'>".$updatedName." ".$updatedDateStart." to ".$updatedDateEnd."</span> was successfully cancelled.</p>";
					echo "</div>";
				} else {
					echo "<div class='error message'>";
						echo "<p>There was a problem cancelling the show <span class='messageShowName'>" .$updatedName. "</span>. Try again or give up.</p>";
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
			<section class="categorySection">
				<h3>Show schedule</h3>
				<div class="showGrid">
			<?php

			$sql = "SELECT * FROM show_schedule 
			INNER JOIN shows ON show_schedule.showID = shows.id 
			INNER JOIN show_locations ON show_schedule.locationID = show_locations.id 
			WHERE endDate >= CURDATE()
			ORDER BY beginDate";
				$result = mysqli_query($conn, $sql);
				$resultCheck = mysqli_num_rows($result);
			
				if ($resultCheck > 0) {
					?>
					<table>
						<caption class="visually-hidden">Shows</caption>
						<thead>
							<tr>
								<th scope="col">Name</th>
								<th scope="col">URL</th>
								<th scope="col">Location</th>
								<th scope="col">Begin</th>
								<th scope="col">End</th>
								<th scope="col">Actions</th>
							</tr>
						</thead>
						<tbody>
					<?php
					while ($row = mysqli_fetch_assoc($result)) {
						
						$id = $row['showID'];
						$name = $row['showName'];
						$url = $row['showURL'];
						$locVenue = $row['venue'];
						$locStreet = $row['street'];
						$locCity = $row['city'];
						$locState = $row['state'];
						$locZip = $row['zip'];
						$begDate = new DateTime($row['beginDate']);
						$endDate = new DateTime($row['endDate']);
						$scheduleID = $row['scheduleID']; //Not primary key. Other unique identifier
						

    					$formatted_beg_date = $begDate->format('F j, Y');
    					$formatted_end_date = $endDate->format('F j, Y');

						?>
						<tr>
							<td><?php echo $name; ?></td>
							<td><?php echo $url; ?></td>
							<td><?php 
								echo "<p>".$locVenue."<br>";
								echo $locStreet."<br>";
								echo $locCity.", ".$locState."<br>";
								echo $locZip."</p>";
								?></td>
							<td><?php echo $formatted_beg_date; ?></td>
							<td><?php echo $formatted_end_date; ?></td>
							
							<td><a class="js-toggleModal js-cancelShow" data-schedule-id="<?php echo $scheduleID ?>" data-id="<?php echo $id; ?>" data-name="<?php echo $name; ?>" data-date-begin="<?php echo $formatted_beg_date; ?>" data-date-end="<?php echo $formatted_end_date; ?>" href="#">Cancel show</a></td>
						</tr>
						<?php

					}
					?>
					</tbody>
					</table>
				<?php
				} else {
					echo "<p>No shows are scheduled at this time.";
				}
			?>
			</div>
			</section>
			<section class="categorySection">
				<h3>Shows</h3>
				<div class="showGrid">
				<?php
					$sql = "SELECT * FROM shows ORDER BY showName";
					$result = mysqli_query($conn, $sql);
					$resultCheck = mysqli_num_rows($result);

					if ($resultCheck > 0) {
						?>
						<table>
							<caption class="visually-hidden">Shows</caption>
							<thead>
								<tr>
									<th scope="col">Name</th>
									<th scope="col">URL</th>
									<th scope="col">Logo File</th>
									<th scope="col">Actions</th>
								</tr>
							</thead>
							<tbody>
						<?php
						while ($row = mysqli_fetch_assoc($result)) {
							$id = $row['id'];
							$name = $row['showName'];
							$url = $row['showURL'];
							$logo = $row['showLogo'];
							?>
							<tr>
								<td><a href="adminShowsDetail.php?showID=<?php echo $id; ?>"><?php echo $name; ?></a></td>
								<td><?php echo $url; ?></td>
								<td><?php echo $logo; ?></td>
								<td class="actions">
									<ul>
									<li><a href="adminShowsDetail.php?showID=<?php echo $id; ?>">Edit</a></li>
									<li>Delete</li>
									</ul>
								</td>
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
			<section class="categorySection">
				<h3>Venues</h3>
				<div class="showGrid">
				<?php
					$sql = "SELECT * FROM show_locations ORDER BY state";
					$result = mysqli_query($conn, $sql);
					$resultCheck = mysqli_num_rows($result);

					if ($resultCheck > 0) {
						?>
						<table>
							<caption class="visually-hidden">Shows</caption>
							<thead>
								<tr>
									<th scope="col">Name</th>
									<th scope="col">Street</th>
									<th scope="col">City</th>
									<th scope="col">State</th>
									<th scope="col">ZIP</th>
									<th scope="col">Actions</th>
								</tr>
							</thead>
							<tbody>
						<?php
						while ($row = mysqli_fetch_assoc($result)) {
							$id = $row['id'];
							$name 		= $row['venue'];
							$street 	= $row['street'];
							$city		= $row['city'];
							$state		= $row['state'];
							$zip 		= $row['zip'];
							?>
							<tr>
								<td><a href="adminVenueDetail.php?venueID=<?php echo $id; ?>"><?php echo $name; ?></a></td>
								<td><?php echo $street; ?></td>
								<td><?php echo $city; ?></td>
								<td><?php echo $state; ?></td>
								<td><?php echo $zip; ?></td>
								<td class="actions">
									<ul>
									<li><a href="adminVenueDetail.php?venueID=<?php echo $id; ?>">Edit</a></li>
									<li>Delete</li>
									</ul>
								</td>
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
</div><!-- End contentContainer -->
	<!-- MODAL CONTENT -->
	<div class="modalOverlay" style="display: none;">
		<div class="modalWindow" id="addVenue" style="display: none;">
			<div class="modalContent">
				<h2>Add a venue</h2>
				<p>Add a new hotel, convention center, or other show location to the database</p>
				<form action="includes/adminLocationCreate.inc.php" method="POST">
					<div class="formGroup">
						<label for="locationName">Name</label>
						<input type="text" id="locationName" name="location_name" required>									   
					</div>
					<div class="formGroup">
						<label for="locationStreet">Street address</label>
						<input type="text" id="locationStreet" name="location_street" required>
					</div>
					<div class="formGroup">
						<label for="locationCity">City</label>
						<input type="text" id="locationCity" name="location_city" required>
					</div>
					<div class="formGroup">
						<label for="locationState">State</label>
						<select id="locationState" name="location_state" required>
							<?php
								$sql = "SELECT * FROM states WHERE country_id = '1' ORDER BY state_name;";
								$result = mysqli_query($conn, $sql);
							
								while($row = mysqli_fetch_assoc($result)) {
									$stateID = $row['state_id'];
									$stateName = $row['state_name'];
									
									echo "<option value='".$stateName."'>".$stateName."</option>";
									
								}
							
							?>
						</select>
					</div>
					<div class="formGroup">
						<label for="locationZip">ZIP</label>
						<input id="locationZip" name="location_zip" type="number" required>
					</div>
					<div class="formGroup">
						<ul class="formControls">
							<li>
								<button type="submit" class="js-submitModal" name="location-create">Submit</button>
							</li>
							<li>
								<button type="button" class="js-closeModal">Cancel</button>
							</li>
						</ul>
					</div>
				</form>
			</div>
		</div>
		<div class="modalWindow" id="addShow" style="display: none;">
			<div class="modalContent">
				<h2>Add a show</h2>
				<p>Convention, swap meet, craft fair - whatever</p>
				<form action="includes/adminShowCreate.inc.php" method="POST">
					<div class="formGroup">
						<label for="showName">Name</label>
						<input type="text" id="showName" name="show_name" required>
					</div>
					<div class="formGroup">
						<label for="showAbrv">Short name</label>
						<input type="text" id="showAbrv" name="show_abrv" required aria-describedby="showShortNameHelp">
						<p class="help" id="showShortNameHelp">e.g. "wasteland", "flashback", etc</p>
					</div>
					<div clss="formGroup">
						<label for="showURL">Show URL</label>
						<input type="text" id="showURL" name="show_url" required>
					</div>
					<div class="formGroup">
						<label for="showLogo">Show logo</label>
						<input type="text" id="showLogo" name="show_logo" aria-describedby="showLogoHelp">
						<p class="help" id="showLogoHelp">assets/img/logos-shows/foo.png</p>
					</div>
					<div class="formGroup">
						<ul class="formControls">
							<li>
								<button type="submit" class="js-submitModal" name="show-create">Submit</button>
							</li>
							<li>
								<button type="button" class="js-closeModal">Cancel</button>
							</li>
						</ul>
					</div>
				</form>
			</div>
		</div>

		<div class="modalWindow" id="scheduleShow" style="display: none;">
			<div class="modalContent">
				<h2>Schedule a show</h2>
				<form action="includes/adminShowSchedule.inc.php" method="POST">
					<div class="formGroup">
						<label for="scheduleShowName">Show</label>
						<select id="scheduleShowName" name="sched_show_name">
							<?php
								$sql = "SELECT * FROM shows ORDER BY showName";
								$result = mysqli_query($conn, $sql);
								$resultCheck = mysqli_num_rows($result);

								if ($resultCheck > 0) {
									while ($row = mysqli_fetch_assoc($result)) {
										$id = $row['id'];
										$name = $row['showName'];
										echo "<option value='".$id."'>".$name."</option>";
									}
								} 
							?>
						</select>
					</div>
					<div class="formGroup">
						<label for="scheduleShowVenue">Venue</label>
						<select id="scheduelShowVenue" name="sched_show_venue">
							<?php
								$sql = "SELECT * FROM show_locations ORDER BY venue";
								$result = mysqli_query($conn, $sql);
								$resultCheck = mysqli_num_rows($result);

								if ($resultCheck > 0) {
									while ($row = mysqli_fetch_assoc($result)) {
										$id = $row['id'];
										$name = $row['venue'];
										$city = $row['city'];
										$state = $row['state'];
										echo "<option value='".$id."'>".$name." : ".$city.", ".$state."</option>";
									}
								} 
							?>
						</select>
					</div>
					<div class="formGroup">
						<label for="scheduelShowStartingDate">Starting</label>
						<input type="date" id="scheduelShowStartingDate" name="sched_show_begin" required>
					</div>
					<div class="formGroup">
						<label for="scheduelShowEndingDate">Ending</label>
						<input type="date" id="scheduelShowEndingDate" name="sched_show_end" required>
					</div>
					<div class="formGroup">
						<ul class="formControls">
							<li>
								<button type="submit" class="js-submitModal" name="show-schedule">Submit</button>
							</li>
							<li>
								<button type="button" class="js-closeModal">Cancel</button>
							</li>
						</ul>
					</div>
				</form>
			</div>
		</div><!-- End modal -->
		
		<div class="modalWindow" id="cancelShow" style="display: none;">
			<div class="modalContent">
				<h2>Cancel show</h2>
				<p>Are you sure you want to cancel this show? This will remove the scheduled event from the website. You can always reschedule later.</p>
				<div class="scheduledShowInfo">
					<h3 class="scheduledShowName"></h3>
					<p><span class="scheduledShowStart"></span> &ndash; <span class="scheduledShowEnd"></span></p>
				</div>
				<form action="includes/adminShowCancel.inc.php" method="POST">
					<div class="formGroup">
						<input type="hidden" id="cancelledShowID" name="cancelled-show-id" value="">
						<input type="hidden" id="cancelledShowName" name="cancelled-show-name" value="">
						<input type="hidden" id="cancelledShowStart" name="cancelled-show-start" value="">
						<input type="hidden" id="cancelledShowEnd" name="cancelled-show-end" value="">
						<input type="hidden" id="cancelledShowScheduleID" name="cancelled-show-scheduleID" value="">
						<ul class="formControls">
							<li>
								<button type="submit" class="js-submitModal" name="show-cancel">Yes, cancel it</button>
							</li>
							<li>
								<button type="button" class="js-closeModal">No, don't cancel</button>
							</li>
						</ul>
					</div>
				</form>
			</div>
		</div><!-- End modal -->
	</div>
</div><!-- Is this end of Main -->
<?php
	require 'includes/footer.inc.php';
?>