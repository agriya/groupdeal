<?php /* SVN: $Id: $ */ ?>
<div class="paypalDocaptureLogs view">
	<dl class="list"><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Id');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalDocaptureLog['PaypalDocaptureLog']['id']) ? $this->Html->cInt($paypalDocaptureLog['PaypalDocaptureLog']['id']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Date Added');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cDateTime($paypalDocaptureLog['PaypalDocaptureLog']['created']);?></dd>							
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Authorizationid');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalDocaptureLog['PaypalDocaptureLog']['authorizationid'])? $this->Html->cText($paypalDocaptureLog['PaypalDocaptureLog']['authorizationid']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Currencycode');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalDocaptureLog['PaypalDocaptureLog']['currencycode'])? $this->Html->cText($paypalDocaptureLog['PaypalDocaptureLog']['currencycode']) : '-' ;?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Payment Status');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalDocaptureLog['PaypalDocaptureLog']['payment_status'])? $this->Html->cText($paypalDocaptureLog['PaypalDocaptureLog']['payment_status']) : '-' ;?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Dodirectpayment Correlationid');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalDocaptureLog['PaypalDocaptureLog']['dodirectpayment_correlationid'])? $this->Html->cText($paypalDocaptureLog['PaypalDocaptureLog']['dodirectpayment_correlationid']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Dodirectpayment Ack');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalDocaptureLog['PaypalDocaptureLog']['dodirectpayment_ack'])? $this->Html->cText($paypalDocaptureLog['PaypalDocaptureLog']['dodirectpayment_ack']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Dodirectpayment Build');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalDocaptureLog['PaypalDocaptureLog']['dodirectpayment_build'])? $this->Html->cText($paypalDocaptureLog['PaypalDocaptureLog']['dodirectpayment_build']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Dodirectpayment Amt');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalDocaptureLog['PaypalDocaptureLog']['dodirectpayment_amt'])? $this->Html->cCurrency($paypalDocaptureLog['PaypalDocaptureLog']['dodirectpayment_amt']):'-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Dodirectpayment Avscode');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalDocaptureLog['PaypalDocaptureLog']['dodirectpayment_avscode'])? $this->Html->cText($paypalDocaptureLog['PaypalDocaptureLog']['dodirectpayment_avscode']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Dodirectpayment Cvv2match');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalDocaptureLog['PaypalDocaptureLog']['dodirectpayment_cvv2match'])? $this->Html->cText($paypalDocaptureLog['PaypalDocaptureLog']['dodirectpayment_cvv2match']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Dodirectpayment Response');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalDocaptureLog['PaypalDocaptureLog']['dodirectpayment_response'])? $this->Html->cText($paypalDocaptureLog['PaypalDocaptureLog']['dodirectpayment_response']):'-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Version');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalDocaptureLog['PaypalDocaptureLog']['version'])? $this->Html->cFloat($paypalDocaptureLog['PaypalDocaptureLog']['version']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Dodirectpayment Timestamp');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalDocaptureLog['PaypalDocaptureLog']['dodirectpayment_timestamp'])? $this->Html->cText($paypalDocaptureLog['PaypalDocaptureLog']['dodirectpayment_timestamp']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Docapture Timestamp');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalDocaptureLog['PaypalDocaptureLog']['docapture_timestamp'])? $this->Html->cText($paypalDocaptureLog['PaypalDocaptureLog']['docapture_timestamp']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Docapture Correlationid');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalDocaptureLog['PaypalDocaptureLog']['docapture_correlationid'])? $this->Html->cText($paypalDocaptureLog['PaypalDocaptureLog']['docapture_correlationid']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Docapture Ack');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalDocaptureLog['PaypalDocaptureLog']['docapture_ack'])? $this->Html->cText($paypalDocaptureLog['PaypalDocaptureLog']['docapture_ack']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Docapture Build');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalDocaptureLog['PaypalDocaptureLog']['docapture_build'])? $this->Html->cText($paypalDocaptureLog['PaypalDocaptureLog']['docapture_build']) :'-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Docapture Transactionid');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalDocaptureLog['PaypalDocaptureLog']['docapture_transactionid'])? $this->Html->cText($paypalDocaptureLog['PaypalDocaptureLog']['docapture_transactionid']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Docapture Parenttransactionid');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalDocaptureLog['PaypalDocaptureLog']['docapture_parenttransactionid'])? $this->Html->cText($paypalDocaptureLog['PaypalDocaptureLog']['docapture_parenttransactionid']) : '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Docapture Receiptid');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalDocaptureLog['PaypalDocaptureLog']['docapture_receiptid'])? $this->Html->cText($paypalDocaptureLog['PaypalDocaptureLog']['docapture_receiptid']): '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Docapture Transactiontype');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalDocaptureLog['PaypalDocaptureLog']['docapture_transactiontype'])? $this->Html->cText($paypalDocaptureLog['PaypalDocaptureLog']['docapture_transactiontype']):'-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Docapture Paymenttype');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalDocaptureLog['PaypalDocaptureLog']['docapture_paymenttype'])? $this->Html->cText($paypalDocaptureLog['PaypalDocaptureLog']['docapture_paymenttype']):'-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Docapture Ordertime');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalDocaptureLog['PaypalDocaptureLog']['docapture_ordertime'])? $this->Html->cText($paypalDocaptureLog['PaypalDocaptureLog']['docapture_ordertime']):'-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Docapture Amt');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalDocaptureLog['PaypalDocaptureLog']['docapture_paymentstatus'])? $this->Html->cFloat($paypalDocaptureLog['PaypalDocaptureLog']['docapture_amt']):'-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Docapture Feeamt');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalDocaptureLog['PaypalDocaptureLog']['docapture_paymentstatus'])? $this->Html->cFloat($paypalDocaptureLog['PaypalDocaptureLog']['docapture_feeamt']):'-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Docapture Taxamt');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalDocaptureLog['PaypalDocaptureLog']['docapture_paymentstatus'])? $this->Html->cFloat($paypalDocaptureLog['PaypalDocaptureLog']['docapture_taxamt']):'-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Docapture Paymentstatus');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalDocaptureLog['PaypalDocaptureLog']['docapture_paymentstatus'])? $this->Html->cText($paypalDocaptureLog['PaypalDocaptureLog']['docapture_paymentstatus']):'-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Docapture Pendingreason');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalDocaptureLog['PaypalDocaptureLog']['docapture_pendingreason'])? $this->Html->cText($paypalDocaptureLog['PaypalDocaptureLog']['docapture_pendingreason']):'-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Docapture Reasoncode');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalDocaptureLog['PaypalDocaptureLog']['docapture_reasoncode'])? $this->Html->cText($paypalDocaptureLog['PaypalDocaptureLog']['docapture_reasoncode']):'-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Docapture Protectioneligibility');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalDocaptureLog['PaypalDocaptureLog']['docapture_protectioneligibility'])? $this->Html->cText($paypalDocaptureLog['PaypalDocaptureLog']['docapture_protectioneligibility']):'-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Docapture Response');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalDocaptureLog['PaypalDocaptureLog']['docapture_response'])? $this->Html->cText($paypalDocaptureLog['PaypalDocaptureLog']['docapture_response']):'-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Dovoid Timestamp');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalDocaptureLog['PaypalDocaptureLog']['dovoid_timestamp'])? $this->Html->cText($paypalDocaptureLog['PaypalDocaptureLog']['dovoid_timestamp']):'-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Dovoid Correlationid');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalDocaptureLog['PaypalDocaptureLog']['dovoid_correlationid'])? $this->Html->cText($paypalDocaptureLog['PaypalDocaptureLog']['dovoid_correlationid']):'-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Dovoid Ack');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalDocaptureLog['PaypalDocaptureLog']['dovoid_ack'])? $this->Html->cText($paypalDocaptureLog['PaypalDocaptureLog']['dovoid_ack']):'-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Dovoid Build');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalDocaptureLog['PaypalDocaptureLog']['dovoid_build'])? $this->Html->cText($paypalDocaptureLog['PaypalDocaptureLog']['dovoid_build']):'-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Dovoid Response')	;?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($paypalDocaptureLog['PaypalDocaptureLog']['dovoid_response'])? $this->Html->cText($paypalDocaptureLog['PaypalDocaptureLog']['dovoid_response']):'-';?></dd>
	</dl>
</div>

