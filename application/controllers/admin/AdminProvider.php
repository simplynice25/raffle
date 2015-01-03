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
    
    public $active = 0;
    
	public static function routing(Application $app)
	{
		$ui = $app['controllers_factory'];
        
		// Overviews
		$ui->match('/', 'admin\AdminProvider::index')->bind('dashboard');

        // Raffles
		$ui->match('/raffle-overview', 'admin\Raffle::raffleOverview')->bind('raffle_overview');
		$ui->match('/raffle-action', 'admin\Raffle::raffleAction')->bind('raffle_action');
		$ui->match('/raffle-delete/{id}', 'admin\Raffle::raffleDelete')->bind('raffle_delete');
		$ui->match('/raffle-active/{id}', 'admin\Raffle::raffleActive')->bind('raffle_active');
		$ui->match('/raffle-search', 'admin\Raffle::raffleSearch')->bind('raffle_search');
        
        // Winners
		$ui->match('/winners-overview', 'admin\Winners::winnersOverview')->bind('winners_overview');
		$ui->match('/winner-has-prize', 'admin\Winners::winnerHasPrize');
        
        // Prizes
		$ui->match('/prizes-overview', 'admin\Prizes::prizesOverview')->bind('prizes_overview');
		$ui->match('/prizes-action', 'admin\Prizes::prizesAction')->bind('prizes_action');

		return $ui;
	}

	public function index(Request $req, Application $app)
	{
        $view = array(
            'title' => 'Dashboard',
            'active_tab' => $this->active,
			'message' => $app['session']->getFlashBag()->get('message'),
        );
        
		return $app['twig']->render('dashboard/index.twig', $view);
	}
}