<?php
	$page_class = 'internal form page';
	$page_title = 'Signup';
	require 'includes/header.inc.php';
?>
	<div class="authentication container">
		<form action="includes/signup.inc.php" method="POST">
			<div class="formSection signUp">
				<div class="formGroup emailControls">
					<label for="userEmail">Email address</label>
					<input id="userEmail" type="text" name="user_email" value="<?php echo $enteredEmail ?>" required aria-required="true">
				</div>
				<div class="formGroup">
					<label for="userFirstName">First name</label>
					<input id="userFirstName" type="text" name="user_first_name" required aria-required="true">
				</div>
				<div class="formGroup">
					<label for="userLastName">Last name</label>
					<input id="userLastName" type="text" name="user_last_name" required aria-required="true">
				</div>
				<div class="formGroup">
					<label for="userPassword">Password</label>
					<input id="userPassword" type="password" name="user_pwd" required aria-required="true">
				</div>
				<div class="formGroup">
					<label for="userPasswordConfirm">Password confirm</label>
					<input id="userPasswordConfirm" type="password" name="user_pwd_confirm" required aria-required="true">
				</div>
				<div class="formGroup actionBar">
					<button type="submit" class="positive action submit" name="signup-submit">Submit</button>
					<a href="index.php" class="negative action cancel">Cancel</a>
				</div>
			</div>
		</form>
	</div>


<?php
	require 'includes/footer.inc.php';
?>