$(function(){
	$('.slideshow').css({ height: $(window).innerHeight() });
	$(window).resize(function(){
		$('.slideshow').css({ height: $(window).innerHeight() });
	});

	$(document).on( 'scroll', function(){
		var div = $(this);
        if(div.scrollTop() == 0){
            $(".header").removeClass("scroll");
        }
		else{
            $(".header").addClass("scroll");
		}
	});
  
});