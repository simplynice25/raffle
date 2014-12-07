<?php

namespace general;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

use plugins\UserProvider;

class Tools
{
	public static function findBy(
		Application $app, 
		$model, 
		$criteria = array('view_status' => 5), 
		$sort = NULL, 
		$limit = NULL, 
		$offset = NULL
	)
	{
		$object = $app['orm.em']->getRepository('models' . $model)->findBy($criteria, $sort, $limit, $offset);
		
		return $object;
	}

	public static function findOneBy(
		Application $app, 
		$model, 
		$criteria = array('view_status' => 5), 
		$sort = NULL
	)
	{
		$object = $app['orm.em']->getRepository('models' . $model)->findOneBy($criteria, $sort);
		
		return $object;
	}
	
	public static function redirect(Application $app, $link, $params)
	{	
		$url = $app['url_generator']->generate($link, $params);

		return $app->redirect($url);
	}

	public static function autoLogin(Application $app, $email = NULL)
    {
		$userProvider = new UserProvider($app);
		$user = $userProvider->loadUserByUsername($email, 1);
		$app['security']->setToken(new \Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken($user, $user->getPassword(), 'default', $user->getRoles()));
        
        return TRUE;
	}

}