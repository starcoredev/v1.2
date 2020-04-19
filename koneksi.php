<?php
session_start();

date_default_timezone_set('Asia/Jakarta');
//$base_url = 'https://www.datapaito.com/';
$base_url = 'http://localhost/projects/webgis/';

if(isset($_SESSION['expiretime'])) {
    if($_SESSION['expiretime'] < time()) {
        //logged out
    }
    else {
        $_SESSION['expiretime'] = time() + 600;
    }
}

function koneksi()
{
	//$con = mysqli_connect("localhost","k7143165_awdelta","awdelta");
	//$con = mysqli_connect("localhost","gardhac1_webgis","webgis123456789");
	$con = mysqli_connect("localhost","root","");
	if (!$con)
	  {
	  die('Sorry, connection failed to server ' );
	  }
	  mysqli_select_db($con,"webgis");
	  //mysqli_select_db($con,"gardhac1_webgis");
	  //mysqli_select_db($con,"k7143165_awdeltagarciaproduction");
	return $con;
}
function execquery($tabel,$query)
{
	$con=koneksi();
	//mysqli_select_db($con, $tabel);
	if (!mysqli_query($con, $query))
	{
		return mysqli_error($con);
	}
	else{
		return 1;
	}
	mysqli_close($con);
}
function execqueryreturn($tabel,$query)
{
	$con=koneksi();
	//mysqli_select_db($con, $tabel);
	$x=''; 
	$result	= mysqli_query($con, $query);
	$row 	= mysqli_fetch_array($result, MYSQLI_NUM);
	$x 		= $row[0];
	
	mysqli_free_result($result);
	return $x;
	mysqli_close($con);
}
function execqueryreturnall($tabel,$query)
{
	$con=koneksi();
	//mysql_select_db($tabel, $con);
	$result=mysqli_query($con,$query);
	//$row 	= mysqli_fetch_array($result, MYSQLI_NUM);
	
	$arr = array();
	while ($row=mysqli_fetch_array($result, MYSQLI_BOTH)){
		array_push($arr, $row);
	}
	
	return $arr;
	mysqli_free_result($result);
	mysql_close($con);
}

function getColumns($tabel){
	$con = koneksi();
	$result = mysqli_query($con, "SHOW COLUMNS FROM ".$tabel);
	$columns = array();
	while($row = mysqli_fetch_array($result, MYSQLI_NUM))
	  {
		$columns[] = $row;
	  }
	return $columns;
}

function generateCode(){
	return md5(date("dmYHis"));
}

function generateURL($tabel, $column, $name){
	$url = str_replace(" ", "-",strtolower($name));
	
	$count = execqueryreturn($tabel, "select count(*) from " . $tabel . " where " . $column . " = '".$url."'");
	
	if($count > 0){
		$url .= "-" . $count;
	}
	
	return $url;
}

function getTableAlias($type,$tabel)
{
	if($type == 1){
		return execqueryreturn("table_alias","select table_alias from table_alias where table_name = '".$tabel."'");
	}
	elseif($type == 0){
		return execqueryreturn("table_alias","select table_name from table_alias where table_alias = '".$tabel."'");
	}
}

function writeFile($file, $content){
	$myfile = fopen($file, "w") or die("Unable to open file!");
	fwrite($myfile, $content);
	fclose($myfile);
}

function Img_Resize($path, $target, $maxsize) 
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

function uploadImage($image,$path)
{
	$_FILES['img']=$image;
	if ( $_FILES['img']['type'] == "image/gif" || $_FILES['img']['type'] == "image/jpg" || $_FILES['img']['type'] == "image/jpeg" || $_FILES['img']['type'] == "image/png" ) {

	 $source = $_FILES['img']['tmp_name'];
	 $target = $path;
	 move_uploaded_file( $source, $target );// or die ("Couldn't copy");
	 $size = getImageSize( $target );

	 $imgstr = "<p><img width=\"$size[0]\" height=\"$size[1]\" ";
	 $imgstr .= "src=\"$target\" alt=\"uploaded image\" /></p>";

	 return $imgstr;
 }	 
}

function reLoadCounter()
{
	$tgl=execqueryreturn("statevisitor","select tanggal from statevisitor");
	$con=koneksi();
	/*echo "<script LANGUAGE='javascript'>alert('".date('mdy',strtotime($tgl)).",".date('mdy')."');</script>";*/
	mysql_select_db(statevisitor, $con);
	if (date('mdy',strtotime($tgl))!=date('mdy'))
	{
		$query="update statevisitor set tanggal=NOW(), today=0";
		if (!mysql_query($query,$con))
		  {
		  die('Error: ' . $query . mysql_error());
		  }
	}
	if($_SESSION['visitor']==''){
		$_SESSION['visitor']='ok';
		$query="update statevisitor set today=today+1, total=total+1";
			if (!mysql_query($query,$con))
			  {
			  die('Error: ' . $query . mysql_error());
			  }
			  
		}
	
	mysql_close($con);
}

