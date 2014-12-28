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
    
    public $active = 2;
    
    public function winnerOverview(Request $req, Application $app)
    {
       $raffles = Tools::findBy($app, '\Raffles', array('view_status' => 5, 'raffle_status' => 1), array('created_at' => 'DESC'));
        
        $view = array(
            'title' => 'Winners',
            'raffles' => $raffles,
            'active_tab' => $this->active,
			'message' => $app['session']->getFlashBag()->get('message'),
        );
        
		return $app['twig']->render('dashboard/winners.twig', $view);
    }
    
    public function winnerAction(Request $req, Application $app)
    {
        $msg = "prize_added";
        $id = (int) $req->get('id');
        $raffle = (int) $req->get('raffle');
        $winner = (int) $req->get('winner');
        $title = $req->get('title');
        $desc = $req->get('desc');
        $amount = (int) $req->get('amount');
        $image = $req->files->get('image');
        $imageExts = array('jpeg', 'jpg', 'gif', 'png');
        
        // Check if the raffle does exist
        $raffle = Tools::findById($app, '\Raffles', $raffle);
        if (empty($raffle))
        {
            $app['session']->getFlashBag()->set('message', 'prize_update_failed');
            return Tools::redirect($app, 'winner_overview');
        }
        
        // Check if to be edit or new
        if ( ! empty($id))
        {
            $prize = Tools::findById($app, '\Prizes', $id);
            if (empty($prize))
            {
                $app['session']->getFlashBag()->set('message', 'prize_update_failed');
                return Tools::redirect($app, 'winner_overview');
            } else {
                $msg = "prize_update_success";
            }
        } else {
            $prize = new \models\Prizes;
            $prize->setPrizeType(0);
            $prize->setViewStatus(5);
            $prize->setCreatedAt('now');
        }
        
        if(isset($image) && ! empty($image))
        {
            // Delete old prize image
            if ( ! empty($id))
            {
                @unlink(UPLOAD_DIR.$prize->getPrizeImage());
            }
            
            $fileName = $image->getClientOriginalName();
            $sizeInByte = $image->getClientSize();
            $extension = strtolower($image->getClientOriginalExtension());
            if (empty($extension))
            {
                $extension = strtolower($image->guessExtension());
            }
                
            if ( ! in_array($extension, $imageExts))
            {
                $app['session']->getFlashBag()->set('message', 'invalid_file_extension');
                return Tools::redirect($app, 'winner_overview');
            }
                
            if (file_exists(UPLOAD_DIR.$fileName))
            {
                $withoutExt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $fileName);
                $fileName = $withoutExt ."_". uniqid(). "." . $extension;
            }
            
            $image->move(UPLOAD_DIR, $fileName);
        }
        
        $prize->setRaffle($raffle);
        $prize->setPrizePlace($winner);
        $prize->setPrizeTitle($title);
        $prize->setPrizeDescription($desc);
        $prize->setPrizeAmount($amount);
        $prize->setPrizeImage($fileName);
        $prize->setModifiedAt('now');
        
		$app['orm.em']->persist($prize);
		$app['orm.em']->flush();

		$app['session']->getFlashBag()->set('message', $msg);

         return Tools::redirect($app, 'winner_overview');
    }
    
    public function winnerHasPrize(Request $req, Application $app)
    {
        $data = null;
        $raffleId = $req->get('raffle');
        $winners = $req->get('winners');
        
        for ($i=1;$i<$winners;$i++)
        {
            $raffle = Tools::findById($app, '\Raffles', $raffleId);
            $hasPrize = Tools::findOneBy($app, '\Prizes', array('raffle' => $raffle, 'prize_place' => $i, 'prize_type' => 0, 'view_status' => 5));
            
            $data .= (!empty($hasPrize)) ? 
                "<option value='$i' data-hasprize='0'>$i ( Has prize )</option>" :
                "<option value='$i' data-hasprize='1'>$i</option>";
        }
        
        return $data;
    }
}