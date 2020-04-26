

<div class="container-fluid">
    <?php echo form_open('Papers/formChecker'); ?>
    <div class="row">
        <div class="col-9">
            <div class="form-group">
                <!-- <label for="email">Email address:</label> -->
                <input type="email" name="email" class="form-control" id="email" placeholder="Search">
            </div>
        
        </div>
        <div class="col-3">
            <div class="row justify-content-between ">
                <button name="search" type="submit" class="btn btn-primary ml-3" value="search">Search</button>
            </div>
        
        </div>

    </div>
    <?php echo form_close(); ?>
</div>


