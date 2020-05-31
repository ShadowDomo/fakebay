<div class="container">
    <div class="row">
        <div class="col alert alert-danger">
            <h3>My Wishlist</h3>

            <?php
                // var_dump($wishlist);

                foreach ($wishlist->result() as $item) {
                    // echo "hi";
                    ?><div class='row'>
                        <div class="col-10">
                        
                        <form method='get' action='<?php echo base_url().'Products/viewProduct' ;?>'>
                        <button type="submit" class="btn btn-link" name="product_id" value="<?php echo $item->product_id?>">
			                <?php echo $item->product_name ?>
                        </button>
                        </form>

                        <?php 
                        echo "<p>{$item->description}</p>";
                        echo "<br>";
                        ?>
                        </div>
                        <div class="col-2">
                            <form method='get' action='<?php echo base_url().'Products/deleteFromWishlist' ;?>'>
                            <button type="submit" class="btn btn-primary" name="product_id" value="<?php echo $item->product_id?>">
                                Delete
                            </button>
                            </form>
                        
                        </div>
                    </div>
                    <?
                }
            ?>
        </div>
    </div>
</div>