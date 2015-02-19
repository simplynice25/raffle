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

	public function registrationProcess(Request $req, Application $app)
	{
		$roles = array('ROLE_USER', 'ROLE_ADMIN', 'ROLE_ENCODER');
		$firstname = $req->get('firstname');
		$lastname = $req->get('lastname');
		
		$email = $req->get('email');
		$password = $req->get('password');
		$c_password = $req->get('c_password');
		$mobile = $req->get('mobile');
		$birthday = $req->get('birthday');
		$address = $req->get('address');

		$role = $req->get('role');

		$sign_up = (int) $req->get('sign_up');

		if ( ! in_array($role, $roles))
		{
            $app['session']->getFlashBag()->set('message', 'invalid_role');
            return Tools::redirect($app, 'users_overview');
		}

		if ($password != $c_password)
		{
            $app['session']->getFlashBag()->set('message', 'password_unmatch');
            return Tools::redirect($app, 'users_overview');
		}
        
        $emailAuthenticate = Tools::findOneBy($app, '\Users', array('email' => $email));
        if ( ! empty($emailAuthenticate))
        {
            $app['session']->getFlashBag()->set('message', 'email_exist');
            return Tools::redirect($app, 'users_overview');
        }
		
		$password = $app['security.encoder.digest']->encodePassword($password, '');
		
		$user = new \models\Users;
		$user->setEmail($email);
		$user->setPassword($password);
		$user->setRoles('ROLE_USER');
		$user->setViewStatus($sign_up );
		$user->setCreatedAt("now");
		$user->setModifiedAt("now");
		$app['orm.em']->persist($user);
		$app['orm.em']->flush();
		
		$profile = new \models\Profiles;
		$profile->setFirstname($firstname);
		$profile->setLastname($lastname);
		$profile->setMobile($mobile);
		$profile->setBirthday(new \DateTime($birthday));
		$profile->setAddress($address);
		$profile->setUser($user);
		$profile->setViewStatus(5);
		$profile->setCreatedAt("now");
		$profile->setModifiedAt("now");
		$app['orm.em']->persist($profile);
		$app['orm.em']->flush();

		/* Redirect user if signed up and activated */
		if ($sign_up === 5)
		{
			$app['session']->getFlashBag()->set('message', 'user_added');

			return Tools::redirect($app, 'users_overview');
		}

        $msg = "confirm_failed";
        $token = md5(uniqid()) . md5($email);
		$generatedUrl = "http://$_SERVER[HTTP_HOST]".$app['url_generator']->generate('registration-process-confirm')."?token=" . $token;
        $html = $app['twig']->render('general/emails/confirm-reg.twig', array( "url" => $generatedUrl, "fullname" => $firstname . " " . $lastname ));
	
        $message = \Swift_Message::newInstance()
                    ->setSubject("Confirm Registration")
                    ->setFrom(array("admin@raffle.com" => "Raffle Draw"))
                    ->setTo(array($email))
                    ->addPart( $html , 'text/html' );

        if ( $app['mailer']->send($message))
        {
            $user->setToken($token);
            $user->setModifiedAt("now");
            $app['orm.em']->persist($user);
            $app['orm.em']->flush();
            
            $msg = "confirm_sent";
        }

		$app['session']->getFlashBag()->set('message', $msg);

		return Tools::redirect($app, 'users_overview');
	}

	public function activationProcess(Request $req, Application $app, $uid = null) 
	{
		$msg = 'invalid_uid';
		if ( is_null($uid) || ! is_numeric($uid))
		{
			$app['session']->getFlashBag()->set('message', $msg);
			return Tools::redirect($app, 'users_overview');
		}

		$user = Tools::findOneBy($app, '\Users', array('id' => $uid));

		if (empty($user))
		{
			$app['session']->getFlashBag()->set('message', $msg);
			return Tools::redirect($app, 'users_overview');
		}

		$user->setViewStatus(5);
		$user->setToken(null);
        $user->setModifiedAt("now");
        $app['orm.em']->persist($user);
        $app['orm.em']->flush();

		$app['session']->getFlashBag()->set('message', 'user_activated');
		return Tools::redirect($app, 'users_overview');
	}
}