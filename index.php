<?php

ini_set('max_execution_time', '0');

include 'koneksi.php';
//echo json_encode($_SERVER);
$domain = $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
if(!isset($_SESSION["user"])){
	//echo "no sesi";
	header("location: login.php");
	exit;
}

$client = execqueryreturnall("client", "select * from client where id = '".$_SESSION["user"]["client_id"]."' ")[0];

$tag_filter = execqueryreturnall("users", "select tag_filter from `users` where email = '".$_SESSION["user"]["email"]."'")[0]["tag_filter"];
$tags = execqueryreturnall("tags", "select * from tags where id in (".$tag_filter.") ");


if(isset($_GET["get"])){	
	$client_id = $_SESSION["user"]["client_id"];
	$column = "id, name as 'nama', address as 'detail', latitude as 'lat', longitude as 'lng', tag, images, 'hide' as 'display'";
	$query = "select [column] from basic_info ";
	

	
	if(isset($_GET["key"])){
		$query .= " where id = '".$_GET["key"]."' ";
	}
	else{
		$ttt = "";
		foreach($tags as $t){
			$tc = "-". $t["id"] . "-";
			$ttt .= " concat('-', REPLACE(tag, ',','-,-'), '-') like  '%".$tc."%' |";
		}
		if(strlen($ttt) > 0)$ttt = str_replace("|", " or ", substr($ttt, 0, strlen($ttt) - 1));
		$query = $query . ' where (' . $ttt . ')';
	}
	
	$count = execqueryreturn("data", str_replace("[column]", "count(*)", $query));
	$data = execqueryreturnall("data", str_replace("[column]", $column, $query));
	$markers = array(); //execqueryreturnall("data", str_replace("[column]", $column, $query));
	
	for($i = 0; $i < count($data); $i++){
		$data[$i]["tags"] = execqueryreturnall("tags", "select * from tags where id in (".$data[$i]["tag"].") ");

	    $ic_url = 'assets/images/ic_c.png';
	    $data[$i]["icon"] = false;

	    for($xxx = count($data[$i]["tags"]) - 1; $xxx >= 0; $xxx--){
	    	$data[$i]["icon"] = findIcon($client_id, $data[$i]["tags"][$xxx]["id"]);

	    	if($data[$i]["icon"] != false)break;
	    }

	    


		/*
		if(strpos($data[$i]["tag"], 'bengkel motor') !== false)$data[$i]["icon"] = findIcon($client_id, "bengkel motor");
		if(strpos($data[$i]["tag"], 'badan usaha') !== false && $data[$i]["icon"] == false)$data[$i]["icon"] = findIcon($client_id, "badan usaha");
		if(strpos($data[$i]["tag"], 'bumdes') !== false && $data[$i]["icon"] == false)$data[$i]["icon"] = findIcon($client_id, "bumdes");
		if(strpos($data[$i]["tag"], 'layanan publik') !== false && $data[$i]["icon"] == false)$data[$i]["icon"] = findIcon($client_id, "layanan publik");
		if(strpos($data[$i]["tag"], 'pelaku usaha kecil') !== false && $data[$i]["icon"] == false)$data[$i]["icon"] = findIcon($client_id, "pelaku usaha kecil");
		if(strpos($data[$i]["tag"], 'survey potensi') !== false && $data[$i]["icon"] == false)$data[$i]["icon"] = findIcon($client_id, "survey potensi");
		if(strpos($data[$i]["tag"], 'crawling') !== false && $data[$i]["icon"] == false)$data[$i]["icon"] = findIcon($client_id, "crawling");
		if(strpos($data[$i]["tag"], 'sekolah') !== false && $data[$i]["icon"] == false)$data[$i]["icon"] = findIcon($client_id, "sekolah");
		if(strpos(str_replace(" ", "_", $data[$i]["tag"]), 'sekolah_dasar') !== false && $data[$i]["icon"] == false)$data[$i]["icon"] = findIcon($client_id, "sekolah dasar");
		*/
		if($data[$i]["icon"] == false)$data[$i]["icon"] = $ic_url;
	
		if(isset($_GET["key"])){
			$data[$i]["detail"] = execqueryreturnall("data", "select * from detail_info where basic_id = '".$data[$i]["id"]."'");
		}	
	}
	
	//$result = array("data"=>$data, "markers"=>$markers, "count"=> ceil($count / 6), "page"=>$page);
	$result = array("data"=>$data);
	echo json_encode($result);
	exit;
}

