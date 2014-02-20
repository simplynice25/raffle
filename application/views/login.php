<?php require_once('header.php'); ?>
<div class="container">
	<div class="row">
		<div class="col-sm-6 col-md-offset-3 login-form">

		<?php if(isset($_GET['login']) && $_GET['login'] == "false"): ?>
        <div class="alert alert-danger">
            <small>Please provide the right credentials.</small>
        </div>
		<?php endif; ?>
		
		<?php if(isset($_GET['block']) && $_GET['block'] == "true"): ?>
        <div class="alert alert-danger">
            <small>An adminastrator blocked you from viewing this system.</small>
        </div>
		<?php endif; ?>
		
		<?php echo form_open('login/verify', array('class' => 'login-interface')); ?>
		<h2>E-Raffle login</h2>
        <div class="form-group">
        	<label><small>Email Address</small></label>
			<div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
				<input type="email" name="user_email" class="form-control" required="required" autofocus>
			</div>
        </div>
		<div class="form-group">
        	<label><small>Password</small></label>
			<div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
				<input type="password" name="user_password" class="form-control" required="required">
			</div>
		</div>
		<button class="btn btn-primary pull-right">Log In</button>
		<?php echo form_close(); ?>
		</div>
	</div>

</div>
</body>
</html>
