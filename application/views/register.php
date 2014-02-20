<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <base href="<?php echo base_url(); ?>"/>
	<title>Registration</title>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/bootstrap/css/bootstrap.min.css"/>
</head>
<body>
<?php require_once('includes/header.php'); ?>

<div class="container">
	<div class="row">
		<div class="col-sm-4 col-md-offset-4" style="margin-top: 150px">

		<?php if(isset($_GET['email']) && $_GET['email'] == "false"): ?>
        <div class="alert alert-danger">
            <small>The email you provided is already in use.</small>
        </div>
		<?php endif; ?>
		
		<?php if(isset($_GET['ban']) && $_GET['ban'] == "true"): ?>
        <div class="alert alert-danger">
            <small>An adminastrator blocked you from viewing this system.</small>
        </div>
		<?php endif; ?>
		
		<?php echo form_open('register/verify'); ?>
        <div class="form-group">
        	<label><small>Full Name</small></label>
			<div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
				<input type="text" name="user_name" class="form-control" required="required">
			</div>
        </div>
        <div class="form-group">
        	<label><small>Email Address</small></label>
			<div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
				<input type="email" name="user_email" class="form-control" required="required">
			</div>
        </div>
		<div class="form-group">
        	<label><small>Password</small></label>
			<div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
				<input type="password" name="user_password" class="form-control" required="required">
			</div>
		</div>
		<a href="login">Have an account? Login!</a>
        <input type="hidden" name="action" value="<?php echo $action; ?>"/>
		<button class="btn btn-success pull-right">Register</button>
		<?php echo form_close(); ?>
		</div>
	</div>

</div>

<?php require_once('includes/footer.php'); ?>
</body>
</html>