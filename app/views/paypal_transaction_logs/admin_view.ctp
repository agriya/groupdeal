<?php /* SVN: $Id: $ */ ?>
<div class="paypalTransactionLogs view">
<?php
	/////////////Quick fix//////////////////////
	if (!empty($paypalTransactionLog['PaypalTransactionLog']['capture_ack']))
	{
		$Pay_sts = 'Completed';
		$error_no = '-';
	}	
	elseif (!empty($paypalTransactionLog['PaypalTransactionLog']['void_ack']))
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
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['id']) ? $this->Html->cInt($paypalTransactionLog['PaypalTransactionLog']['id']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Created');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['created']) ? $this->Html->cDateTime($paypalTransactionLog['PaypalTransactionLog']['created']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Modified');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['modified']) ? $this->Html->cDateTime($paypalTransactionLog['PaypalTransactionLog']['modified']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Date Added');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['date_added']) ? $this->Html->cDateTime($paypalTransactionLog['PaypalTransactionLog']['date_added']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('User');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['username']) ? $this->Html->link($this->Html->cText($paypalTransactionLog['User']['username']), array('controller' => 'users', 'action' => 'view', $paypalTransactionLog['User']['username']), array('escape' => false)) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Transaction');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['Transaction']['id']) ? $this->Html->link($this->Html->cInt($paypalTransactionLog['Transaction']['id']), array('controller' => 'transactions', 'action' => 'view', $paypalTransactionLog['Transaction']['id']), array('escape' => false)) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Deal User');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['DealUser']['id']) ? $this->Html->link($this->Html->cInt($paypalTransactionLog['DealUser']['id']), array('controller' => 'deal_users', 'action' => 'view', $paypalTransactionLog['DealUser']['id']), array('escape' => false)) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Ip');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['Ip']['ip']) ? $this->Html->cText($paypalTransactionLog['Ip']['ip']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Currency Type');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['currency_type']) ? $this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['currency_type']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Txn Id');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['txn_id']) ? $this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['txn_id']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Payer Email');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['payer_email']) ? $this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['payer_email']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Payment Date');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['payment_date']) ? $this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['payment_date']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Email');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['email']) ? $this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['email']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('To Digicurrency');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['to_digicurrency']) ? $this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['to_digicurrency']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('To Account No');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['to_account_no']) ? $this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['to_account_no']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('To Account Name');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['to_account_name']) ? $this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['to_account_name']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Fees Paid By');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['fees_paid_by']) ? $this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['fees_paid_by']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Mc Gross');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['mc_gross']) ? $this->Html->cFloat($paypalTransactionLog['PaypalTransactionLog']['mc_gross']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Mc Fee');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['mc_fee']) ? $this->Html->cFloat($paypalTransactionLog['PaypalTransactionLog']['mc_fee']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Mc Currency');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['mc_currency']) ? $this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['mc_currency']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Payment Status');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $Pay_sts; ?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Pending Reason');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['pending_reason']) ? $this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['pending_reason']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Receiver Email');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['receiver_email']) ? $this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['receiver_email']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Paypal Response');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['paypal_response']) ? $this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['paypal_response']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Error No');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $error_no;?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Error Message');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['error_message']) ? $this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['error_message']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Memo');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['memo']) ? $this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['memo']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Paypal Post Vars');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['paypal_post_vars']) ? $this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['paypal_post_vars']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Is Mass Pay');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo isset($paypalTransactionLog['PaypalTransactionLog']['is_mass_pay']) ? $this->Html->cBool($paypalTransactionLog['PaypalTransactionLog']['is_mass_pay']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Mass Pay Status');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['mass_pay_status']) ? $this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['mass_pay_status']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Masspay Response');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['masspay_response']) ? $this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['masspay_response']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('User Cash Withdrawal Id');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['user_cash_withdrawal_id']) ? $this->Html->cInt($paypalTransactionLog['PaypalTransactionLog']['user_cash_withdrawal_id']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Charity Cash Withdrawal Id');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['charity_cash_withdrawal_id']) ? $this->Html->cInt($paypalTransactionLog['PaypalTransactionLog']['charity_cash_withdrawal_id']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Affiliate Cash Withdrawal Id');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['affiliate_cash_withdrawal_id']) ? $this->Html->cInt($paypalTransactionLog['PaypalTransactionLog']['affiliate_cash_withdrawal_id']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Is Authorization');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo isset($paypalTransactionLog['PaypalTransactionLog']['is_authorization']) ? $this->Html->cBool($paypalTransactionLog['PaypalTransactionLog']['is_authorization']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Authorization Auth Exp');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['authorization_auth_exp']) ? $this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['authorization_auth_exp']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Authorization Transaction Entity');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['authorization_transaction_entity']) ? $this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['authorization_transaction_entity']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Authorization Parent Txn Id');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['authorization_parent_txn_id']) ? $this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['authorization_parent_txn_id']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Authorization Remaining Settle');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['authorization_remaining_settle']) ? $this->Html->cInt($paypalTransactionLog['PaypalTransactionLog']['authorization_remaining_settle']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Authorization Auth Id');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['authorization_auth_id']) ? $this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['authorization_auth_id']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Authorization Auth Amount');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['authorization_auth_amount']) ? $this->Html->cFloat($paypalTransactionLog['PaypalTransactionLog']['authorization_auth_amount']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Authorization Pending Reason');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['authorization_pending_reason']) ? $this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['authorization_pending_reason']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Authorization Payment Gross');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['authorization_payment_gross']) ? $this->Html->cFloat($paypalTransactionLog['PaypalTransactionLog']['authorization_payment_gross']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Authorization Auth Status');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['authorization_auth_status']) ? $this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['authorization_auth_status']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Authorization Data');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['authorization_data']) ? $this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['authorization_data']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Capture Authorizationid');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['capture_authorizationid']) ? $this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['capture_authorizationid']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Capture Timestamp');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['capture_timestamp']) ? $this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['capture_timestamp']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Capture Correlationid');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['capture_correlationid']) ? $this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['capture_correlationid']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Capture Ack');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['capture_ack']) ? $this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['capture_ack']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Capture Version');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['capture_version']) ? $this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['capture_version']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Capture Build');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['capture_build']) ? $this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['capture_build']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Capture Transactionid');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['capture_transactionid']) ? $this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['capture_transactionid']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Capture Parenttransactionid');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['capture_parenttransactionid']) ? $this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['capture_parenttransactionid']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Capture Transactiontype');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['capture_transactiontype']) ? $this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['capture_transactiontype']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Capture Paymenttype');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['capture_paymenttype']) ? $this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['capture_paymenttype']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Capture Expectedecheckcleardate');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['capture_expectedecheckcleardate']) ? $this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['capture_expectedecheckcleardate']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Capture Ordertime');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['capture_ordertime']) ? $this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['capture_ordertime']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Capture Amt');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['capture_amt']) ? $this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['capture_amt']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Capture Feeamt');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['capture_feeamt']) ? $this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['capture_feeamt']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Capture Taxamt');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['capture_taxamt']) ? $this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['capture_taxamt']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Capture Currencycode');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['capture_currencycode']) ? $this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['capture_currencycode']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Capture Paymentstatus');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['capture_paymentstatus']) ? $this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['capture_paymentstatus']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Capture Shippingmethod');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['capture_shippingmethod']) ? $this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['capture_shippingmethod']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Capture Pendingreason');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['capture_pendingreason']) ? $this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['capture_pendingreason']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Capture Reasoncode');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['capture_reasoncode']) ? $this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['capture_reasoncode']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Capture Protectioneligibility');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['capture_protectioneligibility']) ? $this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['capture_protectioneligibility']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Capture Data');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['capture_data']) ? $this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['capture_data']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Void Timestamp');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['void_timestamp']) ? $this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['void_timestamp']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Void Correlationid');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['void_correlationid']) ? $this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['void_correlationid']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Void Ack');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['void_ack']) ? $this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['void_ack']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Void Data');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['void_data']) ? $this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['void_data']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Currency');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['Currency']['code']) ? $this->Html->link($this->Html->cText($paypalTransactionLog['Currency']['code']), array('controller' => 'currencies', 'action' => 'view', $paypalTransactionLog['Currency']['id']), array('escape' => false)) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Converted Currency');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['ConvertedCurrency']['code']) ? $this->Html->link($this->Html->cText($paypalTransactionLog['ConvertedCurrency']['code']), array('controller' => 'currencies', 'action' => 'view', $paypalTransactionLog['ConvertedCurrency']['id']), array('escape' => false)) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Orginal Amount');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['orginal_amount']) ? $this->Html->cFloat($paypalTransactionLog['PaypalTransactionLog']['orginal_amount']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Rate');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalTransactionLog['PaypalTransactionLog']['rate']) ? $this->Html->cFloat($paypalTransactionLog['PaypalTransactionLog']['rate']) : '-';?></dd>
	</dl>
</div>