function findIcon($client_id, $str){
    $ic_url = false;
	/*$id = execqueryreturn("tags", "select id from tags where alias = '".str_replace(" ", "_", $str)."'");*/

	$id = $str;
	$icon = execqueryreturnall("icon", "select * from icon where client_id = '".$_SESSION["user"]["client_id"]."' and tag_id = '".$id."' ");
	if(count($icon) > 0){
		/*$package = execqueryreturnall("package", "select * from package where id = '".$icon[0]["package_id"]."' ");*/

		$package_id = $icon[0]["package_id"];
		$ic_url = 'assets/images/package/' . $package_id . '/' . $package_id . '_marker.png';
	}
	return $ic_url;
}
//$tags = execqueryreturnall("tags", "select * from tags where id in (".$_SESSION["user"]["tag_filter"].") ");
//$icon = execqueryreturnall("icon", "select * from icon where client_id = '".$_SESSION["user"]["client_id"]."' and tag_id in (".$_SESSION["user"]["tag_filter"].") ");
for($i = 0; $i < count($tags); $i++){
	$icon = execqueryreturnall("icon", "select * from icon where client_id = '".$_SESSION["user"]["client_id"]."' and tag_id = '".$tags[$i]["id"]."' ");
	if(count($icon) > 0){
		$icon = $icon[0];
		$package = execqueryreturnall("package", "select * from package where id = '".$icon["package_id"]."' ");
		if(count($package) > 0){
			$icon["package"] = $package[0];
		}
		else{
			$icon["package"] = null;
		}
	}
	else{
		$icon = null;
	}
	$tags[$i]["icon"] = $icon;
}
//echo json_encode($tags);

?>
<!DOCTYPE html>
<html lang="id">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>WEBGIS</title>
	  
		<link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"
   integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
   crossorigin=""/>
   <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"
   integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew=="
   crossorigin=""></script>
   
		<link href="assets/bootstrap/css/bootstrap.css" rel="stylesheet">
		<link rel='stylesheet' id='vc_google_fonts_raleway100200300regular500600700800900-css'  href='https://fonts.googleapis.com/css?family=Raleway%3A100%2C200%2C300%2Cregular%2C500%2C600%2C700%2C800%2C900&#038;ver=6.0.3' type='text/css' media='all' />
				
		<link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet">
		<script src="assets/bootstrap/js/jquery.min.js"></script>
		<link rel="stylesheet" type="text/css" href="assets/css/animate.css">
		
		<script src="assets/bootstrap/js/bootstrap.js"></script>
		
		<!-- datatables -->
		<script type="text/javascript" src="assets/datatables/dataTables.bootstrap.min.js" ></script>
		<script type="text/javascript" src="assets/datatables/jquery.dataTables.min.js" ></script>
		<link href="assets/datatables/jquery.dataTables.min.css" rel="stylesheet">
		<!-- // datatables -->
		
		<!-- scrollbar -->
		<script type="text/javascript" src="assets/scrollbar/jquery.scrollbar.js" ></script>
		<link href="assets/scrollbar/scrollbar.css?<?php echo date("His"); ?>" rel="stylesheet">
		<!-- // scrollbar -->
		
		<!-- flexslider -->
		<script type="text/javascript" src="assets/flexslider/jquery.flexslider.js" ></script>
		<link href="assets/flexslider/flexslider.css" rel="stylesheet">
		<!-- // flexslider -->
		
		<link rel="stylesheet" type="text/css" href="assets/sidebar/sidebar.css?<?php echo date("His"); ?>">
		
		
		
		<link href="assets/css/styles.css?<?php echo date("His"); ?>" rel="stylesheet">
		<!--<link href="assets/css/mobile.css?<?php echo date("His"); ?>" rel="stylesheet">
		<script src="assets/js/script.js?<?php echo date("His"); ?>"></script>-->
		
		
	</head>
	<body>
		<h1 class="title"><?php echo $client["map_title"]; ?></h1>
		<div class="logo">
			<a href="javascript:void(0)" onclick="showAbout()" class="img"><img src="assets/images/logo.png" /></a>
			<a href="logout.php" class="btn btn-sm btn-danger logout"><i class="fa fa-exit"></i> Sign Out</a>
		</div>
		<div class="dialog" onclick="showAbout();">
			<div class="dialog-content">
				<table>
					<tr>
						<td>
							<img src="assets/images/logo.png" />
							<img src="assets/images/about.png" />
						</td>
						<td>
							<p>Our talented people combine art and science to uncover the patterns in big data flood while looking for fresh ideas hidden in small data to gain valueable insights.</p>
							
							<a href="https://www.starcore.co" target="_blank">www.starcore.co</a>
						</td>
					</tr>
				</table>
			</div>
		</div>
		<?php include("sidebar.php"); ?>
		<div id="map"></div>
		<div id="preview" class="close" >
			<a href="#" ><i class="fa fa-close"></i></a>
			<div>
				<div id="slider2">
					<div class="flexslider">
					  <ul class="slides">
						<li><div class="default"><img src="assets/images/no-image.jpg" /></div></li>
					  </ul>
					</div>
				</div>
			</div>
		</div>
		<div class="mapControl">
			<ul>
				<!--<li><a href="javascript:void(0)"><i class="fa fa-globe"></i></a></li>
				<li><a href="javascript:void(0)"><i class="fa fa-crosshairs"></i></a></li>-->
				<li class="p"><a href="javascript:void(0)" onclick="mymap.setZoom(mymap.getZoom() + 1)"><i class="fa fa-plus"></i></a></li>
				<li class="m"><a href="javascript:void(0)" onclick="mymap.setZoom(mymap.getZoom() - 1)"><i class="fa fa-minus"></i></a></li>
				<!--<li><a href="javascript:void(0)"><i class="fa fa-male"></i></a></li>-->
			</ul>
		</div>
		
		
