<?php

namespace plugins;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Silex\Application;

class UserProvider implements UserProviderInterface
{
	private $app;
	
	public function __construct(Application $app) {
		$this->app = $app;
	}

	public function loadUserByUsername($username, $byPassCaptcha = NULL)
    {
        $login_url = $this->app['url_generator']->generate('login');

		if (isset($_POST["recaptcha_response_field"]) && is_null($byPassCaptcha))
		{
            $islogin = true;
			$privatekey = "6Ldy3eISAAAAAHtuTiDny2buEpUOVcM6J_YUXyD0";
			require_once(__DIR__ . '/recaptcha/recaptchalib.php');
	
			$resp = recaptcha_check_answer ($privatekey,
											$_SERVER["REMOTE_ADDR"],
											$_POST["recaptcha_challenge_field"],
											$_POST["recaptcha_response_field"]);

			if ( ! $resp->is_valid) {
				$error = "The CAPTCHA wasn't entered correctly. Please try it again.";
				$this->app['session']->getFlashBag()->set('err_', array($error, 'login_passed'));

				return $this->app->redirect($login_url);
			}
		}

		$user = $this->app['orm.em']->getRepository('models\Users')->findOneBy(array('email' => $username, 'view_status' => 5));

		if ( ! is_object($user))
        {
            $this->app['session']->getFlashBag()->set('login', array('fail'));
			throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
		}

        if (isset($islogin))
            $this->app['session']->getFlashBag()->set('role', array($user->getRoles()));

        $this->app['session']->getFlashBag()->set('err_', array('null', 'login_passed'));
		return new User($user->getEmail(), $user->getPassword(), explode(',', $user->getRoles()), true, true, true, true);
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }
 
        return $this->loadUserByUsername($user->getUsername());
    }
 
    public function supportsClass($class)
    {
        return $class === 'Symfony\Component\Security\Core\User\User';
    }
}