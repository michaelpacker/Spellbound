<?php
	$page_class = 'reset internal form page';
	$page_title = 'Reset Password';
	require "includes/header.inc.php";
?>
	<div class="authentication container">
		<h2>Reset password</h2>
		<?php
			// CHECK FOR TOKEN IN DB

			// Get params from URL 
			$selector = $_GET["selector"];
			$validator = $_GET["validator"];

			if (empty($selector) || empty($validator)) {
				// See if params are empty
				echo "Could not validate your request";
			} else {
				// See if tokens are legit
				if (ctype_xdigit($selector) !== false && ctype_xdigit($validator) !== false) {
					// Now create form
					// close oepn php instead of echoing everything
					?>
						<form action="includes/reset-password.inc.php" method="POST">
							<input type="hidden" name="selector" value="<?php echo $selector ?>">
							<input type="hidden" name="validator" value="<?php echo $validator ?>">
							<input type="password" name="pwd" placeholder="Enter new password">
							<input type="password" name="pwd-repeat" placeholder="Repeat new password">
							<button type="submit" name="reset-password-submit">Reset password</button>
						</form>
					<?php 
				}
			}

		?>
	</div>

<?
	require "includes/footer.inc.php";
?>