<?php
	$page_class = 'admin landing secondary';
	$page_title = 'Admin - Manage sales';

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

	global $target_date;
	// global $current_date = date("Y-m-d h:i:sw");

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
	
	<h2>Manage sales</h2>
	
	
	<?php 

		// Define date variables
		// GET DATE INFO
		$today = strtotime('now');
		// Saturday of scarefest is 1697868000
		$today = 1697868000;
		$currentShow = FALSE;

	
			$sql = "SELECT * FROM show_schedule 
				INNER JOIN shows ON show_schedule.showID = shows.id 
				INNER JOIN show_locations ON show_schedule.locationID = show_locations.id 
				ORDER BY beginDate DESC";
				$result = mysqli_query($conn, $sql);
				$resultCheck = mysqli_num_rows($result);
				$count = 0;
				
				
				if ($resultCheck > 0) {
										
					echo "<ol>";
					
					while ($row = mysqli_fetch_assoc($result)) {
						
						$id = $row['id'];
						$showID = $row['showID'];
						$name = $row['showName'];
						$locCity = $row['city'];
						$locVenue = $row['venue'];
						
						// DO SOME TRANSFORMATION OF THE DATE DATA
						$beginDateRaw = $row['beginDate'];
						
						$beginTimeStamp = strtotime($beginDateRaw); // Convert to UNIX timestamp
						$year = gmdate('Y', $beginTimeStamp);
						
						$endDateRaw = $row['endDate'];
						$endTimeStamp = strtotime($endDateRaw); // Convert to UNIX timestamp
						$middleTimeStamp = 0;
						
						// Do date math
						$middleTimeStamp = $beginTimeStamp + 86400;
						$timeDiff = $endTimeStamp - $beginTimeStamp;
						// 86400 = 1 day
						// 172800 = 2 days
						// 172801 = more than two days
						
						// Find out how many days in the show there are there are
						$dayCount = 0;
						
						if ($timeDiff == 0) {
							$dayCount = 1;
						} elseif ($timeDiff == 86400) {
							$dayCount = 2;
						} elseif ($timeDiff == 172800) {
							$dayCount = 3;
						} elseif ($timeDiff = 259200) {
							$dayCount = 4;
						}
						
						// Build the timestamp array
						$dateRange = array();
						$dateRange = array($beginTimeStamp,$endTimeStamp);
						
						
						for ($i = 1; $i <= $dayCount; $i++) {
							if ($i == 3) {
								//insert one day into the array - add begin_day + 1 day
								$secondDay = $beginTimeStamp + 86400;
								array_push($dateRange, $secondDay);
							} elseif ($i == 4) {
								$secondDay = $beginTimeStamp + 86400;
								$thirdDay = $secondDay + 86400;
								array_push($dateRange, $secondDay, $thirdDay);
							}
						}
						
					// Only display shows that are not in the future
					if ($beginTimeStamp <= $today) {
						// See if the show is happening today	
						foreach ($dateRange as $v) {
							if ($v === $today) {
								$currentShow = TRUE;
							} else {
								$currentShow = FALSE;
							}
						}
						
						if ($currentShow === TRUE) {
							$currentShow = FALSE;
							echo "<li class='latest-show'>";
						} else {
							echo "<li class='show-sale-list-entry'>";
						}

							echo "<p><b>".$name." ".$year."</b></p>";
							echo writeDates($beginTimeStamp, $endTimeStamp, $middleTimeStamp, $timeDiff, $showID, $locCity, $locVenue);
							echo "<p>Total sales</p>";
							echo "</li>";
							$count++;
						} 
						
					} // End result while loop
				echo "</ol>";
				} // End result check
		
	
	?>
	
	

	</div>
</div><!-- Is this end of Main -->
<?php
	require 'includes/footer.inc.php';
?>