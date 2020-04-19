<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bank extends CI_Controller {
	
	function __construct(){
		parent::__construct();
		$this->load->model('Modul_Model');
		$this->load->library('API');
		$this->load->library('FileUpload');
	}

	public function index()
	{
		$param = array(
			'title' => $this->my('title'), 
			'body' => $this->my('bodyPath'), 
			'getURL' => $this->my('getURL'), 
			'addURL' => $this->my('addURL'),
			'editURL' => $this->my("editURL") 
		);
		$this->load->view('index.php', $param);
	}
	
	public function get(){		
		$result = $this->Modul_Model->read($this->my("getQuery"));
		echo json_encode($result);
		
	}

	public function add()
	{				
		$param = array(
			'title' => $this->my("title"), 
			'body' => $this->my("formPath"), 
			'data' => false,
			'form' => "add", 
			'postURL' => $this->my("postURL"), 
			'listFiles' => null
		);
		$this->load->view('index.php', $param);
	}

	public function edit()
	{		
		$id = $this->input->get('key');
		$data = $this->Modul_Model->read("select * from bank where id = '".$id."'");
		
		$param = array(
			'key' => $id, 
			'title' => $this->my("title"), 
			'body' => $this->my("formPath"), 
			'data' => $data,
			'form' => "edit", 
			'postURL' => $this->my("postURL"), 
			'listFiles' => null
		);
		$this->load->view('index.php', $param);
	}
	
	public function post()
	{	
		$data = $this->my("postData");
		
		$key = ""; $msg = "";
		
		if($this->input->post('crud') == "add"){			
			$msg = "Data berhasil disimpan";
			$result = $this->Modul_Model->insert($this->my("tableName"), $data);
		}
		else if($this->input->post('crud') == "edit"){
			$msg = "Data berhasil diubah";
			$key = $this->input->post('key');
			$result = $this->Modul_Model->update($this->my("tableName"), $data, "id", $key);
		}
		else if($this->input->post('crud') == "delete"){
			$msg = "Data berhasil dihapus";
			$key = $this->input->post('key');
			$result = $this->Modul_Model->execute("update " . $this->my("tableName") . 
						" set status = 0, date_saved = '".$this->api->getFullDateTime()."', user_saved = '".$this->my("userlogin")."'
						where id = '".$key."' ");
		}
		
		if($result){			
			echo '[{"status":"1", "msg":"'.$msg.'"}]';
		}
		else{
			echo '[{"status":"0", "msg":"Maaf, Proses Gagal. '.$result.'"}]';
		}
	}
	
	public function my($str){
		if($str == "title"){
			return 'Bank';
		}
		else if($str == "tag"){
			return 'bank';
		}
		else if($str == "bodyPath"){
			return '' . $this->my("tag");
		}
		else if($str == "getQuery"){
			$query = "SELECT id as 'key', bank as '1', atas_nama as '2', nomor_rekening as '3'
					FROM bank where status = 1 order by id";
					
			return $query;
		}
		else if($str == "formPath"){
			return ''.$this->my("tag").'/edit.php';
		}
		else if($str == "addURL"){
			return base_url().$this->my("tag").'/add';
		}
		else if($str == "editURL"){
			return base_url().$this->my("tag").'/edit';
		}
		else if($str == "getURL"){
			return base_url().$this->my("tag").'/get';
		}
		else if($str == "postURL"){
			return base_url().$this->my("tag").'/post';
		}
		else if($str == "tableName"){
			return "bank";
		}
		else if($str == "userlogin"){
			return "administrator";
		}
		else if($str == "postData"){
			$data = array(
				'bank'				=> $this->input->post('bank'),
				'atas_nama'			=> $this->input->post('atas_nama'),
				'nomor_rekening'	=> $this->input->post('nomor_rekening'),
				'date_saved'		=> $this->api->getFullDateTime(),
				'user_saved'		=> $this->my("userlogin")
			);
			return $data;
		}
		else if($str == "wilayah"){
			$query = "select * from gbei_wilayah order by nama";
			$result = $this->Modul_Model->read($query);
			return $result;
		}
	}
}
