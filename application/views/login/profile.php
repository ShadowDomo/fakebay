<div class="container">
    <div class="row ">
        <div class="col">
            <div class="row">
                <h2>My Profile</h2>
                <?php 
					echo form_open('Login/openEditProfile') ?>
                <button name="edit" type="submit" class="btn btn-primary ml-3" value="edit">Edit</button>
                <?php echo form_close();
					?>

            </div>


        </div>

    </div>

    <div class="row">
        <div class="col">
            <div class="row forusername">
                <div class="col-10">
                    <h5>Username: <?php echo $user_details->username; ?></h5>
  
                </div>

            </div>
            <div class="row forusername">
                <div class="col-10">
                    <h5>Email: <?php echo $user_details->email; ?></h5>
                </div>

            </div>
            <div class="row">
                <div class="col">
                    <h5>Rating: <?php echo $user_details->seller_rating; ?></h5>
                </div>
            </div>
        </div>





    </div>
</div>