<?php /* SVN: $Id: admin_index.ctp 79793 2012-12-05 14:14:56Z ananda_176at12 $ */ ?>
<?php 
$debit_total_amt = $credit_total_amt = $gateway_total_fee = 0;
$credit = $debit = 1;
if(!empty($this->request->params['named']['type']) && ($this->request->params['named']['type'] == ConstTransactionTypes :: AddedToWallet) && !empty($this->request->params['named']['stat'])) {
    $debit = 0;
}
if(!empty($this->request->params['named']['type']) && ($this->request->params['named']['type'] == ConstTransactionTypes::AcceptCashWithdrawRequest || $this->request->params['named']['type'] == ConstTransactionTypes::PaidDealAmountToCompany) && !empty($this->request->params['named']['stat'])) {
    $credit = 0;
}
?>	
<div class="transactions index js-response js-responses">
	<div class="clearfix">
		<ul class="clearfix filter-list">
			<li class="filter-admin <?php echo !(!empty($this->request->params['named']['filter']) && $this->request->params['named']['filter'] == 'all') ? 'active' : ''; ?>"><?php echo $this->Html->link(__l('Admin'), array('controller' => 'transactions', 'action' => 'index'), array('title' => __l('Admin'), 'escape' => false)); ?></li>
			<li class="filter-all  <?php echo (!empty($this->request->params['named']['filter']) && $this->request->params['named']['filter'] == 'all') ? 'active' : ''; ?>"><?php echo $this->Html->link(__l('All'), array('controller' => 'transactions', 'action' => 'index', 'filter' => 'all'), array('title' => __l('All'), 'escape' => false)); ?></li>
		</ul>
	</div>
    <div class="page-count-block clearfix">
    <div class="grid_left">
    <?php echo $this->element('paging_counter');?>
    </div>
   <div class="grid_left">
		<?php echo $this->Form->create('Transaction' , array('action' => 'admin_index', 'type' => 'post', 'class' => 'normal search-form clearfix js-ajax-form')); ?>
		<div class="mapblock-info">
			<?php echo $this->Form->autocomplete('User.username', array('label' => __l('User'), 'acFieldKey' => 'Transaction.user_id', 'acFields' => array('User.username'), 'acSearchFieldNames' => array('User.username'), 'maxlength' => '255')); ?>
			<div class="autocompleteblock">            
			</div>
		</div>
		<div class="mapblock-info">
			<?php echo $this->Form->autocomplete('Deal.name', array('label' => __l('Deal'), 'acFieldKey' => 'Transaction.deal_id', 'acFields' => array('Deal.name'), 'acSearchFieldNames' => array('Deal.name'), 'maxlength' => '255')); ?>
			<div class="autocompleteblock">            
			</div>
		</div> 
		<?php
		if(!empty($this->request->data['Transaction']['user_id'])) {
			echo $this->Form->input('user_hidden_id',array('type' => 'hidden', 'value' => $this->request->data['Transaction']['user_id']));
		}
		?>
		
			<div class="clearfix date-time-block">
				<div class="input date-time clearfix">
					<div class="js-datetime">
						<?php echo $this->Form->input('from_date', array('label' => __l('From'), 'type' => 'date', 'minYear' => date('Y')-10, 'maxYear' => date('Y'), 'div' => false, 'empty' => __l('Please Select'), 'orderYear' => 'asc')); ?>
					</div>
				</div>
				<div class="input date-time end-date-time-block clearfix">
					<div class="js-datetime">
						<?php echo $this->Form->input('to_date', array('label' => __l('To '),  'type' => 'date', 'minYear' => date('Y')-10, 'maxYear' => date('Y'), 'div' => false, 'empty' => __l('Please Select'), 'orderYear' => 'asc')); ?>
					</div>
				</div>
			</div>  
			<?php
			echo $this->Form->submit(__l('Filter'));
			 ?>
		<?php echo $this->Form->end(); ?>
	</div>
	<div class="add-block1 grid_right">
    	<?php if(!empty($transactions)) { ?>
    	<?php echo $this->Html->link(__l('CSV'), array('controller' => 'transactions', 'action' => 'index', 'city' => $city_slug, 'hash' => $export_hash, 'ext' => 'csv', 'admin' => true), array('class' => 'export', 'title' => __l('CSV'), 'escape' => false)); ?>
    	<?php } ?>
	</div>
	</div>

	<div class="">

	<?php 
		$get_conversion_currency = $this->Html->getConversionCurrency();
		$conv_var = '';
	?>
	<?php if(isset($get_conversion_currency['supported_currency']) && empty($get_conversion_currency['supported_currency'])):?>
		<?php $conv_var = ' ['.$get_conversion_currency['conv_currency_symbol'].']';?>
	<?php endif;?>
    <table class="list">
        <tr>
            <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Date'), 'Transaction.created');?></div></th>
            <?php if(empty($this->request->params['named']['user_id'])):	 ?>
                <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('User'), 'User.username');?></div></th>
            <?php endif; ?>
            <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Description'),'TransactionType.name');?></div></th>
            <?php if(!empty($credit)){ ?>
                <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Credit'), 'Transaction.amount');?></div></th>
            <?php } ?>
            <?php if(!empty($debit)){?>
                <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Debit'), 'Transaction.amount');?></div></th>
            <?php } ?>
            <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Gateway Fees'), 'Transaction.gateway_fees').' ('.Configure::read('site.currency').')';?></div></th>
        </tr>
    <?php
    if (!empty($transactions)):
    $i = 0;
    foreach ($transactions as $transaction):
        $class = null;
        if ($i++ % 2 == 0) {
            $class = ' class="altrow"';
        }
		/* if(Configure::read('site.is_auto_currency_updation') == 1){
			$curr_code = (!empty($transaction['Currency']['code']) ? $transaction['Currency']['code'] : Configure::read('paypal.currency_code'));
			$curr_symbol = (!empty($transaction['Currency']['symbol']) ? $transaction['Currency']['symbol'] : Configure::read('site.currency'));
		} else { */
			$curr_code = Configure::read('paypal.currency_code');
			$curr_symbol = Configure::read('site.currency');
		//}
    ?>
        <tr<?php echo $class;?>>
                <td><?php echo $this->Html->cDateTimeHighlight($transaction['Transaction']['created']);?></td>
				<?php if(empty($this->request->params['named']['user_id'])):	?>
                    <td class="dl">
						<?php echo $this->Html->getUserAvatarLink($transaction['User'], 'micro_thumb', false); ?>
						<?php echo $this->Html->getUserLink($transaction['User']); ?>
					</td>
	            <?php endif; ?>
                <td class="dl">
                	<?php if(($transaction['Transaction']['transaction_type_id'] == ConstTransactionTypes::DeductedAmountForOfflineCompany || $transaction['Transaction']['transaction_type_id'] == ConstTransactionTypes::AddFundToWallet || $transaction['Transaction']['transaction_type_id'] == ConstTransactionTypes::DeductFundFromWallet) && !empty($transaction['Transaction']['description'])):?>
						<?php echo $this->Html->cText($transaction['Transaction']['description']); ?>					
					<?php else:?>
						<?php echo $this->Html->transactionDescription($transaction);?>
					<?php endif;?>
                </td>
                <?php if(!empty($credit)) {?>
                    <td class="dr">
						<?php
							if($transaction['TransactionType']['is_credit'] && $transaction['TransactionType']['is_credit'] != ConstTransaction::Request):
								echo $this->Html->siteCurrencyFormat($this->Html->cCurrency($transaction['Transaction']['amount'],'span', '', $curr_code), $curr_symbol);
								$credit_total_amt = $credit_total_amt + $transaction['Transaction']['amount']; 
								if(Configure::read('site.is_auto_currency_updation') == 1 && !empty($transaction['Transaction']['converted_amount']) && $transaction['Transaction']['converted_amount'] != '0.00' && !empty($transaction['Transaction']['converted_currency_id'])):
									if($transaction['Transaction']['converted_currency_id'] != $transaction['Transaction']['currency_id']):								
										echo '['.$this->Html->siteCurrencyFormat($this->Html->cCurrency($transaction['Transaction']['converted_amount'],'span', '', $transaction['ConvertedCurrency']['code']), $transaction['ConvertedCurrency']['symbol']).']';
									endif;
								endif;
							else:
								echo '--';
							endif;
						 ?>
					</td>
                <?php } ?>
                <?php if(!empty($debit)) {?>
                    <td class="dr">
						 <?php
					if($transaction['TransactionType']['is_credit'] && $transaction['TransactionType']['is_credit'] != ConstTransaction::Request):
                        echo '--';
                    else:					
						if($transaction['TransactionType']['is_credit'] == ConstTransaction::Request)
						{
						?>
							<span id="tip1" class="js-helptip" title="<?php echo __l("Blocked Amount"); ?>">*</span>
						<?php
						}else {
							$debit_total_amt = $debit_total_amt + $transaction['Transaction']['amount'];
						}echo $this->Html->siteCurrencyFormat($this->Html->cCurrency($transaction['Transaction']['amount'],'span', '', $curr_code), $curr_symbol);							    
								if(Configure::read('site.is_auto_currency_updation') == 1 && !empty($transaction['Transaction']['converted_amount']) && $transaction['Transaction']['converted_amount'] != '0.00' && !empty($transaction['Transaction']['converted_currency_id'])):
									if($transaction['Transaction']['converted_currency_id'] != $transaction['Transaction']['currency_id']):								
										echo '['.$this->Html->siteCurrencyFormat($this->Html->cCurrency($transaction['Transaction']['converted_amount'],'span', '', $transaction['ConvertedCurrency']['code']), $transaction['ConvertedCurrency']['symbol']).']';
									endif;
								endif;
							endif;
						 ?>
					</td>
                <?php } ?>
                <td class="dr">
					<?php echo $this->Html->cFloat($transaction['Transaction']['gateway_fees']);
						 $gateway_total_fee = $gateway_total_fee + $transaction['Transaction']['gateway_fees']; ?>
			    </td>
            </tr>
    <?php
        endforeach; ?>

		<tr class="total-block">
            <td colspan="<?php echo (!empty($this->request->params['named']['user_id'])) ? 2 : 3;?>" class="dr"><?php echo __l('Total');?></td>
             <?php if(!empty($credit)) {?>
            <td class="dr"><?php echo Configure::read('site.currency') . $this->Html->cCurrency($credit_total_amt);?></td>
			 <?php } if(!empty($debit)) {?>
			<td class="dr"><?php echo Configure::read('site.currency') . $this->Html->cCurrency($debit_total_amt);?></td>
			<?php } ?>
			<td class="dr"><?php echo Configure::read('site.currency') . $this->Html->cCurrency($gateway_total_fee);?></td>
        </tr>
   <?php else:
    ?>
        <tr>
            <td colspan="11" class="notice"><?php echo __l('No Transactions available');?></td>
        </tr>
    <?php
    endif;
    ?>
    </table> 
    <?php
    if (!empty($transactions)) {
        ?>
            <div class="js-pagination">
                <?php echo $this->element('paging_links'); ?>
            </div>
        <?php
    }
    ?>
	<?php if(empty($this->request->params['named']['stat'])):?>
      <table class="list">
		<tr>
				<th colspan='5' class="dr"> <?php echo __l('Filter Summary');?></th> 
		</tr>
        <tr>
            <td colspan="4" class="dr"><?php echo __l('Credit');?></td>
            <td class="dr"><?php echo Configure::read('site.currency') . $this->Html->cCurrency($total_credit_amount);?></td>
        </tr>
        <tr>
            <td colspan="4" class="dr"><?php echo __l('Debit');?></td>
            <td class="dr"><?php echo Configure::read('site.currency') . $this->Html->cCurrency($total_debit_amount);?></td>
        </tr>
        <tr class="total-block">
            <td colspan="4" class="dr"><?php echo __l('Transaction Summary (Credit - Debit)');?></td>
            <td class="dr"><?php echo Configure::read('site.currency') . $this->Html->cCurrency($total_credit_amount - $total_debit_amount);?></td>
        </tr>
    </table>
	<?php endif;?>

	   <?php if(!empty($this->request->params['named']['user_id'])): ?>
		   <table class="list">
		   <tr>
				<th colspan='5' class="dr"> <?php echo __l('User Summary') .$selected_user_info;?></th> 
		   </tr>
			<tr>
				<td colspan="4" class="dr"><?php echo __l('Credit');?></td>
				<td class="dr"><?php echo Configure::read('site.currency') . $this->Html->cCurrency($credit_total_amt);?></td>
			</tr>
			<tr>
				<td colspan="4" class="dr"><?php echo __l('Debit');?></td>
				<td class="dr"><?php echo Configure::read('site.currency') . $this->Html->cCurrency($debit_total_amt);?></td>
			</tr>
			<tr>
				<td colspan="4" class="dr"><?php echo __l('Withdraw Request');?></td>
				<td class="dr"><?php echo Configure::read('site.currency') . $this->Html->cCurrency($user['User']['blocked_amount']);?></td>
			</tr>
			<tr class="total-block">
				<td colspan="4" class="dr"><?php echo __l('Transaction Summary (Cr - Db - Withdraw Request)');?></td>
				<td class="dr"><?php echo Configure::read('site.currency') . $this->Html->cCurrency($credit_total_amt - ($debit_total_amt + $user['User']['blocked_amount']));?></td>
			</tr>
			<tr class="total-block">
				<td colspan="4" class="dr"><?php echo $selected_user_info.'  '.__l('Account Balance');?></td>
				<td class="dr"><?php echo Configure::read('site.currency') . $this->Html->cCurrency($user['User']['available_balance_amount']);?></td>
			</tr>
		</table>
	 <?php endif; ?>
	<?php if(isset($get_conversion_currency['supported_currency']) && empty($get_conversion_currency['supported_currency'])):?>
		<div class="page-info"><?php echo __l('All the amount in the transactions are listed in').' '.$get_conversion_currency['currency_code'].', '.__l('Where the processed gateway amount is in').' '.$get_conversion_currency['conv_currency_code'].' '.__l('(Showed in bracket).'); ?></div>
	<?php endif;?>
</div>
</div>
