<?php

namespace admin;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

use general\Tools;

class Raffle
{
    
    public $active = 1;

    public function raffleStatus($tab)
    {
        switch ($tab)
        {
            case 2:
                $x = array(0, 'pending');
                break;
            case 3:
                $x = array(2, 'archives');
                break;
            case 1:
            default:
                $x = array($tab, 'active');
                break;
        }

        return $x;
    }
    
    public function raffleOverview(Request $req, Application $app)
    {
        $tab = (!empty($req->get('tab'))) ? (int) $req->get('tab') : 1;

        $sessionTab = $app['session']->getFlashBag()->get('tab');
        if ($sessionTab)
        {
            $tab = $sessionTab[0];
        }

        $raffleStatus = self::raffleStatus($tab);
        $raffles = Tools::findBy($app, '\Raffles', array('view_status' => 5, 'raffle_status' => $raffleStatus[0]), array('created_at' => 'DESC'), 10, 0);
        
        $view = array(
            'title' => 'Raffle',
            'raffles' => $raffles,
            'active_tab' => $this->active,
            'tab' => array($tab, $raffleStatus[1]),
			'message' => $app['session']->getFlashBag()->get('message'),
        );
        
		return $app['twig']->render('dashboard/raffle.twig', $view);
    }
    
    public function raffleAction(Request $req, Application $app)
    {
        $msg = "raffle_added";
        $id = $req->get('id');
        $title = $req->get('title');
        $desc = $req->get('desc');
        $start = $req->get('start');
        $end = $req->get('end');
        $winners = (int) $req->get('winners');
        $consolations = (int) $req->get('consolations');
        $tab = (!empty($req->get('tab'))) ? (int) $req->get('tab') : 1;
        $app['session']->getFlashBag()->set('tab', $tab);

        $now = new \DateTime('now');
        $newStart = new \DateTime($start);
        $newEnd = new \DateTime($end);

        if ($newStart > $newEnd || $newEnd > $now)
        {
            $app['session']->getFlashBag()->set('message', 'raffle_date_greater_than');
            return Tools::redirect($app, 'raffle_overview');
        }
        
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

        $raffle->setRaffleDescription($desc);
        $raffle->setRaffleTitle($title);
        $raffle->setStartDate($newStart);
        $raffle->setEndDate($newEnd);
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
    
    public function raffleSearch(Request $req, Application $app)
    {
        $tab = $req->get('tab');
        $keyword = $req->get('keyword');
        $raffleStatus = self::raffleStatus($tab);
        
        $query = "SELECT r FROM models\Raffles r
                  WHERE 
                  r.raffle_status = :status AND
                  r.view_status = 5 AND 
                  (r.raffle_title LIKE :keyword OR
                  r.raffle_description LIKE :keyword OR
                  r.start_date LIKE :keyword OR
                  r.end_date LIKE :keyword)";
        
        $query = $app['orm.em']->createQuery($query);
        $query->setParameter("status", $raffleStatus[0]);
        $query->setParameter("keyword", "%" . $keyword . "%");
        
        $view = array(
            'raffles' => $query->getResult(),
            'issearch' => ' for the keyword ' . $keyword,
            'tab' => array($tab, $raffleStatus[1]),
        );
        
        return $app['twig']->render('dashboard/includes/list.raffle.twig', $view);
    }
}