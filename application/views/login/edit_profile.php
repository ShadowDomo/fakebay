<div class="container">
    <div class="row ">
        <div class="col">
            <div class="row">
                <h2>Edit Profile</h2>
            </div>
        </div>

    </div>

    <div class="row">
       <div class="col-5 lefthandside">
       <?php echo form_open('Login/checkEditProfile'); ?>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" class="form-control" id="email" value="<?php echo $user_details->email?>">
            </div>
            <div class="form-group">
                <label for="email">Username:</label>
                <input type="text" name="username" class="form-control" id="username" value="<?php echo $user_details->username?>">
            </div>
            <div class="form-group">
                <label for="phone_number">Phone Number:</label>
                <input type="text" name="phone_number" class="form-control" id="phone_number" value="<?php echo $user_details->phone_number?>">
            </div>
            <div class="form-group">
                <label for="password">Current password:</label>
                <input type="password" name="current_password" class="form-control" id="password" placeholder="*******">
            </div>
            <div class="form-group">
                <label for="password">New password:</label>
                <input type="password" name="new_password" class="form-control" id="password" placeholder="*******">
            </div>
            <div class="form-group">
                <label for="password">Confirm new password:</label>
                <input type="password" name="confirmed_password" class="form-control" id="password"
                    placeholder="*******">
            </div>
            <div class="row justify-content-between ">
                <button name="submit" type="submit" class="btn btn-primary ml-3" value="submit">Submit</button>
            </div>

            <?php
                if ($error != 'none') {
                    echo '<br>';
                    if ($error == "password_not_same") {
                        echo '<div class="alert alert-warning">Passwords do not match. Please try again!</div>';
                    } elseif ($error == "email_invalid" || $error == "invalid_username") {
                        echo '<div class="alert alert-warning">Invalid email or username. Please try again!</div>';
                    } elseif ($error == "password_incorrect") {
                        echo '<div class="alert alert-warning">Password incorrect. Please try again!</div>';
                    } elseif ($error == "phone invalid") {
                        echo '<div class="alert alert-warning">Phone number invalid. Please try again!</div>';
                    } else {
                        echo '';
                    }
                }
			?>
            <?php echo form_close(); ?>

       
       </div>
       <div class="col righthandside">


       </div>





    </div>
</div>