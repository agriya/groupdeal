<?php /* SVN: $Id: $ */ ?>
<div class="paymentGateways form">
	<?php echo $this->Form->create('PaymentGateway', array('class' => 'normal add-live-form')); ?>
		<fieldset>
		<?php				
			echo $this->Form->input('id');
			echo $this->Form->input('display_name', array('info' => __l('Display name is the name displayed in the payment options for user')));			
			if($payment_gateway['PaymentGateway']['name'] == 'Wallet'):
				$info = __l('On activating this, users can make purchases from the site using site wallet payment option.');
			elseif($payment_gateway['PaymentGateway']['name'] == 'PayPal'):
				$info = __l('On activating this, users can make purchases from the site using PayPal payment option.');
			elseif($payment_gateway['PaymentGateway']['name'] == 'CreditCard'):
				$info = __l('On activating this, users can make purchases from the site using CreditCard payment option.');			
			elseif($payment_gateway['PaymentGateway']['name'] == 'AuthorizeNet'):
				$info = __l('On activating this, users can make purchases from the site using AuthorizeNet payment option.');			
			elseif($payment_gateway['PaymentGateway']['name'] == 'PagSeguro'):
				$info = __l('On activating this, users can make purchases from the site using PagSeguro payment option.');			
			endif;
			echo $this->Form->input('is_active', array('label' => __l('Active'), 'info' => $info));
			echo $this->Form->input('is_live_mode', array('type' => 'checkbox', 'label' => __l('Live Mode?'), 'info' => __l('On enabling this, live account will used instead of sandbox payment details. (Enable this, When site is in production stage)')));
			if($payment_gateway['PaymentGateway']['id'] == ConstPaymentGateways::PayPalAuth) {
				echo $this->Form->input('is_mass_pay_enabled', array('label' => __l('Enable for Mass Pay'), 'info' => __l('On enabling this, admin can use this gateway to transfer amount to multiple users.')));
			} else {
				echo $this->Form->input('is_mass_pay_enabled', array('type' => 'hidden'));
			}	
			foreach($paymentGatewaySettings as $paymentGatewaySetting) {
				if($paymentGatewaySetting['PaymentGatewaySetting']['key'] != 'is_enable_wallet') {
					$options['type'] = $paymentGatewaySetting['PaymentGatewaySetting']['type'];
					if($paymentGatewaySetting['PaymentGatewaySetting']['key'] == 'is_enable_for_buy_a_deal'):
						$options['label'] = __l('Enable for Deal Purchase');
					elseif($paymentGatewaySetting['PaymentGatewaySetting']['key'] == 'is_enable_for_gift_card'):
						$options['label'] = __l('Enable for Gift Card');
					elseif($paymentGatewaySetting['PaymentGatewaySetting']['key'] == 'is_enable_for_add_to_wallet'):
						$options['label'] = __l('Enable for Add to Wallet');
					endif;
					$options['value'] = $paymentGatewaySetting['PaymentGatewaySetting']['test_mode_value'];
					$options['div'] = array('id' => "setting-{$paymentGatewaySetting['PaymentGatewaySetting']['key']}");
					if($options['type'] == 'checkbox' && !empty($options['value'])):
						$options['checked'] = 'checked';
					else:
						$options['checked'] = '';
					endif;
					if($options['type'] == 'select'):
						$selectOptions = explode(',', $paymentGatewaySetting['PaymentGatewaySetting']['options']);
						$paymentGatewaySetting['PaymentGatewaySetting']['options'] = array();
						if(!empty($selectOptions)):
							foreach($selectOptions as $key => $value):
								if(!empty($value)):
									$paymentGatewaySetting['PaymentGatewaySetting']['options'][trim($value)] = trim($value);
								endif;
							endforeach;
						endif;
						$options['options'] = $paymentGatewaySetting['PaymentGatewaySetting']['options'];
					endif;
					if (!empty($paymentGatewaySetting['PaymentGatewaySetting']['description']) && empty($options['after'])):
						$options['help'] = "{$paymentGatewaySetting['PaymentGatewaySetting']['description']}";
					else:
						$options['help'] = '';
					endif;
					if(($paymentGatewaySetting['PaymentGatewaySetting']['key'] == 'is_enable_for_buy_a_deal' || ($paymentGatewaySetting['PaymentGatewaySetting']['key'] == 'is_enable_for_gift_card' && $paymentGatewaySetting['PaymentGatewaySetting']['payment_gateway_id'] != ConstPaymentGateways::ExpressCheckout) || ($paymentGatewaySetting['PaymentGatewaySetting']['key'] == 'is_enable_for_add_to_wallet' && $paymentGatewaySetting['PaymentGatewaySetting']['payment_gateway_id'] != ConstPaymentGateways::Wallet && $paymentGatewaySetting['PaymentGatewaySetting']['payment_gateway_id'] != ConstPaymentGateways::ExpressCheckout) || $paymentGatewaySetting['PaymentGatewaySetting']['key'] == 'is_enable_wallet')):
						echo $this->Form->input("PaymentGatewaySetting.{$paymentGatewaySetting['PaymentGatewaySetting']['id']}.test_mode_value", $options);
					endif;
				}	
			}
			if($paymentGatewaySettings && $this->request->data['PaymentGateway']['id'] != ConstPaymentGateways::Wallet) {
		?>
				<div class="clearfix">
					<div class="test-mode-left">
						<label for="PaymentGatewaySetting1TestModeValue"><?php echo __l('Test Mode'); ?></label>
					</div>
					<div class="test-mode-right">
						<label for="PaymentGatewaySetting1LiveModeValue"><?php echo __l('Live Mode'); ?></label>
					</div>
				</div>
				<?php
					$j = $i = $z = 0;
					$options = '';
					foreach($paymentGatewaySettings as $paymentGatewaySetting) {
						$options['type'] = $paymentGatewaySetting['PaymentGatewaySetting']['type'];
						$options['value'] = $paymentGatewaySetting['PaymentGatewaySetting']['test_mode_value'];
						$options['div'] = array('id' => "setting-{$paymentGatewaySetting['PaymentGatewaySetting']['key']}");
						if($options['type'] == 'checkbox' && $options['value']):
							$options['checked'] = 'checked';
						endif;
						if($options['type'] == 'select'):
							$selectOptions = explode(',', $paymentGatewaySetting['PaymentGatewaySetting']['options']);
							$paymentGatewaySetting['PaymentGatewaySetting']['options'] = array();
							if(!empty($selectOptions)):
								foreach($selectOptions as $key => $value):
									if(!empty($value)):
										$paymentGatewaySetting['PaymentGatewaySetting']['options'][trim($value)] = trim($value);
									endif;
								endforeach;
							endif;
							$options['options'] = $paymentGatewaySetting['PaymentGatewaySetting']['options'];
						endif;
						$options['label'] = false;
						if (!empty($paymentGatewaySetting['PaymentGatewaySetting']['description']) && empty($options['after'])):
							$options['help'] = "{$paymentGatewaySetting['PaymentGatewaySetting']['description']}";
						else:
							$options['help'] = '';
						endif;
					?>
					<?php if($paymentGatewaySetting['PaymentGatewaySetting']['key'] == 'payee_account' || $paymentGatewaySetting['PaymentGatewaySetting']['key'] == 'receiver_emails'): ?>
						<?php if($z == 0):?>
							<fieldset class="form-block">
								<h3>
									<?php echo __l('Payee Details'); ?>
								</h3>
						<?php endif; ?>
								<div class="clearfix test-mode-content">
									<span class="label-content"><?php echo Inflector::humanize($paymentGatewaySetting['PaymentGatewaySetting']['key']); ?></span>
                                    <div class="clearfix">
                                    <div class="test-mode-left">
										<?php echo $this->Form->input("PaymentGatewaySetting.{$paymentGatewaySetting['PaymentGatewaySetting']['id']}.test_mode_value", $options); ?>
									</div>
									<div class="test-mode-right">
										<?php
											$options['value'] = $paymentGatewaySetting['PaymentGatewaySetting']['live_mode_value'];
											echo $this->Form->input("PaymentGatewaySetting.{$paymentGatewaySetting['PaymentGatewaySetting']['id']}.live_mode_value", $options);
										?>
									</div>
                                    </div>
								</div>
						<?php if($z == 1): ?>
							</fieldset>
						<?php endif;?>
						<?php $z++;?>
					<?php elseif($paymentGatewaySetting['PaymentGatewaySetting']['key'] == 'token'):?>
						<div class="clearfix test-mode-content">
							<span class="label-content"><?php echo Inflector::humanize($paymentGatewaySetting['PaymentGatewaySetting']['key']); ?></span>
							 <div class="clearfix">
								<div class="test-mode-left">
									<?php echo $this->Form->input("PaymentGatewaySetting.{$paymentGatewaySetting['PaymentGatewaySetting']['id']}.test_mode_value", $options); ?>
								</div>
								<div class="test-mode-right">
									<?php
										$options['value'] = $paymentGatewaySetting['PaymentGatewaySetting']['live_mode_value'];
										echo $this->Form->input("PaymentGatewaySetting.{$paymentGatewaySetting['PaymentGatewaySetting']['id']}.live_mode_value", $options);
									?>
								</div>
							</div>
						</div>
					<?php elseif($paymentGatewaySetting['PaymentGatewaySetting']['key'] == 'masspay_API_UserName' || $paymentGatewaySetting['PaymentGatewaySetting']['key'] == 'masspay_API_Password' || $paymentGatewaySetting['PaymentGatewaySetting']['key'] == 'masspay_API_Signature'):?>
						<?php if($j == 0):?>
							<fieldset class="form-block ">
								<h3><?php echo __l('Mass Payment Details'); ?></h3>
								<div class="info-details">
									<p><?php echo __l('Masspay used to send money to user.');?></p>
									<p><?php echo __l('Create masspay API from paypal profile. Refer').' ';?><a href='https://www.paypal.com/in/cgi-bin/webscr'>https://www.paypal.com/in/cgi-bin/webscr</a></p>
								</div>
						<?php endif;?>
								<div class="clearfix test-mode-content">
									<span class="label-content"><?php echo Inflector::humanize($paymentGatewaySetting['PaymentGatewaySetting']['key']); ?></span>
                                    <div class="clearfix">
                                	<div class="test-mode-left">
										<?php echo $this->Form->input("PaymentGatewaySetting.{$paymentGatewaySetting['PaymentGatewaySetting']['id']}.test_mode_value", $options); ?>
									</div>
									<div class="test-mode-right">
										<?php
											$options['value'] = $paymentGatewaySetting['PaymentGatewaySetting']['live_mode_value'];
											echo $this->Form->input("PaymentGatewaySetting.{$paymentGatewaySetting['PaymentGatewaySetting']['id']}.live_mode_value", $options);
										?>
									</div>
									</div>
								</div>
						<?php if($j == 2):?>
							</fieldset>
						<?php endif;?>
						<?php $j++;?>
					<?php elseif($paymentGatewaySetting['PaymentGatewaySetting']['key'] == 'directpay_API_UserName' || $paymentGatewaySetting['PaymentGatewaySetting']['key'] == 'directpay_API_Password' || $paymentGatewaySetting['PaymentGatewaySetting']['key'] == 'directpay_API_Signature'):?>
						<?php if($i == 0):?>
							<fieldset class="form-block">
								<h3><?php echo __l('Direct Payment Details'); ?></h3>
								<div class=" info-details">
									<p><?php echo __l('Direct pay allowed user to pay directly from credit card using capture authorization and void concept.');?></p>
									<p><?php echo __l('Refer').' ';?><a href='https://www.paypal.com/cgi-bin/webscr?cmd=_wp-pro-overview-outside'>https://www.paypal.com/cgi-bin/webscr?cmd=_wp-pro-overview-outside</a></p>
									<p><?php echo __l('It will let the user to pay only at the deal tipped state.');?></p>
								</div>
						<?php endif;?>
								<div class="clearfix test-mode-content">
									<span class="label-content"><?php echo Inflector::humanize($paymentGatewaySetting['PaymentGatewaySetting']['key']); ?></span>
                                    <div class="clearfix">
                                	<div class="test-mode-left">
										<?php echo $this->Form->input("PaymentGatewaySetting.{$paymentGatewaySetting['PaymentGatewaySetting']['id']}.test_mode_value", $options); ?>
									</div>
									<div class="test-mode-right">
										<?php
											$options['value'] = $paymentGatewaySetting['PaymentGatewaySetting']['live_mode_value'];
											echo $this->Form->input("PaymentGatewaySetting.{$paymentGatewaySetting['PaymentGatewaySetting']['id']}.live_mode_value", $options);
										?>
									</div>
									</div>
								</div>
						<?php if($i == 3):?>
							</fieldset>
						<?php endif;?>
						<?php $i++;?>
					<?php elseif($paymentGatewaySetting['PaymentGatewaySetting']['key'] == 'authorize_net_api_key' || $paymentGatewaySetting['PaymentGatewaySetting']['key'] == 'authorize_net_trans_key'): ?>
						<div class="clearfix test-mode-content">
							<span class="label-content"><?php echo Inflector::humanize($paymentGatewaySetting['PaymentGatewaySetting']['key']); ?></span>
                            <div class="clearfix">
                        	<div class="test-mode-left">
								<?php echo $this->Form->input("PaymentGatewaySetting.{$paymentGatewaySetting['PaymentGatewaySetting']['id']}.test_mode_value", $options); ?>
							</div>
							<div class="test-mode-right">
								<?php
									$options['value'] = $paymentGatewaySetting['PaymentGatewaySetting']['live_mode_value'];
									echo $this->Form->input("PaymentGatewaySetting.{$paymentGatewaySetting['PaymentGatewaySetting']['id']}.live_mode_value", $options);
								?>
							</div>
							</div>
						</div>
                        <?php if($i == 3):?>
							</fieldset>
						<?php endif;?>
						<?php $i++;?>
					<?php elseif($paymentGatewaySetting['PaymentGatewaySetting']['key'] == 'expressecheckout_API_UserName' || $paymentGatewaySetting['PaymentGatewaySetting']['key'] == 'expressecheckout_API_Password' || $paymentGatewaySetting['PaymentGatewaySetting']['key'] == 'expressecheckout_API_Signature'): ?>
						<div class="clearfix test-mode-content">
							<span class="label-content"><?php echo Inflector::humanize($paymentGatewaySetting['PaymentGatewaySetting']['key']); ?></span>
                            <div class="clearfix">
                        	<div class="test-mode-left">
								<?php echo $this->Form->input("PaymentGatewaySetting.{$paymentGatewaySetting['PaymentGatewaySetting']['id']}.test_mode_value", $options); ?>
							</div>
							<div class="test-mode-right">
								<?php
									$options['value'] = $paymentGatewaySetting['PaymentGatewaySetting']['live_mode_value'];
									echo $this->Form->input("PaymentGatewaySetting.{$paymentGatewaySetting['PaymentGatewaySetting']['id']}.live_mode_value", $options);
								?>
							</div>
							</div>
						</div>
					<?php endif;?>
					<?php
				}
			}
		?>
	</fieldset>
	<div class="submit-block clearfix">
		<?php echo $this->Form->submit(__l('Update')); ?>
		<div class="cancel-block">
			<?php echo $this->Html->link(__l('Cancel'), array('controller' => 'payment_gateways', 'action' => 'index', 'admin' => true), array('class' => 'cancel-button'));?>
		</div>
	</div>
	<?php echo $this->Form->end(); ?>
</div>