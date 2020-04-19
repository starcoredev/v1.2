<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
	
	function __construct(){
		parent::__construct();
		$this->load->model('Modul_Model');
		$this->load->library('API');
		$this->load->library('FileUpload');
	}

	public function index()
	{
		$data = array();
		$param = array(
			'title' => $this->my('title'), 
			'body' => $this->my('bodyPath'), 
			'getURL' => $this->my('getURL'), 
			'addURL' => $this->my('addURL'),
			'editURL' => $this->my("editURL"),
			'data' => $data
		);
		$this->load->view('index.php', $param);
	}
	
	public function get(){		
		$result = $this->Modul_Model->read($this->my("getQuery"));
		echo json_encode($result);
		
	}
	
	public function my($str){
		if($str == "title"){
			return 'Dashboard';
		}
		else if($str == "tag"){
			return 'dashboard';
		}
		else if($str == "bodyPath"){
			return '' . $this->my("tag");
		}
		else if($str == "getQuery"){
			$query = "SELECT o.id as 'key', o.invoice as 'invoice', date_format(o.order_date, '%d %M %Y') as '1', o.order_user as '2', (select nama from customer where email = o.order_user) as '3', 
					concat((select count(*) from order_detail where order_id = o.id), ' Item') as '4', concat('Rp ', format(grand_total, 0)) as '5', 
					if(o.payment = 0, '<span class=\"color-red\">Belum dibayar</span>', 
					if((select confirmed from invoice where order_id = o.id) = 1, '<span class=\"color-green\">Terbayar</span>', 
					if((select confirmed from invoice where order_id = o.id) = -1, '<span class=\"color-red\">Ditolak</span>', '<span class=\"color-orange\">Terbayar | Belum Validasi</span>'))) as '6'
					FROM `order` o where o.status = 1 order by o.id desc";
					
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
			return "`order`";
		}
		else if($str == "userlogin"){
			return "administrator";
		}
		else if($str == "postData"){
			$data = array(
				'order_user'	=> $this->input->post('order_user'),
				'order_date'	=> $this->input->post('order_date'),
				'grand_total'	=> $this->input->post('grand_total'),
				'date_saved'	=> $this->api->getFullDateTime(),
				'user_saved'	=> $this->my("userlogin")
			);
			return $data;
		}
	}
}
