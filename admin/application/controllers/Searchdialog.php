<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Searchdialog extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('Modul_Model');
	}

	public function index()
	{
		$param = array();
		$this->load->view('index.php', $param);
	}
	
	public function dialog(){
		$data = array(); $key="";
		
		$target = $this->input->post('target');
		$hidecolumns = $this->input->post('hidecolumns');
		$multiselect = $this->input->post('multiselect');
		$callback = $this->input->post('callback');
		$cancel = $this->input->post('cancel');
		
		if($target == "package"){
			$tag = $this->input->post('tag');
			$key = "ID";
			$data = $this->Modul_Model->read("select id as 'ID', package_name, tag_id from package where  tag_id = '".$tag."' and status = 1");
			for($i = 0; $i < count($data); $i++){				
				$data[$i]["icon_on"] = '<img src="' . CLIENTICONIMAGESRC . 'package/' . $data[$i]["ID"] . '/' . $data[$i]["ID"] . '_on.png?'.date("Ymdhis").'" height="30" />';
				$data[$i]["icon_off"] = '<img src="' . CLIENTICONIMAGESRC . 'package/' . $data[$i]["ID"] . '/' . $data[$i]["ID"] . '_off.png?'.date("Ymdhis").'" height="30" />';
				$data[$i]["icon_marker"] = '<img src="' . CLIENTICONIMAGESRC . 'package/' . $data[$i]["ID"] . '/' . $data[$i]["ID"] . '_marker.png?'.date("Ymdhis").'" height="30" />';
			}
		}
		
		
		$param = array(
			'key' => $key,
			'data' => $data,
			'multiselect' => $multiselect,
			'callback' => $callback,
			'hidecolumns' => $hidecolumns,
			'cancel' => $cancel
		);
		$this->load->view('searchdialog.php', $param);
	}
	
	public function data(){
		$target	= $this->input->post('target'); //Lokasi
		$query	= ""; $data = array();
		if($target == "lokasi"){
			$data = $this->Modul_Model->read("select id as 'key', sentra as 'display' from u_sentra order by sentra");
		}
		
		echo json_encode($data);
	}
}
