<div class="container">
    <div class="row">
        <div class="col"></div>
        <div class="col">
            <h1>Register</h1>
            <?php echo form_open('Login/register'); ?>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" class="form-control" id="email" placeholder="email">
            </div>
            <div class="form-group">
                <label for="email">Username:</label>
                <input type="text" name="username" class="form-control" id="username" placeholder="username">
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" class="form-control" id="password" placeholder="*******">
            </div>
            <div class="form-group">
                <label for="password">Confirm Password:</label>
                <input type="password" name="confirmed_password" class="form-control" id="password"
                    placeholder="*******">
            </div>
            <div class="row justify-content-between ">
                <button name="submit" type="submit" class="btn btn-primary ml-3" value="log in">Submit</button>
                <!-- <button name="submit" type="submit" class="btn btn-primary" value="register">Register</button> -->
            </div>

            <?php
				if ($error == "passworderror") {
					echo '<div class="alert alert-warning">Passwords do not match. Please try again!</div>';
				} else if ($error == "usernameemailerror") {
					echo '<div class="alert alert-warning">Invalid username or email. Please try again!</div>';
				}
			?>
            <?php echo form_close(); ?>
        </div>
        <div class="col"></div>
    </div>
</div>