<script>
	var mymap;
	var marker;
	var markers={};
	var data = [];
	var dataFilter = [];
	var ajax = null;
	var pagination = {"current":1, "count":1, "index":1, "itemCountPerPage":10, "displayedPage":6};
	var tags = <?php echo json_encode($tags); ?>
	
	$(document).ready(function(){
		$("#s").keyup(function(e){
			e.preventDefault();
			setTimeout(function(e){
				reFilter();
			}, 500);
		});
		
		$("#preview > a").click(function(){
			$("#preview").addClass("close");
		});
		
		$(".sidebar-layers .checkbox").change(function(e){
			if($(this).find("input").is(":checked"))$(this).addClass("checked");
			else $(this).removeClass("checked");
			reFilter();
		});
		
		initMap();
	});
	
	function reFilter(){
		//throw new Error('');
		pagination["current"] = 1;
		pagination["index"] = 1;
		buildListData();
	}
	
	function initMap(){
		var mbAttr = '',
			mbUrl = 'https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw';

		var satellite   = L.tileLayer(mbUrl, {id: 'mapbox/satellite-v9', attribution: mbAttr}),
			streets  = L.tileLayer(mbUrl, {id: 'mapbox/streets-v11', attribution: mbAttr});

		mymap = L.map('map', {
			zoomControl: false,
			center: [-2.752950, 122.283331],
			zoom: 5,
			layers: [satellite]
		});

		var baseLayers = {
			"Satellite": satellite,
			"Streets": streets
		};

		var overlays = {};

		L.control.layers(baseLayers, overlays, {position: 'bottomright'}).addTo(mymap);
		
		setTimeout(function(){
			getData();
		}, 500);
	}
	
	function getData(){
		var ddd = {}; //getFilterData();
		if(ajax != null)ajax.abort();
		
		$(".sidebar-lists-list tr:not(.loading)").remove();
		$(".sidebar-lists-list tr.loading").show();
		
		$.each(markers, function(key, value) {
			mymap.removeLayer(value);
		});
		
		ajax = $.ajax({type: "POST", url: "index.php?get=true", data: ddd, dataType: 'json',
			success: function(d){
				data = d["data"];
				buildMarkers();
				//refreshAllMap();
			},
			error: function (data) {
				alert("Err: " + JSON.stringify(data));
			},
			complete : function () {
				//$('html, body').animate({ scrollTop: $("body").offset().top }, 500);
			}
		});
	}
	
	function buildMarkers(){
		var items = "";
		$(".sidebar-lists-list tr.loading").hide();
		
		var nn = 0;
		$.each(data, function(index, array){
			var id = array["id"];
			var lat = array["lat"] * 1;
			var lng = array["lng"] * 1;
			
			var iconURL = array["icon"];
			
			var greenIcon = L.icon({
				iconUrl: iconURL,

				iconSize:     [25, 35], // size of the icon\
				iconAnchor:   [9, 34], // point of the icon which will correspond to marker's location
				popupAnchor:  [5, -35] // point from which the popup should open relative to the iconAnchor
			});

			var tags = "";
				$.each(array["tags"], function(x, y){
					tags += y["name"] + ",";
				});
				tags = tags.substring(0, tags.length - 1);
			
			var content  = '';
				/*content += '<img src = "images/' + id + '/gambar.jpg" />';*/
				content += '	<div class="list-contentx">';
				content += '		<label style="font-weight:bold;">'+array["nama"]+'</label>';
				content += '		<span style="display:block; color:green">'+tags+'</span>';
				/*items += '		<p>Auto Repair Shop</p>';*/
				content += '		<span style="display:block; color:#aaa">'+array["detail"]+'</span>';
				content += '	</div>';
			
			var m = L.marker([lat, lng], {icon: greenIcon}); //.addTo(mymap);
			m.bindPopup(content);
			m.on('mouseover', function (e) {
				this.openPopup();
			});
			m.on('mouseout', function (e) {
				this.closePopup();
			});
			m.on('click', function (e) {
				preview(array);
			});
			
			markers[array["id"]] = m;
			
		});
		buildListData();
	}
	
	function buildListData(){
		var str = $("#s").val().toLowerCase();
		dataFilter = [];
		$.each(data, function(index, array){
			var add = false;
			
			for(var i = 1; i <= 7; i++){
				if($("#l" + i + " input").is(":checked")){
					if(array["tag"].indexOf($("#l" + i + " input").val()) !== -1){
						if(array["nama"].toLowerCase().indexOf(str) !== -1){
							dataFilter.push(array);
							break;
						}
					}
				}
			}
		});
		buildListView();
	}
	
	function buildListView(){
		var items = "";
		
		$(".sidebar-lists-list").children("tbody").html("");
		$.each(dataFilter, function(index, array){
			var i = index + 1;
			if(i >= (((pagination["current"] - 1) * pagination["itemCountPerPage"]) + 1) && i <= pagination["current"] * pagination["itemCountPerPage"]){

				var tags = "";
				$.each(array["tags"], function(x, y){
					tags += y["name"] + ",";
				});
				tags = tags.substring(0, tags.length - 1);

				items += '<tr ><td>';
				items += '	<a ['+array["id"]+'] href="javascript:void(0)" class="x" onclick="preview(dataFilter['+index+'])">';
				items += '		<label>'+array["nama"]+'</label>';
				items += '		<span>'+tags+'</span>';
				items += '		<p>'+array["detail"]+'</p>';
				items += '	</a>';
				items += '</td></tr>';
			}
			else{
				if(i > pagination["current"] * pagination["itemCountPerPage"])return false;
			}
		});
		$(".sidebar-lists-list").children("tbody").html(items);
		
		var idS = [];
			
		for(var i = 0; i < dataFilter.length; i++){
			var id = dataFilter[i]["id"];
			id = id.toLowerCase();
			
			idS.push(id);
		}
		
		$.each(markers, function(key, value) {
			if(idS.indexOf(key.toLowerCase()) !== -1){
				mymap.addLayer(value);
			}
			else{
				mymap.removeLayer(value);
			}
		});
		
		pagination["count"] = dataFilter.length / pagination["itemCountPerPage"];
		buildPagination();
	}
	
	function buildPagination(){
		var count = pagination["count"];
		var index = pagination["index"];
		var current = pagination["current"];
		var displayedPage = pagination["displayedPage"];
		
		var f = Math.ceil(index / displayedPage) * displayedPage;
		
		var first = '<li class="page-item first"><a class="page-link" href="javascript:void(0)" onclick="rebuildPagination(1)"><i class="fa fa-angle-double-left"></i></a></li>';
		var prev  = '<li class="page-item prev"><a class="page-link" href="javascript:void(0)" onclick="rebuildPagination('+(f-displayedPage-1)+')"><i class="fa fa-angle-left"></i></a></li>';
		var next  = '<li class="page-item next"><a class="page-link" href="javascript:void(0)" onclick="rebuildPagination('+(f+1)+')"><i class="fa fa-angle-right"></i></a></li>';
		var last  = '<li class="page-item last"><a class="page-link" href="javascript:void(0)" onclick="rebuildPagination('+count+')"><i class="fa fa-angle-double-right"></i></a></li>';
		
		var items = '';
		
		if(f > (displayedPage * 2))items += first;
		if(f > displayedPage)items += prev;
		
		var ff = f - (displayedPage - 1);
		if(count <= displayedPage){
			ff = 1; f = 1;
		}
		
		for(var i = ff; i <= f; i++){
			var active = '';
			if(i == current)active = ' active ';
			items += '<li class="page-item '+active+'"><a class="page-link" href="javascript:void(0)" onclick="setCurrentPagination('+i+')">'+i+'</a></li>';
		}
		
		if(f < count)items += next;
		if(f < count - displayedPage)items += last;
		
		if(dataFilter.length <= 0)items = '';
		
		//alert(items);
		
		$(".pagination").html(items);
	}
	
	function rebuildPagination(p){
		pagination["index"] = p;
		buildPagination();
	}
	
	function setCurrentPagination(p){
		pagination["current"] = p;
		buildListView();
	}
	
	var tempItems = '';
	function preview(d){
		var tags = "";
		$.each(d["tags"], function(x, y){
			tags += y["name"] + ",";
		});
		tags = tags.substring(0, tags.length - 1);

		$("#d-title").html(d["nama"]);
		$("#d-tag").html(tags);
		$("#d-name").html(d["nama"]);
		$("#d-address").html(d["detail"]);
		
		setTimeout(function(e){
			$(".sidebar .sidebar-detail").addClass("open");
		}, 500);
		
		var latLngs = markers[d["id"]].getLatLng() ;
		var markerBounds = L.latLngBounds(latLngs);
		setTimeout(function(e){
			mymap.flyTo(latLngs, 25, {
				animate: false,
				duration: 0
			});
		}, 500);
		
		var images = d["images"];
		var items = '';
		for(var i = 1; i <= images; i++){
			items += '<li><img src="assets/images/basic/'+d["id"]+'/'+i+'.jpg" /></li>';
		}
		if(items == '')items = '<li><div class="default"><img src="assets/images/no-image.jpg" /></div>';
		
		tempItems = items;
		$('#slider .flexslider').remove();
		$("#slider").html('<div class="flexslider""><ul class="slides">' + items + '</ul></div>');
		
		
		
		//$('#slider').addClass("flexslider");
		if(items != ''){
			setTimeout(function(){
				$('#slider .flexslider').flexslider({
					animation: "slide",
					controlNav: false,
					animationLoop: false,
					slideshow: false
				  });
				
			}, 500);
		}
		
		$('#slider .flexslider ul li img').click(function(){
			var src = $(this).attr('src');
			//$("#preview > div").css("background-image", "url("+src+")");
			
			
			
			$("#preview").removeClass("close");
			setTimeout(function(){
				
				$('#slider2 .flexslider').remove();
				$("#slider2").html('<div class="flexslider""><ul class="slides">' + items + '</ul></div>');
				$("#preview > div #slider2 .flexslider ul li img").height($("#preview > div").height() - 20);
				$('#slider2 .flexslider').flexslider({
					animation: "slide",
					controlNav: true,
					animationLoop: false,
					slideshow: true
				  });
			}, 500);
		});
		
		
		$.ajax({type: "POST", url: "index.php?get=true&key=" + d["id"], data: {}, dataType: 'json',
			success: function(data){
				var item = '';
				$.each(data.data[0]["detail"], function(index, array){
					var ic 	= '';
					var key = array["tipe"];
					var c 	= array["value"];
					var a	= false;
					
					if(key.toLowerCase().indexOf("pegawai") !== -1){
						ic = 'ic_employee.png';
						a = true;
					}
					else if(key.toLowerCase().indexOf("klasifikasi") !== -1){
						ic = 'ic_clasification.png';
						a = true;
					}
					else if(key.toLowerCase().indexOf("jenis") !== -1 || key.toLowerCase().indexOf("target") !== -1 || 
							key.toLowerCase().indexOf("tipe") !== -1){
						ic = 'ic_type.png';
						c = array["tipe"] + " " + c;
						a = true;
					}
					if(a){
						item += '<div class="form-group"><img src="assets/images/'+ic+'" /><label>'+c+'</label></div>';
					}
				});
				$("#detail_info .sidebar-detail-content").html(item);
			},
			error: function (data) {
				alert("Err: " + JSON.stringify(data));
			},
			complete : function () {
				//$('html, body').animate({ scrollTop: $("body").offset().top }, 500);
			}
		});
	}
	
	function goHome(){
		$(".sidebar .sidebar-detail").removeClass("open");
		
		setTimeout(function(e){
			mymap.flyTo([-2.752950, 122.283331], 5, {
				animate: true,
				duration: 0.5
			});
		}, 500);
	}
	
	function preview2(d){
		
		$("#d-title").html(data[d]["nama"]);
		$("#d-tag").html(data[d]["tag"]);
		$("#d-name").html(data[d]["nama"]);
		$("#d-address").html(data[d]["detail"]);
		
		var item = '';
		$.each(data[d]["detail_info"], function(index, array){
			var ic 	= '';
			var key = array["tipe"];
			var c 	= array["value"];
			var a	= false;
			
			if(key.toLowerCase().indexOf("pegawai") !== -1){
				ic = 'ic_employee.png';
				a = true;
			}
			else if(key.toLowerCase().indexOf("klasifikasi") !== -1){
				ic = 'ic_clasification.png';
				a = true;
			}
			else if(key.toLowerCase().indexOf("jenis") !== -1 || key.toLowerCase().indexOf("target") !== -1 || 
					key.toLowerCase().indexOf("tipe") !== -1){
				ic = 'ic_type.png';
				c = array["tipe"] + " " + c;
				a = true;
			}
			if(a){
				item += '<div class="form-group"><img src="assets/images/'+ic+'" /><label>'+c+'</label></div>';
			}
		});
		$("#detail_info .sidebar-detail-content").html(item);
		
		$(".sidebar .sidebar-detail").addClass("open");
				
		var latLngs = [ markers[data[d]["id"]].getLatLng() ];
		//alert(latLngs);
		var markerBounds = L.latLngBounds(latLngs);
		setTimeout(function(e){
			mymap.flyToBounds(markerBounds, {'duration':0.5});
			mymap.flyToBounds(markerBounds, {'duration':0.5});
		}, 500);
		
		var images = data[d]["images"];
		var items = '';
		for(var i = 1; i <= images; i++){
			items += '<li><img src="assets/images/basic/'+data[d]["id"]+'/'+i+'.jpg" /></li>';
		}
		if(items == '')items = '<li><div class="default"><img src="assets/images/no-image.jpg" /></div>';
		
		$('#slider .flexslider').remove();
		$("#slider").html('<div class="flexslider""><ul class="slides">' + items + '</ul></div>');
		
		//$('#slider').addClass("flexslider");
		if(items != ''){
			setTimeout(function(){
				$('#slider .flexslider').flexslider({
					animation: "slide",
					controlNav: false,
					animationLoop: false,
					slideshow: false,
					sync: "#carousel"
				  });
			}, 500);
		}
		
		$('#slider .flexslider ul li img').click(function(){
			var src = $(this).attr('src');
			$("#preview > div").css("background-image", "url("+src+")");
			
			$("#preview").removeClass("close");
		});
	}
	
	function showAbout(){
		if($(".dialog").is(":visible")){
			$(".dialog").hide();
		}
		else{
			$(".dialog").show();
		}
	}
	
