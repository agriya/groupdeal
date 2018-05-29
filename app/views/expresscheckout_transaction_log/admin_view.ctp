<?php /* SVN: $Id: $ */ ?>
<div class="paypalTransactionLogs view">
<?php
	/////////////Quick fix//////////////////////
	if (!empty($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['docapture_ack']))
	{
		$Pay_sts = 'Completed';
		$error_no = '-';
	}	
	elseif (!empty($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['dovoid_payment_status']))
	{
		$Pay_sts = 'Canceled';
		$error_no = '-';
	}
	else
	{
		$Pay_sts = 'Pending';
		$error_no = 2;
	}
?>
	<dl class="list"><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Id');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['id']) ? $this->Html->cInt($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['id']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Created');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['created']) ? $this->Html->cDateTime($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['created']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Modified');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['modified']) ? $this->Html->cDateTime($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['modified']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('User');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($expresscheckoutTransactionLog['User']['username']) ? $this->Html->link($this->Html->cText($expresscheckoutTransactionLog['User']['username']), array('controller' => 'users', 'action' => 'view', $expresscheckoutTransactionLog['User']['username']), array('escape' => false)) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Transaction');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($expresscheckoutTransactionLog['Transaction']['id']) ? $this->Html->link($this->Html->cInt($expresscheckoutTransactionLog['Transaction']['id']), array('controller' => 'transactions', 'action' => 'view', $expresscheckoutTransactionLog['Transaction']['id']), array('escape' => false)) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Deal User');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($expresscheckoutTransactionLog['DealUser']['id']) ? $this->Html->link($this->Html->cInt($expresscheckoutTransactionLog['DealUser']['id']), array('controller' => 'deal_users', 'action' => 'view', $expresscheckoutTransactionLog['DealUser']['id']), array('escape' => false)) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Ip');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($expresscheckoutTransactionLog['Ip']['ip']) ? $this->Html->cText($expresscheckoutTransactionLog['Ip']['ip']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Currency Type');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['currency_type']) ? $this->Html->cText($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['currency_type']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Txn Id');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['transaction_id']) ? $this->Html->cText($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['transaction_id']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Payer Email');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['payer_email']) ? $this->Html->cText($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['payer_email']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Payment Date');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['payment_date']) ? $this->Html->cText($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['payment_date']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Email');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['email']) ? $this->Html->cText($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['email']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Mc Gross');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['mc_gross']) ? $this->Html->cFloat($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['mc_gross']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Mc Fee');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['mc_fee']) ? $this->Html->cFloat($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['mc_fee']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Mc Currency');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['mc_currency']) ? $this->Html->cText($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['mc_currency']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Payment Status');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $Pay_sts; ?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Pending Reason');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['pending_reason']) ? $this->Html->cText($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['pending_reason']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Error No');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $error_no;?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Error Message');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['error_message']) ? $this->Html->cText($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['error_message']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Is Authorization');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo isset($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['is_authorization']) ? $this->Html->cBool($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['is_authorization']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Authorization Auth Amount');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['mc_gross']) ? $this->Html->cFloat($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['mc_gross']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Authorization Pending Reason');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['pending_reason']) ? $this->Html->cText($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['pending_reason']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Capture Timestamp');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['docapture_timestamp']) ? $this->Html->cText($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['docapture_timestamp']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Capture Correlationid');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['docapture_correlationid']) ? $this->Html->cText($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['docapture_correlationid']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Capture Ack');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['docapture_ack']) ? $this->Html->cText($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['docapture_ack']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Capture Build');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['docapture_build']) ? $this->Html->cText($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['docapture_build']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Capture Transactionid');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['docapture_transactionid']) ? $this->Html->cText($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['docapture_transactionid']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Capture Parenttransactionid');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['docapture_parenttransactionid']) ? $this->Html->cText($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['docapture_parenttransactionid']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Capture Transactiontype');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['docapture_transactiontype']) ? $this->Html->cText($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['docapture_transactiontype']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Capture Paymenttype');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['docapture_paymenttype']) ? $this->Html->cText($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['docapture_paymenttype']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Capture Ordertime');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['docapture_ordertime']) ? $this->Html->cText($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['docapture_ordertime']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Capture Amt');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['docapture_amt']) ? $this->Html->cText($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['docapture_amt']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Capture Feeamt');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['docapture_feeamt']) ? $this->Html->cText($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['docapture_feeamt']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Capture Taxamt');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['docapture_taxamt']) ? $this->Html->cText($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['docapture_taxamt']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Capture Paymentstatus');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['docapture_paymentstatus']) ? $this->Html->cText($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['docapture_paymentstatus']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Capture Pendingreason');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['docapture_pendingreason']) ? $this->Html->cText($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['docapture_pendingreason']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Capture Reasoncode');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['docapture_reasoncode']) ? $this->Html->cText($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['docapture_reasoncode']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Capture Protectioneligibility');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['docapture_protectioneligibility']) ? $this->Html->cText($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['docapture_protectioneligibility']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Void Timestamp');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['dovoid_timestamp']) ? $this->Html->cText($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['dovoid_timestamp']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Void Correlationid');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['dovoid_correlationid']) ? $this->Html->cText($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['dovoid_correlationid']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Void Payment Status');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['dovoid_payment_status']) ? $this->Html->cText($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['dovoid_payment_status']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Currency');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($expresscheckoutTransactionLog['Currency']['code']) ? $this->Html->link($this->Html->cText($expresscheckoutTransactionLog['Currency']['code']), array('controller' => 'currencies', 'action' => 'view', $expresscheckoutTransactionLog['Currency']['id']), array('escape' => false)) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Converted Currency');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($expresscheckoutTransactionLog['ConvertedCurrency']['code']) ? $this->Html->link($this->Html->cText($expresscheckoutTransactionLog['ConvertedCurrency']['code']), array('controller' => 'currencies', 'action' => 'view', $expresscheckoutTransactionLog['ConvertedCurrency']['id']), array('escape' => false)) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Orginal Amount');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['orginal_amount']) ? $this->Html->cFloat($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['orginal_amount']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Rate');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['rate']) ? $this->Html->cFloat($expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['rate']) : '-';?></dd>
	</dl>
</div>