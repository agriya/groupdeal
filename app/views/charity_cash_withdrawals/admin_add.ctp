<?php /* SVN: $Id: $ */ ?>
<div class="charityCashWithdrawals form">
<?php echo $this->Form->create('CharityCashWithdrawal', array('class' => 'normal'));?>
	<fieldset>
	<?php
		$transaction_fee = Configure::read('affiliate.site_commission_amount');
		$transaction_fee_type = Configure::read('affiliate.site_commission_type');
		if(!empty($transaction_fee)){
			$transactions = ($transaction_fee_type == 'amount') ? $this->Html->siteCurrencyFormat($this->Html->cCurrency($transaction_fee)) : $transaction_fee.'%';
			$transactions = __l('Transaction Fee').':'. $transactions;
		}
		else{
			$transactions = '';
		}
			
		echo $this->Form->input('charity_id');
		echo $this->Form->input('amount',array('label' => __l('Amount'),'after' => Configure::read('site.currency') . '<span class="info sfont">' . sprintf('%s', $transactions) . '</span>'));
	?>
	<div class="submit-block">
        <?php echo $this->Form->end(__l('Add'));?>
    </div>
    </fieldset>
</div>
