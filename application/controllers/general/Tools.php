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
    
    public static function findById(Application $app, $model, $ids)
    {
        return $app['orm.em']->find('models' . $model, $ids);
    }
	
	public static function redirect(Application $app, $link, $params = NULL)
	{
        if ( ! is_null($params))
        {
		    $url = $app['url_generator']->generate($link, $params);
        }
        else
        {
		    $url = $app['url_generator']->generate($link);
        }
        
		return $app->redirect($url);
	}

	public static function autoLogin(Application $app, $email = NULL)
    {
		$userProvider = new UserProvider($app);
		$user = $userProvider->loadUserByUsername($email, 1);
		$app['security']->setToken(new \Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken($user, $user->getPassword(), 'default', $user->getRoles()));
        
        return TRUE;
	}

    public static function isLogged(Application $app, $action = NULL)
    {
        $username = $role = NULL;
        $token = $app['security']->getToken();

        if( null !== $token ) {
            if (is_object($token->getUser()))
            {
                $user = $token->getUser();
                $roles = $user->getRoles();
                $username = $user->getUsername();
                
                if ( ! is_null($action))
                {
                    return self::findOneBy($app, '\Users', array('view_status' => 5));
                }
                
                $role = $roles[0];
            }
        }

        if (empty($username) || empty($role))
        {
            $err_ = $app['session']->getFlashBag()->get('err_');
           // print_r($err_);
           // exit;
            if (! empty($err_) && !isset($err_[1]) || empty($err_))
            {
                return null;
            }
            
            $app['session']->getFlashBag()->set('err_', ( ! empty($err_) && isset($err_[0])) ? $err_ : null);
            return self::redirect($app, 'login');
        }
        
        $app['session']->set('credentials', array($username, $role));
        
        return NULL;
    }
    
    public static function delete($app, $object)
    {
        $object->setViewStatus(1);
        $object->setModifiedAt('now');
		$app['orm.em']->persist($object);
		$app['orm.em']->flush();
        
        return TRUE;
    }
    
    public function thumb(Request $request, Application $app, $params = NULL, $q = NULL)
    {
        $res = preg_match("^([0-9]+)x([0-9]+)([a-z]*)^i", $params , $matches);
        
        $_GET['src'] = $app['request']->getBaseUrl() . '/upload/' . $q;
        
        $file = UPLOAD_DIR . $q;
        
        if ( ! file_exists($file))
		{
			$app->abort(404);
        }
        
		$_GET['w'] = $matches[1];
		$_GET['h'] = $matches[2];
		$_GET['a'] = $matches[3];
        $_GET['q'] = 100;
        
        if (isset($matches[3]) && ! empty($matches[3]))
        {
            switch ($matches[3])
            {
                case "f":
                case "fit":
                    $_GET['zc'] = 0;
                    break;
                case "b":
                case "borders":
                    $_GET['zc'] = 2;
                    break;
                case "r":
                case "resize":
                    $_GET['zc'] = 3;
                    break;
                case "c":
                case "crop":
                default:
                    $_GET['zc'] = 1;
                    break;
            }
        }
        
        $_SERVER['QUERY_STRING'] = http_build_query($_GET);
        
        require_once(APP_DIR . "TimThumb/timthumb.php");
    }
}