<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logout extends CI_Controller {

	public function __construct() {
		parent::__construct();
    }

	public function index() {
		$mysession = $this->session->userdata('raffle');
		if (!$mysession) redirect('login');
		
		$this->session->unset_userdata('raffle');
		redirect('login');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */