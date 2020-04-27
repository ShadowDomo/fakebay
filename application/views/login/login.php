<div class="container">
	
	<div class="row">
		
		<div class="col"></div>
		<div class="col">
			<h1>Login</h1>
			<?php echo form_open('Login/formChecker'); ?>
			<div class="form-group">
				<label for="email">Email address:</label>
				<input type="email" name="email" class="form-control" id="email" placeholder="email">
			</div>
			<div class="form-group">
				<label for="password">Password:</label>
				<input type="password" name="password" class="form-control" id="password" placeholder="*******">
			</div>
			<div class="row justify-content-between ">
				<button name="submit" type="submit" class="btn btn-primary ml-3" value="log in">Submit</button>
				<button name="submit" type="submit" class="btn btn-primary mr-3" value="register">Register</button>
			</div>
			<?php echo form_close(); ?>
			<?php 
			if ($error == "invalid") {
				echo '<br>';
				echo '<div class="alert alert-warning">Incorrect email or password. Please try again!</div>';
			}
			?>
		</div>
		<div class="col"></div>
	</div>
</div>

