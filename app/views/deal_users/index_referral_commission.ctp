<div class="index_referral_commission">
	<div class="referral-pending-block">
		<h2><?php echo __l('Pending Referral Commission'); ?></h2>
		<?php echo $this->element('deal_users-referral_commission', array('type' => 'pending', 'cache' => array('config' => 'sec 1', 'key' => $this->Auth->user('id') . 'pending'))); ?>
	</div>
	<div class="referral-completed-block">
		<h2><?php echo __l('Earned Referral Commission'); ?></h2>
		<?php echo $this->element('deal_users-referral_commission', array('type' => 'completed', 'cache' => array('config' => 'sec 1', 'key' => $this->Auth->user('id') . 'completed'))); ?>
	</div>
</div>