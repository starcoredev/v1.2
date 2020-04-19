
<h3>Form User | <b><?php echo $client[0]["name"]; ?></b></h3>
<hr />
<div class="col-md-12">
	<div class="container">
		<form id="form" class="form-horizontal label-left">
			<div class="col-md-6">
				<input type="hidden" name="key" value="<?php if($data){echo $data[0]["id"];} ?>" />
				<input type="hidden" name="crud" value="<?php echo $form; ?>" />
				<input type="hidden" name="client_id" value="<?php echo $client[0]["id"]; ?>" />
				<input type="hidden" name="type" value="<?php echo $type; ?>" />

				<br />
				<div class="form-group">
					<label class="control-label col-sm-3" >User Name</label>
					<div class="col-sm-8">
						<input type="text" class="form-control input-md" name="username" placeholder="User Name" value="<?php echo $data[0]["username"]; ?>">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" >Email</label>
					<div class="col-sm-8">
						<input type="text" class="form-control input-md" name="email" placeholder="Email" value="<?php echo $data[0]["email"]; ?>">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" >Password</label>
					<div class="col-sm-5">
						<input type="text" readonly="readonly" id="pwd" class="form-control input-md" name="pwd" placeholder="Password" value="<?php echo $data[0]["upwd"]; ?>">
					</div>
					<div class="col-sm-2">
						<button class="btn btn-md btn-warning" onclick="generatePwd()"><i class="fa fa-refresh"></i> Generate</button>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" >Tag Filter</label>
					<div class="col-sm-8">
						<input type="hidden" id="tag_filter" name="tag_filter" value="<?php echo $data[0]["tag_filter"]; ?>" />
					<?php
					$tArray = array();
					if($data)$tArray = explode(",", $data[0]["tag_filter"]);
					foreach($tags as $t){
						$checked = '';
						
						if(in_array($t["id"], $tArray))$checked = ' checked="checked" ';
						
						echo 	'<label class="tags col checkbox align-left" value="'.$t["id"].'">'.$t["name"].'
								  <input type="checkbox" value="'.$t["id"].'"  '.$checked.' onchange="buildCol()" >
								  <span class="checkmark"></span>
								</label>';
					}
					?>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" >Status Active</label>
					<div class="col-sm-8">
						<label class="col checkbox align-left">
								  <input type="checkbox" <?php echo ($data?($data[0]["active"]==1?' checked="checked" ' : '') : '') ;?>  name="active" >
								  <span class="checkmark"></span>
								</label>
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
	$("#form").submit(function(e){
		e.preventDefault();
	});
	setTimeout(function(e){
		formx = new Form("#form");
	}, 500);
	
	
});

function generatePwd(){
	$("#pwd").val("");
	$("#pwd").attr("type", "text");
	
	var n = "";
	for(var i = 1; i < 10; i++){
		n += (Math.floor(Math.random() * 10) + "");
	}
	$("#pwd").val(n);
}

function buildCol(){
	var tags = ''; 
	$('.tags').each(function(i, obj) {
		if($(this).find("input").is(":checked"))tags += $(this).attr("value") + ",";
	});
	$("#tag_filter").val(tags.substring(0, tags.length-1));
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
					setTimeout(function(e){
						closeModalDialog(true, 2);
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

</script>

<style>


.checkbox {
    position: relative;
    padding-left: 25px;
    margin-bottom: 0px;
    cursor: pointer;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
	
}

.checkbox span {
	
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
    left: 0;
    height: 15px;
    width: 15px;
    background-color: #aaa;
}

/* On mouse-over, add a grey background color */
.checkbox:hover input ~ .checkmark {
    background-color: #ccc;
}

/* When the checkbox is checked, add a blue background */
.checkbox input:checked ~ .checkmark {
    background-color: #2196F3;
}

/* Create the checkmark/indicator (hidden when not checked) */
.checkbox .checkmark:after {
    content: "";
    position: absolute;
	top:0;
	left:0;
    display: none;
}

/* Show the checkmark when checked */
.checkbox input:checked ~ .checkmark:after {
    display: block;
}

/* Style the checkmark/indicator */
.checkbox .checkmark:after {
    left: 35%;
	transform: translateX(-50%);
    top: 10%;
	transform: translateY(-50%);
    width: 5px;
    height: 10px;
    border: solid white;
    border-width: 0 3px 3px 0;
    -webkit-transform: rotate(45deg);
    -ms-transform: rotate(45deg);
    transform: rotate(45deg);
}
</style>