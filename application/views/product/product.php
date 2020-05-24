
<!-- <div class="col-12 alert alert-danger"></div> -->
<!-- 
// echo $product_details->product_id;
// echo '<br>'; -->

<div class="row ">
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

        <?php
         if ($bid_history->result() != NULL) {
            if ($bid_history->result()[0]->user_ID == $this->session->user_id && $total_seconds > 0) {
            ?> 
            <h5 class="alert alert-success">You are currently the highest bidder! </h5>
            <?php } elseif ($bid_history->result()[0]->user_ID == $this->session->user_id &&
            $total_seconds < 0) {
            ?> 
            <h5 class="alert alert-success">You won this auction with the highest bid of <?php echo 
            $bid_history->result()[0]->bid_price;
            ?> </h5>
            <?php }
        } 

         ?>
        
        
        <h5>Current Price   :</h5>
        <p><?php echo "$ {$product_details->current_price} " ?></p>
        <h5>Seller:</h5>
        <p><?php echo $seller_details->username .' ('.$seller_details->seller_rating.')' ?></p>
        
        <!-- countdown -->
        <h5><?php 
            if ($total_seconds < 0) {
                echo "Auction ended already.";
            } else {
                echo "Auction ends in ";
                if ($days > 0) {
                    echo "{$days}d ";
                } 
                if ($hours > 0) {
                    echo "{$hours}h ";
                }
                if ($minutes > 0) {
                    echo "{$minutes}m ";
                }
                if ($seconds > 0) {
                    echo "{$seconds}s";
                }
            }
        
        ?>
        </h5>
        

        <?php foreach ($bid_history->result() as $row) {
            // personalised messages
            if ($row->user_ID == $this->session->user_id) {
                echo "You ";
            } else {
                echo "User ";
            }
             echo "placed a bid with price: \${$row->bid_price}";    
             echo '<br>';
	    }
        ?>
        <br>
        

        <form method='post' action='<?php echo base_url()."Products/makeBid/{$product_details->product_id}" ;?>'>
        <div class="row">
            <div class="col-7">
                <div class="form-group">
                    <input type="text" name="bid_price" <?php 
                    // disable input if auction has ended
                    if ($total_seconds < 0) {
                        echo "disabled=true";
                    }
                    ?>class="form-control" id="bid_price" placeholder="Enter Bid price">
                </div>
            
            </div>
            <div class="col-5   ">
                <div class="row">
                    <button  type="submit" <?php 
                    // disable input if auction has ended   
                    if ($total_seconds < 0) {
                        echo "disabled=true";
                    }
                    ?>class="btn btn-primary">Bid</button>
                </div>
            </div>
        </div>
        <!-- for error message -->
        <div class="<?php 
            if ($this->session->has_userdata('error') && $this->session->error == "bid error") {
                echo "alert alert-warning"; }?>"> 
        <?php 
            if ($this->session->has_userdata('error') && $this->session->error == "bid error") {
                echo "<h5>";
                echo "Please enter a bid which is higher than the current price!";
                $this->session->unset_userdata('error');
                echo "</h5>";
            }
        ?>


        </div>
        </form>
    </div>

</div>






