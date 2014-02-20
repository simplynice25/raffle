<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <base href="<?php echo base_url(); ?>"/>
	<title>Login</title>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/bootstrap/css/bootstrap.min.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/styles/style.css"/>
</head>
<body>
<nav class="navbar navbar-default navbar-fixed-top header" role="navigation">
<div class="container">
	<div class="navbar-header">
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
			<span class="sr-only">Toggle navigation</span>
			<span class="glyphicon glyphicon-bar"></span>
			<span class="glyphicon glyphicon-bar"></span>
			<span class="glyphicon glyphicon-bar"></span>
		</button>
	</div>
	<a href="" class="navbar-brand hidden-sm">E-Raffle</a>
	<div class="collapse navbar-collapse navbar-ex1-collapse">
		<ul class="nav navbar-nav">
			<li><a href="home">Homepage</a></li>
			<li><a href="join">Join Us!</a></li>
			<li><a href="about">About us</a></li>
			<li><a href="contact">Contact Us</a></li>
			<li><a href="terms">Terms & Conditions</a></li>
		</ul>
		<ul class="nav navbar-nav pull-right">
			<li class="dropdown">
            <?php if(isset($session) && $session['raffle']) { ?>
			<a href="logout">Sign out</a>
            <?php } else { ?>
            <a href="login">Sign in</a>
            <?php } ?>
			</li>
		</ul>
	</div>
</div>
</nav>