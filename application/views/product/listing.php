<div class="container-fluid alert alert-danger">

    <div class="row">
        <div class="col">
            <h3>Sell something</h3>
            <?php echo form_open_multipart('Products/makeListing'); ?>
            <div class="form-group">
                <label for="product_name">Product name</label>
                <input type="text" name="product_name" class="form-control" id="product_name" placeholder="Back to the future sneakers size 8">
            </div>
            <div class="form-group">
                <label for="starting_price">Starting price</label>
                <input type="text" name="starting_price" class="form-control" id="starting_price" placeholder="40.00">
            </div>
             <div class="form-group">
                <label for="date">Auction end date</label>
                <input type="date" name="end_date" class="form-control" id="date">
            </div>
            <div class="form-group">
                <label for="time">Auction end time</label>
                <input type="time" name="end_time" class="form-control" id="time">
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" class="form-control" rows="10" id="description" placeholder="Real sneakers from the back to the future set. Found at a garage sale for a few years ago. They are a bit crusty but a little elbow grease should fix them up. Size 8 US."></textarea>
            </div>
            <div class="form-group">
                <input type="file" id="file upload" name="uploaded_file"> 
            </div>
            <div class="row justify-content-between ">
                <button name="submit" type="submit" class="btn btn-primary ml-3" value="submit listing">Submit</button>
            </div>


            <?php echo form_close(); ?>
        </div>
    </div>


</div>