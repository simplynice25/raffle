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
        $ui->match('/settings-action', 'user\UserProvider::userSettingsAction')->bind('user_settings_action');

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
        $user = Tools::isLogged($app, 1);
        $profile = Tools::findOneBy($app, '\Profiles', array('user' => $user));
        
        $view = array(
            'title' => 'Settings',
            'profile' => $profile,
			'message' => $app['session']->getFlashBag()->get('message'),
        );
        
		return $app['twig']->render('front/settings.twig', $view);
    }
    
    public function userSettingsAction(Request $req, Application $app)
    {
        $msg = "profile_updated";
        $user = Tools::isLogged($app, 1);
        $profile = Tools::findOneBy($app, '\Profiles', array('user' => $user));
        
        $firstname = $req->get('firstname');
        $lastname = $req->get('lastname');
        $email = $req->get('email');
        $contact_number = $req->get('contact_number');
        $password = $req->get('password');
        $confirm_password = $req->get('confirm_password');
        $birthday = $req->get('birthday');
        $address = $req->get('address');
        
        // Validate password
        if ( ! empty($password) && $password != $confirm_password)
        {
            $msg = "password_not_match";
        }
        
        // Validate email
        $emailAuthenticate = Tools::findOneBy($app, '\Users', array('email' => $email));
        if ( ! empty($emailAuthenticate) && $email != $user->getEmail())
        {
            $msg = "email_exist";
        }
        
        // If there's an error redirect
        if ($msg != "profile_updated")
        {
            $app['session']->getFlashBag()->set('message', $msg);
            return Tools::redirect($app, 'user_settings');
        }

        // Update user
        $user->setEmail($email);
        if ( ! empty($password)) {
            $user->setPassword($app['security.encoder.digest']->encodePassword($password, ''));
        }
        $user->setModifiedAt("now");
        $app['orm.em']->persist($user);
        $app['orm.em']->flush();

        // Update profile
        $profile->setFirstname($firstname);
        $profile->setLastname($lastname);
        $profile->setMobile($contact_number);
        $profile->setBirthday(new \DateTime($birthday));
        $profile->setAddress($address);
        $profile->setModifiedAt("now");
        $app['orm.em']->persist($profile);
        $app['orm.em']->flush();
        
        $app['session']->getFlashBag()->set('message', $msg);
        
        return Tools::redirect($app, 'user_settings');
    }
}