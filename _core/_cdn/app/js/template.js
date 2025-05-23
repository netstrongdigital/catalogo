$( document ).ready(function() {

	// FITS
		function minfit() {
			var screen_height = $(window).height();
			// MINFIT
			var header_height = $(".header").height();
			var footer_height = $(".footer").height();
			var minfit = screen_height - ( header_height + footer_height );
			$(".minfit").css("min-height",minfit);
		}
		minfit();
		function minfitmobile() {
			var screen_height = $(window).height();
			// MINFIT
			var header_height = $(".header-interna").height();
			var footer_height = $(".footer").height();
			var minfit = screen_height - ( header_height + footer_height );
			$(".minfitmobile").css("min-height",minfit);
		}
		minfitmobile();
		// FULL
		function fullfit() {
			var screen_height = $(window).height();
			$(".fullfit").css("min-height",screen_height).animate(500);
		}
		fullfit();
		$(window).on('resize', function(){
			minfit();
			fullfit();
		});

	// SIDEBARS

	if( $.isFunction( $.sidr ) ) {

		$('.sidrLeft').sidr({
			side: 'left',
			name: 'sidrLeft',
			source: '#sidebarLeft',
			renaming: false
	    });

	    $( "body > *:not(.sidr)" ).click(function() {
		    $.sidr('close', 'sidrLeft')
		    $.sidr('close', 'sidrRight');
	    });

		// $('body').swipe( {
	 //        swipeLeft: function () {
	 //            $.sidr('close', 'sidrLeft');
	 //        },
	 //        swipeRight: function () {
	 //            $.sidr('close', 'sidrRight');
	 //        },
	 //        threshold: 45
	 //    });

	}

});

// Clean pass autofill

$('#the_form input[type=password]').val('')

// Campos dependentes

$( ".elemento-dependente" ).fadeOut(0);

function campo_dependente(dependente) {

	$(".elemento-dependente[dependente="+dependente+"]").fadeOut(0);
	var thisvalue = $( dependente ).val();
	$(".elemento-dependente[dependente="+dependente+"].elemento-dependente[dependente_value="+thisvalue+"]").fadeIn(0);
	$(".elemento-dependente[dependente="+dependente+"].elemento-dependente[dependente_value_2="+thisvalue+"]").fadeIn(0);
	$(".elemento-dependente[dependente="+dependente+"].elemento-dependente[dependente_value_3="+thisvalue+"]").fadeIn(0);
	$(".elemento-dependente[dependente="+dependente+"].elemento-dependente[dependente_value_4="+thisvalue+"]").fadeIn(0);
	$(".elemento-dependente[dependente="+dependente+"].elemento-dependente[dependente_value_5="+thisvalue+"]").fadeIn(0);

}

$( ".campo-dependente" ).trigger("change");

// MASK

$( document ).ready(function() {

	$(".maskdate").mask("99/99/9999",{placeholder:""});
	$(".maskrg").mask("99999999-99",{placeholder:""});
	$(".maskcpf").mask("999.999.999-99",{placeholder:""});
	$(".maskcnpj").mask("99.999.999/9999-99",{placeholder:""});
	$(".maskcel").mask("(99) 99999-9999");
	$(".maskcep").mask("99999-999");
	$(".dater").mask("99/99/9999");
	$(".masktime").mask("99:99:99");
	$(".maskmoney").maskMoney({
	    prefix: "R$ ",
	    decimal: ",",
	    thousands: "."
	});

});

$(".form-field-radio").click(function() {
	$(this).children('input').prop('checked',true);
});

$( ".elemento-oculto" ).fadeOut(0);

$(".form-field-radio").click(function() {
	var showlement = $(this).children('input').attr("element-show");
	var hidelement = $(this).children('input').attr("element-hide");
	$( showlement ).fadeIn(100);
	$( hidelement ).fadeOut(100);
	$(this).children('input').prop('checked',true);
});

$(document).on('submit','#the_form',function(){
   $(".processing").show();
});

$(document).ready(function(){
	$(".grudado").sticky({topSpacing:0});
});

function resizelastinfinitecol() {

	var largura = $(".container").width();
	$(".col-infinite-last").css("width",largura);
	$(".col-infinite-last").css("max-width",largura);

}

resizelastinfinitecol();

$(window).on('resize', function(){
	resizelastinfinitecol();
});

// Campo numero

$( ".campo-numero .decrementar" ).click(function() {

	var valor = parseInt( $(this).siblings('input').val() );
	var newvalor = valor-1;
	if( newvalor >= 1 ) {
		$(this).siblings('input').val(newvalor);
	} else {
		$(this).siblings('input').val(1);
	}
	$(this).siblings('input').trigger("change");

});

$( ".campo-numero .incrementar" ).click(function() {

	var valor = parseInt( $(this).siblings('input').val() );
	var newvalor = valor+1;
	$(this).siblings('input').val(newvalor);
	$(this).siblings('input').trigger("change");

});

$(".carousel").on("touchstart", function(event){
        var xClick = event.originalEvent.touches[0].pageX;
    $(this).one("touchmove", function(event){
        var xMove = event.originalEvent.touches[0].pageX;
        if( Math.floor(xClick - xMove) > 5 ){
            $(this).carousel('next');
        }
        else if( Math.floor(xClick - xMove) < -5 ){
            $(this).carousel('prev');
        }
    });
    $(".carousel").on("touchend", function(){
            $(this).off("touchmove");
    });
});

function telacheia() {

  if (!document.fullscreenElement && !document.mozFullScreenElement && !document.webkitFullscreenElement && !document.msFullscreenElement ) {if (document.documentElement.requestFullscreen) {document.documentElement.requestFullscreen(); } else if (document.documentElement.msRequestFullscreen) {document.documentElement.msRequestFullscreen(); } else if (document.documentElement.mozRequestFullScreen) {document.documentElement.mozRequestFullScreen(); } else if (document.documentElement.webkitRequestFullscreen) {document.documentElement.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT); } } else {if (document.exitFullscreen) {document.exitFullscreen(); } else if (document.msExitFullscreen) {document.msExitFullscreen(); } else if (document.mozCancelFullScreen) {document.mozCancelFullScreen(); } else if (document.webkitExitFullscreen) {document.webkitExitFullscreen(); } }
}

InstantClick.init();