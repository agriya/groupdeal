<?php /* SVN: $Id: add_to_wallet.ctp 79535 2012-09-28 13:07:19Z rajeshkhanna_146ac10 $ */ ?>
<div class="js-card-over-block">
<?php if($this->Auth->user('user_type_id') != ConstUserTypes::Company):?>
<h2><?php echo __l('Add Amount to Wallet'); ?></h2>
	<?php endif; ?>

<?php
	if(Configure::read('site.currency_symbol_place') == 'left'):
		$currecncy_place = 'between';
	else:
		$currecncy_place = 'after';
	endif;	
?>		
<?php 
	echo $this->Form->create('User', array('action' => 'add_to_wallet', 'class' => 'normal'));
	if (!Configure::read('wallet.max_wallet_amount')):
        $max_amount = 'No limit';
    else:
        $max_amount = $this->Html->siteCurrencyFormat($this->Html->cCurrency(Configure::read('wallet.max_wallet_amount')));
    endif;
	echo $this->Form->input('amount',array('label' => __l('Amount'), $currecncy_place => '<span class="currency">'.Configure::read('site.currency'). '</span>')); ?>
	<span class="info sfont"> <?php echo sprintf(__l('Minimum Amount: %s <br/> Maximum Amount: %s'),$this->Html->siteCurrencyFormat($this->Html->cCurrency(Configure::read('wallet.min_wallet_amount'))), $max_amount)?>  </span>
	<?php
		$is_show_credit_card = 0;
		if (empty($gateway_options['Paymentprofiles'])):
			$is_show_credit_card = 1;
		endif;
	?>
	<?php echo $this->Form->input('User.payment_gateway_id', array('legend' => false, 'before' => '<span class="payment textb no-pad payment-type">'.__l('Payment Type').'</span>',  'type' => 'radio', 'options' => $gateway_options['paymentGateways'], 'class' => 'js-payment-type {"is_show_credit_card":"' . $is_show_credit_card . '"}')); ?>
	<div class="user-payment-profile js-show-payment-profile <?php echo (!empty($gateway_options['paymentGateways'][ConstPaymentGateways::AuthorizeNet])) ? '' : 'hide'; ?>">
		<?php 
			if (!empty($gateway_options['Paymentprofiles'])):
				echo $this->Form->input('payment_profile_id', array('legend' => __l('Pay with this card(s)'), 'type' => 'radio', 'options' => $gateway_options['Paymentprofiles']));
				echo $this->Html->link(__l('Add new card'), '#', array('class' => 'add js-add-new-card'));
			endif;
		?>
	</div>
			<?php $get_conversion_currency = $this->Html->getConversionCurrency();?>
			<?php if(isset($get_conversion_currency['supported_currency']) && empty($get_conversion_currency['supported_currency'])):?>
            <table>
			<tr>
				<td colspan='3' class="dl">
                <div class="page-info" id="currency-changing-info">
					<?php
						echo __l("<p>Note: Currently, Payment Gateways doesn't allow").' '.$get_conversion_currency['currency_code'].' '.__l("currency to be processed. It'll converted to").' '.$get_conversion_currency['conv_currency_code'].' '.__l("before processing. <strong>You wont be charged extra.</strong></p><p>You can also check the converted amount in <strong>My Transactions</strong>.</p>");
					?>
                </div>    
				</td>
				<!--<td class="dr buy-dr" ><?php echo $get_conversion_currency['conv_currency_code'].' '.__l('Equivalent');?></td>
				<td class="dr buy-dr">
					<span class='js-converted-conversion'></span>
				</td>-->
			</tr>
            </table>
			<?php endif;?>
	<?php if (!empty($gateway_options['paymentGateways'][ConstPaymentGateways::CreditCard]) || !empty($gateway_options['paymentGateways'][ConstPaymentGateways::AuthorizeNet])): ?>
		<div class="clearfix billing-info-block js-credit-payment login-left-block credit-payment-block js-clone <?php echo ($this->request->data['User']['payment_gateway_id'] == ConstPaymentGateways::CreditCard || (!empty($gateway_options['paymentGateways'][ConstPaymentGateways::AuthorizeNet]) && $is_show_credit_card)) ? '' : 'hide' ?>">
		  <div class="billing-left grid_left">
		  <h3><?php echo __l('Billing Information'); ?></h3>
			<?php
				echo $this->Form->input('User.firstName', array('label' => __l('First Name')));
				echo $this->Form->input('User.lastName', array('label' => __l('Last Name')));
				echo $this->Form->input('User.creditCardType', array('label' => __l('Card Type'), 'type' => 'select', 'options' => $gateway_options['creditCardTypes']));
				echo $this->Form->input('User.creditCardNumber', array('AUTOCOMPLETE' => 'OFF', 'label' => __l('Card Number'))); ?>
				<div class="input date required">
				<label><?php echo __l('Expiration Date'); ?> </label>
				<?php echo $this->Form->month('User.expDateMonth', array('value' => date('m')));
			     echo $this->Form->year('User.expDateYear', date('Y'), date('Y')+25, array('value' => date('Y')+2, 'orderYear' => 'asc'));?>
				<?php if($check_expire) :?>
                <div class="error-message"><?php echo $check_expire; ?></div>
                <?php endif; ?>
                </div>
				<?php echo $this->Form->input('User.cvv2Number', array('AUTOCOMPLETE' => 'OFF', 'maxlength' =>'4', 'label' => __l('Card Verification Number:')));
			?>
			</div>
		  <div class="billing-right grid_left">
			<h3><?php echo __l('Billing Address'); ?></h3>
			<?php
				echo $this->Form->input('User.address', array('label' => __l('Address')));
				echo $this->Form->input('User.city', array('label' => __l('City')));
				echo $this->Form->input('State.name', array('label' => __l('State')));
						
				echo $this->Form->input('User.zip', array('label' => __l('Zip code')));
				echo $this->Form->input('User.country', array('label' => __l('Country'), 'type' => 'select', 'options' => $gateway_options['countries'], 'empty' => __l('Please Select')));
				echo $this->Form->input('User.is_show_new_card', array('type' => 'hidden'));
			 ?>   
			 </div>
		</div>
	<?php endif; ?>  

    <div class="submit-block buy-submit-block clearfix">
	<?php echo $this->Form->submit(__l('Add to Wallet')); ?>
	
	</div>
    <?php echo $this->Form->end();
?>
</div>