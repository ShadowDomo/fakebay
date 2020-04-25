<div class="container">
	<div class="row">
		<div class="col"></div>
		<div class="col">
			<?php echo form_open('Papers/login'); ?>
			<div class="form-group">
				<label for="email">Email address:</label>
				<input type="email" name="email" class="form-control" id="email" placeholder="johndoe@gmail.com">
			</div>
			<div class="form-group">
				<label for="password">Password:</label>
				<input type="password" name="password" class="form-control" id="password" placeholder="*******">
			</div>
			<button type="submit" class="btn btn-default" value="log in">Submit</button>
			<?php echo form_close(); ?>
		</div>
		<div class="col"></div>
	</div>
</div>

