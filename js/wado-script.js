jQuery(document).ready(function(){
	jQuery(document).on('click', '.term-description.wado-index button.wado-more', function(e){
		console.log(jQuery(this));
		jQuery(this).css('display', 'none');
		jQuery('.term-description.wado-index button.wado-less').css('display', 'block');
		jQuery('.term-description.wado-index').removeClass('wado-less').addClass('wado-more');
	});	  
	
	jQuery(document).on('click', '.term-description.wado-index button.wado-less', function(e){
		console.log(jQuery(this));
		jQuery(this).css('display', 'none');
		jQuery('.term-description.wado-index button.wado-more').css('display', 'block');
		jQuery('.term-description.wado-index').removeClass('wado-more').addClass('wado-less');
	});

	/*isEllipsisActive = function (obj) {
		console.log('scroll: '+obj.scrollWidth);
		console.log('client: '+obj.clientWidth);
		console.log('scroll > client: '+obj.scrollWidth > obj.clientWidth);
		return obj.scrollWidth > obj.clientWidth;
	}
	
	jQuery('ul.wado-index-list li a h2[data-toggle="tooltip"]').each(function(i) {
		if (isEllipsisActive(this)) {
			jQuery('[data-toggle="tooltip"]').tooltip('enable'); 
			console.log('Active');
		} else {
			jQuery('[data-toggle="tooltip"]').tooltip('disable');
			console.log('Inactive');
		}
	});*/
})