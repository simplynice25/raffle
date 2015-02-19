<?php

namespace user;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

use general\Tools;

class Raffle
{
    public static function routing(Application $app)
    {
        $ui = $app['controllers_factory'];
        
        // Overviews
        $ui->match('/get-winners-consos', 'user\Raffle::getWinnersConsos');
        $ui->match('/get-person', 'user\Raffle::getPerson');
        $ui->match('/get-prizes', 'user\Raffle::getPrizes')->bind('get-prizes');

        $before = function (Request $request, Application $app) {
            return Tools::isLogged($app);
        };

        $ui->before($before);

        return $ui;
    }

    public function getWinnersConsos(Request $req, Application $app)
    {
        $data = array();
        $tables = array('raffle_winners_archive', 'raffle_consos_archive');
        $raffleId = (int) $req->get('id');
        
        if ($raffleId === 0) {
            return false;
        }

        for ($i=0;$i<2;$i++)
        {
            $table = $tables[$i];
            $query = "SELECT GROUP_CONCAT(CONCAT('', user_id, '' )) as ids FROM $table WHERE raffle_id = $raffleId ORDER BY order_number ASC";
            $result = $app['db']->executeQuery($query)->fetchAll();
        
            $data[] = explode(',', $result[0]['ids']);
        }

        return json_encode($data);
    }

    public function getPerson(Request $req, Application $app)
    {
        $profile = null;
        $id = (int) $req->get('id');
        $type = (int) $req->get('type');
        $place = (int) $req->get('place');
        $raffle_ = (int) $req->get('raffle');

        $raffle = Tools::findOneBy($app, '\Raffles', array('id' => $raffle_));
        $model = ($type === 1) ? '\Winners' : '\Consolations';
        $prizes = Tools::findBy($app, $model, array('winner' => $place, 'raffle' => $raffle));

        $user = Tools::findOneBy($app, '\Users', array('id' => $id));
        if ( ! empty($user))
            $profile = Tools::findOneBy($app, '\Profiles', array('user' => $user));

        $view = array(
            'user' => $user,
            'profile' => $profile,
            'prizes' => $prizes,
        );

        return $app['twig']->render('front/include/person.raffle.twig', $view);
    }

    public function getPrizes(Request $req, Application $app)
    {
        $profile = null;
        $place = (int) $req->get('place');
        $type = (int) $req->get('type');
        $raffle_ = (int) $req->get('raffle');

        $raffle = Tools::findOneBy($app, '\Raffles', array('id' => $raffle_));
        $model = ($type === 1) ? '\Winners' : '\Consolations';
        $prizes = Tools::findBy($app, $model, array('winner' => $place, 'raffle' => $raffle));

        $view = array(
            'prizes' => $prizes,
            'heading' => ($type == 1) ? 'Winner Prizes' : 'Conso Prizes'
        );

        return $app['twig']->render('front/include/prize.raffle.twig', $view);
    }
}