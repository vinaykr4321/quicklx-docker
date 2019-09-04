$( document ).ready(function() {

	/*$('#add').click(function(){
		$('.pickusers :selected').each(function(){
			$(".users").append(new Option($(this).text(),$(this).val()));
			$(".alluser").append('<option value="'+$(this).val()+'" selected>'+$(this).text()+'</option>');
		});

		$('.pickusers :selected').hide();
		$(".pickusers option:selected").prop("selected", false);
	});

	$('#remove').click(function(){
		$('.users :selected').each(function(){
			//$('.users :selected').remove();
			$(this).remove();
			$(".alluser option[value=" + $(this).val() + "]").remove();
			$(".pickusers option[value=" + $(this).val() + "]").show();
		    //selected[$(this).val()]=$(this).text();
		});
	});

	$('#addall').click(function(){
		$('.users option').remove();
		$(".alluser option").remove();
		$('.pickusers option').each(function(){
			$(".users").append(new Option($(this).text(),$(this).val()));
			$(".alluser").append('<option value="'+$(this).val()+'" selected>'+$(this).text()+'</option>');
			$(".pickusers option[value=" + $(this).val() + "]").hide();
		});
	});

	$('#removeall').click(function(){
		$('.users option').remove();
		$(".alluser option").remove();
		$('.pickusers option').show();
		/*$('.users option').each(function(){
			$(".users").append(new Option($(this).text(),$(this).val()));
			$(".pickusers option[value=" + $(this).val() + "]").hide();
		});/
	});*/


	$('#view,#set').click(function(){
		if($('#id_users option').filter(':selected').text() == '')
	    {
	       	alert('Please select a user');
	   		return false;
	    }
	});



	window.onmousedown = function (e) {
	    var el = e.target;
	    if (el.tagName.toLowerCase() == 'option' && el.parentNode.hasAttribute('multiple')) {
	        e.preventDefault();

	        // toggle selection
	        if (el.hasAttribute('selected')) el.removeAttribute('selected');
	        else el.setAttribute('selected', '');

	        // hack to correct buggy behavior
	        var select = el.parentNode.cloneNode(true);
	        el.parentNode.parentNode.replaceChild(select, el.parentNode);
	    }
	}
	$('#id_users option').click(function(){
		window.onmousedown = function (e) {
		    var el = e.target;
		    if (el.tagName.toLowerCase() == 'option' && el.parentNode.hasAttribute('multiple')) {
		        e.preventDefault();

		        // toggle selection
		        if (el.hasAttribute('selected')) el.removeAttribute('selected');
		        else el.setAttribute('selected', '');

		        // hack to correct buggy behavior
		        var select = el.parentNode.cloneNode(true);
		        el.parentNode.parentNode.replaceChild(select, el.parentNode);
		    }
		}	
		
	});


$('#selectall').click(function(){
	$('#id_users option').attr('selected', 'selected');
	});

$('#removeall').click(function(){
	$('#id_users option').removeAttr("selected");
	});










}); // document.ready close here