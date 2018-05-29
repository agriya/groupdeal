<?php /* SVN: $Id: buy.ctp 54596 2011-05-25 12:35:27Z arovindhan_144at11 $ */ ?>
	<?php echo $this->Form->create('Deal', array('action' => 'buy', 'class' => 'normal', 'data-ajax' => "false")); ?>
    <h4><?php echo $deal['Deal']['name'];?></h4>
 
            <div class="buy-list">  
            	<span>					
				<p class="gift-link"><?php
						 echo $this->Html->link(sprintf(__l('Give this %s as a gift'),Configure::read('site.name')), array('controller'=>'deals','action'=>'buy',$deal['Deal']['parent_id'],$deal['Deal']['id'],'type' => 'gift'), array('data-theme'=>'b', 'data-role' => 'button', 'class' => 'gift', 'title' => sprintf(__l('Give this %s as a gift'),Configure::read('site.name'))));
                ?></p>
                </span>
				<ul data-role="listview">
				<li><?php
						$min_info = $deal['Deal']['buy_min_quantity_per_user'];
						$max_info = $deal['Deal']['buy_max_quantity_per_user'];
						if(empty($deal['Deal']['buy_max_quantity_per_user']) && empty($deal['Deal']['max_limit'])){
							$max_info = __l('Unlimited');
						}
						elseif(!empty($deal['Deal']['buy_max_quantity_per_user']) && !empty($deal['Deal']['max_limit'])){
							if(!empty($user_quantity)){
								$user_balance = $deal['Deal']['buy_max_quantity_per_user'] - $user_quantity;
							}
							else{
								$user_balance = $deal['Deal']['buy_max_quantity_per_user'];
							}
							$current_balance = $deal['Deal']['max_limit'] - $deal['Deal']['deal_user_count'];
                            if($current_balance  < $user_balance) {
                                $max_info = $current_balance;
                            } else{
								 $max_info = $user_balance;
							}							
						}
						elseif(!empty($deal['Deal']['buy_max_quantity_per_user']) && empty($deal['Deal']['max_limit'])){
							if(!empty($user_quantity)){
								$max_info = $deal['Deal']['buy_max_quantity_per_user'] - $user_quantity;
							}
							else{
								$max_info = $deal['Deal']['buy_max_quantity_per_user'];
							}
						}
						elseif(empty($deal['Deal']['buy_max_quantity_per_user']) && !empty($deal['Deal']['max_limit'])){
							$max_info = $deal['Deal']['max_limit'] - $deal['Deal']['deal_user_count'];
						}
						
						if(!empty($max_info)){
							if($max_info < $min_info){
								$max_info = $min_info;
							}
						}												
						echo $this->Form->input('quantity',array( 'class' => 'js-quantity', 'after' => '<span class="info sfont">' . sprintf(__l('Minimum Quantity: %s <br /> Maximum Quantity: %s'),$min_info,$max_info). '</span>'));?>
                        <?php echo $this->Form->input('user_available_balance',array('type' => 'hidden', 'value' => $user_available_balance));  ?>
                </li>
				
				<li>
				<label><?php echo __l('Price');?></label>
				<?php echo $this->Html->siteCurrencyFormat($this->Html->cCurrency($this->request->data['Deal']['deal_amount'])); ?>
                </li>
				<li>
				<label><?php echo __l('Total');?></label>
				<span class="js-buy-total">					
					<?php if(Configure::read('site.currency_symbol_place') == 'left'):?>
						<?php echo Configure::read('site.currency');?><span class="js-deal-total"><?php echo $this->Html->cCurrency($this->request->data['Deal']['total_deal_amount']); ?></span>
					<?php else:?>
						<span class="js-deal-total"><?php echo $this->Html->cCurrency($this->request->data['Deal']['total_deal_amount']); ?></span><?php echo Configure::read('site.currency');?>
					<?php endif;?>
				</span>
				</li>
				</ul>
            </div>   
			<?php if(Configure::read('wallet.is_handle_wallet_as_in_groupon') && $this->Auth->sessionValid()):?>
           	<div class="buy-list"> 
			<ul data-role="listview">
			<?php if(!empty($user_available_balance) && $user_available_balance != '0.00'):?>
				<li>
				<span><?php echo Configure::read('site.name').' '.__l('bucks');?>
                    <span>(<?php
                        if($this->request->data['Deal']['total_deal_amount'] > $user_available_balance){
                            echo __l('You will have used all your Bucks.');
                        }elseif($this->request->data['Deal']['total_deal_amount'] < $user_available_balance){
                            $balance_amount = $user_available_balance - $this->request->data['Deal']['total_deal_amount'];
							echo "<span class='js-update-remaining-bucks'>".__l('You will have').' '.$balance_amount.' '.__l('Bucks remaining.')."</span>";
                        }elseif($this->request->data['Deal']['total_deal_amount'] == $user_available_balance){
                            echo __l('You will have used all your Bucks.');
                        }
                     ?>)</span>
                </span>
				<span>
						<?php 
							if($this->request->data['Deal']['total_deal_amount'] > $user_available_balance){
								$used_bucks = $user_available_balance;
							}elseif($this->request->data['Deal']['total_deal_amount'] < $user_available_balance){
								$used_bucks = $this->request->data['Deal']['total_deal_amount'];
							}elseif($this->request->data['Deal']['total_deal_amount'] == $user_available_balance){
								$used_bucks = $user_available_balance;
							} 
						?>
						<?php if(Configure::read('site.currency_symbol_place') == 'left'):?>
							<?php echo Configure::read('site.currency');?><span class="js-update-total-used-bucks"><?php echo $this->Html->cCurrency($used_bucks); ?></span>
						<?php else:?>
							<span class="js-update-total-used-bucks"><?php echo $this->Html->cCurrency($used_bucks);?></span> <?php echo Configure::read('site.currency'); ?>
						<?php endif;?>
                </span>
				</li>
			 
			 
			<?php endif;?>						
			
				<li>
				<span><?php echo __l('My Price:').' '?></span>
				<span>
				<?php $my_price = ($user_available_balance > $this->request->data['Deal']['total_deal_amount']) ? 0 : ($this->request->data['Deal']['total_deal_amount'] - $user_available_balance); ?>
				<?php if(Configure::read('site.currency_symbol_place') == 'left'):?>
					<?php echo Configure::read('site.currency');?> <span class="js-amount-need-to-pay"> <?php echo $this->Html->cCurrency($my_price); ?></span>
				<?php else:?>
					<span class="js-amount-need-to-pay"><?php echo $this->Html->cCurrency($my_price);?></span> <?php echo Configure::read('site.currency'); ?>
				<?php endif;?>
				</span>
				</li>
			</ul>
            </div>  
			<?php endif;?>
            
			<?php if(Configure::read('charity.is_enabled') == 1 && $deal['Deal']['charity_percentage'] > 0):?>
           	<div class="buy-list"> 
			<ul data-role="listview">
            <li> 
				   <span>
					<?php if(Configure::read('charity.who_will_choose') == ConstCharityWhoWillChoose::Buyer):?>
					<?php echo sprintf(__l('For every deal purchased, %s will donate %s of amount to charity that you selected from the pull-down'),Configure::read('site.name'),$deal['Deal']['charity_percentage'].'%');
					   echo $this->Form->input('charity_id',array('label' => false));  
					?>
					<?php else: ?>
						<?php 
						echo $this->Form->input('charity_id',array('type' => 'hidden'));  
						echo sprintf(__l('For every deal purchased, %s will donate %s of amount to'),Configure::read('site.name'),$deal['Deal']['charity_percentage'].'%');?>
						<?php if(!empty($deal['Charity']) && !(env('HTTPS'))): ?>
							<a href="<?php echo $deal['Charity']['url']; ?>" target="_blank"><?php echo $this->Html->cText($deal['Charity']['name']); ?></a>
						<?php else:  
						    echo __l('charity');
						endif; ?>
					<?php endif; ?>					
					</span>
				 </li>
			</ul>
            </div>  
		   <?php endif; ?>
		 <?php 
            echo $this->Form->input('deal_id',array('type' => 'hidden')); 
            echo $this->Form->input('sub_deal_id',array('type' => 'hidden')); 
            echo $this->Form->input('user_id',array('type' => 'hidden')); 
            echo $this->Form->input('is_gift',array('type' => 'hidden')); 
            echo $this->Form->input('deal_amount',array('type' => 'hidden')); 
        ?>
        <?php if($this->request->data['Deal']['is_gift'] || !$this->Auth->sessionValid() || (!empty($user['User']['fb_user_id']) && empty($user['User']['email'])) ||!empty($gateway_options['paymentGateways'])):	 ?>
			<div class="login-left-block ver-mspace ver-space">
					<?php if($this->request->data['Deal']['is_gift']): ?>
						<div class="deal-gift-form">
							<?php
								echo $this->Form->input('gift_from',array('label' => __l('From'))); 
								echo $this->Form->input('gift_to',array('label' => __l('Friend Name'))); 
								echo $this->Form->input('gift_email',array('label' => __l('Friend Email'))); 
								echo $this->Form->input('message',array('type' => 'textarea', 'label' => __l('Message'))); 
							?>
						</div>
					<?php endif; ?>
					<?php if(Configure::read('wallet.is_handle_wallet_as_in_groupon')):?>
                    	<?php 
							$show_class= '';
							if($this->request->data['Deal']['total_deal_amount'] <= $user_available_balance)
								$show_class = 'hide';
							if($this->request->data['Deal']['deal_amount'] == 0)
								$show_class = '';	
						?>		
							  
					<div class="js-payment-gateway <?php echo $show_class; ?>">
						<?php $get_conversion_currency = $this->Html->getConversionCurrency();?>
						<?php if(isset($get_conversion_currency['supported_currency']) && empty($get_conversion_currency['supported_currency'])):?>
										<div class="page-info" id="currency-changing-info">
											<?php
												echo __l("<p>Note: Currently, Payment Gateways doesn't allow").' '.$get_conversion_currency['currency_code'].' '.__l("currency to be processed. It'll converted to").' '.$get_conversion_currency['conv_currency_code'].' '.__l("before processing. <strong>You wont be charged extra.</strong></p><p>You can also check the converted amount in <strong>My Transactions</strong>.</p>");
											?>
										</div>    
						<?php endif;?>
					<?php else:?>
						<div class="clearfix">
					<?php endif;?>
					<?php if(!$this->Auth->sessionValid() || (!empty($user['User']['fb_user_id']) && empty($user['User']['email']))): ?>
							<?php
								if(!$this->Auth->sessionValid()):
							?>	
									<div data-role="fieldcontain">
									<?php echo $this->Form->input('User.username',array('div'=>false,'info' => __l(''))); ?>
                                    </div>
                                    <div data-role="fieldcontain">
									<?php echo $this->Form->input('User.email',array('div'=>false)); ?>
                                    </div>
                                    <div data-role="fieldcontain">
									<?php echo $this->Form->input('User.passwd', array('div'=>false)); ?>
                                    </div>
                                    <div data-role="fieldcontain">
									<?php echo $this->Form->input('User.confirm_password', array('type' => 'password', 'div'=>false)); ?>
                                    </div>
                                <?php   
								elseif(!empty($user['User']['fb_user_id']) && empty($user['User']['email'])):
								?>
                                	<div data-role="fieldcontain">
									<?php echo $this->Form->input('User.email',array('div'=>false)); ?>
                                    </div>
								<?php endif;  ?>
					  <?php endif; ?>
					  <?php
						if(!isset($is_show_credit_card)):
							$is_show_credit_card = 0;
							if (empty($gateway_options['Paymentprofiles'])):
								$is_show_credit_card = 1;
							endif;
						endif;
					  ?>
                      <?php if($this->request->data['Deal']['deal_amount'] == 0){
					  			echo $this->Form->input('payment_gateway_id', array('type' => 'hidden'));
					  		}
							else{
								?>	
					  <div data-role="fieldcontain">
						<fieldset data-role="controlgroup">
						  <legend><?php echo __l('Payment Type');?></legend>
							   <?php echo $this->Form->input('payment_gateway_id', array('div' => false, 'legend' => false, 'type' => 'radio', 'options' => $gateway_options['paymentGateways'], 'class' => 'js-payment-type {"is_show_credit_card":"' . $is_show_credit_card . '"}'));?>           
						</fieldset>
					</div>
					<div class="user-payment-profile js-show-payment-profile <?php echo (!empty($gateway_options['paymentGateways'][ConstPaymentGateways::AuthorizeNet]) && (empty($this->request->data['Deal']['payment_gateway_id']) || $this->request->data['Deal']['payment_gateway_id'] == ConstPaymentGateways::AuthorizeNet)) ? '' : 'hide'; ?>">					   
						<?php 
							if (!empty($gateway_options['Paymentprofiles'])):
								echo $this->Form->input('payment_profile_id', array('legend' => __l('Pay with this card(s)'), 'type' => 'radio', 'options' => $gateway_options['Paymentprofiles']));
								?><input type="button" data-theme="b" data-role="button" class="js-add-new-card" value="<?php echo __l('Add new card'); ?>">
							<?php 
							endif;
						?>
					</div>
					<?php if (!empty($gateway_options['paymentGateways'][ConstPaymentGateways::CreditCard]) || !empty($gateway_options['paymentGateways'][ConstPaymentGateways::AuthorizeNet])): ?>
						<div class="clearfix js-credit-payment <?php echo ($this->request->data['Deal']['payment_gateway_id'] == ConstPaymentGateways::CreditCard || (!empty($gateway_options['paymentGateways'][ConstPaymentGateways::AuthorizeNet]) && $is_show_credit_card)) ? '' : 'hide'; ?>">
						  <h3><?php echo __l('Billing Information'); ?></h3>
                                <div data-role="fieldcontain">
                                <?php echo $this->Form->input('Deal.firstName', array('div'=>false)); ?>
                                </div>
                                <div data-role="fieldcontain">
                                <?php echo $this->Form->input('Deal.lastName', array('div'=>false)); ?>
                                </div>
                                <div data-role="fieldcontain">
                                <?php echo $this->Form->input('Deal.creditCardType', array('div'=>false, 'type' => 'select', 'options' => $gateway_options['creditCardTypes']));?>
                                </div>
                                <div data-role="fieldcontain">
                                <?php echo $this->Form->input('Deal.creditCardNumber', array('AUTOCOMPLETE' => 'OFF', 'div'=>false)); ?>
                                </div>
                                <div data-role="fieldcontain">									
										<label class="ui-select"><?php echo __l('Expiration Month');?></label>
										<?php echo $this->Form->month('Deal.expDateMonth', array('value' => date('m'))); ?>																
                                </div>
								<div data-role="fieldcontain">
									<label class="ui-select"><?php echo __l('Expiration Year');?></label>																	
									<?php echo $this->Form->year('Deal.expDateYear', date('Y'), date('Y')+25, array('value' => date('Y')+2, 'orderYear' => 'asc')); ?>																	
                                </div>
                                <div data-role="fieldcontain">
                                <?php echo $this->Form->input('Deal.cvv2Number', array('AUTOCOMPLETE' => 'OFF', 'maxlength' =>'4', 'div'=>false));?>
                                </div>
							<h3><?php echo __l('Billing Address'); ?></h3>
                            	<div data-role="fieldcontain">
								<?php echo $this->Form->input('Deal.address', array('div'=>false)); ?>
                                </div>
                                <div data-role="fieldcontain">
                                <?php echo $this->Form->input('Deal.city', array('div'=>false)); ?>
                                </div>
                                <div data-role="fieldcontain">
                                <?php echo $this->Form->input('State.name', array('div'=>false)); ?>
                                </div>
                                <div data-role="fieldcontain">
                                <?php echo $this->Form->input('Deal.zip', array('div'=>false)); ?>
                                </div>
                                <div data-role="fieldcontain">
                                <?php echo $this->Form->input('Deal.country', array('div'=>false, 'type' => 'select', 'options' => $gateway_options['countries'], 'empty' => __l('Please Select')));?>
                                </div>
                                <div data-role="fieldcontain">
                                <?php echo $this->Form->input('Deal.is_show_new_card', array('type' => 'hidden', 'id' => 'UserIsShowNewCard')); ?>   
                                </div>
						</div>
					<?php endif; ?>    
					
                 <?php } ?>
                 </div>
					<?php if(Configure::read('wallet.is_handle_wallet_as_in_groupon')):?>
						<?php echo $this->Form->input('is_purchase_via_wallet', array('type' => 'hidden', 'value' => ($this->request->data['Deal']['total_deal_amount'] <= $user_available_balance) ? 1 : 0));?>
					<?php endif;?>  
                    <fieldset class="ui-grid-a">
                    <div class="ui-block-a">
                        <?php
                            if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'gift'){
                                 echo $this->Html->link(__l('Cancel'), array('controller' => 'deals', 'action' => 'buy',$deal['Deal']['id'], 'admin' => false), array('data-role'=>'button'));
                            } else {
                                echo $this->Html->link(__l('Cancel'), array('controller' => 'deals', 'action' => 'view',$deal['Deal']['slug'], 'admin' => false), array('data-role'=>'button'));
                            }
                        ?>
                    </div>
					<div class="ui-block-b">                  
                    <?php echo $this->Form->submit(__l('Complete My Order'),array('data-theme'=>'b', 'title' => __l('Complete My Order'), 'class' => ((!empty($user_available_balance) || $user_available_balance != '0.00')  ? 'js-buy-confirm' : '')));?>
                    </div>
                    </fieldset>
			  </div>
       	<?php else: ?>
        		<fieldset class="ui-grid-a">
                <div class="ui-block-a">
                    <?php
                        if(!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'gift'){
                             echo $this->Html->link(__l('Cancel'), array('controller' => 'deals', 'action' => 'buy',$deal['Deal']['id'], 'admin' => false), array('data-role'=>'button'));
                        }else{
                            echo $this->Html->link(__l('Cancel'), array('controller' => 'deals', 'action' => 'view',$deal['Deal']['slug'], 'admin' => false), array('data-role'=>'button'));
                        }
    
                    ?>
                </div>
				<div class="ui-block-b">   
                <?php echo $this->Form->submit(__l('Complete My Order'),array('data-theme'=>'b','title' => __l('Complete My Order'), 'class' => ($user_available_balance ? 'js-buy-confirm' : '')));?>
                </div>
                </fieldset>
        <?php endif; ?>
    <?php	echo $this->Form->end();?>
	

