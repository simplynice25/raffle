<?php include(__DIR__ . "/../header.php"); ?>
<div class="container">
	<div class="row">
		<div class="col-md-12 join-interface">
			<?php echo form_open('join/process'); ?>
				<?php if(isset($_GET['entry']) && $_GET['entry'] == "true"): ?>
                <div class="alert alert-success">
                    <small>Entry has been added to our database.</small>
                </div>
                <?php endif; ?>
				<div class="form-group">
					<label for="receipt"><small>Receipt Number</small></label>
					<input type="text" id="receipt" name="receipt" class="form-control" required autofocus />
				</div>
				<div class="form-group">
					<label for="last_name"><small>Last Name</small></label>
					<input type="text" id="last_name" name="last_name" class="form-control" required />
				</div>
				<div class="form-group">
					<label for="first_name"><small>First Name</small></label>
					<input type="text" id="first_name" name="first_name" class="form-control" required />
				</div>
				<div class="form-group">
					<label for="email"><small>Email Address</small></label>
					<input type="text" id="email" name="email" class="form-control" required/>
				</div>
				<div class="form-group">
					<label for="phone"><small>Phone Number</small></label>
					<input type="text" id="phone" name="phone" class="form-control" required/>
				</div>
				<div class="form-group">
					<label for="mobile"><small>Mobile Number</small></label>
					<input type="text" id="mobile" name="mobile" class="form-control" required/>
				</div>
				<div class="form-group">
					<label for="address"><small>Address</small></label>
					<textarea id="address" class="form-control" name="address" required></textarea>
				</div>
				<div class="form-group">
					<label for="message"><small>Leave us a message</small></label>
					<textarea id="message" class="form-control" name="message" required></textarea>
				</div>
                <!--
				<div class="form-group">
					<label for="file"><small>Upload file</small></label>
					<input type="file" id="file" class="form-control" />
				</div>
                -->
				<div class="form-group">
					<button class="btn btn-sm btn-primary pull-right">Submit</button>
				</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>
<footer></footer>
</body>
</html>