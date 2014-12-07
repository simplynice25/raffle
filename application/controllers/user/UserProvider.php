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

		$before = function (Request $request, Application $app) {

            $username = NULL;
            $token = $app['security']->getToken();

            if( null !== $token ) {
                if (is_object($token->getUser()))
                {
                    $user = $token->getUser();
                    $roles = $user->getRoles();
                    $username = $user->getUsername();
                }
            }
            
            $app['session']->set('credentials', array($username));
            
			return NULL;
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
}