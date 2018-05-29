jQuery(document).ready(function($) {
	$("div:jqmData(role='page')").live('pageshow',function(){
		  $('a[href]').attr('href', function(index, href) {
			var param = 'app=' + Math.floor(Math.random()*100000);

			if (href.charAt(href.length - 1) === '?') //Very unlikely
				return href + param;
			else if (href.indexOf('?') > 0)
				return href + '&' + param;
			else
				return href + '?' + param;
		});
		$('div.message').fadeOut(12000, function() {
			$('div.message').remove();
		});
	});	
	$('input.js-payment-type').live('change', function(e){
		if ($('.js-payment-type:checked').val() == 2) {
			$('.js-hide-for-credit, .js-show-payment-profile').slideUp('fast');
            $('.js-credit-payment').slideDown('fast');            
		} else if ($('.js-payment-type:checked').val() == 3) {
            $('.js-hide-for-credit, .js-credit-payment, .js-show-payment-profile').slideUp('fast');
            $('.js-right-block').removeClass('wallet-login-block');
        } else if ($('.js-payment-type:checked').val() == 4) {
            $('.js-hide-for-credit').slideUp('fast');
            $('.js-show-payment-profile').slideDown('fast');
			if ($('#UserIsShowNewCard').val() == 1) {
				$('.js-credit-payment').slideDown('fast');
			} else {
				$('.js-credit-payment').slideUp('fast');
			}
            $('.js-right-block').removeClass('wallet-login-block');
        } else {
            $('.js-credit-payment, .js-show-payment-profile').slideUp('fast');
            $('.js-hide-for-credit').slideDown('fast');
            $('.js-right-block').addClass('wallet-login-block');
        }
	});	
	$('form input.js-quantity').live('keyup', function() {
        var new_amount = parseFloat(parseInt($(this).val()) * parseFloat($('#DealDealAmount').val()));
		var avail_balance = $('#DealUserAvailableBalance').val();
        new_amount = isNaN(new_amount) ? 0: new_amount;
		new_amount = Math.round(new_amount * 1000) / 1000;
        $('.js-deal-total').html(new_amount);
		if(avail_balance > new_amount){
			$('.js-update-remaining-bucks').html(__l('You will have') + ' ' + (avail_balance - new_amount) +' '+ __l('Bucks remaining.'));
			$('.js-update-total-used-bucks').html(new_amount);
		} else if(new_amount >= avail_balance){
			$('.js-update-remaining-bucks').html('You will have used all your Bucks.');
			$('.js-update-total-used-bucks').html(0);
		}
        $('.js-amount-need-to-pay').html(($('#DealUserAvailableBalance').val() > new_amount) ? 0: (Math.round(parseFloat(new_amount - $('#DealUserAvailableBalance').val())* 1000) / 1000));
		if(parseFloat(new_amount - $('#DealUserAvailableBalance').val()) > 0){
			$('.js-payment-gateway').slideDown('fast');		
			$('#DealIsPurchaseViaWallet').val(0);
		}else{
			$('.js-payment-gateway').slideUp('fast');
			$('#DealIsPurchaseViaWallet').val(1);
		}
        return false;
    });
	$('input.js-add-new-card').live('click', function() {
		$('.js-credit-payment').slideDown('fast');
		$('#UserIsShowNewCard').val(1);
		return false;
	});
});