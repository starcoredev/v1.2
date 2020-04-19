
<!-- Page Content -->
<div class="page-title-2">
	<div class="col-md-6"><h1>Form Kategori</h1></div>	
</div>

<div class="col-md-12 page-content after-title">
	<div class="container">
		<form id="form" class="form-horizontal label-left">
			<div class="col-md-6">
				<input type="hidden" name="key" value="<?php if($data){echo $data[0]["id"];} ?>" />
				<input type="hidden" name="crud" value="<?php echo $form; ?>" />

				<br />
				<div class="form-group">
					<label class="control-label col-sm-3" >Bank</label>
					<div class="col-sm-5">
						<select class="form-control input-md" name="bank">
							<?php
							$bank = array("BRI", "BCA", "Mandiri");
							for($i = 0; $i < count($bank); $i++){
								$selected = "";
								if($data[0]["bank"] == $bank[$i])$selected = ' selected = "selected" ';
								echo '<option value="'.$bank[$i].'" '.$selected.' >'.$bank[$i].'</option>';
							}
							?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" >Atas Nama</label>
					<div class="col-sm-5">
						<input type="text" class="form-control input-md" name="atas_nama" placeholder="Atas Nama" value="<?php echo $data[0]["atas_nama"]; ?>">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" >Nomor Rekening</label>
					<div class="col-sm-5">
						<input type="text" class="form-control input-md" name="nomor_rekening" placeholder="Nomor Rekening" value="<?php echo $data[0]["nomor_rekening"]; ?>">
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



<script>

var formx;

$(document).ready(function(e){
	formx = new Form("#form");
	
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

