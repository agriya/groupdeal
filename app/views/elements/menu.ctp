<?php
if($this->Auth->sessionValid()  and  $this->Auth->user('user_type_id') == ConstUserTypes::Company):
		$company = $this->Html->getCompany($this->Auth->user('id'));
endif;
?>
<?php if($this->Auth->sessionValid()){  ?>
<ul class="user-menu grid_right">
			<?php if($this->Auth->user('is_openid_register')):
			$reg_type_class='open-id';
			endif;
			if($this->Auth->user('fb_user_id')):
			$reg_type_class='facebook';
			endif;
	?>
  <?php $current_user_details = array('username' => $this->Auth->user('username'), 'user_type_id' =>  $this->Auth->user('user_type_id'), 'id' =>  $this->Auth->user('id'), 'fb_user_id' =>  $this->Auth->user('fb_user_id'));?>
  <li class="user grid_right pr">
    <span class="user-img-left grid_left">
    <?php $current_user_details['UserAvatar'] = $this->Html->getUserAvatar($this->Auth->user('id'));
       echo $this->Html->getUserAvatarLink($current_user_details, 'medium_thumb'); ?>
    </span>
    <span class="user-img-right grid_left">
     <?php echo $this->Html->getUserLink($current_user_details );?> <?php echo $this->Html->link(__l('My Account'), array('controller' => 'user_profiles', 'action' => 'my_account', $this->Auth->user('id')), array('class'=>'user-name1 right-space textn','title' => 'My Account', 'rel' => 'address:/' . __l('My_Account'))); ?>
    </span>
           <?php //if($this->Auth->user('user_type_id') != ConstUserTypes::Company){
        ?>
     <div class="sub-menu-block <?php if($this->Auth->user('user_type_id') == ConstUserTypes::Company): echo "merchant-submenu"; endif;?> pa w-bg round-5">
      <div class="arrow-icon pa"></div>
      <div class="sub-menu-inner-block1">
        <div class="menu-header-block clearfix">
           <?php if($this->Auth->sessionValid() && $this->Html->isWalletEnabled('is_enable_for_add_to_wallet') && isset($user_available_balance)){ ?>
            <p class="total-balance no-mar"> <?php echo __l('Balance: '); ?> <span><?php echo $this->Html->siteCurrencyFormat($this->Html->cCurrency($user_available_balance)); ?></span> </p>
            <?php } ?>
        </div>
 
        <div class="space sub-menu-inner-block clearfix ">
          <div class="left-space menu-inner-left grid_left">
            <ul class="submenu">
              <?php if($this->Auth->sessionValid()): ?>
              <li>
                <?php echo $this->Html->link(__l('My Stuff'), array('controller' => 'users', 'action' => 'my_stuff'), array('class' =>'mystuff textb','title' => __l('My Stuff'))); ?>
                <ul>
                  <?php if($this->Auth->user('user_type_id') == ConstUserTypes::Company || !$this->Html->isAllowed($this->Auth->user('user_type_id'))){?>
                  <li> <?php echo $this->Html->link(__l('Dashboard'), array('controller' => 'companies', 'action' => 'dashboard'), array('title' => __l('Dashboard')));?> </li>
                  <?php }	
                  if($this->Auth->user('user_type_id') != ConstUserTypes::Company):?>
                  <li><?php echo $this->Html->link(__l('My Account'), array('controller' => 'user_profiles', 'action' => 'my_account', $this->Auth->user('id')), array('title' => 'My Account', 'rel' => 'address:/' . __l('My_Account'))); ?></li>
				  <?php
				  else:
				  ?>
				  <li><?php echo $this->Html->link(__l('My Account'), array('controller' => 'companies', 'action' => 'edit', $this->Auth->user('id')), array('title' => 'My Account', 'rel' => 'address:/' . __l('My_Account'))); ?></li>
                  <?php endif; ?>
                  <?php if($this->Auth->sessionValid() && $this->Html->isAllowed($this->Auth->user('user_type_id'))){?>
                  <li>
                    <?php  echo $this->Html->link(sprintf(__l('My %s'), Configure::read('site.name')), array('controller' => 'deal_users', 'action' => 'index'), array('title' => 'My Purchases', 'rel' => 'address:/' . __l('My_Purchases')));?>
                  </li>
                  <li><?php echo $this->Html->link(__l('My Gift Cards'), array('controller' => 'gift_users', 'action' => 'index', 'admin' => false), array('title' => 'My Gift Cards', 'rel' => 'address:/' . __l('My_Gift_Cards')));?></li>
                  <?php } ?>
                  <?php if($this->Auth->user('user_type_id') != ConstUserTypes::Company):?>
                  
                  <li><?php echo $this->Html->link(__l('My Transactions'), array('controller' => 'transactions', 'action' => 'index', 'admin' => false), array('title' => 'My Transactions', 'rel' => 'address:/' . __l('My_Transactions')));?></li>
                  <?php endif;?>
                  <?php if($this->Auth->user('user_type_id') == ConstUserTypes::Company):?>
                  <li> <?php //echo $this->Html->link(__l('My Merchant'), array('controller' => 'companies', 'action' => 'edit',$company['Company']['id']), array('title' => __l('My Merchant'))); ?> </li>
                  <li> <?php //echo $this->Html->link(__l('My Branches'), array('controller' => 'company_addresses', 'action' => 'index'), array('class' => '', 'title' => __l('My Branches')));?> </li>
                  <?php endif;?>
                  <?php	if (Configure::read('company.is_user_can_withdraw_amount') && $massPayEnableCount > 0): ?>
                  <li><?php echo $this->Html->link(__l('Transfer Accounts'), array('controller' => 'money_transfer_accounts', 'action' => 'index'), array('title' => __l('Transfer Accounts'))); ?></li>
                  <?php endif; ?>
                </ul>
              </li>
              <?php if($this->Auth->user('user_type_id') == ConstUserTypes::Company && !empty($company['Company'])){
		         	if(!$this->Html->isWalletEnabled('is_enable_for_add_to_wallet')){ ?>
            </ul>
          </div>

           <div class="menu-inner-right grid_left">

            <ul class="submenu">
              <?php } ?>
              <li>
                <?php //echo $this->Html->link(__l('Deals'), '#', array('class' =>'mystuff textb','title' => __l('Deals'))); ?>
                <ul>
                  <li> <?php //echo $this->Html->link(__l('My Deals'), array('controller' => 'deals', 'action' => 'index', 'company' => $company['Company']['slug'], 'type' => 'all' ), array('title' => __l('My Deals')));?> </li>
                  <li> <?php //echo $this->Html->link(__l('Add Deal'), array('controller' => 'deals', 'action' => 'add'), array('class'=>'add-deal', 'title' => __l('Add Deal')));?> </li>
                  <?php //if(Configure::read('deal.is_live_deal_enabled')) { ?>
                  <li> <?php //echo $this->Html->link(__l('My Live Deals'), array('controller' => 'deals', 'action' => 'index', 'company' => $company['Company']['slug'], 'type' => 'all', 'view' => 'live' ), array('title' => __l('My Live Deals')));?> </li>
                  <li> <?php //echo $this->Html->link(__l('Add Live Deal'), array('controller' => 'deals', 'action' => 'live_add'), array('class'=>'add-deal', 'title' => __l('Add Live Deal')));?> </li>
                  <?php //} ?>
                </ul>
              </li>
              <?php } ?>
              <?php endif; ?>
              <?php if($this->Auth->sessionValid() && ($this->Html->isWalletEnabled('is_enable_for_add_to_wallet') || (Configure::read('affiliate.is_enabled') && $this->Auth->user('is_affiliate_user')))): ?>
            </ul>

             </div>
               <div class="menu-inner-right grid_left">
                 <ul class="submenu">
              <?php if(Configure::read('affiliate.is_enabled') && $this->Auth->user('is_affiliate_user')){?>
              <li> <?php echo $this->Html->link(__l('Affiliate'), '#', array('class' =>'mystuff textb','title' => __l('Affiliates'))); ?>
                <ul>
                  <li><?php echo $this->Html->link(__l('Affiliate'), array('controller' => 'affiliates', 'action' => 'index'), array('title' => __l('Affiliates'))); ?></li>
                </ul>
              </li>
              <?php } ?>
              <?php if($this->Html->isWalletEnabled('is_enable_for_add_to_wallet') && $this->Auth->user('user_type_id') != ConstUserTypes::Company){?>
              <li> <?php echo $this->Html->link(__l('Wallet'), '#', array('class' =>'mystuff textb','title' => __l('Wallet'))); ?>
                <ul>
                  <?php if($this->Html->isAllowed($this->Auth->user('user_type_id')) && $this->Auth->user('user_type_id') != ConstUserTypes::Company): ?>
                  <?php $class = ($this->request->params['controller'] == 'users' && $this->request->params['action'] == 'add_to_wallet') ? " "."active" : null; ?>
                  <li> <?php echo $this->Html->link(__l('Add Amount to Wallet'), array('controller' => 'users', 'action' => 'add_to_wallet'), array('class' => ''.$class, 'title' => __l('Add amount to wallet'))); ?> </li>
                  <?php endif; ?>
                  <?php if ((Configure::read('company.is_user_can_withdraw_amount') && $this->Auth->user('user_type_id') != ConstUserTypes::Company) || (Configure::read('user.is_user_can_with_draw_amount') && $this->Auth->user('user_type_id') == ConstUserTypes::User)) { ?>
                  <?php $class = ($this->request->params['controller'] == 'user_cash_withdrawals' && $this->request->params['action'] == 'user_cash_withdrawals') ? " "."active" : null; ?>
                  <li> <?php echo $this->Html->link(__l('Withdraw Fund Request'), array('controller' => 'user_cash_withdrawals', 'action' => 'index'), array('title' => __l('Withdraw Fund Request'),'class'=>''.$class));?> </li>
                  <?php } ?>
                </ul>
              </li>
              <?php } ?>
              <?php endif; ?>
               </ul>
             </div>
            
              <div class="menu-inner-right1 grid_left">
                 <ul class="submenu">
                 <?php if($this->Auth->sessionValid() && $this->Html->isAllowed($this->Auth->user('user_type_id'))){
			     if(Configure::read('friend.is_enabled')){ ?>
                  <li> <?php echo $this->Html->link(__l('Friends'), '#', array('class' =>'mystuff textb','title' => __l('Friends'))); ?>
                   <ul>

                  <li><?php echo $this->Html->link(__l('My Friends'), array('controller' => 'user_friends', 'action' => 'lst', 'admin' => false), array('title' => 'My Friends', 'rel' => 'address:/' . __l('My_Friends')));?></li>
                  <li><?php echo $this->Html->link(__l('Import Friends'), array('controller' => 'user_friends', 'action' => 'import', 'admin' => false), array('title' => 'Import Friends', 'rel' => 'address:/' . __l('Import_Friends'))); ?></li>
                  
                  </ul>
                  </li>
                   <?php } }  ?>
                   <li class="logout no-pad"> <?php echo $this->Html->link(__l('Logout'), array('controller' => 'users', 'action' => 'logout'), array('class' => 'logout-link round-5', 'title' => __l('Logout'))); ?> </li>
                    </ul>
                    </div>
                   
                    </div>
 

                    
                  </div>
                </div>
                                   <?php
                   // }
                    ?>
  </li>
</ul>
  <?php } ?>
  <?php if(!$this->Auth->sessionValid()): ?>
  <ul class="signin-list clearfix">
    <li class="grid_left"><?php echo $this->Html->link(__l('Sign In'), array('controller' => 'users', 'action' => 'login'), array('title' => __l('Sign In')));;?></li>
    <li class="grid_left"><?php echo $this->Html->link(__l('Sign Up'), array('controller' => 'users', 'action' => 'register', 'admin' => false), array('title' => __l('Sign Up')));?></li>
  </ul>
  <?php endif; ?>

