<h3>Results:</h3>



<?php

foreach ($results->result() as $row) {

	echo $row->product_id . '<br>';
	echo $row->product_name . '<br>';
	echo $row->description . '<br>';
}
?>