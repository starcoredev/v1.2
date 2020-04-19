<?php 
for($i = 1; $i <= $fileTotal; $i++){ 
$ext = explode("|", $fileExt);
$src = base_url() . "assets/images/no-image.png";
for($e = 0; $e < count($ext); $e++){
	$number = "_" . $i;
	if(isset($withNumber)){
		if(!$withNumber)$number = "";
	}
	
	$path = $filePath . $number . "." . $ext[$e];
	if(file_exists($path)){
		$thumb = "_thumb";
		if(isset($withThumb)){
			if(!$withThumb)$thumb = "";
		}
		$src = $fileSrc . $number . $thumb . "." . $ext[$e];
		break;
	}
}
?>
<div class="fileinput <?php echo $fileinputClass; ?>">
	<div class="fileinput-content">
		<a href="javascript:void(0)"><img src="<?php echo $src . "?" . date("YmdHis"); ?>" /><label>Change Image</label></a>
		
		<input type="file" name="file_<?php echo $i; ?>" />
		<button value="<?php echo $src; ?>">Clear</button>
	</div>
</div>
<?php } ?>
