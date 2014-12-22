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
		$ui->match('/raffle-delete/{id}', 'admin\AdminProvider::raffleDelete')->bind('raffle_delete');
		$ui->match('/raffle-active/{id}', 'admin\AdminProvider::raffleActive')->bind('raffle_active');

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
        $tab = (!empty($req->get('tab'))) ? (int) $req->get('tab') : 1;
        $raffleStatus = ($tab == 1) ? $tab : 0;
        $raffles = Tools::findBy($app, '\Raffles', array('view_status' => 5, 'raffle_status' => $raffleStatus), array('created_at' => 'DESC'), 10, 0);
        
        $view = array(
            'title' => 'Raffle',
            'raffles' => $raffles,
            'tab' => array($tab, ($tab == 1) ? 'active' : 'pending'),
			'message' => $app['session']->getFlashBag()->get('message'),
        );
        
		return $app['twig']->render('dashboard/raffle.twig', $view);
    }
    
    public function raffleAction(Request $req, Application $app)
    {
        $msg = "raffle_added";
        $id = $req->get('id');
        $title = $req->get('title');
        $start = $req->get('start');
        $end = $req->get('end');
        $winners = (int) $req->get('winners');
        $consolations = (int) $req->get('consolations');
        $tab = (!empty($req->get('tab'))) ? (int) $req->get('tab') : 1;
        
        $raffle = new \models\Raffles;
        if ( ! empty($id))
        {
            $raffle = Tools::findById($app, '\Raffles', $id);
            if (empty($raffle))
            {
                $app['session']->getFlashBag()->set('message', 'raffle_update_failed');
                return Tools::redirect($app, 'raffle_overview');
            } else {
                $msg = "raffle_update_success";
            }
        } else {
            $raffle->setRaffleStatus(0);
            $raffle->setViewStatus(5);
            $raffle->setCreatedAt('now');
        }

        $raffle->setRaffleTitle($title);
        $raffle->setStartDate(new \DateTime($start));
        $raffle->setEndDate(new \DateTime($end));
        $raffle->setWinners($winners);
        $raffle->setConsolations($consolations);
        $raffle->setModifiedAt('now');
		$app['orm.em']->persist($raffle);
		$app['orm.em']->flush();

		$app['session']->getFlashBag()->set('message', $msg);

         return Tools::redirect($app, 'raffle_overview', array('tab' => $tab));
    }
    
    public function raffleDelete(Request $req, Application $app, $id = NULL)
    {
        $tab = (!empty($req->get('tab'))) ? (int) $req->get('tab') : 1;
        $object = Tools::findById($app, '\Raffles', $id);
        
        Tools::delete($app, $object);
        
        $app['session']->getFlashBag()->set('message', 'raffle_deleted');
        
        return Tools::redirect($app, 'raffle_overview', array('tab' => $tab));
    }
    
    public function raffleActive(Request $req, Application $app, $id = NULL)
    {
        $msg = "raffle_activate_failed";
        $tab = (!empty($req->get('tab'))) ? (int) $req->get('tab') : 1;
        
        $object = Tools::findById($app, '\Raffles', $id);
        if ( ! empty($object))
        {
            $msg = "raffle_activate_success";
            
			$datetime1 = new \DateTime('now');
			$datetime2 = $object->getEndDate();
			if ($datetime1 > $datetime2)
            {
                $app['session']->getFlashBag()->set('message', 'cannot_activate_due_to_end_date');
                return Tools::redirect($app, 'raffle_overview', array('tab' => $tab));
            }

            $object->setRaffleStatus(1);
            $object->setModifiedAt('now');
            $app['orm.em']->persist($object);
            $app['orm.em']->flush();
        }
        
        $app['session']->getFlashBag()->set('message', $msg);
        
        return Tools::redirect($app, 'raffle_overview', array('tab' => $tab));
    }
}