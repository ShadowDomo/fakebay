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
                <label for="phone_number">Phone Number:</label>
                <input type="text" name="phone_number" class="form-control" id="phone_number" placeholder="0412345678">
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
				if (isset($this->session->error)) {
                    echo '<br>';
                    echo '<div class="alert alert-warning">';
                    echo $this->session->error;
                    echo '</div>';
                    $this->session->unset_userdata('error');
                }
			?>
            <?php echo form_close(); ?>
        </div>
        <div class="col"></div>
    </div>
</div>