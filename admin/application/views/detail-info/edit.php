
<!-- Page Content -->
<div class="page-title-2">
	<div class="col-md-6"><h1>Form Detail Info | <?php echo $bname; ?></h1></div>	
</div>

<div class="col-md-12 page-content after-title">
	<div class="container">
		<form id="form" class="form-horizontal label-left">
			<div class="col-md-6">
				<input type="hidden" name="key" value="<?php if($data){echo $data[0]["id"];} ?>" />
				<input type="hidden" name="crud" value="<?php echo $form; ?>" />
				<input type="hidden" name="basic_id" value="<?php echo $basic_id; ?>" />

				<div class="form-group">
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" >Tipe</label>
					<div class="col-sm-6">
						<select name="tipe" class="form-control input-md">
							<?php 
							foreach($detail_info_tipe as $d){ 
							$c = "";
							if($data){
								if($data[0]["tipe"] == $d["tipe"]){
									$c = ' selected="selected" ';
								}
							}
							?>
							<option <?php echo $c; ?> value="<?php echo $d["tipe"]; ?>"><?php echo $d["tipe"]; ?></option>
							<?php } ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" >Value</label>
					<div class="col-sm-9">
						<textarea class="form-control input-md" name="value" placeholder="Value"><?php echo $data[0]["value"]; ?></textarea>
					</div>
				</div>
				
				<div class="col-md-12 line-break"><hr /></div>
				<div class="form-group">
					<label class="control-label col-sm-2" ></label>
					<div class="col-sm-8">
						<?php if($form == "add"){?>
						<button class="btn btn-sm btn-success" onclick="formx.post(this);" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Please wait..."><i class="fa fa-save"></i>Simpan</button>
						<?php } ?>
						<?php if($form == "edit"){?>
						<button class="btn btn-sm btn-primary" onclick="formx.post(this);" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Please wait..."><i class="fa fa-edit"></i>Edit</button>
						<button class="btn btn-sm btn-danger" onclick="$('#form input[name=crud]').val('delete'); formx.post(this);"><i class="fa fa-trash" aria-hidden="true"></i>Hapus</button>
						<?php } ?>
					</div>
				</div>
			</div>
		</form>
	</div>
	<div class=" no-background no-padding">
		<div class="alert" role="alert"><p></p></div>
	</div>
</div>


<link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"
   integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
   crossorigin=""/>
   <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"
   integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew=="
   crossorigin=""></script>
<script>

var formx;

$(document).ready(function(e){
	formx = new Form("#form");
	
	$(".checkbox input").change(function(){refreshTag();});
	$(".btn-refresh-marker").click(function(e){
		var lat = 0;
		var lng = 0;
		
		if($("#latitude").val() != '')lat = $("#latitude").val();
		if($("#longitude").val() != '')lng = $("#longitude").val();
		
		reposMarker(lat, lng);
	});
	
	loadMap();
});

function refreshTag(){
	var item = '';
	$(".checkbox").each(function(i, obj) {
		var input = $(this).find("input");
		if($(input).is(":checked")){
			item += $(input).val() + ",";
		}
	});	
	$("#tag").val(item.substring(0, item.length - 1));
}

var mymap;
var marker;
function loadMap(){
	var mbAttr = '',
		mbUrl = 'https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw';

	var satellite   = L.tileLayer(mbUrl, {id: 'mapbox/satellite-v9', attribution: mbAttr}),
		streets  = L.tileLayer(mbUrl, {id: 'mapbox/streets-v11', attribution: mbAttr});

	mymap = L.map('map', {
		zoomControl: true,
		center: [-2.752950, 122.283331],
		zoom: 4,
		layers: [satellite]
	});

	var baseLayers = {
		"Satellite": satellite,
		"Streets": streets
	};

	var overlays = {};

	L.control.layers(baseLayers, overlays, {position: 'bottomright'}).addTo(mymap);
	
	var lat = mymap.getCenter().lat;
	var lng = mymap.getCenter().lat;
	var center = mymap.getCenter();
	
	if($("#latitude").val() != '')lat = $("#latitude").val();
	if($("#longitude").val() != '')lng = $("#longitude").val();
	
	center = [lat, lng];
	
	$("#latitude").val(lat);
	$("#longitude").val(lng);
	marker = L.marker(center, {draggable: true}).addTo(mymap);
	marker.on('drag', function(e) {
		$("#latitude").val(marker.getLatLng().lat);
		$("#longitude").val(marker.getLatLng().lng);
    });
}

function reposMarker(lat, lng){
	var newLatLng = new L.LatLng(lat, lng);
    marker.setLatLng(newLatLng); 
}

var Form = function(form){
	var tableColumnCount 	= 8;
	var form				= $(form);
	
	Form.prototype.post = function(btn){
		$(btn).button("loading");
		
		var data = new FormData(form[0]);
		$.ajax({type: "POST", url: "<?php echo $postURL; ?>", dataType: 'json', data: data, processData: false, contentType: false,
			success: function(data){				
				if(data[0].status == 1){
					showAlert(".alert", btn, "alert-success", data[0].msg);
				}
				else{
					showAlert(".alert", btn, "alert-danger", data[0].msg);
				}
			},
			error: function (data) {
				showAlert(".alert", btn, "alert-danger", JSON.stringify(data));
			},
			complete : function () {
				$('html, body').animate({ scrollTop: $("body").offset().top }, 500);
			}
		});
	};
}

$("#formxx").submit(function(e){
	e.preventDefault();
	
	$("#form .btn-suc")
	
});


</script>
<style>
.mapform{
	text-align:left;
	padding-top:15px;
}
.mapform .btn i{
	margin:0px;
}

#map{
	width:100%;
	height:300px;
}
.checkbox {
  display: inline-block;
  position: relative;
  padding-left: 20px;
  margin-right:15px;
  cursor: pointer;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
  width:28%;
}

/* Hide the browser's default checkbox */
.checkbox input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
  height: 0;
  width: 0;
}

/* Create a custom checkbox */
.checkbox .checkmark {
  position: absolute;
  top: 0;
  left: 0;
  height: 15px;
  width: 15px;
  margin-top:5px;
  background-color: #ccc;
}

/* On mouse-over, add a grey background color */
.checkbox:hover input ~ .checkmark {
  background-color: #aaa;
}

/* When the checkbox is checked, add a blue background */
.checkbox input:checked ~ .checkmark {
  background-color: #337ab7;
}

/* Create the checkmark/indicator (hidden when not checked) */
.checkmark:after {
  content: "";
  position: absolute;
  display: none;
}

/* Show the checkmark when checked */
.checkbox input:checked ~ .checkmark:after {
  display: block;
}

/* Style the checkmark/indicator */
.checkbox .checkmark:after {
  left: 5px;
  top: 1px;
  width: 5px;
  height: 10px;
  border: solid white;
  border-width: 0 3px 3px 0;
  -webkit-transform: rotate(45deg);
  -ms-transform: rotate(45deg);
  transform: rotate(45deg);
}
</style>
