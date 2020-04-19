
<!-- Page Content -->
<div class="page-title-2">
	<div class="col-md-6"><h1>Form Reset Password</h1></div>	
</div>

<div class="col-md-12 page-content after-title">
	<div class="container">
		<form id="form" class="form-horizontal label-left">
			<div class="col-md-6">
				<br />
				<div class="form-group">
					<label class="control-label col-sm-4" >Password Lama</label>
					<div class="col-sm-5">
						<input type="password" class="form-control input-md" name="old" name="old" placeholder="Password Lama" value="">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-4" >Password Baru</label>
					<div class="col-sm-5">
						<input type="password" class="form-control input-md" name="new" placeholder="Password Lama" value="">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-4" >Ulangi Password Baru</label>
					<div class="col-sm-5">
						<input type="password" class="form-control input-md" name="new2" placeholder="Ulangi Password Baru" value="">
					</div>
				</div>
				
				<div class="col-md-12 line-break"><hr /></div>
				<div class="form-group">
					<label class="control-label col-sm-4" ></label>
					<div class="col-sm-8">
						<button class="btn btn-sm btn-success" onclick="formx.post(this);" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Please wait..."><i class="fa fa-save"></i>Reset Password</button>
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

