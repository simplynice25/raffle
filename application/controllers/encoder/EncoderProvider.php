<?php

namespace encoder;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

use general\Tools;

class EncoderProvider
{
    
    public $active = 0;
    
	public static function routing(Application $app)
	{
		$ui = $app['controllers_factory'];
        
		// Overviews
		$ui->match('/', 'encoder\EncoderProvider::index')->bind('encoder_dashboard');
		$ui->match('/new-receipt', 'encoder\EncoderProvider::newReceipt')->bind('new_receipt');

		$before = function (Request $request, Application $app) {
            return Tools::isLogged($app);
		};

		$ui->before($before);

		return $ui;
	}

	public function index(Request $req, Application $app)
	{
		$raffles = Tools::findBy($app, '\Raffles', array('raffle_status' => 1));

		$view = array(
			'title' => 'Encoder Dashboard',
			'raffles' => $raffles,
			'message' => $app['session']->getFlashBag()->get('message'),
			'email_message' => $app['session']->getFlashBag()->get('email_message'),
			'loginUrl' => "http://$_SERVER[HTTP_HOST]/raffle/web/login",
		);

		return $app['twig']->render('encoder/dashboard.twig', $view);
	}

	public function newReceipt(Request $req, Application $app)
	{
		$raffleId = (int) $req->get('raffle');
		$fullname = $req->get('fullname');
		$email = ( ! empty($req->get('email'))) ? $req->get('email') : null;
		$receipt_ = $req->get('receipt');

		$raffle = Tools::findOneBy($app, '\Raffles', array('id' => $raffleId));
		if (empty($raffle))
		{
            $app['session']->getFlashBag()->set('message', 'invalid_raffle_id');
            return Tools::redirect($app, 'encoder_dashboard');
		}

		// Remove " 'raffle' => $raffle " if you want one receipt for one raffle only
		// 'raffle' => $raffle, 
		$validateReceipt = Tools::findBy($app, '\EncodedReceipts', array('receipt_number' => $receipt_));
		if ( ! empty($validateReceipt))
		{
            $app['session']->getFlashBag()->set('message', 'receipt_number_exist');
            return Tools::redirect($app, 'encoder_dashboard');
		}

		$receipt = new \models\EncodedReceipts;
		$receipt->setReceiptNumber($receipt_);
		$receipt->setFullName($fullname);
		$receipt->setEmail($email);
		$receipt->setRaffle($raffle);
		$receipt->setViewStatus(5);
		$receipt->setCreatedAt('now');
		$receipt->setModifiedAt('now');

		$app['orm.em']->persist($receipt);
		$app['orm.em']->flush();

		if ( ! is_null($email))
		{
			$emailMsg = 'receipt_email_sent';
			$generatedUrl = "http://$_SERVER[HTTP_HOST]/raffle/web/login";

			$html = $app['twig']->render('encoder/includes/email.receipt.twig', array( "url" => $generatedUrl, 'name' => $fullname ));
	
			$message = \Swift_Message::newInstance()
						->setSubject("Raffle Registration")
						->setFrom(array("admin@raffle.com" => "Raffle Registration"))
						->setTo(array($email))
						->addPart( $html , 'text/html' );
	
			if ( ! $app['mailer']->send($message))
			{
				$emailMsg = 'receipt_email_failed';
			}
		}

        $app['session']->getFlashBag()->set('message', 'receipt_added');
        $app['session']->getFlashBag()->set('email_message', $emailMsg);
        return Tools::redirect($app, 'encoder_dashboard');
	}
}