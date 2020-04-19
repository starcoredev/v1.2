
<h3>Form Icon | <b><?php echo $client[0]["name"]; ?></b></h3>
<hr />
<div class="col-md-12">
	<div class="container">
		<form id="form" class="form-horizontal label-left">
			<div class="col-md-10">
				<input type="hidden" name="key" value="<?php if($data){echo $data[0]["id"];} ?>" />
				<input type="hidden" name="crud" value="<?php echo $form; ?>" />
				<input type="hidden" name="client_id" value="<?php echo $client[0]["id"]; ?>" />
				<input type="hidden" name="type" value="<?php echo $type; ?>" />
				<input type="hidden" id="package_id" name="package_id" value="<?php echo $data[0]["package_id"]; ?>" />

				<br />
				<div class="form-group">
					<label class="control-label col-sm-2" >Tags</label>
					<div class="col-sm-5">
						<select class="form-control input-md" id="tag_id" name="tag_id">
							<?php
							foreach($tags as $t){
								$selected = '';
								
								if($data[0]){
									if($data[0]["tag_id"] == $t["id"])$selected = ' selected="selected" ';
								}
								
								echo '<option '.$selected.' value="'.$t["id"].'">'.$t["name"].'</option>';
							}							
							?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" >Package Name</label>
					<div class="col-sm-3">
						<input type="text" class="form-control input-md" readonly="readonly" id="package_name" name="package_name" placeholder="Package Name" value="<?php echo $data[0]["package_name"]; ?>">
					</div>
					<div class="col-sm-1">
						<button class="btn btn-md" onclick="cariPackage()"><i class="fa fa-search"></i>&nbsp;Cari Package</button>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" >Icon ON</label>
					<div id="icon_on" class="col-sm-5"><?php echo $data[0]["icon_on"]; ?></div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" >Icon OFF</label>
					<div id="icon_off" class="col-sm-5"><?php echo $data[0]["icon_off"]; ?></div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" >Icon Marker</label>
					<div id="icon_marker" class="col-sm-5"><?php echo $data[0]["icon_marker"]; ?></div>
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
		
<script>

var formx;

$(document).ready(function(e){
	$("#tag_id").change(function(e){
		clear();
	});
	$("#form").submit(function(e){
		e.preventDefault();
	});
	setTimeout(function(e){
		formx = new Form("#form");
	}, 500);
	
});

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
					setTimeout(function(e){
						closeModalDialog(true, 1);
					}, 500);
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

function clear(){
	$("#package_id").val("");
	$("#package_name").val("");
	$("#icon_on").html("");
	$("#icon_off").html("");
	$("#icon_marker").html("");
}

function cariPackage(){
	showDialogxxx("<?php echo base_url(); ?>search-dialog/dialog", {"target" : "package", "hidecolumns":["ID", "tag_id"], "tag":$("#tag_id").val(), "client": "<?php echo $client[0]["id"]; ?>", "multiselect" : false, "callback": "cariPackageCallback", "cancel": "closeDialog"});
}
function cariPackageCallback(d){
	//alert(JSON.stringify(d));
	$("#package_id").val(d[0]["ID"]);
	$("#package_name").val(d[0]["package_name"]);
	$("#icon_on").html(d[0]["icon_on"]);
	$("#icon_off").html(d[0]["icon_off"]);
	$("#icon_marker").html(d[0]["icon_marker"]);
	
	closeDialog();
}
function closeDialog(){
	$("#myModalxxx").modal("hide");
}

function showDialogxxx(target, param){
	$("#myModalxxx").modal();
	
	$.ajax({type: "POST", url: target, dataType: 'html', data: param, 
		success: function(data){
			//alert(data);
			//alert(JSON.stringify(data));
			$("#myModalxxx .modal-content .modal-body").html(data);
		},
		error: function (data) {
			//alert(data);
			//alert(JSON.stringify(data));
			$("#myModalxxx .modal-content .modal-body").html(JSON.stringify(data));
		}
	});
	//$("#myModal .modal-body").load(target);
}

</script>

