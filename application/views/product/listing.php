<script>

$(document).ready(function () {
    var num_pics = 1;
    $('#add_photo').click(function (e) { 
    e.preventDefault();
    num_pics++;
    $('#file_upload').append(`<input type='file' name='uploaded_file_${num_pics}'>`);
    $('#counter').val(num_pics);
});
});



</script>


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
                <input type="hidden" id="counter" name="count_files" value="1"> 
            </div>
            <div class="row">
                <div class="col-2 form-group" id="file_upload">
                    <input type="file" name="uploaded_file_1"> 
                </div>
                <div class="col-2"></div>
                <?php 
                    if (isset($this->session->error)) {
                        echo "<div class='col-7 alert alert-warning'>";
                        echo $this->session->error;
                        echo "</div>";

                        $this->session->unset_userdata('error');
                    }                


                ?>
            </div>
            <div class="row justify-content-between">
                <button name="add_photo" type="button" class="btn btn-light ml-3" id="add_photo">Add another photo</button>
            </div>
            <br>
            <div class="row justify-content-between ">
                <button name="submit" type="submit" class="btn btn-primary ml-3" value="submit listing">Submit</button>
            </div>


            <?php echo form_close(); ?>
        </div>
    </div>


</div>