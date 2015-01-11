<?php

namespace admin;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

use general\Tools;

class Users
{
	public $active = 5;

	public function usersOverview(Request $req, Application $app)
	{
		$adminCredentials = $app['session']->get('credentials');

		$users = Tools::findBy($app, '\Users', array(), array('created_at' => 'DESC'));

		$view = array(
			'title' => 'Users', 
			'users' => $users,
			'credentials' => $adminCredentials,
			'profiles' => self::getUsersProfile($app, $users),
            'active_tab' => $this->active,
			'message' => $app['session']->getFlashBag()->get('message'),
		);

		return $app['twig']->render('dashboard/users.twig', $view);
	}

	public function getUsersProfile($app, $users)
	{
		$userData = array();
		foreach ($users as $u)
		{
			$userData[] = Tools::findOneBy($app, '\Profiles', array('user' => $u));
		}

		return $userData;
	}

	public function userAction(Request $req, Application $app)
	{
		$userId = (int) $req->get('user');
		$status = (int) $req->get('status');

		$user = Tools::findOneBy($app, '\Users', array('id' => $userId));
		if (empty($user))
		{
			return json_encode(array('message'=>'error'));
		}

		$user->setViewStatus($status);
        $user->setModifiedAt('now');
        
		$app['orm.em']->persist($user);
		$app['orm.em']->flush();

		return json_encode(array('message'=>'success'));
	}

	public function usersSearch(Request $req, Application $app)
	{
        $keyword = $req->get('keyword');
        
        $query = "SELECT u FROM models\Users u
        		  LEFT JOIN models\Profiles p
        		  WITH p.user = u.id
                  WHERE u.id IS NOT NULL AND 
                  (
                  	u.email LIKE :keyword OR
                  	p.firstname LIKE :keyword OR
                  	p.lastname LIKE :keyword OR
                  	p.mobile LIKE :keyword OR
                  	p.birthday LIKE :keyword OR
                  	p.address LIKE :keyword
                  )";
        
        $query = $app['orm.em']->createQuery($query);
        $query->setParameter("keyword", "%" . $keyword . "%");
        $users = $query->getResult();

		$adminCredentials = $app['session']->get('credentials');

        $view = array(
        	'users' => $users,
			'credentials' => $adminCredentials,
			'profiles' => self::getUsersProfile($app, $users),
        );

		return $app['twig']->render('dashboard/includes/list.users.twig', $view);
	}

	public function userRole(Request $req, Application $app)
	{
		$role = $req->get('role');
		$userId = (int) $req->get('userId');

		/*
		* Check if the role exist
		*/
		$roles = array('ROLE_USER', 'ROLE_ENCODER', 'ROLE_ADMIN');
		if (!in_array($role, $roles))
		{
			return json_encode(array('message'=> 'error'));
		}

		$user = Tools::findOneBy($app, '\Users', array('id' => $userId));
		if (empty($user))
		{
			return json_encode(array('message'=> 'error'));
		}

		$user->setRoles($role);
        $user->setModifiedAt('now');

		$app['orm.em']->persist($user);
		$app['orm.em']->flush();

		return json_encode(array('message'=>'success'));
	}
}