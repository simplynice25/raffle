<?php

namespace admin;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

use general\Tools;

class AdminProvider
{
	public static function routing(Application $app)
	{
		$ui = $app['controllers_factory'];
        
		// Overviews
		$ui->match('/', 'admin\AdminProvider::index')->bind('dashboard');
		$ui->match('/raffle-overview', 'admin\AdminProvider::raffleOverview')->bind('raffle_overview');
		$ui->match('/raffle-action', 'admin\AdminProvider::raffleAction')->bind('raffle_action');

		return $ui;
	}

	public function index(Request $req, Application $app)
	{
        $view = array(
            'title' => 'Dashboard',
			'message' => $app['session']->getFlashBag()->get('message'),
        );
        
		return $app['twig']->render('dashboard/index.twig', $view);
	}
    
    public function raffleOverview(Request $req, Application $app)
    {
        $raffles = Tools::findBy($app, '\Raffles', array('view_status' => 5));
        $view = array(
            'title' => 'Raffle',
            'raffles' => $raffles,
			'message' => $app['session']->getFlashBag()->get('message'),
        );
        
		return $app['twig']->render('dashboard/raffle.twig', $view);
    }
    
    public function raffleAction(Request $req, Application $app)
    {
        $msg = "raffle_added";
        $title = $req->get('title');
        $start = $req->get('start');
        $end = $req->get('end');
        $winners = (int) $req->get('winners');
        $consolations = (int) $req->get('consolations');
        
        $raffle = new \models\Raffles;
        $raffle->setRaffleTitle($title);
        $raffle->setStartDate(new \DateTime($start));
        $raffle->setEndDate(new \DateTime($end));
        $raffle->setWinners($winners);
        $raffle->setConsolations($consolations);
        $raffle->setViewStatus(5);
        $raffle->setCreatedAt('now');
        $raffle->setModifiedAt('now');
		$app['orm.em']->persist($raffle);
		$app['orm.em']->flush();

		$app['session']->getFlashBag()->set('message', $msg);

         return Tools::redirect($app, 'raffle_overview');
    }
}