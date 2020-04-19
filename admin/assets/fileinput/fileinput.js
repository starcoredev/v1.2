$(".fileinput a").click(function(e){
	$(this).parent().find("input[type=file]").click();
});
$(".fileinput input[type=file]").change(function(e){
	var input = this;
	var ext = input.files[0]['name'].substring(input.files[0]['name'].lastIndexOf('.') + 1).toLowerCase();
	
	if (input.files && input.files[0] && (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) {
		var reader = new FileReader();
		reader.onload = function (e) {
			$(input).parent().find("img").attr('src', '' + e.target.result + '');
		}
		
		reader.readAsDataURL(input.files[0]);
	}
});
$(".fileinput button").click(function(e){
	e.preventDefault();
	
	$(this).parent().find("img").attr("src", $(this).val());
	$(this).parent().find("input[type=file]").val("");
	
});