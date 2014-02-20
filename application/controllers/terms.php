
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Terms extends CI_Controller {

	public function __construct() {
		parent::__construct();
    }

	public function index() {
		$mysession = $this->session->userdata('raffle');
		if(!$mysession) redirect('login');
		
		$data = array(
			'session' => $mysession
		);

		$this->load->view('include/terms', $data);
	}
}