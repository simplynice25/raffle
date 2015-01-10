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

		$before = function (Request $request, Application $app) {
            return Tools::isLogged($app);
		};

		$ui->before($before);

		return $ui;
	}

	public function index(Request $req, Application $app)
	{
		return "Hello encoder.";
	}
}