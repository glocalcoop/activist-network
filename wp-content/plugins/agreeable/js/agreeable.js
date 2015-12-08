jQuery(document).ready(function($) {

	$(document).on("DOMSubtreeModified", function(){
		$('.ag-open-popup-link').magnificPopup({
		  type:'inline',
		  midClick: true,
		});
	});
	
	$(document).ready(function(){
		$('.ag-open-popup-link').magnificPopup({
		  type:'inline',
		  midClick: true,
		});
	});
	
	if($('.woocommerce .login')) {
		$(".woocommerce>#agreeable_login_field").insertBefore(".woocommerce .login #rememberme");
	}
			
});