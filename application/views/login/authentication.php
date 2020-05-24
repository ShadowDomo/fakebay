
<div class="container">

    <div class="row">
        <div class="col"></div>
        <div class="col">
            <h5>Two-Factor Authentication</h5>
        </div>
        <div class="col"></div>
    </div>

    <div class="row">
        <div class="col"></div>
        <div class="col">
            <h6>Please enter the code sent to your phone</h6>
        </div>
        <div class="col"></div>
    </div>

    <div class="row">
        <div class="col"></div>
        <div class="col">
         <?php echo form_open('Login/checkTwoFactor'); ?>
            <div class="form-group">
                <label for="secret">Code:</label>
                <input type="text" name="secret" class="form-control" id="secret">
            </div>
            
            <div class="row justify-content-between ">
                <button name="submit" type="submit" class="btn btn-primary ml-3" value="submit">Submit</button>
            </div>
             <div class="<?php 
            if ($this->session->has_userdata('error') && $this->session->error == "two factor") {
                echo "alert alert-warning"; }?>"> 
            <?php 
                if ($this->session->has_userdata('error') && $this->session->error == "two factor") {
                    echo "<h5>";
                    echo "Wrong code! please try again";
                    $this->session->unset_userdata('error');
                    echo "</h5>";
                }
            ?>


            </div>
            <?php echo form_close(); ?>
        </div>
        <div class="col"></div>
    
    </div>
</div>
