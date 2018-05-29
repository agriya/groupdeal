<div class="js-tabs">
	<ul class="clearfix">
		<li>
        <em></em><?php echo $this->Html->link(__l('My Profile'), array('controller' => 'user_profiles', 'action' => 'edit', $user_id, 'admin' => false), array('title' => 'My Profile', 'rel'=> '#Request_API_Key')); ?></li>
		<li><em></em><?php echo $this->Html->link(__l('My Connections'), array('controller' => 'users', 'action' => 'profile_image', 'connect' => 'linked_accounts', $user_id, 'admin' => false), array('title' => 'My Connections', 'rel'=> '#Connect')); ?></li>
		<?php $is_show_credit_cards = $this->Html->isAuthorizeNetEnabled(); ?>
		<?php if (!empty($is_show_credit_cards)): ?>
			<li><em></em><?php echo $this->Html->link(__l('Credit Cards'), array('controller' => 'user_payment_profiles', 'action' => 'index', 'admin' => false), array('title' => 'Credit Cards', 'rel' => '#Credit_cards')); ?></li>
		<?php endif; ?>
		<?php if(!$this->Auth->user('fb_user_id') && !$this->Auth->user('is_openid_register')){?>
			<li><em></em><?php  echo $this->Html->link(__l('Change Password'),array('controller'=> 'users', 'action'=>'change_password'),array('title' => 'Change Password', 'rel' => '#Change_Password')); ?></li>
		<?php } ?>
		<?php if($this->Auth->user('user_type_id') != ConstUserTypes::Company):?>
			  <li><em></em><?php echo $this->Html->link(__l('Privacy Settings'), array('controller' => 'user_permission_preferences', 'action' => 'edit', $user_id, 'admin' => false), array('title' => 'Privacy Settings', 'rel' => '#Privacy_Settings'));?></li>
		<?php endif; ?>
		<?php if($this->Auth->user('user_type_id') != ConstUserTypes::Company):?>
			  <li><em></em><?php echo $this->Html->link(__l('Manage Subscriptions'), array('controller' => 'subscriptions', 'action' => 'manage_subscription',  'city' => $this->request->params['named']['city'], 'admin' => false), array('title' => 'Manage Subscriptions', 'rel' => '#Manage_Subscriptions'));?></li>
		<?php endif; ?>
		<?php if (Configure::read('site.is_api_enabled')): ?>
			 <!--<li>-->
			 <?//php echo $this->Html->link(__l('My').' '.Configure::read('site.name').' '.__l(' API'), array('controller' => 'users', 'action' => 'my_api', $this->Auth->user('id'), 'admin' => false), array('title' => 'Request API Key', 'rel'=> '#Request_API_Key'));?>
			<!--</li>-->
		 <?php endif; ?>
	</ul>
</div>