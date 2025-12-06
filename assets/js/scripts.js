// JavaScript Document

$(document).ready(function() {
	
	$('.js-toggleModal').click(function() {
		$('.modalOverlay').css('display','block');
	});
	
	$('.js-addVenue').click(function(){
		$('#addVenue.modalWindow').css('display','inline-block');
	});
	$('.js-addShow').click(function(){
		$('#addShow.modalWindow').css('display','inline-block');
	});
	$('.js-scheduleShow').click(function(){
		$('#scheduleShow.modalWindow').css('display','inline-block');
	});
	$('.js-cancelShow').click(function(){
		$('.scheduledShowName').empty();
		$('.scheduledShowStart').empty();
		$('.scheduledShowEnd').empty();
		$('#cancelShow.modalWindow').css('display','inline-block');
		// $('#cancelledShowID').attr();
	});
	$('.js-deleteProduct').click(function() {
		$('.deletedProductName').empty();
		$('#deleteProduct.modalWindow').css('display','inline-block');
	});
	
	
	$('.formGroup').on('click', '.js-closeModal', function() {
		$(this).parents('.modalWindow').css('display','none');
		$(this).parents('.modalOverlay').css('display', 'none');
		
		// If this was the "cancel show modal", empty out the variables
		
		/* if ($(this).parents('#cancelShow').length > 0) {
			$('.scheduledShowName').empty();
			$('.scheduledShowStart').empty();
			$('.scheduledShowEnd').empty();
		} */
		
	});
	
	// Is this even used?
	$('.js-productDetail').click(function() {
		$('#productDetail.modalWindow').css('display','block');
		
			var record_id = $(this).attr('data-id');
			
			$.ajax ({
				url: "includes/modal_processors/store.php",
				type: "POST",
				data: {product:record_id},
				
				success:function(response){
					$('.modalContent').html(response);
				}
				
			});
	});
	
	$('.js-closeModal').click(function() {
		$(this).parents('.modalOverlay').css('display','none');
		$(this).parents('.modalWindow').css('display','none');
		$(this).siblings('.modalContent').empty();
	});
	
	
	// Get show information from Cancel button click
	$('.js-cancelShow').click(function(){ 
		var scheduleID = $(this).attr('data-id');
		var scheduleShow = $(this).attr('data-name');
		var scheduleBegin = $(this).attr('data-date-begin');
		var scheduleEnd = $(this).attr('data-date-end');
		var scheduleEntryID = $(this).attr('data-schedule-id');
		// Put the info into the modal
		$('.scheduledShowName').append(scheduleShow);
		$('.scheduledShowStart').append(scheduleBegin);
		$('.scheduledShowEnd').append(scheduleEnd);
		// Put the info in the hidden form fields so I don't have to try to find it all again in the processing file
		// Really - this saves having to do the date transform, table joins and all of that jazz
		$('#cancelledShowID').attr('value', scheduleID);
		$('#cancelledShowName').attr('value', scheduleShow);
		$('#cancelledShowStart').attr('value', scheduleBegin);
		$('#cancelledShowEnd').attr('value', scheduleEnd);
		$('#cancelledShowScheduleID').attr('value', scheduleEntryID);
	
	});

	// Get product information from Delete product button click
	$('.js-deleteProduct').click(function() {
		var productID = $(this).attr('data-id');
		var productName = $(this).attr('data-name');
		var productType = $(this).attr('data-type');
		
		// Put the infor into the modal
		$('.deletedProductName').append(productName);
		// Put the info in the hidden form fields
		$('#deletedProductID').attr('value', productID);
		$('#deletedProductName').attr('value', productName);
		$('#deletedProductType').attr('value', productType);
	});
	
	
	// Set active store page
	if ($('.catalogContainer').attr('data-category') === 'earrings') {
		$('#earrings-tab').addClass('active');
	} else if ($('.catalogContainer').attr('data-category') === 'necklace') {
		$('#necklaces-tab').addClass('active');
	}
		
	// STORE ADMIN SELECT
	$(function() {
		$('#productCategorySelect').change(function() {
			this.form.submit();
		});
	});
	$(function() {
		$('#supplierSelect').change(function() {
			this.form.submit();
		});
	});
	
	// STORE CATALOG SLIDE

	$('.cardContainer').on('click', '.js-detailToggle', function() { 
		
		var thisCard = $(this).parents('.cardContainer');
		$(thisCard).toggleClass("flip");
		
		
		
		
	});
	
	// Product Detail Description Character Counter
	// See how many characters are in the text area when the page loads
	var descCount = $('#productDesc').val().length;
	if (descCount > 0) {
		$('.char_limit_count').html(descCount);
	}
	$('#productDesc').on('input', function() {	
		// genericize this in a function so that it can be used across other fields with character limits
		
		
		var count = $(this).val();
		count = count.length;
			
		$('.char_limit_count').html(count);
		if (count >= 500) {
			$(this).addClass('error');
			
			var helpID = $(this).attr('aria-describedby');
			$('#' + helpID).addClass('error');
			// add alert icon
			var alertIcon = "<i class='fas fa-exclamation-triangle formHelpError' role='image' aria-label='Alert'></i> ";
			$('#' + helpID).prepend(alertIcon);
			// change color of characters past the 500 mark?
		} else if (count < 500) {
			$(this).removeClass('error');
			helpID = $(this).attr('aria-describedby');
			$('#' + helpID).removeClass('error');
			$('.fa-exclamation-triangle').remove();
		}
	});
	
		// STORE CATALOG CARD FLIP
	/* $('.cardContainer').on('click', '.js-detailToggle', function() {
		
		var thisCard = $(this).parents('.card.test');
		
		if ( $(thisCard).hasClass('opened')) {
			
			$(thisCard).toggleClass('opened');
			$('.card').removeAttr('style');
		} else {

			// Open the card
			// $(thisCard).toggleClass('opened');
			var cWidth = $(this).parents('.card').outerWidth();
			var cHeight = $(this).parents('.card').outerHeight();
			var vHeight = $(window).height();
			var vWidth = $(window).width();
			vHeight = vHeight - cHeight;
			vWidth = vWidth - cWidth;
			vHeight = vHeight / 2;
			vWidth = vWidth / 2;
			
			// $(thisCard).css({'width':cWidth,'height':cHeight});
			// $(thisCard).css({'position':'fixed','top':vHeight,'left':vWidth});
			
			// Find the x/y of every card, for that card, set the position to that.

			
			var cardLayout = [];
			// Build layout array
			// capture position, top, left, width, height
			var cardOffset;
			$('.card').each(function() {
				cardOffset = $(this).offset();
				var cardTop = cardOffset.top;
				var cardLeft = cardOffset.left;
				var cardWidth = $(this).outerWidth();
				var cardHeight = $(this).outerHeight();
				cardWidth = cardWidth +'px';
				cardHeight = cardHeight + 'px';
				
				cardLayout.push({'position':'absolute','top':cardTop,'left':cardLeft,'width':cardWidth,'height':cardHeight});

			});

			// Assign layout to each card
			$('.card').each(function(i) {
				$(this).css(cardLayout[i]);
			
			});
			// Pop out selected card
			$(this).removeAttr('style');
			$(thisCard).css({'width':cWidth,'height':cHeight});
			$(thisCard).css({'position':'fixed','top':vHeight,'left':vWidth});
			
		}

		
		
	});
	*/
	
	
	function scaleSlide() {
		$('.itemImage').each(function() {
			var imgWidth = $(this).outerWidth();
			$(this).parent('.slide').css('width',imgWidth);
	
		});
	}
	

	
	$(window).resize(scaleSlide);
});