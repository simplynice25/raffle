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
		//$raffles = Tools::findBy($app, '\Raffles', array('raffle_status' => 1));
		$users = Tools::findBy($app, '\Profiles', array('view_status' => 5));

		$now = new \DateTime();
		$raffleData = array();
		$sql = "SELECT r FROM models\Raffles r WHERE
				r.raffle_status = 1 AND r.end_date >= :now
				ORDER BY r.created_at DESC";

				$query = $app['orm.em']->createQuery($sql);
		        $query->setParameter("now", $now->format('Y-m-d'));
		        $raffles = $query->getResult();

		$view = array(
			'title' => 'Encoder Dashboard',
			'raffles' => $raffles,
			'users' => $users,
			'message' => $app['session']->getFlashBag()->get('message'),
			'email_message' => $app['session']->getFlashBag()->get('email_message'),
			'loginUrl' => "http://$_SERVER[HTTP_HOST]".$app['url_generator']->generate('login'),
		);

		return $app['twig']->render('encoder/dashboard.twig', $view);
	}

	public function newReceipt(Request $req, Application $app)
	{
		$status = 5;
		$raffleId = (int) $req->get('raffle');

		/* Existing user */
		$user = (int) $req->get('user');
		$or_options = (int) $req->get('or_options');

		/* Non-existing */
		$fullname = $req->get('fullname');
		$email = $req->get('email');
		$email = ( ! empty($email)) ? $req->get('email') : null;

		$receipt_ = $req->get('receipt');

		/* Check if raffle does exist */
		$raffle = Tools::findOneBy($app, '\Raffles', array('id' => $raffleId));
		if (empty($raffle))
		{
            $app['session']->getFlashBag()->set('message', 'invalid_raffle_id');
            return Tools::redirect($app, 'encoder_dashboard');
		}

		/* Check if the receipt # does exist */
		$validateReceipt = Tools::findBy($app, '\EncodedReceipts', array('receipt_number' => $receipt_));
		if ( ! empty($validateReceipt))
		{
            $app['session']->getFlashBag()->set('message', 'receipt_number_exist');
            return Tools::redirect($app, 'encoder_dashboard');
		}

		$receipt = new \models\EncodedReceipts;

		/* Add to user receipts if user id exist */
		if ( ! empty($user))
		{
			$userCheck = Tools::findOneBy($app, '\Users', array('id' => $user, 'view_status' => 5));
			if ( ! empty($userCheck) && $or_options === 1)
			{
				/* If user exist, fill in fullname and email of Encoded Receipts and set view_Status to 2 */
				$profile = Tools::findOneBy($app, '\Profiles', array('user' => $userCheck, 'view_status' => 5));
				$email = $userCheck->getEmail();
				$fullname = $profile->getFirstname() . " " . $profile->getLastname();

				$status = 2;

				$receipt->setUser($userCheck);
			}
		}

		$receipt->setReceiptNumber($receipt_);
		$receipt->setFullName($fullname);
		$receipt->setEmail($email);
		$receipt->setRaffle($raffle);
		$receipt->setViewStatus($status);
		$receipt->setCreatedAt('now');
		$receipt->setModifiedAt('now');

		$app['orm.em']->persist($receipt);
		$app['orm.em']->flush();

		$email = null;
		$emailMsg = 'receipt_email_sent';
		if ( ! is_null($email))
		{
			$emailMsg = 'receipt_email_sent';
			$generatedUrl = "http://$_SERVER[HTTP_HOST]".$app['url_generator']->generate('login');

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