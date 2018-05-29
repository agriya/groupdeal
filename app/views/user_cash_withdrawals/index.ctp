<?php /* SVN: $Id: index.ctp 79518 2012-09-27 12:32:41Z balamurugan_177at12 $ */ ?>
<div class="userCashWithdrawals index js-response js-withdrawal_responses js-responses">
<?php if($this->Auth->user('user_type_id') != ConstUserTypes::Company):?>
<h2><?php echo __l('Withdraw Fund Request');?></h2>
	<?php endif; ?>

<?php if(!empty($moneyTransferAccounts) || $is_wallet_enabled) : ?>
        <?php echo $this->element('../user_cash_withdrawals/add', array('cache' => array('config' => 'site_element_cache'))); ?>
<?php else:
	if($massPayEnableCount >0) :?>
	<div class="page-info">
	<?php	
		echo $this->Html->link(__l('Your money transfer account is empty, so click here to update money transfer account.'), array('controller' => 'money_transfer_accounts', 'action'=>'index'), array('title' => __l('money transfer accounts')));	
	?>
	</div>
<?php  endif;
endif;?>

<?php
?>
<?php echo $this->element('paging_counter');?>
<table class="list">
    <tr>
		<th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Requested On'), 'UserCashWithdrawal.created');?></div></th>
        <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Amount').' ('.Configure::read('site.currency').')', 'UserCashWithdrawal.amount');?></div></th>
        <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Status'),'WithdrawalStatus.name');?></div></th>
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
		<td><?php echo $this->Html->cDateTime($userCashWithdrawal['UserCashWithdrawal']['created']);?></td>
    	<td class="dr"><?php echo $this->Html->cCurrency($userCashWithdrawal['UserCashWithdrawal']['amount']);?></td>
		<td><?php echo $this->Html->cText($userCashWithdrawal['WithdrawalStatus']['name']);?></td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="8" class="notice"><?php echo __l('No withdraw fund requests available');?></td>
	</tr>
<?php
endif;
?>
</table>

<?php if (!empty($userCashWithdrawals)):?>
	<div class="js-pagination">
		<?php
			echo $this->element('paging_links');
		?>
	</div>
<?php endif;?>
</div>