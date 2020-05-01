
<!-- <div class="col-12 alert alert-danger"></div> -->
<!-- 
// echo $product_details->product_id;
// echo '<br>'; -->

<div class="row">
    <!-- left hand side of page -->
    <div class="col-8 ">
        <h3> <?php echo ucfirst($product_details->product_name); ?></h3>
        <!-- <br> -->
        <img class="border border-danger" src="<?php echo base_url() . 'assets/' . $product_details->image_filename ?>">
        <br>
        <br>

        <h5>Description:</h5>
        <p> <?php echo $product_details->description; ?> </p>




    </div>

    <!-- right hand side with auction -->
    <div class="col-4">
        <h5>Price:</h5>
        <p><?php echo $product_details->starting_price?></p>
        <h6>Seller:</h6>
        <p><?php echo $seller_details->username .' ('.$seller_details->seller_rating.')' ?></p>
        




    </div>

</div>






