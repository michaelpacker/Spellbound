<?php
	$page_class = 'login internal form page';
	$page_title = 'Login';
	require 'includes/header.inc.php';
?>
	<div class="authentication container">
		<div class="formContainer">
			<h2>Login</h2>
			<div class="formResponse" aria-live="polite">
				<?php
					$enteredEmail = '';

					if (isset($_GET['error'])) {

						$enteredEmail = $_GET['userEmail'];

						if ($_GET['error'] == 'emptyFields') {
							echo "<p class='error'>Fill in all fields</p>";
						}
						else if ($_GET['error'] == 'invalidEmail') {
							echo "<p class='error'>Invalid email</p>";
						}
						else if ($_GET['error'] == 'passwordMatch') {
							echo "<p class='error'>Passwords did not match.</p>";
						}
						else if ($_GET['error'] == 'invalidCredentials') {
							echo "<p>Invalid username or password.</p>";
						}

					}
					else if ($_GET['signup'] == 'success')  {
						echo "<p class='success'>Sign up successful. You may now <a href='gatewayEnter.php'>Log in</a></p>";

					}	
				?>
			</div>
			<form action="includes/login.inc.php" method="POST" class="modalForm logIn">
				<div class="formSection logIn">
					<div class="formGroup">
						<label for="userEmail">Email address</label>
						<input id="userEmail" type="text" name="user_email" value="<?php echo $enteredEmail ?>" required aria-required="true">
					</div>
					<div class="formGroup">
						<label for="userPassword">Password</label>
						<input id="userPassword" type="password" name="user_pwd" required aria-required="true">
					</div>
					<div class="formGroup actionBar">
						<button type="submit" name="login-submit" class="positive action submit" >Log In</button>
					</div>
					<div class="formGroup actionBar">
						<p><a href="passwordResetRequest.php">Reset your password</a></p>
					</div>
				</div>
			</form>
		</div>
	</div>

<?php
	require 'includes/footer.inc.php';
?>