<?php

namespace admin;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class AdminProvider
{
	public static function routing(Application $app)
	{
		$ui = $app['controllers_factory'];
		// Overviews
		$ui->match('/', 'admin\AdminProvider::index')->bind('admin_overview');

		return $ui;
	}

	public function index(Request $req, Application $app)
	{
		return "Hello admin!";
	}
}