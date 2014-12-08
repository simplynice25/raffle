<?php

namespace user;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

use general\Tools;

class UserProvider
{
	public static function routing(Application $app)
	{
		$ui = $app['controllers_factory'];
        
		// Overviews
		$ui->match('/', 'user\UserProvider::index')->bind('user_overview');
        $ui->match('/settings', 'user\UserProvider::userSettings')->bind('user_settings');

		$before = function (Request $request, Application $app) {
            return Tools::isLogged($app);
		};

		$ui->before($before);

		return $ui;
	}

	public function index(Request $req, Application $app)
	{
        $view = array(
            'title' => 'Homepage',
        );
        
		return $app['twig']->render('front/index.twig', $view);
	}
    
    public function userSettings(Request $req, Application $app)
    {
        return "settings";
    }
}