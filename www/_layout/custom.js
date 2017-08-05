

	$(function(){


		init_forms();

		init_sideNavigation();

		init_panels();

	});
	

	function init_sideNavigation(){

		$("#navigation > li > a").click(function(){

			var parent = $(this).closest('li');
			

			if ($('ul',parent).size()){

				if ($(parent).hasClass('active')){

					$('ul',parent).slideUp('fast',function(){

						$(parent).removeClass('active');

					});

				}else{

					$('ul',parent).slideDown('fast');

					$(parent).addClass('active');

				}
				return false;
			}		

		});
	}
	

	function init_forms(){ 	

		if($("select, input:checkbox, input:radio, input:file").size()){

			$("select, input:checkbox, input:radio, input:file").not(".noUniform").uniform();

		}		

		$("form .submit").click(function(){

			$(this).closest('form').submit(); 

		});

	}

	function init_panels() {

		$('.panel .collapse').click(function(){
			if ($(this).closest('.panel').hasClass('collapsed')){

				var restoreHeight = $(this).attr('id');

				$(this).closest('.panel').animate({height:restoreHeight+'px'}, function() {  
					$(this).removeClass('collapsed');
				});
			}else{
				var currentHeight = $(this).closest('.panel').height();		

				$(this).attr('id', currentHeight);
				$(this).closest('.panel').addClass('collapsed').animate({height:'45px'}, function(){});
			}
		}); 

		$('.panel .tabs li').click(function(){

			var parent = $(this).closest('.panel');

			var content = $('a', this).attr('rel');

			$('.tabs .active', parent).removeClass('active');

			$(this).addClass('active');
			
			$('.tabs-content > .active', parent).removeClass('active');
			
			$('#'+content).addClass('active');
		
			return false;

		});
	}
