<?php

namespace general;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

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

}