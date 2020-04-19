<h3>Form Client</h3>
<hr />
<div class="col-md-12">
	<div class="container">
		<form id="form" class="form-horizontal label-left">
			<div class="col-md-12">
				<input type="hidden" name="key" value="<?php if($data){echo $data[0]["id"];} ?>" />
				<input type="hidden" name="crud" value="<?php echo $form; ?>" />

				<br />
				<div class="form-group">
					<label class="control-label col-sm-1" >Name</label>
					<div class="col-sm-8">
						<input type="text" class="form-control input-md" name="name" placeholder="Name" value="<?php echo $data[0]["name"]; ?>">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-1" >Max User</label>
					<div class="col-sm-2">
						<input type="number" class="form-control input-md" min="0" name="max_user" placeholder="Max User" value="<?php echo $data[0]["max_user"]; ?>">
					</div>
					<label class="control-label col-sm-1" >Start Date</label>
					<div class="col-sm-2">
						<input type="text" class="datetimepicker form-control input-md" name="start_date" placeholder="Start Date" value="<?php echo $data[0]["start_date"]; ?>">
					</div>
					<label class="control-label col-sm-1" >End Date</label>
					<div class="col-sm-2">
						<input type="text" class="datetimepicker form-control input-md" name="end_date" placeholder="End Date" value="<?php echo $data[0]["end_date"]; ?>">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-1" >Map Title</label>
					<div class="col-sm-8">
						<textarea class="form-control input-md" name="map_title" placeholder="Map Title" ><?php echo $data[0]["map_title"]; ?></textarea>
					</div>
				</div>
				
				<div class="col-md-12 line-break"><hr /></div>
				<div class="form-group">
					<label class="control-label col-sm-1" ></label>
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

$(function () {
	try{
		$('.datetimepicker').datetimepicker({format : 'YYYY-MM-DD'});
		
	}
	catch(e){
		alert(e);
	}
});

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
					setTimeout(function(e){
						closeModalDialog(true);
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

