<?php /* SVN: $Id: index.ctp 1721 2010-04-17 11:06:44Z preethi_083at09 $ */ ?>
<div class="userCashWithdrawals index js-response js-withdrawal_responses js-responses">
<?php if(!empty($moneyTransferAccounts)) { ?>
        <?php echo $this->element('../affiliate_cash_withdrawals/add', array('cache' => array('config' => 'site_element_cache'))); ?>
<?php }
else
{ ?>
<div class="page-info">
<b>
<?php
 echo $this->Html->link(__l('Your money transfer account is empty, so click here to update your money transfer account.'), array('controller' => 'money_transfer_accounts', 'action'=>'index'), array('title' => __l('Edit money transfer accounts')));
 ?>
 </b>
 </div>
<?php
}
?>
<?php echo $this->element('paging_counter');?>
  <div class="overflow-block">
<table class="list">
    <tr>
		<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Requested On'), 'AffiliateCashWithdrawal.created');?></div></th>
        <th class="dr"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Amount').' ('.Configure::read('site.currency').')', 'AffiliateCashWithdrawal.amount');?></div></th>
<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Status'),'AffiliateCashWithdrawalStatus.name');?></div></th>
    </tr>
<?php
if (!empty($userCashWithdrawals)):
$i = 0;
foreach ($userCashWithdrawals as $userCashWithdrawal):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td><?php echo $this->Html->cDateTime($userCashWithdrawal['AffiliateCashWithdrawal']['created']);?></td>
    	<td class="dr"><?php echo $this->Html->siteCurrencyFormat($this->Html->cCurrency($userCashWithdrawal['AffiliateCashWithdrawal']['amount']));?></td>
		<td>
		<?php 
			if($userCashWithdrawal['AffiliateCashWithdrawalStatus']['id'] == ConstAffiliateCashWithdrawalStatus::Pending):
				echo __l('Pending');
			elseif($userCashWithdrawal['AffiliateCashWithdrawalStatus']['id'] == ConstAffiliateCashWithdrawalStatus::Approved):
				echo __l('Under Process');
			elseif($userCashWithdrawal['AffiliateCashWithdrawalStatus']['id'] == ConstAffiliateCashWithdrawalStatus::Rejected):
				echo __l('Rejected');
			elseif($userCashWithdrawal['AffiliateCashWithdrawalStatus']['id'] == ConstAffiliateCashWithdrawalStatus::Failed):
				echo __l('Failed');
			elseif($userCashWithdrawal['AffiliateCashWithdrawalStatus']['id'] == ConstAffiliateCashWithdrawalStatus::Success):
				echo __l('Success');
				if(!empty($userCashWithdrawal['AffiliateCashWithdrawal']['commission_amount'])):
				echo "<p>".($this->Html->siteCurrencyFormat($this->Html->cCurrency($userCashWithdrawal['AffiliateCashWithdrawal']['amount'] - $userCashWithdrawal['AffiliateCashWithdrawal']['commission_amount']))).' = ['.$this->Html->siteCurrencyFormat($this->Html->cCurrency($userCashWithdrawal['AffiliateCashWithdrawal']['amount'])).' - '.$this->Html->siteCurrencyFormat($this->Html->cCurrency($userCashWithdrawal['AffiliateCashWithdrawal']['commission_amount'])).']'."</p>";				
				endif;
			else:
				echo $this->Html->cText($userCashWithdrawal['AffiliateCashWithdrawalStatus']['name']);
			endif;
		?>
		</td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="8" class="notice"><?php echo __l('No withdraw requests available');?></td>
	</tr>
<?php
endif;
?>
</table>
</div>
<?php
if (!empty($userCashWithdrawals)) { ?>
      <div class="js-pagination"> <?php echo $this->element('paging_links'); ?> </div> 
<?php } ?>
</div>
