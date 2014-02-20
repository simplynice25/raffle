<?php foreach($user as $us){}?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <base href="<?php echo base_url(); ?>"/>
	<title>Settings</title>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/bootstrap/css/bootstrap.min.css"/>
</head>
<body>
<?php include(__DIR__ . "/../includes/header.php"); ?>

<div class="container">
	<div class="row">
        <?php if($us->user_favorite == 1): ?>
        <div class="alert alert-success">
            Hi <?php echo ucfirst($us->user_name); ?>! Thank you for ordering at keanna's flower shop. With regards to your ordering history you are now classified as our regular/priority customer.
        </div>
		<?php endif; ?>
		<div class="col-sm-4 col-md-offset-4">

		<?php if(isset($_GET['update']) && $_GET['update'] == "true"): ?>
        <div class="alert alert-success">
            Information saved.
        </div>
		<?php endif; ?>
		<?php echo form_open('settings/update'); ?>
        <div class="form-group">
        	<label for="user_name"><small>Full Name</small></label>
			<input type="text" id="user_name" name="user_name" class="form-control" value="<?php echo $us->user_name; ?>" required="required">
        </div>
        <div class="form-group">
        	<label for="user_email"><small>Email Address</small></label>
			<input type="email" id="user_email" name="user_email" class="form-control" value="<?php echo $us->user_email; ?>" required="required">
        </div>
        <div class="form-group">
        	<label for="user_birthday"><small>Birthday</small></label>
			<input type="date" id="user_birthday" name="user_birthday" class="form-control" value="<?php if($us->user_birthday != "0000-00-00") echo date("Y-m-d", strtotime($us->user_birthday)); ?>">
        </div>
		<div class="form-group">
        	<label for="user_address"><small>Address</small></label>
			<textarea id="user_address" name="user_address" class="form-control"><?php echo $us->user_address; ?></textarea>
		</div>
		<div class="form-group">
        	<label for="user_password"><small>Password</small></label>
			<input type="password" id="user_password" name="user_password" class="form-control">
			<small>Please leave this blank if you don't want to change password.</small>
		</div>
		<input type="hidden" name="action" value="<?php echo $action; ?>"/>
		<button class="btn btn-success pull-right">Update</button>
		<?php echo form_close(); ?>
		</div>
	</div>

</div>

<?php include(__DIR__ . "/../includes/footer.php"); ?>
</body>
</html>
