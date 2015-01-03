<?php

namespace admin;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

use general\Tools;

class Prizes
{
    public $active = 2;

    public function prizesOverview(Request $req, Application $app)
    {
        $prizes = Tools::findBy($app, '\Prizes', array('view_status' => 5), array('created_at' => 'DESC'));
        
        $view = array(
            'title' => 'Prizes',
            'active_tab' => $this->active,
            'prizes' => $prizes,
			'message' => $app['session']->getFlashBag()->get('message'),
        );
        
        return $app['twig']->render('dashboard/prizes.twig', $view);
    }
    
    public function prizesAction(Request $req, Application $app)
    {
        $msg = "prize_added";
        $link = "prizes_overview";
        $id = $req->get('id');
        $title = $req->get('title');
        $desc = $req->get('desc');
        $image = $req->files->get('image');
        
        $imageExts = array('jpeg', 'jpg', 'gif', 'png');
        
        if ( ! empty($id))
        {
            $prize = Tools::findById($app, '\Prizes', $id);
            if (empty($prize))
            {
                $app['session']->getFlashBag()->set('message', 'prize_update_failed');
                return Tools::redirect($app, $link);
            }
            $msg = 'prize_update_success';
        } else {
            $prize = new \models\Prizes;
            $prize->setViewStatus(5);
            $prize->setCreatedAt('now');
        }
        
        if(isset($image) && ! empty($image))
        {
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
                return Tools::redirect($app, $link);
            }
                
            if (file_exists(UPLOAD_DIR.$fileName))
            {
                $withoutExt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $fileName);
                $fileName = $withoutExt ."_". uniqid(). "." . $extension;
            }
            
            $image->move(UPLOAD_DIR, $fileName);
            
            $prize->setPrizeImage($fileName);
        }

        $prize->setPrizeTitle($title);
        $prize->setPrizeDesc($desc);
        $prize->setModifiedAt('now');
        
		$app['orm.em']->persist($prize);
		$app['orm.em']->flush();

		$app['session']->getFlashBag()->set('message', $msg);

         return Tools::redirect($app, $link);
    }
}