<?php /* SVN: $Id: index.ctp 79788 2012-12-05 07:39:50Z ananda_176at12 $ */ ?>
<?php if(empty($this->request->params['named']['stat']) && !isset($this->request->data['Transaction']['tab_check']) && empty($this->request->params['named']['page'])): ?>
<?php if($this->Auth->user('user_type_id') != ConstUserTypes::Company):?>
<h2><?php echo __l('Transactions'); ?></h2>
<?php endif; ?>
    <div class="js-tabs">
        <ul class="clearfix">
            <li><em></em><?php echo $this->Html->link(__l('Today'), array('controller' => 'transactions', 'action' => 'index', 'stat' => 'day'), array('title' => 'Day Transactions')); ?></li>
            <li><em></em><?php echo $this->Html->link(__l('This Week'), array('controller' => 'transactions', 'action' => 'index', 'stat' => 'week'), array('title' => 'This Week Transactions')); ?></li>
            <li><em></em><?php echo $this->Html->link(__l('This Month'), array('controller' => 'transactions', 'action' => 'index', 'stat' => 'month'), array('title' => 'This Month Transactions')); ?></li>
            <li><em></em><?php echo $this->Html->link(__l('All'), array('controller' => 'transactions', 'action' => 'index', 'stat' => 'all'), array('title' => 'All Transactions')); ?></li>
        </ul>
    </div>
