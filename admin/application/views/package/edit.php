
<h3>Form Package</b></h3>
<hr />
<div class="col-md-12">
	<div class="container">
		<form id="form" class="form-horizontal label-left">
			<div class="col-md-10">
				<input type="hidden" name="key" value="<?php if($data){echo $data[0]["id"];} ?>" />
				<input type="hidden" name="crud" value="<?php echo $form; ?>" />

				<br />
				<div class="form-group">
					<label class="control-label col-sm-2" >Tags</label>
					<div class="col-sm-5">
						<select class="form-control input-md" name="tag_id">
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
					<div class="col-sm-5">
						<input type="text" class="form-control input-md" name="package_name" placeholder="Package Name" value="<?php echo $data[0]["package_name"]; ?>">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" >Icon ON</label>
					<div class="col-sm-3">
						<input type="file" class="form-control input-md" name="icon_on" />
					</div>
					<?php
					if($data)echo $data[0]["ic_on"];
					?>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" >Icon OFF</label>
					<div class="col-sm-3">
						<input type="file" class="form-control input-md" name="icon_off" />
					</div>
					<?php
					if($data)echo $data[0]["ic_off"];
					?>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" >Icon Marker</label>
					<div class="col-sm-3">
						<input type="file" class="form-control input-md" name="icon_marker" />
					</div>
					<?php
					if($data)echo $data[0]["ic_marker"];
					?>
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
					showAlert("#form .alert", btn, "alert-success", data[0].msg);


					if($('#form input[name=crud]').val() == 'delete')alert("Data berhasil dihapus");

					setTimeout(function(e){
						closeModalDialog(true, 1);
					}, 500);
				}
				else{
					showAlert("#form .alert", btn, "alert-danger", data[0].msg);
				}
			},
			error: function (data) {	
				showAlert("#form .alert", btn, "alert-danger", JSON.stringify(data));
			},
			complete : function () {
				$('html, body').animate({ scrollTop: $("body").offset().top }, 500);
			}
		});
	};
}


</script>

