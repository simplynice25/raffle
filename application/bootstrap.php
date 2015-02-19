<?php

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\Loader\YamlFileLoader;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

use general\Tools;

define('UPLOAD_DIR', dirname(__FILE__) . '/../web/upload/');
define('APP_DIR', dirname(__FILE__) . '/plugins/');

include_once('settings.php');
require_once(__DIR__ . '/../vendor/autoload.php');

$app_debug = TRUE;
$app = new Silex\Application();

$app['debug'] = $app_debug;
$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__ . "/models"), $app_debug);

// Registrations
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider(), $twig_conf);
$app->register(new Silex\Provider\MonologServiceProvider(), $monolog_conf);
$app->register(new DerAlex\Silex\YamlConfigServiceProvider($database_conf));
$app->register(new Silex\Provider\TranslationServiceProvider(), $trans_conf);
$app['translator'] = $app->share($app->extend('translator', function($translator, $app) {
	 $translator->addLoader('yaml', new YamlFileLoader());
	 $translator->addResource('yaml', __DIR__.'/../src/locales/en.yml', 'en');
	 return $translator;
}));

$app["twig"] = $app->share($app->extend("twig", function (\Twig_Environment $twig, Silex\Application $app) {
    $twig->addExtension(new custom_twig\Captcha($app));
    $twig->addExtension(new custom_twig\DueRaffles($app));
    $twig->addExtension(new custom_twig\Suffix($app));

    return $twig;
}));

$app->register(new Silex\Provider\DoctrineServiceProvider(), array( 'db.options' => $app['config']['database'] ));
$app['orm.em'] = EntityManager::create($app['db'], $config);
$app['security.encoder.digest'] = $app->share(function ($app) {
	return new MessageDigestPasswordEncoder('sha1', false, 1);
});

$app->register(new Silex\Provider\SessionServiceProvider());
$app['session.storage.handler'] = null;

$app->register(new Silex\Provider\SecurityServiceProvider(), array(
	'security.firewalls' => array(
		'admin' => array(
			'pattern'   => '^.*$',
			'anonymous' => true,
			'form'      => array('login_path' => '/', 'check_path' => '/login_check'),
			'logout'    => array('logout_path' => '/logout'),
			'users'     => $app->share(function() use($app) {
				return new plugins\UserProvider($app);
			})
		)
	),
	'security.access_rules' => array(
        //array('/u', 'IS_AUTHENTICATED_ANONYMOUSLY'),
        //array('^/raffle/*', 'IS_AUTHENTICATED_ANONYMOUSLY'),
		array('^/u/', 'ROLE_USER'),
		array('^/encoder/', 'ROLE_ENCODER'),
		array('^/dashboard/', 'ROLE_ADMIN'),
	),
	'security.role_hierarchy' => array(
	    'ROLE_ADMIN' => array('ROLE_USER', 'ROLE_ENCODER'),
	)
));

$app->register(new Silex\Provider\SwiftmailerServiceProvider());
$app['swiftmailer.options'] = $mail_conf;

$app['locale'] = 'en';

/* Get raffles for navbar */
	$now = new \DateTime();
	$raffleData = array();
	$sql = "SELECT r FROM models\Raffles r WHERE
			r.raffle_status = 1 AND r.end_date >= :now
			ORDER BY r.created_at DESC";

			$query = $app['orm.em']->createQuery($sql);
	        $query->setParameter("now", $now->format('Y-m-d'));
	        $raffles = $query->getResult();

	foreach ($raffles as $raffle)
	{
		$raffleData[] = array($raffle->getId(), $raffle->getRaffleTitle());
	}

	$app['session']->set('raffleNavs', $raffleData);
	
	unset($sql);
	unset($raffles);
/* End of getting raffles for navbar */

/* Get due raffles today */
    $qb = $app['orm.em']->createQueryBuilder();
    $qb->select('count(table)')
        ->from('models\Raffles', 'table')
        ->where('table.raffle_status = 1')
        ->andWhere('table.end_date = :now')
        ->setParameter('now', $now->format('Y-m-d'));

    $count = $qb->getQuery()->getSingleScalarResult();
    
    $app['session']->set('raffleDue', $count);
/* End of getting due raffles today */

$app->mount('/', user\UserProvider::routing($app));
$app->mount('/r', user\Raffle::routing($app));
$app->mount('/login', general\UserBridge::routing($app));
$app->mount('/encoder', encoder\EncoderProvider::routing($app));
$app->mount('/dashboard', admin\AdminProvider::routing($app));

$app->get('/thumbnails/{params}/{q}', 'general\Tools::thumb')->bind('img-thumb');

//echo $app['security.encoder.digest']->encodePassword('p@55w0rd','');

/* End of bootstrap file */