</script>
		
		<style>
		.sidebar{
			
		}
		#preview{
			position:fixed;
			width:100%;
			height:100%;
			padding:5%;
			padding-left:400px;
			top:0;
			background:rgba(0,0,0,0.8);
			z-index:1999;
			
		}
		#preview.close{
			display:none;
		}
		#preview > a{
			position:absolute;
			color:#fff;
			background:#333;
			width:30px;
			height:30px;
			margin-left:-10px;
			margin-top:-10px;
			text-align:center;
			padding-top:3px;
			-webkit-border-radius: 50px;
-moz-border-radius: 50px;
border-radius: 50px;
		}
		#preview > a:hover{
			background:#000;
		}
		#preview > div{
			height:90%;
			background-size:cover;
			background-position:center;
		}
		#preview > div #slider2,
		#preview > div #slider2 .flexslider,
		#preview > div #slider2 .flexslider ul,
		#preview > div #slider2 .flexslider ul li{
			height:100%;
		}
		#preview > div #slider2 .flexslider ul li{
			position:relative;
			overflow: hidden;
		}
		#preview > div #slider2 .flexslider ul li img{
			display: block;
			*border:5px solid red;
			*margin : 0 auto;
			*position:absolute
			*width: auto;
			*height:100%;
			*top: 50%;
			*left: 50%;
			*min-height: 100%;
			*min-width: 100%;
			*transform: translate(-50%, -50%);
		}
		</style>
	</body>
	
</html>