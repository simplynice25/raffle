<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct() {
		parent::__construct();
    }

	public function index() {
		$mysession = $this->session->userdata('raffle');
		if($mysession) redirect('home');

		$this->load->view('login');
	}

	public function verify() {
		$mysession = $this->session->userdata('raffle');
		if($mysession) redirect('home');

		$email = $this->input->post("user_email");
		$password = $this->input->post("user_password");
		
		$this->db->from('users');
		$this->db->where('email', $email);
		$this->db->where('password', sha1($password));
		$login = $this->db->get();
		if($login->num_rows() < 1) redirect("login?login=false");

		foreach($login->result() as $row) {
			if($row->view_status == 1)  redirect("login?block=true");
			$sess_array = array(
				'raffle'     => TRUE,
				'id'         => $row->id,
				'full_name'  => $row->full_name,
				'email'      => $row->email,
				'level'      => $row->level,
			);
		}
		
		$this->session->set_userdata('raffle', $sess_array);
		redirect("home");
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
