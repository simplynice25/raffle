<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

	public function __construct() {
		parent::__construct();
    }

	public function index() {
		$mysession = $this->session->userdata('raffle');
		
		$data = array(
			'session' => $mysession
		);

		$this->load->view('include/home', $data);
	}
}