function html_cut($text, $max_length)
{
    $tags   = array();
    $result = "";

    $is_open   = false;
    $grab_open = false;
    $is_close  = false;
    $in_double_quotes = false;
    $in_single_quotes = false;
    $tag = "";

    $i = 0;
    $stripped = 0;

    $stripped_text = strip_tags($text);

    while ($i < strlen($text) && $stripped < strlen($stripped_text) && $stripped < $max_length)
    {
        $symbol  = $text{$i};
        $result .= $symbol;

        switch ($symbol)
        {
           case '<':
                $is_open   = true;
                $grab_open = true;
                break;

           case '"':
               if ($in_double_quotes)
                   $in_double_quotes = false;
               else
                   $in_double_quotes = true;

            break;

            case "'":
              if ($in_single_quotes)
                  $in_single_quotes = false;
              else
                  $in_single_quotes = true;

            break;

            case '/':
                if ($is_open && !$in_double_quotes && !$in_single_quotes)
                {
                    $is_close  = true;
                    $is_open   = false;
                    $grab_open = false;
                }

                break;

            case ' ':
                if ($is_open)
                    $grab_open = false;
                else
                    $stripped++;

                break;

            case '>':
                if ($is_open)
                {
                    $is_open   = false;
                    $grab_open = false;
                    array_push($tags, $tag);
                    $tag = "";
                }
                else if ($is_close)
                {
                    $is_close = false;
                    array_pop($tags);
                    $tag = "";
                }

                break;

            default:
                if ($grab_open || $is_close)
                    $tag .= $symbol;

                if (!$is_open && !$is_close)
                    $stripped++;
        }

        $i++;
    }

    while ($tags)
        $result .= "</".array_pop($tags).">";

    return $result;
}

function replaceTags($startPoint, $endPoint, $newText, $source) {
    return preg_replace('#('.preg_quote($startPoint).')(.*)('.preg_quote($endPoint).')#si', '$1'.$newText.'$3', $source);
}

function get_between($input, $start, $end)
{
  $substr = substr($input, strlen($start)+strpos($input, $start), (strlen($input) - strpos($input, $end))*(-1));
  return $substr;
} 

function getBrowserType () {
$u_agent = $_SERVER['HTTP_USER_AGENT']; 
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version= "";

    //First get the platform?
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';
    }
    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'mac';
    }
    elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'windows';
    }

    // Next get the name of the useragent yes seperately and for good reason
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) 
    { 
        $bname = 'Internet Explorer'; 
        $ub = "MSIE"; 
    } 
    elseif(preg_match('/Firefox/i',$u_agent)) 
    { 
        $bname = 'Mozilla Firefox'; 
        $ub = "Firefox"; 
    } 
    elseif(preg_match('/Chrome/i',$u_agent)) 
    { 
        $bname = 'Google Chrome'; 
        $ub = "Chrome"; 
    } 
    elseif(preg_match('/Safari/i',$u_agent)) 
    { 
        $bname = 'Apple Safari'; 
        $ub = "Safari"; 
    } 
    elseif(preg_match('/Opera/i',$u_agent)) 
    { 
        $bname = 'Opera'; 
        $ub = "Opera"; 
    } 
    elseif(preg_match('/Netscape/i',$u_agent)) 
    { 
        $bname = 'Netscape'; 
        $ub = "Netscape"; 
    } 

    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
    ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
        // we have no matching number just continue
    }

    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
            $version= $matches['version'][0];
        }
        else {
            $version= $matches['version'][1];
        }
    }
    else {
        $version= $matches['version'][0];
    }

    // check if we have a number
    if ($version==null || $version=="") {$version="?";}

    return json_encode(array(
        'userAgent' => $u_agent,
        'name'      => $bname,
        'version'   => $version,
        'platform'  => $platform,
        'pattern'    => $pattern
    ));
}

function selfURL() {
$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
$protocol = strleft(strtolower($_SERVER["SERVER_PROTOCOL"]), "/").$s;
$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
return $protocol."://".$_SERVER['SERVER_NAME'].$port.$_SERVER['REQUEST_URI'];
}

function strleft($s1, $s2) { return substr($s1, 0, strpos($s1, $s2));
}

function getIpAddress(){
if (!empty($_SERVER["HTTP_CLIENT_IP"]))
{
 //check for ip from share internet
 $ip = $_SERVER["HTTP_CLIENT_IP"];
}
elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
{
 // Check for the Proxy User
 $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
}
else
{
 $ip = $_SERVER["REMOTE_ADDR"];
}

// This will print user's real IP Address
// does't matter if user using proxy or not.
return $ip;

}


?>
