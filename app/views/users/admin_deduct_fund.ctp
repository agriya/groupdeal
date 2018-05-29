<?php /* SVN: $Id: admin_add.ctp 6515 2010-06-02 10:45:44Z sreedevi_140ac10 $ */ ?>
<div class="users form">
<?php echo $this->Form->create('User', array('action' => 'deduct_fund', 'class' => 'normal'));?>
	<fieldset>
	<?php
		if(Configure::read('site.currency_symbol_place') == 'left'):
			$currecncy_place = 'between';
		else:
			$currecncy_place = 'after';
		endif;	
	?>		
 	<p class="fund-available"><?php echo sprintf(__l('Available balance amount: %s'), $this->Html->siteCurrencyFormat($this->Html->cCurrency($user['User']['available_balance_amount']))); ?></p>
	<?php
        echo $this->Form->input('Transaction.user_id', array('type' => 'hidden'));
		echo $this->Form->input('Transaction.amount', array($currecncy_place => '<span class="currency">'.Configure::read('site.currency'). '</span>'));
		echo $this->Form->input('Transaction.description', array('type' => 'textarea'));
	?>
	</fieldset>
	<div class="submit-block clearfix">
    <?php echo $this->Form->submit(__l('Deduct Fund'));?>
    </div>
    <?php echo $this->Form->end();?>
</div>