<?php else: ?>
    <div class="transactions index js-response js-responses">
    <div class="js-search-responses">
    <div class="clearfix">
        <div>
            <?php echo $this->element('paging_counter');?>
        </div>
        <div>
        <?php
            echo $this->Form->create('Transaction', array('action' => 'index' ,'class' => 'normal js-ajax-form'));
         ?>
         <div class="clearfix">
            <div class="clearfix omega grid_left alpha date-time-block">
                <div class="input date-time omega alpha grid_left clearfix">
                    <div class="js-datetime">
                        <?php echo $this->Form->input('from_date', array('label' => __l('From'), 'type' => 'date', 'minYear' => date('Y')-10, 'maxYear' => date('Y'), 'div' => false, 'empty' => __l('Please Select'), 'orderYear' => 'asc')); ?>
                    </div>
                </div>
                <div class="input date-time end-date-time-block  omega alpha grid_left clearfix">
                    <div class="js-datetime">
                        <?php echo $this->Form->input('to_date', array('label' => __l('To '),  'type' => 'date', 'minYear' => date('Y')-10, 'maxYear' => date('Y'), 'div' => false, 'empty' => __l('Please Select'), 'orderYear' => 'asc')); ?>
                    </div>
                </div>
            </div>
          <?php
          echo $this->Form->input('tab_check', array('type' => 'hidden','value' => 'tab_check')); ?>
       	  <div class="grid_2 omega alpha transection-submit-block clearfix">
            <?php
            	echo $this->Form->submit(__l('Filter'));
            ?>
            </div>
            </div>
            <?php
            	echo $this->Form->end();
            ?>
    </div>
    </div>
	<?php 
		$get_conversion_currency = $this->Html->getConversionCurrency();
		$conv_var = '';
	?>
	<?php if(isset($get_conversion_currency['supported_currency']) && empty($get_conversion_currency['supported_currency'])):?>
		<?php $conv_var = ' ['.$get_conversion_currency['conv_currency_symbol'].']';?>
	<?php endif;?>
    <table class="list">
        <tr>
            <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Date'), 'created');?></div></th>
            <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Description'),'transaction_type_id');?></div></th>
            <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Credit'), 'amount');?></div></th>
            <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Debit'), 'amount');?></div></th>
        </tr>
    <?php
		if (!empty($transactions)):
			$i = 0;
			$j = 1;
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
            <td><?php echo $this->Html->cDateTime($transaction['Transaction']['created']);?></td>
            <td class="dl">
            	<?php if(($transaction['Transaction']['transaction_type_id'] == ConstTransactionTypes::DeductedAmountForOfflineCompany || $transaction['Transaction']['transaction_type_id'] == ConstTransactionTypes::AddFundToWallet || $transaction['Transaction']['transaction_type_id'] == ConstTransactionTypes::DeductFundFromWallet) && !empty($transaction['Transaction']['description'])):?>
					<?php echo $this->Html->cText($transaction['Transaction']['description']); ?>					
				<?php else:?>
					<?php echo $this->Html->transactionDescription($transaction);?>
				<?php endif;?>
            </td>
            <td class="dr">
                <?php
                    if($transaction['TransactionType']['is_credit'] && $transaction['TransactionType']['is_credit'] != ConstTransaction::Request):
						echo $this->Html->siteCurrencyFormat($this->Html->cCurrency($transaction['Transaction']['amount'],'span', '', $curr_code), $curr_symbol);
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
            <td class="dr">
                <?php
                    if($transaction['TransactionType']['is_credit'] && $transaction['TransactionType']['is_credit'] != ConstTransaction::Request):
                        echo '--';
                    else:					
						if($transaction['TransactionType']['is_credit'] == ConstTransaction::Request)
						{
							echo __l("Request ");
						}
						echo $this->Html->siteCurrencyFormat($this->Html->cCurrency($transaction['Transaction']['amount'],'span', '', $curr_code), $curr_symbol);
						if(Configure::read('site.is_auto_currency_updation') == 1 && !empty($transaction['Transaction']['converted_amount']) && $transaction['Transaction']['converted_amount'] != '0.00' && !empty($transaction['Transaction']['converted_currency_id'])):
							if($transaction['Transaction']['converted_currency_id'] != $transaction['Transaction']['currency_id']):								
								echo '['.$this->Html->siteCurrencyFormat($this->Html->cCurrency($transaction['Transaction']['converted_amount'],'span', '', $transaction['ConvertedCurrency']['code']), $transaction['ConvertedCurrency']['symbol']).']';
							endif;
						endif;
                    endif;
                 ?>
            </td>
        </tr>
    <?php
        $j++;
        endforeach;
    ?>
    <?php
    else:
    ?>
        <tr>
            <td colspan="11" class="notice"><?php echo __l('No Transactions available');?></td>
        </tr>
    <?php
    endif;
    ?>
    </table>
    <table class="list">
        <tr>
            <td colspan="4" class="dr"><?php echo __l('Credit');?></td>
            <td class="dr"><?php echo Configure::read('site.currency') . $this->Html->cCurrency($total_credit_amount);?></td>
        </tr>
        <tr>
            <td colspan="4" class="dr"><?php echo __l('Debit');?></td>
            <td class="dr"><?php echo Configure::read('site.currency') . $this->Html->cCurrency($total_debit_amount);?></td>
        </tr>
		<?php if ((Configure::read('company.is_user_can_withdraw_amount') && $this->Auth->user('user_type_id') == ConstUserTypes::Company) || (Configure::read('user.is_user_can_with_draw_amount') && $this->Auth->user('user_type_id') == ConstUserTypes::User)): ?>
        <tr>
            <td colspan="4" class="dr"><?php echo __l('Withdraw Request');?></td>
            <td class="dr"><?php echo Configure::read('site.currency') . $this->Html->cCurrency($blocked_amount);?></td>
        </tr>
		<?php endif;?>
        <tr class="total-block">
            <td colspan="4" class="dr">
			<?php if ((Configure::read('company.is_user_can_withdraw_amount') && $this->Auth->user('user_type_id') == ConstUserTypes::Company) || (Configure::read('user.is_user_can_with_draw_amount') && $this->Auth->user('user_type_id') == ConstUserTypes::User)): ?>
				<?php echo __l('Transaction Summary (Cr - Db - Withdraw Request)');?>
			<?php else:?>
				<?php echo __l('Transaction Summary (Cr - Db)');?>			
			<?php endif;?>
			</td>
            <td class="dr"><?php echo Configure::read('site.currency') . $this->Html->cCurrency($total_credit_amount - ($total_debit_amount + $blocked_amount));?></td>
        </tr>
        <tr class="total-block">
            <td colspan="4" class="dr"><?php echo __l('Account Balance');?></td>
            <td class="dr"><?php echo Configure::read('site.currency') . $this->Html->cCurrency($user_available_balance);?></td>
        </tr>
    </table>
	<?php if(isset($get_conversion_currency['supported_currency']) && empty($get_conversion_currency['supported_currency'])):?>
		<div class="page-info"><?php echo __l('All the amount in the transactions are listed in').' '.$get_conversion_currency['currency_code'].', '.__l('Where the processed gateway amount is in').' '.$get_conversion_currency['conv_currency_code'].' '.__l('(Showed in bracket).'); ?></div>
	<?php endif;?>    <?php
    if (!empty($transactions)) {
        ?>
            <div class="js-pagination">
                <?php echo $this->element('paging_links'); ?>
            </div>
        <?php
    }
    ?>
    </div>
    </div>
<?php endif; ?>