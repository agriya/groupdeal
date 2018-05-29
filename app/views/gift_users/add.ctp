<?php /* SVN: $Id: add.ctp 79521 2012-09-28 05:41:00Z ananda_176at12 $ */ ?>
<div class="giftUsers form js-card-over-block">
	<h2><?php echo __l('Customize Your Gift Card');?></h2>
        <?php echo $this->Form->create('GiftUser', array('class' => 'normal'));?>
		<div class="clearfix">
			<div class="gift-card grid_right grid_12 clearfix">
			<div class="gift-side1">
            <h3 class="gift-title"><span id="js-gift-from"><?php echo $this->request->data['GiftUser']['from']; ?></span></h3>
            <p> <?php echo __l('has given you'); ?></p>
            <p class="card-amount">
				<?php if(Configure::read('site.currency_symbol_place') == 'left'):?>
					<?php echo Configure::read('site.currency');?><span id="js-gift-amount"><?php echo $this->Html->cCurrency($this->request->data['GiftUser']['amount']); ?></span>
				<?php else:?>
					<span id="js-gift-amount"><?php echo $this->Html->cCurrency($this->request->data['GiftUser']['amount']);?></span> <?php echo Configure::read('site.currency'); ?>
				<?php endif;?>				
			</p>
            <p><?php echo sprintf(__l('credit to %s '), Configure::read('site.name')); ?></p>
            <div class="remeber-block">
            <p><?php echo __l('Redemption Code:'); ?>
            </p>
            <p class="code-info textb">
            xxxxxx-xxxxxx
            </p>
            </div>
			</div>
			<div class="gift-side2">
            <dl class="card-info clearfix">
            <dt><?php echo __l('to'); ?></dt>
            <dd id="js-gift-to"><?php echo $this->request->data['GiftUser']['friend_name']; ?></dd>
            </dl>
            <p id="js-gift-message" class="card-message">
            <?php echo $this->request->data['GiftUser']['message']; ?>
            </p>
			</div>
			</div>
        	<div class="grid_11">
			<?php
				if(Configure::read('site.currency_symbol_place') == 'left'):
					$currecncy_place = 'between';
				else:
					$currecncy_place = 'after';
				endif;	
			?>		
        	<?php
				echo $this->Form->input('user_available_balance',array('type' => 'hidden', 'value' => $user_available_balance));
                echo $this->Form->input('user_id', array('type' => 'hidden'));
				echo $this->Form->input('from', array('label' => __l('From'),'type'=>'text', 'info' => __l('Name you want the recipient to see'), 'class' => '{"update" : "js-gift-from", "default_value" : "Gift Buyer"}'));
				if(!empty($user['User']['fb_user_id']) && empty($user['User']['email'])):
					echo $this->Form->input('User.email', array('label' => __l('Email')));
				endif; 	
        		echo $this->Form->input('friend_name', array('AUTOCOMPLETE' => 'OFF','label' => __l('Friend Name'), 'class' => '{"update" : "js-gift-to", "default_value" : "Gift Receiver"}'));
        		echo $this->Form->input('friend_mail', array('AUTOCOMPLETE' => 'OFF','label' => __l('Delivery to Email')));
        		echo $this->Form->input('message', array('label' => __l('Personal Message (Optional)'), 'class' => '{"update" : "js-gift-message", "default_value" : "Your Message"}'));
				echo $this->Form->input('amount', array('AUTOCOMPLETE' => 'OFF','label' => __l('Gift Card Amount'),  $currecncy_place => '<span class="currency">'.Configure::read('site.currency'). '</span>', 'class' => '{"update" : "js-gift-amount", "default_value" : "0"}'));
        	?>
        	</div>
        	</div>
			<?php if(Configure::read('wallet.is_handle_wallet_as_in_groupon')):?>
			<div class="wallet-block textb space ver-mspace">
				<?php $my_price = ($user_available_balance > $this->request->data['GiftUser']['amount']) ? 0 : ($this->request->data['GiftUser']['amount'] - $user_available_balance); ?>
				<?php
					if(!empty($this->request->data['GiftUser']['amount'])){ //3 5
						if($this->request->data['GiftUser']['amount'] >= $user_available_balance){
							$gift_price = 0;
						}else{
							$gift_price = $user_available_balance - $this->request->data['GiftUser']['amount'];
						}					
					}else{
						$gift_price = $user_available_balance;
					}
				?>
			
							<p>
								<?php echo Configure::read('site.name').' '.__l('bucks')?>
								<span>
									(<?php
									if($gift_price <= 0){
										echo "<span class='js-update-remaining-bucks'>".__l('You have used all your Bucks.')."</span>";
									}else{
										echo "<span class='js-update-remaining-bucks'>".__l('You have').' '.$gift_price.' '.__l('Bucks remaining.')."</span>";						
									}
									?>)
								</span>
							</p>
				
					
							<p>
								<?php echo __l('My Price')?>
								<?php if(Configure::read('site.currency_symbol_place') == 'left'):?>
									<?php echo Configure::read('site.currency');?><span class="js-amount-need-to-pay"><?php echo $this->Html->cCurrency($my_price); ?></span>
								<?php else:?>
									<span class="js-amount-need-to-pay"><?php echo $this->Html->cCurrency($my_price);?></span> <?php echo Configure::read('site.currency'); ?>
								<?php endif;?>
							</p>
			
				 </div>
			<?php endif;?>
			<?php
				$is_show_credit_card = 0;
				if (empty($gateway_options['Paymentprofiles'])):
					$is_show_credit_card = 1;
				endif;
			  ?>
			<div class="clearfix">
				<div class="js-payment-gateway">
				<?php $get_conversion_currency = $this->Html->getConversionCurrency();?>
					<?php if(isset($get_conversion_currency['supported_currency']) && empty($get_conversion_currency['supported_currency'])):?>
					<table>
							<tr>
								<td class="dl">
									<div class="page-info" id="currency-changing-info">
										<?php
											echo __l("<p>Note: Currently, Payment Gateways doesn't allow").' '.$get_conversion_currency['currency_code'].' '.__l("currency to be processed. It'll converted to").' '.$get_conversion_currency['conv_currency_code'].' '.__l("before processing. <strong>You wont be charged extra.</strong></p><p>You can also check the converted amount in <strong>My Transactions</strong>.</p>");
										?>
									</div>    
								</td>
							</tr>
					</table>
					<?php endif;?>
				  <?php echo $this->Form->input('payment_gateway_id', array('legend' => __l('Payment Type'), 'type' => 'radio', 'options' => $gateway_options['paymentGateways'], 'class' => 'js-payment-type {"is_show_credit_card":"' . $is_show_credit_card . '"}'));?>
					<div class="user-payment-profile js-show-payment-profile <?php echo (!empty($gateway_options['paymentGateways'][ConstPaymentGateways::AuthorizeNet])) ? '' : 'hide'; ?>">
						<?php 
							if (!empty($gateway_options['Paymentprofiles'])):
								echo $this->Form->input('payment_profile_id', array('legend' => __l('Pay with this card(s)'), 'type' => 'radio', 'options' => $gateway_options['Paymentprofiles']));
								echo $this->Html->link(__l('Add new card'), '#', array('class' => 'js-add-new-card add'));
							endif;
						?>
					</div>
					<?php if(!empty($gateway_options['paymentGateways'][ConstPaymentGateways::CreditCard]) || !empty($gateway_options['paymentGateways'][ConstPaymentGateways::AuthorizeNet])): ?>
					<div class="clearfix billing-info-block viewpage-content space hor-mspace js-clone js-credit-payment <?php echo (!empty($gateway_options['paymentGateways'][ConstPaymentGateways::CreditCard]) && !empty($this->request->data['GiftUser']['payment_gateway_id']) && $this->request->data['GiftUser']['payment_gateway_id'] == ConstPaymentGateways::CreditCard || (!empty($gateway_options['paymentGateways'][ConstPaymentGateways::AuthorizeNet]) && $is_show_credit_card)) ? '' : 'hide'; ?>">
					  <div class="billing-left grid_left">
					  <h3><?php echo __l('Billing Information'); ?></h3>
						<?php
							echo $this->Form->input('GiftUser.firstName', array('label' => __l('First Name')));
							echo $this->Form->input('GiftUser.lastName', array('label' => __l('Last Name')));
							echo $this->Form->input('GiftUser.creditCardType', array('label' => __l('Card Type'), 'type' => 'select', 'options' => $gateway_options['creditCardTypes']));
							echo $this->Form->input('GiftUser.creditCardNumber', array('AUTOCOMPLETE' => 'OFF', 'label' => __l('Card Number'))); ?>
							<div class="input date required">
							<label><?php echo __l('Expiration Date'); ?> </label>
							<?php echo $this->Form->month('GiftUser.expDateMonth', array('value' => date('m'))); 
							echo $this->Form->year('GiftUser.expDateYear', date('Y'), date('Y')+25, array('value' => date('Y')+2, 'orderYear' => 'asc'));?>
							<?php if($check_expire) :?>
                            <div class="error-message"><?php echo $check_expire; ?></div>
                            <?php endif; ?>
							</div>
							<?php echo $this->Form->input('GiftUser.cvv2Number', array('AUTOCOMPLETE' => 'OFF', 'maxlength' =>'4', 'label' => __l('Card Verification Number:')));
						?>
						</div>
					  <div class="billing-right grid_left">
						<h3><?php echo __l('Billing Address'); ?></h3>
						<?php
							echo $this->Form->input('GiftUser.address', array('label' => __l('Address')));
							echo $this->Form->input('GiftUser.city', array('label' => __l('City')));
							echo $this->Form->input('State.name', array('label' => __l('State')));
																	
							echo $this->Form->input('GiftUser.zip', array('label' => __l('Zip code')));
							echo $this->Form->input('GiftUser.country', array('label' => __l('Country'), 'type' => 'select', 'options' => $gateway_options['countries'], 'empty' => __l('Please Select')));
							echo $this->Form->input('GiftUser.is_show_new_card', array('type' => 'hidden', 'id' => 'UserIsShowNewCard'));
						 ?>   
						 </div>
					</div>
				<?php endif; ?>
				</div>
				<?php if(Configure::read('wallet.is_handle_wallet_as_in_groupon')):?>
					<?php echo $this->Form->input('is_purchase_via_wallet', array('type' => 'hidden', 'value' => ($this->request->data['GiftUser']['amount'] <= $user_available_balance) ? 1 : 0));?>
				<?php endif;?>
                <?php echo $this->Form->input('group_wallet', array('type' => 'hidden', 'value' => Configure::read('wallet.is_handle_wallet_as_in_groupon')));?>
                 <div class="submit-block buy-submit-block clearfix">
                    <?php
                    	echo $this->Form->submit(__l('Complete Purchase'));
                    ?>
                </div>
                </div>
                <?php
                	echo $this->Form->end();
                ?>
</div> 