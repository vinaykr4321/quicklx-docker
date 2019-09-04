$(document).ready(function(){

	$(".custom-select option:first").attr("value","All").text("All Companies").removeAttr("data-ignore");

	/*var select_company = function(Y, companyid) {
	options = $('.custom-select').children('option');
	options.each(function(){
	if((this.value == companyid) && (window.location.search == "" )){
	$(".custom-select option:first").removeAttr("selected");
	$(this).attr('selected', 'selected');
	}
	});
	}*/

	var urlParams = new URLSearchParams(location.search);

	if ($('ul').hasClass("pagination")) {
		$("#selectperpage").show();
	} else if(urlParams.has('select')){
		$("#selectperpage").show();
	} else if(!$('ul').hasClass("pagination")){
		$("#selectperpage").hide();
	}


	$(".generaltable .c2").css({"display":"block","word-wrap":"break-word"});
	var C3width = $(".generaltable .c3").outerWidth();
	$(".generaltable .c2").width(C3width);

	$(window).resize(function(){
		$(".generaltable .c2").width($(".generaltable .c3").width());
	});

});
