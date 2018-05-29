<?php /* SVN: $Id: buy.ctp 80007 2013-04-24 07:13:11Z balamurugan_177at12 $ */ ?>
	<div class="buying-form ver-space pr js-card-over-block">
    <h2><?php echo __l('Your Purchase'); ?></h2>
	<?php echo $this->Form->create('Deal', array('action' => 'buy', 'class' => 'normal')); ?>
    	<table class="list buy-list round-5" summary="list">
        	<tr>
            	<th class="dl deal-descriptions"><?php echo __l('Description'); ?></th>
                <th><?php echo __l('Quantity'); ?></th>
                <th class="deal-price"><?php echo __l('Price'); ?></th>
                <th class="dr"><?php echo __l('Total'); ?></th>
                <th class="total-after">&nbsp;</th>
            </tr>
            <tr class="no-bar">
            	<td class="dl deal-descriptions">
            	<div class="clearfix">
                    <?php echo $this->Html->showImage('Deal', $deal['Attachment'][0], array('dimension' => 'medium_thumb_buy_deal', 'class'=>'grid_left','alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($deal['Deal']['name'], false)), 'title' => $this->Html->cText($deal['Deal']['name'], false)));?>
                    <?php //echo $this->Html->image("userimge.png", array('alt'=> __l('[Image: Deal image]'), 'class'=>'grid_left', 'title' => __l('Deal image'))); ?>
    				<div class="grid_left deal-name">
                     <p><?php echo $deal['Deal']['name'];?></p>
					 <?php
					 $deal_city_name=$this->request->params['named']['city'];
					 ?>
                     <p class="city-name">in <?php echo $deal_city_name;?></p>
                    </div>
                </div>
                </td>
				<td class="quantity-info"><?php
						$min_info = $deal['Deal']['buy_min_quantity_per_user'];
						$max_info = $deal['Deal']['buy_max_quantity_per_user'];
						if($deal['Deal']['is_now_deal'] == 0){
							if(empty($max_info) && empty($deal['Deal']['buy_max_quantity_per_user']) && empty($deal['Deal']['max_limit'])){
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
									$min_info = $max_info;
								}
							}
							echo $this->Form->input('quantity',array('label' => false, 'class' => 'js-quantity', 'after' => '<span class="info sfont">' . sprintf(__l('Minimum Quantity: %s <br /> Maximum Quantity: %s'),$min_info,$max_info). '</span>'));
						}
						else{
							$user_each_purchase = '';
							if(empty($deal['Deal']['maxmium_purchase_per_day']) && empty($deal['Deal']['user_each_purchase_max_limit'])){
								$max_info = 'Unlimit';
								$user_each_purchase = 'Unlimit';
							}
							else if(!empty($deal['Deal']['maxmium_purchase_per_day']) && empty($deal['Deal']['user_each_purchase_max_limit'])){
								$max_info = $deal['Deal']['maxmium_purchase_per_day'] - $deal['Deal']['deal_user_count'];
								$user_each_purchase = $max_info ;
							}
							else if(empty($deal['Deal']['maxmium_purchase_per_day']) && !empty($deal['Deal']['user_each_purchase_max_limit'])){
								$max_info = 'Unlimit';
								$user_each_purchase = $deal['Deal']['user_each_purchase_max_limit'];
							}
							else if(!empty($deal['Deal']['maxmium_purchase_per_day']) && !empty($deal['Deal']['user_each_purchase_max_limit'])){
								$max_info = $deal['Deal']['maxmium_purchase_per_day'] - $deal['Deal']['deal_user_count'];
								$user_each_purchase = $deal['Deal']['user_each_purchase_max_limit'];
								if($max_info < $user_each_purchase ){
									$user_each_purchase = $max_info;
								}
							}
							echo $this->Form->input('quantity',array('label' => false, 'class' => 'js-quantity', 'after' => '<span class="info sfont">' . sprintf(__l('Minimum Quantity: %s <br /> Maximum Quantity: %s <br /> Maximum quantity limit per purchase: %s'),$min_info,$max_info, $user_each_purchase). '</span>'));
						}
?>
                        <?php echo $this->Form->input('user_available_balance',array('type' => 'hidden', 'value' => $user_available_balance));  ?>
                </td>
				<td class="price-info"><?php echo $this->Html->siteCurrencyFormat($this->Html->cCurrency($this->request->data['Deal']['deal_amount'])); ?></td>
				<td class="dr total-info">
					<?php if(Configure::read('site.currency_symbol_place') == 'left'):?>
						<?php echo Configure::read('site.currency');?><span class="js-deal-total"><?php echo $this->Html->cCurrency($this->request->data['Deal']['total_deal_amount']); ?></span>
					<?php else:?>
						<span class="js-deal-total"><?php echo $this->Html->cCurrency($this->request->data['Deal']['total_deal_amount']); ?></span><?php echo Configure::read('site.currency');?>
					<?php endif;?>
				</td>
				<td></td>
            </tr>
         
            <tr class="gift-link">
                <td colspan="2" class="dl">
                    <p class="gift-link">
                        <?php echo $this->Html->link(sprintf(__l('Give this %s as a gift'),Configure::read('site.name')), array('controller'=>'deals','action'=>'buy',$deal['Deal']['parent_id'],$deal['Deal']['id'],'type' => 'gift'), array('class' => 'gift', 'title' => sprintf(__l('Give this %s as a gift'),Configure::read('site.name'))));?>
                    </p>
                </td>
                <td colspan="2" class="dr"><span class="total">Total Amount</span>
                    <?php if(Configure::read('site.currency_symbol_place') == 'left'):?>
						<?php echo Configure::read('site.currency');?><span class="js-deal-total"><?php echo $this->Html->cCurrency($this->request->data['Deal']['total_deal_amount']); ?></span>
					<?php else:?>
						<span class="js-deal-total"><?php echo $this->Html->cCurrency($this->request->data['Deal']['total_deal_amount']); ?></span><?php echo Configure::read('site.currency');?>
					<?php endif;?>
                </td>
                <td>&nbsp;</td>
              </tr>
        
        	<?php if(Configure::read('wallet.is_handle_wallet_as_in_groupon') && $this->Auth->sessionValid()):?>
			<?php if(!empty($user_available_balance) && $user_available_balance != '0.00'):?>
            <tr>
				<td class="buy-dr" colspan="3">
				<div class="clearfix">
                    <div class="buck-info dl round-5">
                         <p class="bucks-title">
                           <?php echo Configure::read('site.name').' '.__l('bucks');?>
                        </p>
                        <span><?php
                            if($this->request->data['Deal']['total_deal_amount'] > $user_available_balance){
                                echo __l('You will have used all your Bucks.');
                            }elseif($this->request->data['Deal']['total_deal_amount'] < $user_available_balance){
                                $balance_amount = $user_available_balance - $this->request->data['Deal']['total_deal_amount'];
    							echo "<span class='js-update-remaining-bucks'>".__l('You will have').' '.round($balance_amount,2).' '.__l('Bucks remaining.')."</span>";
                            }elseif($this->request->data['Deal']['total_deal_amount'] == $user_available_balance){
                                echo __l('You will have used all your Bucks.');
                            }
                         ?></span>
                     </div>
                     </div>
                </td>
				<td class="dr buy-dr">
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
							-<?php echo Configure::read('site.currency');?><span class="js-update-total-used-bucks"><?php echo $this->Html->cCurrency($used_bucks); ?></span>
						<?php else:?>
							-<span class="js-update-total-used-bucks"><?php echo $this->Html->cCurrency($used_bucks);?></span> <?php echo Configure::read('site.currency'); ?>
						<?php endif;?>
                    </span>
                </td>
			</tr>
    		<?php endif;?>
			<tr>
				<td class="dr buy-dr buy-dr1" colspan="3"><?php echo __l('My Price:').' '?></td>
				<td class="dr buy-dr buy-dr1">
				<?php $my_price = ($user_available_balance > $this->request->data['Deal']['total_deal_amount']) ? 0 : ($this->request->data['Deal']['total_deal_amount'] - $user_available_balance); ?>
				<?php if(Configure::read('site.currency_symbol_place') == 'left'):?>
					<?php echo Configure::read('site.currency');?> <span class="js-amount-need-to-pay"> <?php echo $this->Html->cCurrency($my_price); ?></span>
				<?php else:?>
					<span class="js-amount-need-to-pay"><?php echo $this->Html->cCurrency($my_price);?></span> <?php echo Configure::read('site.currency'); ?>
				<?php endif;?>
				</td>
				<td class="dr buy-dr buy-dr1">&nbsp;</td>
			</tr>
			<?php endif;?>
			<?php  if(Configure::read('charity.is_enabled') == 1 && $deal['Deal']['charity_percentage'] > 0):?>
                <tr>
				   <td class="dr buy-purchased-info" colspan="4">
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
					</td>
				</tr>
		   <?php  endif; ?>
        </table>
    
		 <?php 
            echo $this->Form->input('deal_id',array('type' => 'hidden')); 
            echo $this->Form->input('sub_deal_id',array('type' => 'hidden')); 
            echo $this->Form->input('user_id',array('type' => 'hidden')); 
            echo $this->Form->input('is_gift',array('type' => 'hidden')); 
            echo $this->Form->input('deal_amount',array('type' => 'hidden')); 
        ?>
        <?php if($this->request->data['Deal']['is_gift'] || !$this->Auth->sessionValid() || (!empty($user['User']['fb_user_id']) && empty($user['User']['email'])) ||!empty($gateway_options['paymentGateways'])):	 ?>
			<div class="dealsbuy-block ver-mspace ver-space">
					<?php if($this->request->data['Deal']['is_gift']): ?>
					<div class="clearfix billing-info-block">
						<div class="deal-gift-form billing-left">
							<?php
								echo $this->Form->input('gift_from',array('label' => __l('From')));
								echo $this->Form->input('gift_to',array('label' => __l('Friend Name')));
								echo $this->Form->input('gift_email',array('label' => __l('Friend Email')));
								echo $this->Form->input('message',array('type' => 'textarea', 'label' => __l('Message')));
							?>
						</div>
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
					<?php else:?>
						<div class="clearfix">
					<?php endif;?>
					<?php if(!$this->Auth->sessionValid() || (!empty($user['User']['fb_user_id']) && empty($user['User']['email']))): ?>
                    <fieldset class="form-block1 round-5">
                        <h3><?php echo __l('User details');?></h3>
                            <div class="clearfix billing-info-block">
                                <div class="deal-gift-form billing-left">
        							<?php
        								if(!$this->Auth->sessionValid()):
        									echo $this->Form->input('User.username',array('label' => __l('Username'),'info' => __l('Must start with an alphabet. <br/> Must be minimum of 3 characters and <br/> Maximum of 20 characters <br/> No special characters and spaces allowed')));
        									echo $this->Form->input('User.email',array('label' => __l('Email')));
        									echo $this->Form->input('User.passwd', array('label' => __l('Password')));
        									echo $this->Form->input('User.confirm_password', array('type' => 'password', 'label' => __l('Password Confirmation')));
        								elseif(!empty($user['User']['fb_user_id']) && empty($user['User']['email'])):
        									echo $this->Form->input('User.email',array('label' => __l('Email')));
        								endif;  ?>
        						</div>
        					</div>
                     </fieldset>
					  <?php endif; ?>
					  <?php
						if(!isset($is_show_credit_card)):
							$is_show_credit_card = 0;
							if (empty($gateway_options['Paymentprofiles'])):
								$is_show_credit_card = 1;
							endif;
						endif;
					  ?>
					<fieldset class="form-block1 round-5">
                        <h3><?php echo __l('Payment type');?></h3>
                          <?php if($this->request->data['Deal']['deal_amount'] == 0){
    					  			echo $this->Form->input('payment_gateway_id', array('type' => 'hidden'));
    					  		}
    							else{
    					  echo $this->Form->input('payment_gateway_id', array('legend' => __l(''), 'type' => 'radio', 'options' => $gateway_options['paymentGateways'], 'class' => 'js-payment-type {"is_show_credit_card":"' . $is_show_credit_card . '"}'));?>
                        <div class="user-payment-profile js-show-payment-profile <?php echo (!empty($gateway_options['paymentGateways'][ConstPaymentGateways::AuthorizeNet]) && (empty($this->request->data['Deal']['payment_gateway_id']) || $this->request->data['Deal']['payment_gateway_id'] == ConstPaymentGateways::AuthorizeNet)) ? '' : 'hide'; ?>">
    						<?php
    							if (!empty($gateway_options['Paymentprofiles'])):
    								echo $this->Form->input('payment_profile_id', array('legend' => __l('Pay with this card(s)'), 'type' => 'radio', 'options' => $gateway_options['Paymentprofiles']));
    								echo $this->Html->link(__l('Add new card'), '#', array('class' => 'add js-add-new-card'));
    							endif;
    						?>
    					</div>
                  </fieldset>
					<?php if (!empty($gateway_options['paymentGateways'][ConstPaymentGateways::CreditCard]) || !empty($gateway_options['paymentGateways'][ConstPaymentGateways::AuthorizeNet])): ?>
                            <div class="clearfix billing-info-block js-clone js-credit-payment <?php echo ($this->request->data['Deal']['payment_gateway_id'] == ConstPaymentGateways::CreditCard || (!empty($gateway_options['paymentGateways'][ConstPaymentGateways::AuthorizeNet]) && $is_show_credit_card)) ? '' : 'hide'; ?>">
                               <fieldset class="form-block1 round-5">
                                <h3><?php echo __('Billing details');?></h3>
                                  <div class="billing-left grid_left">
        						    <h4><?php echo __l('Billing Information'); ?></h4>
        							<?php
        								echo $this->Form->input('Deal.firstName', array('label' => __l('First Name')));
        								echo $this->Form->input('Deal.lastName', array('label' => __l('Last Name')));
        								echo $this->Form->input('Deal.creditCardType', array('label' => __l('Card Type'), 'type' => 'select', 'options' => $gateway_options['creditCardTypes']));
        								echo $this->Form->input('Deal.creditCardNumber', array('AUTOCOMPLETE' => 'OFF', 'label' => __l('Card Number'))); ?>
        								<div class="input date required">
            								<label><?php echo __l('Expiration Date'); ?> </label>
            								<?php echo $this->Form->month('Deal.expDateMonth', array('value' => date('m')));
            								echo $this->Form->year('Deal.expDateYear', date('Y'), date('Y')+25, array('value' => date('Y')+2, 'orderYear' => 'asc'));?>
            								<?php if($check_expire) :?>
                                            <div class="error-message"><?php echo $check_expire; ?></div>
                                            <?php endif; ?>
        								</div>
        								<?php echo $this->Form->input('Deal.cvv2Number', array('AUTOCOMPLETE' => 'OFF', 'maxlength' =>'4', 'label' => __l('Card Verification Number:')));
        							?>
        					       	</div>
        					       	<div class="billing-right">
        							<h4><?php echo __l('Billing Address'); ?></h4>
        							<?php
        								echo $this->Form->input('Deal.address', array('label' => __l('Address')));


        								echo $this->Form->input('Deal.city', array('label' => __l('City')));
        								echo $this->Form->input('State.name', array('label' => __l('State')));

        								echo $this->Form->input('Deal.zip', array('label' => __l('Zip code')));
        								echo $this->Form->input('Deal.country', array('label' => __l('Country'), 'type' => 'select', 'options' => $gateway_options['countries'], 'empty' => __l('Please Select')));
        								echo $this->Form->input('Deal.is_show_new_card', array('type' => 'hidden', 'id' => 'UserIsShowNewCard'));
        							 ?>
        							</div>
   							      </fieldset>
        						</div>
            				<?php endif; ?>
                 <?php } ?>
                 </div>
                <div class="submit-block buy-submit-block clearfix">
					<?php if(Configure::read('wallet.is_handle_wallet_as_in_groupon')):?>
						<?php echo $this->Form->input('is_purchase_via_wallet', array('type' => 'hidden', 'value' => ($this->request->data['Deal']['total_deal_amount'] <= $user_available_balance) ? 1 : 0));?>
					<?php endif;?>                    
                    <?php echo $this->Form->submit(__l('Complete My Order'),array('title' => __l('Complete My Order'), 'class' => ((!empty($user_available_balance) || $user_available_balance != '0.00')  ? 'js-buy-confirm' : '')));?>
                    <div class="cancel-block">
                        <?php
                            if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'gift'){
								 if(isset($deal['Deal']['parent_id']) && !empty($deal['Deal']['parent_id']))
								 {
									$deal_id = $deal['Deal']['parent_id'].'/'.$deal['Deal']['id'];
								 } else {
									$deal_id = $deal['Deal']['id'];
								 }
								 echo $this->Html->link(__l('Cancel'), array('controller' => 'deals', 'action' => 'buy',$deal_id, 'admin' => false), array('class' => 'cancel-button'));
                            } else {
								if(isset($deal['Deal']['parent_id']) && !empty($deal['Deal']['parent_id']) && !empty($main_deal_slug))
								{
									$slug = $main_deal_slug;
								} else {
									$slug = $deal['Deal']['slug'];
								}
                                echo $this->Html->link(__l('Cancel'), array('controller' => 'deals', 'action' => 'view',$slug, 'admin' => false), array('class' => 'cancel-button'));
                            }
                        ?>
                    </div>
                </div>
			  </div>
       	<?php else: ?>
            <div class="submit-block clearfix">
                <?php echo $this->Form->submit(__l('Complete My Order'),array('title' => __l('Complete My Order'), 'class' => ($user_available_balance ? 'js-buy-confirm' : '')));?>
                <div class="cancel-block">
                    <?php
                        if(!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'gift'){
							 if(isset($deal['Deal']['parent_id']) && !empty($deal['Deal']['parent_id']))
							 {
							 	$deal_id = $deal['Deal']['parent_id'].'/'.$deal['Deal']['id'];
							 } else {
							 	$deal_id = $deal['Deal']['id'];
							 }
                             echo $this->Html->link(__l('Cancel'), array('controller' => 'deals', 'action' => 'buy',$deal_id, 'admin' => false), array('class' => 'cancel-button'));
                        }else{
							if(isset($deal['Deal']['parent_id']) && !empty($deal['Deal']['parent_id']) && !empty($main_deal_slug))
							{
								$slug = $main_deal_slug;
							} else {
								$slug = $deal['Deal']['slug'];
							}
                            echo $this->Html->link(__l('Cancel'), array('controller' => 'deals', 'action' => 'view',$slug, 'admin' => false), array('class' => 'cancel-button'));
                        }
    
                    ?>
                </div>
            </div>
            </div>
        <?php endif; ?>
		<?php	echo $this->Form->end();?>
    <?php if(!$this->Auth->sessionValid()):?>
		<div class="login-right-block js-right-block top-mspace pa">
            <div class="login-message-lineheight js-login-message ">
                <h3><?php echo __l('Already Have An Account?');?></h3>
               
                <div class="clearfix">
                 <p class="login-info-block grid_right sfont"><?php echo sprintf(__l('If you have purchased a %s before, you can sign in using your %s.'), Configure::read('site.name'),Configure::read('user.using_to_login')); ?></p>
                <div class="submit-block cancel-block submit-cancel-block">
                    <?php echo $this->Html->link(__l('Login'), '#', array('title' => __l('Sign In'), 'class' => "cancel-button js-toggle-show {'container':'js-login-form', 'hide_container':'js-login-message'}"));?>
                </div>
                </div>
                <div class="facebook-block">
            <?php if(!(!empty($this->request->params['prefix']) && $this->request->params['prefix'] == 'admin')):  ?>
                <div class="openid-block multiple-logins clearfix">
                    <h5><?php echo __l('Sign In using: '); ?></h5>
                    <ul class="open-id-list open-id-list1 clearfix">
                        <li class="grid_left face-book">
                             <?php if(Configure::read('facebook.is_enabled_facebook_connect')):  ?>
                                <?php echo $this->Html->link(__l('Sign in with Facebook'), array('controller' => 'users', 'action' => 'login','type'=>'facebook'), array('title' => __l('Sign in with Facebook'), 'escape' => false)); ?>
                             <?php endif; ?>
                        </li>
                        <?php if(Configure::read('twitter.is_enabled_twitter_connect')):?>
                            <li class="grid_left twiiter"><?php echo $this->Html->link(__l('Sign in with Twitter'), array('controller' => 'users', 'action' => 'login',  'type'=> 'twitter', 'admin'=>false), array('class' => 'Twitter', 'title' => __l('Sign in with Twitter')));?></li>
                        <?php endif;?>
                            <?php if(Configure::read('foursquare.is_enabled_foursquare_connect')):?>
                                <li class="grid_left foursquare"><?php echo $this->Html->link(__l('Sign in with Foursquare'), array('controller' => 'users', 'action' => 'login',  'type'=> 'foursquare', 'admin'=>false), array('class' => 'Foursquare', 'title' => __l('Sign in with Foursquare')));?></li>
                            <?php endif;?>
                        <?php if(Configure::read('user.is_enable_yahoo_openid')):?>
                            <li class="grid_left yahoo"><?php echo $this->Html->link(__l('Sign in with Yahoo'), array('controller' => 'users', 'action' => 'login', 'type'=>'yahoo'), array('title' => __l('Sign in with Yahoo')));?></li>
                        <?php endif;?>
                        <?php if(Configure::read('user.is_enable_gmail_openid')):?>
                            <li class="grid_left gmail"><?php echo $this->Html->link(__l('Sign in with Gmail'), array('controller' => 'users', 'action' => 'login', 'type'=>'gmail'), array('title' => __l('Sign in with Gmail')));?></li>
                        <?php endif;?>
                        <?php if(Configure::read('user.is_enable_openid')):?>
                            <li class="grid_left open-id"><?php 	echo $this->Html->link(__l('Sign in with Open ID'), array('controller' => 'users', 'action' => 'login','type'=>'openid'), array('class'=>'','title' => __l('Sign in with Open ID')));?></li>
                        <?php endif;?>
                    </ul>
                </div>
			
            <?php endif; ?>
            	</div>
            </div>
            <div class="js-login-form hide">
                <?php
				// Temp Fix Avoid teh Validation Message in login Page due the Validation the Another Form
				unset($this->validationErrors['User']['username']);
				unset($this->validationErrors['User']['passwd']);
				$subdealid = (!empty($sub_deal_id))? '/'.$sub_deal_id:'';
				$subdeal_id = (!empty($sub_deal_id))? '_'.$sub_deal_id:'';
				echo $this->element('users-login', array('is_buy' => '1','f' => 'deals/buy/'.$this->request->data['Deal']['deal_id'].''.$subdealid));?>
            </div>
        </div>
    <?php endif;?>
	</div>