<div class="clearfix">
<div class="page-info master-page-info">
	<?php echo __l("In order to withdrawal cash/amount for the charity from the account balance in the site, You first need to create a 'Money tranfer account'. You can also add multiple transfer accounts with different gateways and mark any one of them as 'Primary'. The approved withdrawal amount from your account balance will be credited to the 'Primary' marked transfer account.");?>
</div>
    <?php echo $this->element('charity_money_transfer_accounts-admin_add'); ?>
</div>

<div class="charityMoneyTransferAccounts index">
<?php
?>
<?php echo $this->element('paging_counter');?>
<?php echo $this->Form->create('CharityMoneyTransferAccount' , array('class' => 'normal','action' => 'update')); ?> <?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); 
echo $this->Form->input('charity_id',array('type' => 'hidden', 'value' => $charity_id));
?>
<table class="list">
    <tr>
		<th class="dl"><?php echo __l('Action');?></div></th>
        <th><?php echo $this->Paginator->sort(__l('Payment Gateway'), 'PaymentGateway.name');?></th>        
    </tr>
<?php
if (!empty($charityMoneyTransferAccounts)):
$i = 0;
foreach ($charityMoneyTransferAccounts as $charityMoneyTransferAccount):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td class="dl">
		<?php $options = array($charityMoneyTransferAccount['CharityMoneyTransferAccount']['id'] => ''); 
		echo $this->Form->input('CharityMoneyTransferAccount.checked', array('type' => 'radio', 'options' => $options ,'div'=>false, 'label' => false, 'legend' => false, 'value' => $charityMoneyTransferAccount['CharityMoneyTransferAccount']['id'])); ?><label for="CharityMoneyTransferAccountChecked<?php echo $charityMoneyTransferAccount['CharityMoneyTransferAccount']['id']; ?>">
		<?php echo $this->Html->cText($charityMoneyTransferAccount['CharityMoneyTransferAccount']['account']);?></label></td>
    	<td class="dc"><?php echo $this->Html->cText($charityMoneyTransferAccount['PaymentGateway']['name']);?></td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="8" class="notice"><?php echo __l('No charity money transfer account available');?></td>
	</tr>
<?php
endif;
?>
</table>
<div class="clearfix chartiy-submit-block">
<?php echo $this->Form->submit(__l('Delete'), array('name' => 'data[CharityMoneyTransferAccount][delete]'));?>
</div>
<?php echo $this->Form->end(); ?>
<?php if (!empty($charityMoneyTransferAccount)):?>
		<?php
			echo $this->element('paging_links');
		?>
<?php endif;?>
</div>