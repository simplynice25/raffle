<?php

namespace user;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

use general\Tools;

class HomeProvider
{
	public static function routing(Application $app)
	{
		$ui = $app['controllers_factory'];
        
		// Overviews
		$ui->match('/{id}', 'user\UserProvider::index')->bind('raffle_details');

		$before = function (Request $request, Application $app) {
            return Tools::isLogged($app);
		};

		$ui->before($before);

		return $ui;
	}
}