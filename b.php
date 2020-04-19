<?php
ini_set('max_execution_time', '0');

include 'koneksi.php';

$basic_info = execqueryreturnall("tags", "select id, tag from basic_info ");
$tags = execqueryreturnall("tags", "select * from tags  ");


echo '<table border="1">';
echo '<thead><th>No</th><th>ID</th><th>Tag</th><th>Tag Alias</th><th>Tag ID</th><th></th></thead>';
echo '<tbody>';

$n = 0;
foreach($basic_info as $b){
	$n++;
	$id = $b["id"];
	$tag = $b["tag"];
	$tag_alias = str_replace(", ", ",", strtolower($tag));
	$tag_alias = str_replace(" ", "_", strtolower($tag_alias));
	$tag_alias = str_replace("/bumades", "", $tag_alias);
	$tag_alias = str_replace("pelaku_usaha_kecil", "pelaku_usaha", $tag_alias);

	$a = str_replace(", ", ",", $tag_alias);
	$a = str_replace(",", "','", $tag_alias);
	$tag_ids = execqueryreturnall("tags", "select * from tags where alias in ('".$a."')  ");

	$tag_id = "";
	foreach($tag_ids as $t){
		$tag_id .= "".$t["id"].",";
	}
	$tag_id = substr($tag_id, 0, strlen($tag_id) - 1);

	$q = " update basic_info set tag = '".$tag_id."' where id = '".$id."' ";
	$st = (execquery("basic_info", $q) ? "Berhasil" : "Gagal");
	
	echo '<tr>';
	echo '<td>'.$n.'</td>';
	echo '<td>'.$id.'</td>';
	echo '<td>'.$tag.'</td>';
	echo '<td>'.$tag_alias.'</td>';
	echo '<td>'.$tag_id.'</td>';
	echo '<td>'.$st.'</td>';
	//echo '<td>'. json_encode($tag_ids).'</td>';
	echo '</tr>';
}

echo '</tbody>';
echo '</table>';


?>