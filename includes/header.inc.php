<?php
	session_start();
	include 'includes/dbh.inc.php';
	if (isset($_SESSION['user-id'])) { 
		$user_id 	= $_SESSION['user-id'];
		$user_fname = $_SESSION['user-first-name'];
		$user_lname = $_SESSION['user-last-name'];
		$user_email = $_SESSION['user-email'];
	}
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Spellbound Jewelry - <?php echo $page_title; ?></title>
        <meta name="description" content="">
        <meta name = "viewport" content = "user-scalable=no, initial-scale=1.0, maximum-scale=1.0, width=device-width /">
        <meta name="apple-mobile-web-app-capable" content="yes"/>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> 

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
        <link rel="stylesheet" href="assets/css/style.css">
        
        <script src="assets/js/vendor/modernizr-2.6.2.min.js"></script>
		<script src="assets/js/scripts.js"></script>
		<script src="assets/js/tabs.js"></script>
        
    </head>
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

        <!-- Many thanks to http://css-tricks.com -->
	<?php 
		if ($page_title == 'Home' || $page_title == 'Shows') {
			echo "<body class='full_black'>";
		} else {
			echo "<body>";
		}
	?>
	
		
		<header class="<?php echo $page_class ?>">

			<h1>Spellbound</h1> <!-- linked for sub-pages removed -->
		<?php 
			if ($user_id) {
				?>
					<div class="accountControls">
						<ul>
							<li><?php echo $user_fname; ?></li>
							<li><a href="admin.php">Admin</a></li>
							<li>
								<form action="includes/logout.inc.php" method="post">
									<button type="submit" name="logout-submit">Logout</button>
								</form>
							</li>
						</ul>
					</div>
				<?php
			}
		?>
		</header>