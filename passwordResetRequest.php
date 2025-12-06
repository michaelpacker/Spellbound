<?php
	$page_class = 'reset internal form page';
	$page_title = 'Reset Password';
	require 'includes/header.inc.php';
?>
	<div class="authentication container">
		<div class="formContainer">
			<h2>Reset Password</h2>
			<p>An email will be sent to you with instructions for resetting your password.</p>
			<form action="includes/reset-request.inc.php" method="post">
				<input type="text" name="email" placeholder="Enter email" aria-label='Email'>
				<div class="formGroup actionBar">
					<button type="submit" name="reset-request-submit">Submit</button>
				</div>
				<div class="formGroup actionBar">
					<p></p><a href="adminLogin.php">Return to login</a></p>
				</div>
			</form>
			<?php
				if (isset($_GET["reset"])) {
					if ($_GET["reset"] == "success") {
						echo '<p class="success">A link to reset your password has been sent to the email address you provided. Give it a few minutes to show up.</p><p>You will have about an hour to reset your password.</p>';
					}
				}
			?>
		</div>
	</div>
<?php
	require 'includes/footer.inc.php';
?>