<?php
namespace general;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class UserLogin {

	public static function routing(Application $app)
	{
		$routing = $app['controllers_factory'];
		$routing->match('/', 'general\UserLogin::index')->bind('login');
		$routing->match('/pre-registration', 'general\UserLogin::preRegistration')->bind('pre-registration');
		$routing->match('/registration', 'general\UserLogin::registration')->bind('registration');
		$routing->match('/registration-process', 'general\UserLogin::registrationProcess')->bind('registration-process');

		return $routing;
	}	

	public function index(Request $req, Application $app)
	{
		$role = NULL;
		$token = $app['security']->getToken();

		if (null !== $token) {
		    $user = $token->getUser();
		}

		if ($app['security']->isGranted("ROLE_ADMIN")) {
			return $app->redirect($app['url_generator']->generate("user_overview"));
		}

		$view = array(
			'title' => 'Raffle',
			'err_'  => $app['session']->getFlashBag()->get('err_'),
			'error' => $app['security.last_error']($req),
			'last_username' => $app['session']->get('_security.last_username'),
		);

		return $app['twig']->render('general/index.twig', $view);
	}
	
	public function preRegistration(Request $req, Application $app)
	{
		/* TODO: Validate OR # */
		$preRegData = array(
			'firstname' => $req->get('firstname'),
			'lastname' => $req->get('lastname'),
			'or_num' => $req->get('or_num'),
		);

		$app['session']->set('registration', $preRegData);

		$url = $app['url_generator']->generate('registration');

		return $app->redirect($url);
	}
	
	public function registration(Request $req, Application $app)
	{
		$preReg = $app['session']->get('registration');
		
		$view = array(
			'title' => 'Registration',
			'firstname' => $preReg['firstname'],
			'lastname' => $preReg['lastname'],
			'or_num' => $preReg['or_num'],
		);

		return $app['twig']->render('general/registration.twig', $view);
	}
	
	public function registrationProcess(Request $req, Application $app)
	{
		/* TODO: Check for email duplicate */
		$preReg = $app['session']->get('registration');
		
		$firstname = $preReg['firstname'];
		$lastname = $preReg['lastname'];
		$orNum = $preReg['or_num'];
		
		$email = $req->get('email');
		$password = $req->get('password');
		$mobile = $req->get('mobile');
		$birthday = $req->get('birthday');
		$address = $req->get('address');
		
		$password = $app['security.encoder.digest']->encodePassword($password, '');
		
		$user = new \models\Users;
		$user->setEmail($email);
		$user->setPassword($password);
		$user->setRoles('ROLE_USER');
		$user->setViewStatus(5);
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
		
		$receipt = new \models\Receipts;
		$receipt->setReceiptNumber($orNum);
		$receipt->setUser($user);
		$receipt->setViewStatus(5);
		$receipt->setCreatedAt("now");
		$receipt->setModifiedAt("now");
		$app['orm.em']->persist($receipt);
		$app['orm.em']->flush();
		
		return "done";
	}
}