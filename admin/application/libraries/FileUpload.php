<?php

class FileUpload{

    public function __construct()
    {
		//parent::__construct();
		date_default_timezone_set("Asia/Bangkok");
		$this->CI =& get_instance(); 
    }
	
	public function splitLookup($lookup){
		$lookup = str_replace("[", "", $lookup);
		$lookup = str_replace("]", "", $lookup);
		$lookup = str_replace("\"", "", $lookup);
		$lookup = explode(",", $lookup);
		
		return $lookup;
	}
	
	public function do_upload($file, $path, $name, $ext)
	{
		$result = array("data"=>array(), "msg"=>"", "success"=>false);
		
		$config['upload_path']          = $path;
		$config['file_name']          	= $name;
		$config['allowed_types']        = $ext;
		$config['max_size']             = 1024 * 3; // 3MB
		$config['max_width']            = 1024 * 3;
		$config['max_height']           = 768 * 3;
		$config['overwrite'] 			= TRUE;
		$config['file_ext_tolower'] 	= TRUE;

		$this->CI->load->library('upload', $config);
		$this->CI->upload->initialize($config);
		
		if (!is_dir($path))mkdir($path, 0777, true);
		
		if ( ! $this->CI->upload->do_upload($file))
		{
			$result["msg"] = $this->CI->upload->display_errors();
		}
		else
		{
			$result["data"] = $this->CI->upload->data(); $result["success"] = true;
		}
		return $result;
	}
	
	public function Img_Resize($path, $target, $maxsize) 
	{
		//copy($path, $target);
		//$path = $target;
		$source         = $path;
		$destination    = $target;

		$size = getimagesize($source);
		$width_orig = $size[0];
		$height_orig = $size[1];
		unset($size);
		$height = $maxsize+1;
		$width = $maxsize;
		while($height > $maxsize){
			$height = round($width*$height_orig/$width_orig);
			$width = ($height > $maxsize)?--$width:$width;
		}
		unset($width_orig,$height_orig,$maxsize);
		$images_orig    = imagecreatefromstring( file_get_contents($source) );
		$photoX         = imagesx($images_orig);
		$photoY         = imagesy($images_orig);
		$images_fin     = imagecreatetruecolor($width,$height);
		imagesavealpha($images_fin,true);
		$trans_colour   = imagecolorallocatealpha($images_fin,0,0,0,127);
		imagefill($images_fin,0,0,$trans_colour);
		unset($trans_colour);
		ImageCopyResampled($images_fin,$images_orig,0,0,0,0,$width+1,$height+1,$photoX,$photoY);
		unset($photoX,$photoY,$width,$height);
		imagepng($images_fin,$destination);
		unset($destination);
		ImageDestroy($images_orig);
		ImageDestroy($images_fin);

	}
	
	function findImageByName($filePath, $fileSrc, $fileExt, $default){
		$src = $default;
		$ext = explode("|", $fileExt);
		for($e = 0; $e < count($ext); $e++){			
			$path = $filePath . "." . $ext[$e];
			if(file_exists($path)){
				$src = $fileSrc . "." . $ext[$e];
				break;
			}
		}
		
		return $src;
	}
}

?>