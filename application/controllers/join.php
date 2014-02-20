<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Join extends CI_Controller {

	public function __construct() {
		parent::__construct();
    }

	public function index() {
		$mysession = $this->session->userdata('raffle');
		
		$data = array(
			'session' => $mysession
		);

		$this->load->view('include/join', $data);
	}
	
	public function process() {
		$data = array(
			'receipt' => $this->input->post("receipt"),
			'last_name' => $this->input->post("last_name"),
			'first_name' => $this->input->post("first_name"),
			'email' => $this->input->post("email"),
			'phone' => $this->input->post("phone"),
			'mobile' => $this->input->post("mobile"),
			'address' => $this->input->post("address"),
			'message' => $this->input->post("message"),
			'view_status' => 0,
			'date_created' => date("Y-m-d")
		);
		
		$this->db->insert("entry", $data);
		redirect("join?entry=true");
	}
}