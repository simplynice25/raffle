<?php

date_default_timezone_set('Asia/Manila');

// Database
$database_conf = __DIR__ . '/../src/config/database.yml';

// Monolog configuration
$monolog_conf  = array(
	'monolog.logfile' => __DIR__.'/../src/development.log',
);

// Twig configuration
$twig_conf = array(
	'twig.path' => __DIR__.'/views'
);

// Translation configuration
$trans_conf = array(
	'locale_fallback' => 'en',
);

// Mail configuration
$mail_conf = array(
	'host'       => 'ssl://smtp.gmail.com',
	'port'       => 465,
	'username'   => 'charlottegatchalian20@gmail.com',
	'password'   => 'rommel4693',
	'encryption' => NULL,
	'auth_mode'  => NULL
);

/* End of settings file */