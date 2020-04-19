<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
	
	function __construct(){
		parent::__construct();
		$this->load->model('Modul_Model');
		$this->load->library('API');
		$this->load->library('FileUpload');
	}
	
	public function index()
	{
		$this->load->view('login');
	}
	
	public function post(){
		header("location: ../dashboard");
	}
}
