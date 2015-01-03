<?php

namespace admin;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

use general\Tools;

class Winners
{
    
    public $active = 3;
    
    public function winnersOverview(Request $req, Application $app)
    {
       $raffles = Tools::findBy($app, '\Raffles', array('view_status' => 5, 'raffle_status' => 1), array('created_at' => 'DESC'));
       $prizes = Tools::findBy($app, '\Prizes', array('view_status' => 5), array('created_at' => 'DESC'));
        
        $view = array(
            'title' => 'Winners',
            'raffles' => $raffles,
            'prizes' => $prizes,
            'isprizes' => self::isPrizeAdded($req, $app, $prizes, (!empty($raffles))?$raffles{0}:null, 1),
            'active_tab' => $this->active,
			'message' => $app['session']->getFlashBag()->get('message'),
        );
        
		return $app['twig']->render('dashboard/winners.twig', $view);
    }
    
    public function isPrizeAdded(Request $req, Application $app, $prizes, $raffle = NULL, $winner = 1)
    {
        $isPrize = array();
        
        foreach ($prizes as $prize)
        {
            $qb = $app['orm.em']->createQueryBuilder();
            $qb->select('count(table)')
            ->from('models\Winners', 'table')
            ->where('table.raffle = :raffle')
            ->andWhere('table.prize = :prize')
            ->andWhere('table.winner = :winner')
            ->setParameter('raffle', $raffle)
            ->setParameter('prize', $prize)
            ->setParameter('winner', $winner);
    
            $count = $qb->getQuery()->getSingleScalarResult();
            $isPrize[] = ($count == 0) ? 0 : 1;
        }
        
        return $isPrize;
    }
}