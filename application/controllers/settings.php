<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends CI_Controller {

	public function __construct() {
		parent::__construct();
    }

	public function index() {
		$mysession = $this->session->userdata('logged');
		if(!$mysession) redirect('home');

		if($mysession["user_level"] == 0 || !$this->uri->segment(2)) {
			$action = $mysession["user_id"];
		} else {
			$action = $this->uri->segment(2);
		}		
		$this->db->where("user_id", $action);
		$user = $this->db->get("users");
		
		$data = array(
			'session' => $mysession,
			'user'    => $user->result(),
			'action'  => $action
		);

		$this->load->view('user/settings', $data);
	}
	
	public function update() {
		$mysession = $this->session->userdata('logged');
		if(!$mysession) redirect('home');
		
		$action = $this->input->post("action");

		$data = array(
			'user_name' => $this->input->post("user_name"),
			'user_email' => $this->input->post("user_email"),
			'user_address' => $this->input->post("user_address"),
			'user_birthday' => $this->input->post("user_birthday")
		);

		if(!empty($action)) {
			$data['user_password'] = sha1($this->input->post("user_password"));
		}

		$this->db->where("user_id", $action);
		$this->db->update("users", $data);

		if($action == $mysession["user_id"]) {
			redirect("settings?update=true");
		} else {
			redirect("settings/" . $action . "?update=true");
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
