<ul class="admin-links clearfix">

	<?php $class = ($this->request->params['controller'] == 'users' && $this->request->params['action'] == 'admin_stats') ? 'admin-active' : null; ?>
    <li class="no-bor  alpha omega <?php echo $class;?>">
    	 <span class="amenu-left clearfix">
             <span class="amenu-right clearfix">
                 <span class="menu-center dashboard clearfix">
                     <span><?php echo __l('Dashboard'); ?></span>
                 </span>
            </span>
         </span>
          <div class="admin-sub-block">
              <div class="admin-sub-lblock">
                    <div class="admin-sub-rblock">
                        <div class="admin-sub-cblock">
                        	<ul class="">
                            <li>
                                <h4><?php echo __l('Dashboard'); ?></h4>
                                <ul>
                                <li>
                                <?php echo $this->Html->link(__l('Snapshot'), array('controller' => 'users', 'action' => 'stats'),array('title' => __l('Snapshot'))); ?>
                                </li>
                                </ul>
                                </li>
                            </ul>
                        </div>
                	</div>
        	 </div>
             <div class="admin-bot-lblock">
				<div class="admin-bot-rblock">
					<div class="admin-bot-cblock"></div>
				</div>
            </div>
        </div>
    </li>
   <?php $controller = array('users', 'user_profiles',  'user_logins',  'user_comments');
	$class = ( in_array( $this->request->params['controller'], $controller ) && !in_array($this->request->params['action'], array('admin_logs', 'admin_stats', 'admin_referred_users')) ) ? 'admin-active' : null; ?>
    
    <li class="no-bor alpha omega <?php echo $class;?>">
	
		 <span class="amenu-left clearfix">
             <span class="amenu-right clearfix">
                 <span class="menu-center admin-users clearfix">
                      <span> <?php echo __l('Users'); ?></span>
                 </span>
            </span>
         </span>
         <div class="admin-sub-block">
          <div class="admin-sub-lblock">
            <div class="admin-sub-rblock">
            <div class="admin-sub-cblock">
    		<ul class="admin-sub-links">
        	<li>
            	<h4><?php echo __l('Users'); ?></h4>
                <ul>
    			<?php $class = ($this->request->params['controller'] == 'user_profiles' ||  ($this->request->params['controller'] == 'users'  && ($this->request->params['action'] == 'admin_index' || $this->request->params['action'] == 'change_password' || $this->request->params['action'] == 'admin_add' )) ) ? ' class="active"' : null; ?>
    			<li <?php echo $class;?>><?php echo $this->Html->link(__l('Users'), array('controller' => 'users', 'action' => 'index', 'main_filter_id' => 'all'),array('title' => __l('Users'))); ?></li>
    			<li <?php echo $class;?>><?php echo $this->Html->link(__l('Add User'), array('controller' => 'users', 'action' => 'add'),array('title' => __l('Add User'))); ?></li>
    			<?php $class = ( $this->request->params['controller'] == 'user_profiles') ? ' class="active"' : null; ?>
                <?php $class = ($this->request->params['controller'] == 'user_logins') ? ' class="active"' : null; ?>
    			<li <?php echo $class;?>><?php echo $this->Html->link(__l('User Logins'), array('controller' => 'user_logins', 'action' => 'index'),array('title' => __l('User Logins'))); ?></li>
    			<?php $class = ($this->request->params['controller'] == 'user_comments') ? ' class="active"' : '';?>
    			<li <?php echo $class; ?>><?php echo $this->Html->link(__l('User Comments'), array('controller' => 'user_comments', 'action' => 'index'), array('title' => __l('User Comments'), 'escape' => false)); ?></li>
        		</ul>
               </li> 
            </ul>
        	</div>
        	</div>
    	 </div>
             <div class="admin-bot-lblock">
				<div class="admin-bot-rblock">
					<div class="admin-bot-cblock"></div>
				</div>
            </div>
        </div>
	</li>
    <?php $controller = array('companies', 'company_addresses');
	$class = (in_array( $this->request->params['controller'], $controller )) ? 'admin-active' : null; ?>

	<li class="no-bor  alpha omega <?php echo $class;?>">
	
		 <span class="amenu-left clearfix">
             <span class="amenu-right clearfix">
                 <span class="menu-center admin-company clearfix">
                      <span> <?php echo __l('Merchants'); ?></span>
                 </span>
            </span>
         </span>
         <div class="admin-sub-block">
          <div class="admin-sub-lblock">
            <div class="admin-sub-rblock">
            <div class="admin-sub-cblock">
    		<ul class="admin-sub-links">
        	<li>
            	<h4><?php echo __l('Merchants'); ?></h4>
                <ul>
                <?php $class = ($this->request->params['controller'] == 'companies' || $this->request->params['controller'] == 'company_addresses') ? ' active' :null; ?>
                <li class=" merchant-info <?php echo $class;?>"><?php echo $this->Html->link(__l('Snapshot'), array('controller' => 'companies', 'action' => 'merchant_stats'),array('title' => __l('Snapshot'))); ?>
				<span class="sub-link-info"><?php echo __l('Top 10, by revenue, by coupons');?></span>
				</li>
                <li class=" <?php echo $class;?>" ><?php echo $this->Html->link(__l('Merchants'), array('controller' => 'companies', 'action' => 'index'),array('title' => __l('Merchants'))); ?></li>
            	<li class=" <?php echo $class;?>" ><?php echo $this->Html->link(__l('Add Merchant'), array('controller' => 'companies', 'action' => 'add'),array('title' => __l('Add Merchant'))); ?></li>
            	<?php $class = ($this->request->params['controller'] == 'company_addresses' && $this->request->params['action'] == 'admin_branches') ? ' class="active"' : null; ?>
				<li class=" <?php echo $class;?>">
			 	<?php echo $this->Html->link(__l('Branches & Online Users'), array('controller' => 'company_addresses', 'action' => 'admin_branches'),array('title' => __l('Branches & Online Users'))); ?>
                </li>
            	</ul>
             </li>   
            <li>
            	<h4><?php echo __l('Suggestions'); ?></h4>
                <ul>
				<?php $class = ($this->request->params['controller'] == 'business_suggestions') ? ' class="active"' : null; ?>
                 <li <?php echo $class;?>><?php echo $this->Html->link(__l('Business Suggestions'), array('controller' => 'business_suggestions', 'action' => 'index'), array('title' => __l('Business Suggestions'))); ?></li>
                 </ul>
             </li>
             </ul>
        	</div>
        	</div>
    	 </div>
             <div class="admin-bot-lblock">
				<div class="admin-bot-rblock">
					<div class="admin-bot-cblock"></div>
				</div>
            </div>
        </div>
	
	</li>
    <?php $controller = array('deals',  'deal_users');
	$class = ( in_array( $this->request->params['controller'], $controller )  && !in_array($this->request->params['action'], array('admin_referral_commission')) ) ? 'admin-active' : null; ?>
	<li class="no-bor alpha omega <?php echo $class;?>">
        <span class="amenu-left clearfix">
         <span class="amenu-right clearfix">
         <span class="menu-center admin-deals clearfix">
            <span><?php echo __l('Deals'); ?></span>
         </span>
        </span>
         </span>
      <div class="admin-sub-block">
           <div class="admin-sub-lblock">
                    <div class="admin-sub-rblock">
                        <div class="admin-sub-cblock">
    	<ul class="admin-sub-links">
        	<li>
            	<h4><?php echo __l('Deals'); ?></h4>
                <ul>
                <li class="<?php echo $class;?>"><?php echo $this->Html->link(__l('Snapshot'), array('controller' => 'deals', 'action' => 'deal_stats'),array('title' => __l('Snapshot'))); ?></li>
                    <li class="<?php echo $class;?>">
                     <?php echo $this->Html->link(__l('Deals'), array('controller' => 'deals', 'action' => 'index', 'type' => 'all'),array('title' => __l('Deals'))); ?>
                    </li>
                    <?php $class = ($this->request->params['controller'] == 'deals' && $this->request->params['action'] == 'admin_add') ? ' class="active"' : null; ?>
                    <li <?php echo $class;?>><?php echo $this->Html->link(__l('Add Deal'), array('controller' => 'deals', 'action' => 'admin_add'), array('title' => __l('Add Deal'))); ?></li>
                    <?php $class = ($this->request->params['controller'] == 'deal_users') ? ' class="active"' : null; ?>
                    <li <?php echo $class;?>><?php echo $this->Html->link(__l('Deal Orders/Coupons'), array('controller' => 'deal_users', 'action' => 'index'), array('title' => __l('Deal Orders/Coupons'))); ?></li>
                    <?php $class = ($this->request->params['controller'] == 'deals' && $this->request->params['action'] == 'admin_live') ? ' class="active"' : null; ?>
                </ul>
                <h4><?php echo __l('Live Deals'); ?></h4>
                <ul>
                    <li <?php echo $class;?>>
                     <?php echo $this->Html->link(__l('Live Deals'), array('controller' => 'deals', 'action' => 'live', 'type' => 'all'),array('title' => __l('Live Deals'))); ?>
                    </li>
                    <?php $class = ($this->request->params['controller'] == 'deals' && $this->request->params['action'] == 'admin_live_add') ? ' class="active"' : null; ?>
                    <li <?php echo $class;?>><?php echo $this->Html->link(__l('Add Live Deal'), array('controller' => 'deals', 'action' => 'live_add'), array('title' => __l('Add Live Deal'))); ?></li>
                 </ul> 
        	</li>
        </ul>
        </div>
        </div>
         </div>
            <div class="admin-bot-lblock">
				<div class="admin-bot-rblock">
					<div class="admin-bot-cblock"></div>
				</div>
            </div>
        </div>
    </li>
   <?php $controller = array('subscriptions', 'mail_chimp_lists' );
	$class = ( in_array( $this->request->params['controller'], $controller ) ) ? 'admin-active' : null; ?>

	<li class="no-bor subscriptions alpha omega <?php echo $class;?>">
     <span class="amenu-left clearfix">
         <span class="amenu-right clearfix">
         <span class="menu-center admin-subscriptions clearfix">
            <span><?php echo __l('Subscriptions'); ?></span>
         </span>
        </span>
         </span>
	 <div class="admin-sub-block">
             <div class="admin-sub-lblock">
                    <div class="admin-sub-rblock">
                        <div class="admin-sub-cblock">
                    <ul class="admin-sub-links">
                        <li>
                            <h4><?php echo __l('Subscriptions'); ?></h4>
                            <ul>
							<?php $class = ($this->request->params['controller'] == 'subscriptions' && $this->request->params['action'] == 'admin_index') ? ' class="active"' : null; ?>
                            <li <?php echo $class;?>><?php echo $this->Html->link(__l('Subscriptions'), array('controller' => 'subscriptions', 'action' => 'admin_index', 'type' => 'subscribed'),array('title' => __l('Subscriptions'))); ?></li>
                            <li class="setting-overview"><?php echo $this->Html->link(__l('Settings'), array('controller' => 'settings', 'action' => 'edit', 1),array('title' => __l('Settings'))); ?></li>
                            <li class="setting-overview customize-subscription"><?php echo $this->Html->link(__l('Customize Subscription Page'), array('controller' => 'subscriptions', 'action' => 'admin_subscription_customise'),array('class' => 'customize-subscriptions', 'title' => __l('Customize Subscription Page'))); ?></li>
                              <?php $class = ($this->request->params['controller'] == 'subscriptions' && $this->request->params['action'] == 'admin_subscription_customise') ? ' class="active"' : null; ?>
							<?php $class = ($this->request->params['controller'] == 'mail_chimp_lists') ? ' class="active"' : null; ?>
                            <li <?php echo $class;?>><?php echo $this->Html->link(__l('MailChimp Mailing Lists'), array('controller' => 'mail_chimp_lists', 'action' => 'index'), array('title' => __l('MailChimp Mailing Lists'))); ?></li>
                            </ul>
                          </li> 
        </ul>
        </div>
        </div>
        </div>
               <div class="admin-bot-lblock">
				<div class="admin-bot-rblock">
					<div class="admin-bot-cblock"></div>
				</div>
            </div>
        </div>
	</li>
    <?php $controller = array('payment_gateways', 'transactions', 'gift_users', 'user_cash_withdrawals',  'affiliate_cash_withdrawals' );
	$class = ( in_array( $this->request->params['controller'], $controller ) ) ? 'admin-active' : null; ?>

	<li class="no-bor alpha omega <?php echo $class;?>">
	 <span class="amenu-left clearfix">
             <span class="amenu-right clearfix">
                 <span class="menu-center admin-payment clearfix">
                    <span><?php echo __l('Payments'); ?></span>
                 </span>
            </span>
         </span>
     <div class="admin-sub-block">
           <div class="admin-sub-lblock">
                    <div class="admin-sub-rblock">
                        <div class="admin-sub-cblock">
		<ul class="admin-sub-links">
            <li>
                <h4><?php echo __l('Payments'); ?></h4>
                <ul>
					<?php
                    $class = ($this->request->params['controller'] == 'transactions') ? ' class="active"' : null; ?>
                   <li <?php echo $class;?>><?php echo $this->Html->link(__l('Transactions'), array('controller' => 'transactions', 'action' => 'index'),array('title' => __l('Transactions'))); ?></li>
                     <?php $class = ($this->request->params['controller'] == 'gift_users') ? ' class="active"' : null; ?>
                   <li <?php echo $class;?>><?php echo $this->Html->link(__l('Gift Cards'), array('controller' => 'gift_users', 'action' => 'index'),array('title' => __l('Gift Cards'))); ?></li>
                    <?php $class = ($this->request->params['controller'] == 'payment_gateways') ? ' class="active"' : null; ?>
                    <li class="setting-overview"><?php echo $this->Html->link(__l('Payment Gateways'), array('controller' => 'payment_gateways', 'action' => 'index'), array('title' => __l('Payment Gateways')));?></li>
                 </ul>
                <h4><?php echo __l('Withdraw Fund Requests'); ?></h4>
                <ul>
					<?php
                    if($this->Html->isWalletEnabled('is_enable_for_add_to_wallet')){
                       if((Configure::read('company.is_user_can_withdraw_amount')) || (Configure::read('user.is_user_can_with_draw_amount'))){?>
                    <?php $class = ($this->request->params['controller'] == 'user_cash_withdrawals') ? ' class="active"' : null; ?>
                    <li <?php echo $class;?>><?php echo $this->Html->link(__l('Users & Merchants'), array('controller' => 'user_cash_withdrawals', 'action' => 'index', 'filter_id' => ConstWithdrawalStatus::Pending),array('title' => __l('Users & Merchants'))); ?></li>
                    <?php } } ?>
                   <li><?php echo $this->Html->link(__l('Affiliates'), array('controller' => 'affiliate_cash_withdrawals', 'action' => 'index', 'filter_id' => ConstAffiliateCashWithdrawalStatus::Pending),array('title' => __l('Affiliates'))); ?></li>
                 </ul>
                <h4><?php echo __l('Release payment'); ?></h4>
                <ul>
                    <li><?php echo $this->Html->link(__l('Charities'),array('controller'=>'charities','action'=>'index'),array('title' => __l('Charities')));?></li>
                 </ul>
               </li>    
               
		</ul>
		</div>
		</div>
		</div>
             <div class="admin-bot-lblock">
				<div class="admin-bot-rblock">
					<div class="admin-bot-cblock"></div>
				</div>
            </div>
        </div>
	</li>
    <?php $controller = array('charities', 'affiliates', 'affiliate_requests',  'charity_cash_withdrawals', 'charity_money_transfer_accounts','affiliate_types');
	$class = ( in_array( $this->request->params['controller'], $controller ) || in_array($this->request->params['action'], array('admin_referral_commission', 'admin_referred_users')) ) ? 'admin-active' : null; ?>
	<li class="no-bor alpha omega <?php echo $class;?>">
		 <span class="amenu-left clearfix">
             <span class="amenu-right clearfix">
                 <span class="menu-center admin-charity clearfix">
                    <span><?php echo __l('Partners'); ?></span>
                 </span>
            </span>
         </span>
        <div class="admin-sub-block">
           <div class="admin-sub-lblock">
                    <div class="admin-sub-rblock">
                        <div class="admin-sub-cblock">
                	   <ul class="admin-sub-links">
                            <li>
                                <h4><?php echo __l('Affiliates'); ?></h4>
                                <ul>
								<?php $class = ($this->request->params['controller'] == 'affiliates') ? ' class="active"' : null; ?>
                				<li <?php echo $class;?>><?php echo $this->Html->link(__l('Affiliates'), array('controller' => 'affiliates', 'action' => 'index'),array('title' => __l('Affiliates'))); ?></li>
								<li><?php echo $this->Html->link(__l('Requests'), array('controller' => 'affiliate_requests', 'action' => 'index'), array('title' => __l('Affiliate Requests'))); ?></li>
                                <li><?php echo $this->Html->link(__l('Withdraw Fund Requests'), array('controller' => 'affiliate_cash_withdrawals', 'action' => 'index', 'filter_id' => ConstAffiliateCashWithdrawalStatus::Pending),array('title' => __l('Withdraw Fund Requests'))); ?></li>
                                <li class="setting-overview"><?php echo $this->Html->link(__l('Common Settings'), array('controller' => 'settings', 'action' => 'edit', 9),array('title' => __l('Common Settings'), 'class' => 'affiliate-settings')); ?></li>
                                <li><?php echo $this->Html->link(__l('Commission Settings'), array('controller' => 'affiliate_types', 'action' => 'edit'),array('title' => __l('Commission Settings'))); ?></li>
                                </ul>
                             </li>   
                            <li>
                                <h4><?php echo __l('Referrals'); ?></h4>
                                <ul>
                                <li <?php echo $class;?>><?php echo $this->Html->link(__l('Referrals'), array('controller' => 'users', 'action' => 'referred_users'),array('title' => __l('Referrals'))); ?></li>
                				<li <?php echo $class;?>><?php echo $this->Html->link(__l('Referral Commissions'), array('controller' => 'deal_users', 'action' => 'referral_commission'),array('title' => __l('Referral Commissions'))); ?></li>
								</ul>
                             </li> 
                            <li>
                                <h4><?php echo __l('Charities'); ?></h4>
                                <ul>
								<?php $controller = array('charities', 'charity_cash_withdrawals');
                                $class = ( in_array( $this->request->params['controller'], $controller ) ) ? 'admin-active' : null; ?>
                                <li <?php echo $class;?>><?php echo $this->Html->link(__l('Charities'), array('controller' => 'charities', 'action' => 'index'), array('title' => __l('Charities')));?></li>
    							<li><?php echo $this->Html->link(__l('Add Charity'),array('controller'=>'charities','action'=>'add'),array('title' => __l('Add Charity')));?></li>
                                 <li><?php echo $this->Html->link(__l('Payment History'),array('controller'=>'charity_cash_withdrawals','action'=>'index'),array('title' => __l('Payment History')));?></li>

                                </ul>
                             </li>   
                        </ul>
                     </div>
            </div>
        </div>
            <div class="admin-bot-lblock">
				<div class="admin-bot-rblock">
					<div class="admin-bot-cblock"></div>
				</div>
            </div>
        </div>
		
	</li>
    <?php $controller = array('apns_messages', 'apns_devices', 'apns_feedback_logs' );
	$class = ( in_array( $this->request->params['controller'], $controller ) ) ? 'admin-active' : null; ?>

    <li class="no-bor masters alpha omega <?php echo $class;?>">
      <span class="amenu-left clearfix">
             <span class="amenu-right clearfix">
                 <span class="menu-center admin-topics clearfix">
                   <span><?php echo __l('iPhone'); ?></span>
                 </span>
            </span>
         </span>
        <div class="admin-sub-block">
            <div class="admin-sub-lblock">
                    <div class="admin-sub-rblock">
                        <div class="admin-sub-cblock">
                    	<ul class="admin-sub-links">
                        <li>
                            <h4><?php echo __l('iPhone Notifications'); ?></h4>
                            <ul>
                                <?php $class = ($this->request->params['controller'] == 'apns_devices') ? ' class="active"' : null; ?>
                                <li <?php echo $class;?>><?php echo $this->Html->link(__l('Registered Devices'), array('controller' => 'apns_devices', 'action' => 'index'),array('title' => __l('Registered Devices'))); ?></li>
								<?php //if(Configure::read('subscription.iphone_apns_push_mail') == ConstIphoneApnsPushMail::Site) { ?>
								<?php $class = ($this->request->params['controller'] == 'apns_messages') ? ' class="active"' : null; ?>
                                <li <?php echo $class;?>><?php echo $this->Html->link(__l('Sent Push Messages'), array('controller' => 'apns_messages', 'action' => 'index'),array('title' => __l('Sent Push Messages'))); ?></li>
								<?php $class = ($this->request->params['controller'] == 'apns_devices' && $this->request->params['action'] == 'broadcast') ? ' class="active"' : null; ?>
                                <li <?php echo $class;?>><?php echo $this->Html->link(__l('Broadcast Message'), array('controller' => 'apns_devices', 'action' => 'index'),array('title' => __l('Broadcast Message'))); ?></li>
                                <?php $class = ($this->request->params['controller'] == 'apns_feedback_logs') ? ' class="active"' : null; ?>
                                <li <?php echo $class;?>><?php echo $this->Html->link(__l('Unregistered Devices Feedbacks'), array('controller' => 'apns_feedback_logs', 'action' => 'index'),array('title' => __l('Unregistered Devices Feedbacks'))); ?></li>
                			<?php //} ?>
                            </ul>
                            </li>
                        </ul>
                        </div>
                       </div>
                      </div>
	          
                <div class="admin-bot-lblock">
				<div class="admin-bot-rblock">
					<div class="admin-bot-cblock"></div>
				</div>
            </div>
        </div>
	</li>
    <?php /*?><?php 
	$controller = array('paypal_transaction_logs', 'paypal_transaction_logs',  'paypal_transaction_logs', 'paypal_docapture_logs', 'authorizenet_docapture_logs');
	$class = ( in_array( $this->request->params['controller'], $controller ) || ($this->request->params['controller'] == 'users' && $this->request->params['action'] == 'admin_logs') ) ? 'admin-active' : null; ?>
    <li class="masters subscriptions <?php echo $class;?>">
    	 <span class="amenu-left">
             <span class="amenu-right">
                 <span class="menu-center admin-diagnostics">
                 <?php echo $this->Html->image('admin-image/icon-diagonistics.png');?>
                    <span><?php echo __l('Diagnostics'); ?></span>
                 </span>
            </span>
         </span>
	<div class="admin-sub-block">
           <div class="admin-sub-lblock">
                    <div class="admin-sub-rblock">
                        <div class="admin-sub-cblock">
                    		<ul class="admin-sub-links">
                               <?php $class = ($this->request->params['controller'] == 'paypal_transaction_logs' && $this->request->params['named']['type'] == 'normal') ? ' class="active"' : null; ?>
                    			<li <?php echo $class;?>><?php echo $this->Html->link(__l('Payment Transaction Log'), array('controller' => 'paypal_transaction_logs', 'action' => 'index', 'type' => 'normal'),array('title' => __l('Payment Transaction Log'))); ?></li>
                    			<?php $class = ($this->request->params['controller'] == 'paypal_transaction_logs' && $this->request->params['named']['type'] == 'mass') ? ' class="active"' : null; ?>
                    			<li <?php echo $class;?>><?php echo $this->Html->link(__l('Mass Payment Transaction Log'), array('controller' => 'paypal_transaction_logs', 'action' => 'index', 'type' => 'mass'),array('title' => __l('Mass Payment Transaction Log'))); ?></li>
                    			<?php $class = ($this->request->params['controller'] == 'paypal_docapture_logs') ? ' class="active"' : null; ?>
                    			<li <?php echo $class;?>><?php echo $this->Html->link(__l('Paypal Docapture Log'), array('controller' => 'paypal_docapture_logs', 'action' => 'index'),array('title' => __l('Paypal Docapture Log'))); ?></li>
                    			<?php $class = ($this->request->params['controller'] == 'authorizenet_docapture_logs') ? ' class="active"' : null; ?>
                    			<li <?php echo $class;?>><?php echo $this->Html->link(__l('Authorizenet Docapture Log'), array('controller' => 'authorizenet_docapture_logs', 'action' => 'index'),array('title' => __l('Authorizenet Docapture Log'))); ?></li>
                      			<?php $class = ($this->request->params['controller'] == 'users' && $this->request->params['action'] == 'admin_logs') ? ' class="active"' : null; ?>
                    			<li <?php echo $class;?>><?php echo $this->Html->link(__l('Debug & Error Log'), array('controller' => 'users', 'action' => 'logs'),array('title' => __l('Debug & Error Log'))); ?></li>
                    			<li <?php echo $class;?>><?php echo $this->Html->link(__l('Diagnostics'), Router::url('/', true).'diagnose.php', array('title' => __l('Diagnostics'), 'target' => '_blank')); ?></li>
                            </ul>
	               	</div>
	           	</div>
		  </div>
               <div class="admin-bot-lblock">
				<div class="admin-bot-rblock">
					<div class="admin-bot-cblock"></div>
				</div>
            </div>
        </div>
	</li><?php */?>
    <?php $class = ($this->request->params['controller'] == 'settings') ? 'admin-active' : null; ?>
	<li class="masters alpha omega setting-masters-block masters-block <?php echo $class;?>">
	 <span class="amenu-left clearfix">
         <span class="amenu-right clearfix">
             <span class="menu-center admin-setting clearfix">
                <span><?php echo __l('Settings'); ?></span>
             </span>
        </span>
    </span>
     <div class="admin-sub-block">
           <div class="admin-sub-lblock">
                    <div class="admin-sub-rblock">
                        <div class="admin-sub-cblock">
                    		<ul class="admin-sub-links clearfix">
                                <li>
                                <ul>
                                <li class="setting-overview setting-overview1 clearfix"><?php echo $this->Html->link(__l('Overview'), array('controller' => 'settings', 'action' => 'index'),array('title' => __l('Overview'), 'class' => 'setting-overview')); ?></li>
                                <li>
                                  <h4 class="setting-title"><?php echo __l('Settings'); ?></h4>
                            	    <ul>
                            	       <li class="admin-sub-links-left">
                                            <ul>
            	                               <li>
                                              
                                                    <ul>
                                                        <li><?php echo $this->Html->link(__l('System'), array('controller' => 'settings', 'action' => 'edit', 1),array('title' => __l('System'))); ?></li>
                                                        <li><?php echo $this->Html->link(__l('Developments'), array('controller' => 'settings', 'action' => 'edit', 2),array('title' => __l('Developments'))); ?></li>
                                                        <li><?php echo $this->Html->link(__l('SEO'), array('controller' => 'settings', 'action' => 'edit', 3),array('title' => __l('SEO'))); ?></li>
                                                        <li><?php echo $this->Html->link(__l('Regional, Currency & Language'), array('controller' => 'settings', 'action' => 'edit', 4),array('title' => __l('Regional, Currency & Language'))); ?></li>
                                                        <li><?php echo $this->Html->link(__l('Account '), array('controller' => 'settings', 'action' => 'edit', 5),array('title' => __l('Account'))); ?></li>
                                                        <li><?php echo $this->Html->link(__l('Deal'), array('controller' => 'settings', 'action' => 'edit', 6),array('title' => __l('Deal'))); ?></li>
                                                        <li><?php echo $this->Html->link(__l('Payment'), array('controller' => 'settings', 'action' => 'edit', 7),array('title' => __l('Payment'))); ?></li>
                                                        <li><?php echo $this->Html->link(__l('Charity'), array('controller' => 'settings', 'action' => 'edit', 8),array('title' => __l('Charity'))); ?></li>
                                                        <li><?php echo $this->Html->link(__l('Affiliate'), array('controller' => 'settings', 'action' => 'edit', 9),array('title' => __l('Affiliate'))); ?></li>
                                                    </ul>
                                                </li>
                                            </ul>
                            	       </li>
                            	       <li class="admin-sub-links-right">
                                            <ul>
            	                               <li>
                                                    <ul>
                                                        <li><?php echo $this->Html->link(__l('Referrals'), array('controller' => 'settings', 'action' => 'edit', 10),array('title' => __l('Referrals'))); ?></li>
                                                        <li><?php echo $this->Html->link(__l('Mobile Apps & Push Notification'), array('controller' => 'settings', 'action' => 'edit', 11),array('title' => __l('Mobile Apps & Push Notification'))); ?></li>
                                                        <li><?php echo $this->Html->link(__l('Widget'), array('controller' => 'settings', 'action' => 'edit', 12),array('title' => __l('Widget'))); ?></li>
                                                        <li><?php echo $this->Html->link(__l('Friends'), array('controller' => 'settings', 'action' => 'edit', 13),array('title' => __l('Friends'))); ?></li>
                                                        <li><?php echo $this->Html->link(__l('Third Party API'), array('controller' => 'settings', 'action' => 'edit', 14),array('title' => __l('Third Party API'))); ?></li>
                                                        <li><?php echo $this->Html->link(__l('CDN'), array('controller' => 'settings', 'action' => 'edit', 15),array('title' => __l('CDN'))); ?></li>
                                                        <li><?php echo $this->Html->link(__l('Module Manager'), array('controller' => 'settings', 'action' => 'edit', 16),array('title' => __l('Module Manager'))); ?></li>
                                                        <li class="<?php echo $class;?>"><?php echo $this->Html->link(__l('Customize Subscription Page'), array('controller' => 'subscriptions', 'action' => 'admin_subscription_customise'),array('class' => 'customize-subscriptions', 'title' => __l('Customize Subscription Page'))); ?></li>
                                                    </ul>
                                                </li>
                                            </ul>
                            	       </li>
                                    </ul>
                            	</li>
                                </ul>
                                </li>
                            </ul>
		              </div>
	           	</div>
		</div>
             <div class="admin-bot-lblock">
				<div class="admin-bot-rblock">
					<div class="admin-bot-cblock"></div>
				</div>
            </div>
        </div>
	</li>
   	<?php $controller = array('currencies', 'spanail_tspanplates',  'pages', 'transaction_types', 'translations', 'languages',  'banned_ips', 'cities', 'states', 'countries',  'user_educations', 'user_spanployments', 'user_income_ranges', 'user_relationships', 'genders', 'privacy_types', 'deal_categories', 'charity_categories', 'affiliate_widget_sizes', 'ips');
	$class = ( in_array( $this->request->params['controller'], $controller ) ) ? 'admin-active' : null; ?>
	<li class="masters masters-block  <?php echo $class;?>">
		 <span class="amenu-left clearfix">
             <span class="amenu-right clearfix">
                 <span class="menu-center admin-masters clearfix">
                   <span> <?php echo __l('Masters'); ?></span>
                 </span>
            </span>
         </span>
       <div class="admin-sub-block">
            <div class="admin-sub-lblock">
                    <div class="admin-sub-rblock">
                        <div class="admin-sub-cblock">
	    <ul class="admin-sub-links clearfix">
	    <li>
	    <div class="page-info master-page-info"><?php echo __l('Warning! Please edit with caution.');?></div>
	    <ul>
	    <li class="admin-sub-links-left">
    	    <ul>
            	<li>
                <h4><?php echo __l('Regional'); ?></h4>
                <ul>
    				<?php $class = ($this->request->params['controller'] == 'cities') ? ' class="active"' : null; ?>
                    <li <?php echo $class;?>><?php echo $this->Html->link(__l('Cities'), array('controller' => 'cities', 'action' => 'index'),array('title' => __l('Cities'))); ?></li>
                    <?php $class = ($this->request->params['controller'] == 'city_suggestions') ? ' class="active"' : null; ?>
                    <li <?php echo $class;?>><?php echo $this->Html->link(__l('City Suggestions'), array('controller' => 'city_suggestions', 'action' => 'index'), array('title' => __l('City Suggestions'))); ?></li>
                    <?php $class = ($this->request->params['controller'] == 'countries') ? ' class="active"' : null; ?>
                    <li <?php echo $class;?>><?php echo $this->Html->link(__l('Countries'), array('controller' => 'countries', 'action' => 'index'),array('title' => __l('Countries'))); ?></li>
    				<?php $class = ($this->request->params['controller'] == 'states') ? ' class="active"' : null; ?>
                    <li <?php echo $class;?>><?php echo $this->Html->link(__l('States'), array('controller' => 'states', 'action' => 'index'),array('title' => __l('States'))); ?></li>
                    <?php $class = ($this->request->params['controller'] == 'currencies') ? ' class="active"' : null; ?>
                    <li <?php echo $class;?>><?php echo $this->Html->link(__l('Currencies'), array('controller' => 'currencies', 'action' => 'index'),array('title' => __l('Currencies'))); ?></li>
    				<?php $class = ($this->request->params['controller'] == 'banned_ips') ? ' class="active"' : null; ?>
                    <li <?php echo $class;?>><?php echo $this->Html->link(__l('Banned IPs'), array('controller' => 'banned_ips', 'action' => 'index'),array('title' => __l('Banned IPs'))); ?></li>
                </ul>
                </li>
                <li>
                <h4><?php echo __l('Languages'); ?></h4>
                <ul>
                	<?php $class = ($this->request->params['controller'] == 'languages') ? ' class="active"' : null; ?>
                    <li <?php echo $class;?>><?php echo $this->Html->link(__l('Languages'), array('controller' => 'languages', 'action' => 'index'),array('title' => __l('Languages'))); ?></li>
                    <?php $class = ($this->request->params['controller'] == 'translations') ? ' class="active"' : null; ?>
                    <li <?php echo $class;?>><?php echo $this->Html->link(__l('Translations'), array('controller' => 'translations', 'action' => 'index'),array('title' => __l('Translations'))); ?></li>
                </ul>
                </li>
                <li>
                <h4><?php echo __l('Static pages'); ?></h4>
                <ul>
    				<?php $class = ($this->request->params['controller'] == 'pages') ? ' class="active"' : null; ?>
                    <li <?php echo $class;?>><?php echo $this->Html->link(__l('Manage Static Pages'), array('controller' => 'pages', 'action' => 'index', 'plugin' => NULL),array('title' => __l('Manage Static Pages')));?></li>
                </ul>
            </li>
            <li>
            <h4><?php echo __l('Email'); ?></h4>
            <ul>
        		<?php $class = ($this->request->params['controller'] == 'email_templates') ? ' class="active"' : null; ?>
                <li <?php echo $class;?>><?php echo $this->Html->link(__l('Email Templates'), array('controller' => 'email_templates', 'action' => 'index'),array('title' => __l('Email Templates'))); ?></li>
            </ul>
            </li>
            </ul>
            </li>
            
            <li class="admin-sub-links-right">
            <ul>
        	<li>
            <h4><?php echo __l('Demographics'); ?></h4>
            <ul>
				<?php $class = ($this->request->params['controller'] == 'user_educations') ? ' class="active"' : null; ?>
                <li <?php echo $class;?>><?php echo $this->Html->link(__l('Educations'), array('controller' => 'user_educations', 'action' => 'index'), array('title' => __l('Educations'))); ?></li>
				<?php $class = ($this->request->params['controller'] == 'genders') ? ' class="active"' : null; ?>
				<?php $class = ($this->request->params['controller'] == 'user_employments') ? ' class="active"' : null; ?>
                <li <?php echo $class;?>><?php echo $this->Html->link(__l('Employments'), array('controller' => 'user_employments', 'action' => 'index'), array('title' => __l('Employments'))); ?></li>
                <li <?php echo $class;?>><?php echo $this->Html->link(__l('Genders'), array('controller' => 'genders', 'action' => 'index'),array('title' => __l('Genders'))); ?></li>
                <?php $class = ($this->request->params['controller'] == 'user_income_ranges') ? ' class="active"' : null; ?>
                <li <?php echo $class;?>><?php echo $this->Html->link(__l('Income Ranges'), array('controller' => 'user_income_ranges', 'action' => 'index'), array('title' => __l('Income Ranges'))); ?></li>
                <?php $class = ($this->request->params['controller'] == 'user_relationships') ? ' class="active"' : null; ?>
                <li <?php echo $class;?>><?php echo $this->Html->link(__l('Relationships'), array('controller' => 'user_relationships', 'action' => 'index'), array('title' => __l('Relationships'))); ?></li>
            </ul>
            </li>
        	<li>
            <h4><?php echo __l('Others'); ?></h4>
            <ul>
                <li><?php echo $this->Html->link(__l('Widgets'), array('controller' => 'affiliate_widget_sizes', 'action' => 'index'),array('title' => __l('Widgets'))); ?></li>
				<?php $class = ($this->request->params['controller'] == 'privacy_types') ? ' class="active"' : null; ?>
                <li <?php echo $class;?>><?php echo $this->Html->link(__l('Privacy Types'), array('controller' => 'privacy_types', 'action' => 'index'),array('title' => __l('Privacy Types'))); ?></li>
                <?php if(Configure::read('charity.is_enabled') == 1):?>
                 <?php $class = ($this->request->params['controller'] == 'charity_categories') ? ' class="active"' : null; ?>
                 <li <?php echo $class;?>><?php echo $this->Html->link(__l('Charity Categories'), array('controller' => 'charity_categories', 'action' => 'index'), array('title' => __l('Charity Categories')));?></li>
                <?php endif; ?>		
                <?php $class = ($this->request->params['controller'] == 'deal_categories') ? ' class="active"' : null; ?>
                <li <?php echo $class;?>><?php echo $this->Html->link(__l('Live Deal Categories'), array('controller' => 'deal_categories', 'action' => 'index'),array('title' => __l('Live Deal Categories'))); ?></li>
            	<?php $class = ($this->request->params['controller'] == 'transaction_types') ? ' class="active"' : null; ?>
				<li <?php echo $class;?>><?php echo $this->Html->link(__l('Transaction Types'), array('controller' => 'transaction_types', 'action' => 'index'),array('title' => __l('Transaction Types'))); ?></li>
				<?php $class = ($this->request->params['controller'] == 'ips') ? ' class="active"' : null; ?>
				<li<?php echo $class;?>><?php echo $this->Html->link(__l('IPs'), array('controller' => 'ips', 'action' => 'index'), array('title' => __l('IPs'))); ?></li>
             </ul>
            </li>
            </ul>
            </li>
            </ul>
            </li>
        </ul>
        </div>
        </div>
          </div>
               <div class="admin-bot-lblock">
				<div class="admin-bot-rblock">
					<div class="admin-bot-cblock"></div>
				</div>
            </div>
        </div>
	</li>
    </ul>