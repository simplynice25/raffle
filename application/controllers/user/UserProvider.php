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

        // Raffles
        $ui->match('/raffle/{id}', 'user\UserProvider::raffleDetails')->bind('raffle_details');

        // Static pages
        $ui->match('/archives', 'user\UserProvider::archives')->bind('archives');
        $ui->match('/about', 'user\UserProvider::aboutUs')->bind('about-us');
        $ui->match('/contact', 'user\UserProvider::contact')->bind('contact');
        $ui->match('/developers', 'user\UserProvider::developers')->bind('developers');

		$before = function (Request $request, Application $app) {
            
            $login = $app['session']->getFlashBag()->get('login');
            if ( ! empty($login) && isset($login[0]) && $login[0] == 'fail') {
                return Tools::redirect($app, 'login');
            }

            $role = $app['session']->getFlashBag()->get('role');
            if ( ! empty($role) && isset($role[0]) && $role[0] == "ROLE_ADMIN") {
                return Tools::redirect($app, 'dashboard');
            }

            return Tools::isLogged($app);
		};

		$ui->before($before);

		return $ui;
	}

    public function getUserNames($app)
    {
        $dql = "SELECT p.firstname fname, p.lastname lname FROM models\Profiles p
                JOIN p.user u WHERE u.roles = :role AND u.view_status = 5";

        $query = $app['orm.em']->createQuery($dql);
        $query->setParameter("role", "ROLE_USER");
        $query->setFirstResult(0);
        $query->setMaxResults(100);
        $names = $query->getResult();

        return $names;
    }

	public function index(Request $req, Application $app)
	{
        $archives = Tools::findBy($app, '\Raffles', array('raffle_status' => 2), array('end_date' => 'DESC'), 5, 0);
        $names = self::getUserNames($app);
        $view = array(
            'title' => 'Sherie Anne\'s',
            'archives' => $archives,
            'names' => $names,
            'viewing' => 1,
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

    public function archives(Request $req, Application $app)
    {
        $ym = null;
        $year = $req->get('year');
        $month = $req->get('month');
        if (empty($year) && empty($month))
        {
            $archives = Tools::findBy($app, '\Raffles', array('raffle_status' => 2), array('end_date' => 'DESC'), 10, 5);
        } else {
            $ym = $year ."-". $month;
            $dql = "SELECT r FROM models\Raffles r WHERE r.end_date LIKE :end_date AND r.raffle_status = :raffle_status
                    ORDER BY r.end_date DESC";

            $query = $app['orm.em']->createQuery($dql);
            $query->setParameter("raffle_status", 2);
            $query->setParameter("end_date", '%'. $ym .'%');
            $archives = $query->getResult();
        }

        $names = self::getUserNames($app);
        $view = array(
            'title' => 'Archives',
            'archives' => $archives,
            'names' => $names,
            'hide' => true,
            'date' => (empty($year) && empty($month)) ? date('Y-m-d') : $ym . "-01",
            'viewing' => 5,
        );
        
        return $app['twig']->render('front/index.twig', $view);
    }

    public function aboutUs(Application $app)
    {
        $view = array(
            'title' => 'About Us',
            'viewing' => 2,
        );
        return $app['twig']->render('front/about.twig', $view);
    }

    public function contact(Application $app)
    {
        $view = array(
            'title' => 'Contact',
            'viewing' => 3,
        );
        return $app['twig']->render('front/contact.twig', $view);
    }

    public function developers(Application $app)
    {
        $view = array(
            'title' => 'Developers',
            'viewing' => 6,
        );
        return $app['twig']->render('front/developers.twig', $view);
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

    public function raffleDetails(Request $req, Application $app, $id = null)
    {
        if (is_null($id))
        {
            return false;
        }

        $raffle = Tools::findOneBy($app, '\Raffles', array('id' => $id));

        $view = array(
            'title' => 'Raffle details',
            'raffle' => $raffle,
            'viewing' => 4,
        );

        return $app['twig']->render('front/raffle.details.twig', $view);
    }
}