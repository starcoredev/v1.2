<?php

class API{

    public function __construct()
    {
		//parent::__construct();
		date_default_timezone_set("Asia/Bangkok");
		$this->CI =& get_instance(); 
    }
	
	public function getLookup(){
		$data = $this->CI->Modul_Model->read("select * from lookup order by nama");
		
		return $data;
	}
	
	public function splitLookup($lookup){
		$lookup = str_replace("[", "", $lookup);
		$lookup = str_replace("]", "", $lookup);
		$lookup = str_replace("\"", "", $lookup);
		$lookup = explode(",", $lookup);
		
		return $lookup;
	}
	
	public function generateID($char, $len, $val){
		$result = $char;
		for($i = 1; $i <= $len-count($val) ; $i++){
			$result .= "0";
		}
		$result .= $val;
		return $result;
	}
	
	public function getFullDateTime(){
		$date = date("Y-m-d H:i:s");
		return $date;
	}
		
	public function getDiffDate($t1, $t2, $type, $force = true){
		$t1 = date_create($t1);
		$t2 = date_create($t2);
		
		$interval = date_diff($t2, $t1);
		
		$val=$interval->format('%' . $type);
		
		if ($t2 > $t1 && $force) {
			$val = "-1";
		}
		
		return $val;
	}
	
	public function getDiffTimeMinute($t1, $t2){
		$t1 = strtotime($t1);
		$t2 = strtotime($t2);
		
		$t = floor(($t1 - $t2) / 60);
		return $t;
	}
	
	public function getDiffTimeSecond($t1, $t2){
		$t1 = strtotime($t1);
		$t2 = strtotime($t2);
		
		$t = ($t1 - $t2);
		return $t;
	}
	
	
	public function getDayWeek($w){
		$week = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
		
		return $week[$w];
	}
	
	public function getMonth($m){
		$month = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
		
		return $month[$m-1];
	}


    public function show_hello_world()
    {
        $text = "Hello World";
        return $text;
    }
}

?>