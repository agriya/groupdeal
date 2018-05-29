<?php
if($this->Auth->sessionValid()  and  $this->Auth->user('user_type_id') == ConstUserTypes::Company):
		$company = $this->Html->getCompany($this->Auth->user('id'));
endif;
?>
<ul class="admin-links merchant-links clearfix">

	<?php $class = ($this->request->params['controller'] == 'companies' && $this->request->params['action'] == 'dashboard') ? 'admin-active' : null; ?>
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
                                <?php echo $this->Html->link(__l('Snapshot'), array('controller' => 'companies', 'action' => 'dashboard'),array('title' => __l('Snapshot'))); ?>
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
    <?php $controller = array('companies');
          $action = array('edit');
	$class = (in_array($this->request->params['controller'], $controller ) && in_array($this->request->params['action'], $action )) ? 'admin-active' : null; ?>

	<li class="no-bor  alpha omega <?php echo $class;?>">
	
		 <span class="amenu-left clearfix">
             <span class="amenu-right clearfix">
                 <span class="menu-center merchant-company clearfix">
                      <span> <?php echo __l('Merchants'); ?></span>
                 </span>
            </span>
         </span>
         <div class="admin-sub-block">
          <div class="admin-sub-lblock">
            <div class="admin-sub-rblock">
            <div class="admin-sub-cblock">
            <ul class="">
                            <li>

                                <?php echo $this->Html->link(__l('My Merchants'), array('controller' => 'companies', 'action' => 'edit',$this->Auth->user('id')),array('title' => __l('My Merchants'))); ?>
   
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
	<?php $controller = array('company_addresses');
	$class = (in_array( $this->request->params['controller'], $controller )) ? 'admin-active' : null; ?>

	<li class="no-bor  alpha omega <?php echo $class;?>">

		 <span class="amenu-left clearfix">
             <span class="amenu-right clearfix">
                 <span class="menu-center merchant-branch clearfix">
                      <span> <?php echo __l('Branches'); ?></span>
                 </span>
            </span>
         </span>
         <div class="admin-sub-block">
          <div class="admin-sub-lblock">
            <div class="admin-sub-rblock">
            <div class="admin-sub-cblock">
            <ul class="">
                            <li>

                                <?php echo $this->Html->link(__l('My Branches'), array('controller' => 'company_addresses', 'action' => 'index'), array('class' => '', 'title' => __l('My Branches')));?>

                            </li>
							<li>

                                <?php echo $this->Html->link(__l('Add Branches'), array('controller' => 'company_addresses', 'action' => 'add','company_id' => $company['Company']['id']), array('class' => '', 'title' => __l('My Branches')));?>

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

	$class = ( in_array( $this->request->params['controller'], $controller )  && ((!$this->request->params['named']['view'] || $this->request->params['action']=='add') && $this->request->params['action']!='live_add') && !in_array($this->request->params['action'], array('admin_referral_commission')) ) ? 'admin-active' : null; ?>
	<li class="no-bor alpha omega <?php echo $class;?>">
        <span class="amenu-left clearfix">
         <span class="amenu-right clearfix">
         <span class="menu-center merchant-deals clearfix">
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
            	<?php echo $this->Html->link(__l('My Deals'), array('controller' => 'deals', 'action' => 'index', 'company' => $company['Company']['slug'], 'type' => 'all' ), array('title' => __l('My Deals')));?>
            </li>
            <li>
                <?php echo $this->Html->link(__l('Add Deal'), array('controller' => 'deals', 'action' => 'add'), array('class'=>'add-deal', 'title' => __l('Add Deal')));?>
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
	$class = ( in_array( $this->request->params['controller'], $controller )  && ($this->request->params['named']['view'] || ($this->request->params['action']=='live_add' && $this->request->params['action']!='add')) && !in_array($this->request->params['action'], array('admin_referral_commission')) ) ? 'admin-active' : null; ?>
	<li class="no-bor alpha omega <?php echo $class;?>">
        <span class="amenu-left clearfix">
         <span class="amenu-right clearfix">
         <span class="menu-center merchant-livedeals clearfix">
            <span><?php echo __l('Live Deals'); ?></span>
         </span>
        </span>
         </span>
      <div class="admin-sub-block">
           <div class="admin-sub-lblock">
                    <div class="admin-sub-rblock">
                        <div class="admin-sub-cblock">
    	<ul class="admin-sub-links">
        	<?php if(Configure::read('deal.is_live_deal_enabled')) { ?>
                  <li> <?php echo $this->Html->link(__l('My Live Deals'), array('controller' => 'deals', 'action' => 'index', 'company' => $company['Company']['slug'], 'type' => 'all', 'view' => 'live' ), array('title' => __l('My Live Deals')));?> </li>
                  <li> <?php echo $this->Html->link(__l('Add Live Deal'), array('controller' => 'deals', 'action' => 'live_add'), array('class'=>'add-deal', 'title' => __l('Add Live Deal')));?> </li>
                  <?php } ?>

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
    <?php $controller = array('transactions');
	$class = (in_array( $this->request->params['controller'], $controller )) ? 'admin-active' : null; ?>

	<li class="no-bor  alpha omega <?php echo $class;?>">

		 <span class="amenu-left clearfix">
             <span class="amenu-right clearfix">
                 <span class="menu-center merchant-transaction clearfix">
                      <span> <?php echo __l('Transactions'); ?></span>
                 </span>
            </span>
         </span>
         <div class="admin-sub-block">
          <div class="admin-sub-lblock">
            <div class="admin-sub-rblock">
            <div class="admin-sub-cblock">
            <ul class="">
                            <li><?php echo $this->Html->link(__l('My Transactions'), array('controller' => 'transactions', 'action' => 'index', 'admin' => false), array('title' => 'My Transactions', 'rel' => 'address:/' . __l('My_Transactions')));?></li>
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
    <?php $controller = array('money_transfer_accounts');
	$class = (in_array( $this->request->params['controller'], $controller )) ? 'admin-active' : null; ?>
	<?php	if (Configure::read('company.is_user_can_withdraw_amount') && $massPayEnableCount > 0): ?>
	<li class="no-bor  alpha omega <?php echo $class;?>">

		 <span class="amenu-left transfer clearfix">
             <span class="amenu-right clearfix">
                 <span class="menu-center merchant-transfer clearfix">
                      <span> <?php echo __l('Transfer Accounts'); ?></span>
                 </span>
            </span>
         </span>
         <div class="admin-sub-block">
          <div class="admin-sub-lblock">
            <div class="admin-sub-rblock">
            <div class="admin-sub-cblock">
            <ul class="">
                
                  <li><?php echo $this->Html->link(__l('Transfer Accounts'), array('controller' => 'money_transfer_accounts', 'action' => 'index'), array('title' => __l('Transfer Accounts'))); ?></li>
                  
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
	<?php endif; ?>
    <?php $controller = array('user_cash_withdrawals');
	$class = (in_array( $this->request->params['controller'], $controller )) ? 'admin-active' : null; ?>

	<li class="no-bor  alpha omega <?php echo $class;?>">

		 <span class="amenu-left clearfix">
             <span class="amenu-right clearfix">
                 <span class="menu-center merchant-wallet clearfix">
                      <span> <?php echo __l('Wallet'); ?></span>
                 </span>
            </span>
         </span>
         <div class="admin-sub-block">
          <div class="admin-sub-lblock">
            <div class="admin-sub-rblock">
            <div class="admin-sub-cblock">
            <ul class="">
                 <?php if($this->Html->isAllowed($this->Auth->user('user_type_id'))): ?>
                  <?php $class = ($this->request->params['controller'] == 'users' && $this->request->params['action'] == 'add_to_wallet') ? " "."active" : null; ?>
                  <li> <?php echo $this->Html->link(__l('Add Amount to Wallet'), array('controller' => 'users', 'action' => 'add_to_wallet'), array('class' => ''.$class, 'title' => __l('Add amount to wallet'))); ?> </li>
                  <?php endif; ?>
                  <?php if ((Configure::read('company.is_user_can_withdraw_amount') && $this->Auth->user('user_type_id') == ConstUserTypes::Company) || (Configure::read('user.is_user_can_with_draw_amount') && $this->Auth->user('user_type_id') == ConstUserTypes::User)) { ?>
                  <?php $class = ($this->request->params['controller'] == 'user_cash_withdrawals' && $this->request->params['action'] == 'user_cash_withdrawals') ? " "."active" : null; ?>
                  <li> <?php echo $this->Html->link(__l('Withdraw Fund Request'), array('controller' => 'user_cash_withdrawals', 'action' => 'index'), array('title' => __l('Withdraw Fund Request'),'class'=>''.$class));?> </li>
                  <?php
                  }
                  ?>
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