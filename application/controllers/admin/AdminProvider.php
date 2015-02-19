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
        $ui->match('/raffle-generate', 'admin\Raffle::raffleGenerate')->bind('raffle_generate');
        
        // Winners
		$ui->match('/winners-overview', 'admin\Winners::winnersOverview')->bind('winners_overview');
		$ui->match('/winner-prizes', 'admin\Winners::winnerPrizes');
		$ui->match('/add-prize', 'admin\Winners::addPrize');
        
        // Winners
		$ui->match('/consolations-overview', 'admin\Consolations::consolationsOverview')->bind('consolations_overview');
		$ui->match('/conso-prizes', 'admin\Consolations::consoPrizes');
		$ui->match('/add-conso-prize', 'admin\Consolations::addConsoPrize');

		// Users
		$ui->match('/users-overview', 'admin\Users::usersOverview')->bind('users_overview');
		$ui->match('/user-action', 'admin\Users::userAction')->bind('user_action');
		$ui->match('/users-search', 'admin\Users::usersSearch')->bind('users_search');
		$ui->match('/user-role', 'admin\Users::userRole')->bind('user_role');
		$ui->match('/admin-reg-user', 'admin\Users::registrationProcess')->bind('admin_reg_user');
		$ui->match('/admin-user-activation/{uid}', 'admin\Users::activationProcess')->bind('admin_user_activation');
        
        // Prizes
		$ui->match('/prizes-overview', 'admin\Prizes::prizesOverview')->bind('prizes_overview');
		$ui->match('/prizes-action', 'admin\Prizes::prizesAction')->bind('prizes_action');

		$before = function (Request $request, Application $app) {
            return Tools::isLogged($app);
		};

		$ui->before($before);

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