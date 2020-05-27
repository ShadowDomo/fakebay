<h3>Results:</h3>





<?php foreach ($results as $row) {
	$good = $row[0] ?>
	<form method='get' action='<?php echo base_url().'Products/viewProduct' ;?>'>
		<button  type="submit" class="btn btn-link" name="product_id" value="<?php echo $good->product_id?>">
			<?php echo $good->product_name ?>
		</button>
	</form>
	<?php 
		// echo anchor('Products/viewProduct');
		// echo '<br>';
		// echo $row->product_name . '<br>';
		echo $good->description . '<br>';
	}
?>