<h3>Results:</h3>





<?php foreach ($results->result() as $row) { ?>
	<form method='get' action='<?php echo base_url().'Products/viewProduct' ;?>'>
		<button  type="submit" class="btn btn-link" name="product_id" value="<?php echo $row->product_id?>">
			<?php echo $row->product_name ?>
		</button>
	</form>
	<?php 
		// echo anchor('Products/viewProduct');
		// echo '<br>';
		// echo $row->product_name . '<br>';
		echo $row->description . '<br>';
	}
?>